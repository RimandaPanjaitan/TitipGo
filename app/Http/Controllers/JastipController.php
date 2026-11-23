<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\JastipItem;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JastipController extends Controller
{
    private function getDefaultUser()
    {
        $name = session('user_name');

        return [
            'name'    => $name ?: 'Pengguna',
            'email'   => session('user_email', ''),
            'phone'   => session('user_phone', ''),
            'city'    => session('user_city', 'Lokasi Belum Diatur'),
            'address' => session('user_address', ''),
            'avatar'  => session('user_avatar', 'https://api.dicebear.com/9.x/lorelei/svg?seed=' . urlencode($name ?: 'User') . '&backgroundColor=ffd5dc'),
        ];
    }

    // 1. Landing & Auth (Login / Sign Up Terpisah Pembeli vs Jastiper)
    public function landing()
    {
        if (session('is_jastiper')) {
            return redirect()->route('jastiper.dashboard');
        }
        $featured = JastipItem::latest()->take(4)->get();
        return view('landing', compact('featured'));
    }

    public function authForm(Request $request)
    {
        $role = $request->get('role', 'buyer');
        $mode = $request->get('mode', 'login');
        return view('auth', compact('role', 'mode'));
    }

    public function authSubmit(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'email'       => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/i'],
            'phone'       => 'required|string|max:30',
            'street'      => 'required|string|max:150',
            'city'        => 'required|string|max:100',
            'province'    => 'required|string|max:100',
            'postal_code' => ['required', 'regex:/^[0-9]{5}$/'],
            'password'    => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',      // minimal 1 huruf kecil
                'regex:/[A-Z]/',      // minimal 1 huruf besar
                'regex:/[0-9]/',      // minimal 1 angka
                'regex:/[\W_]/',      // minimal 1 karakter spesial
            ],
            'role'        => 'required|in:buyer,jastiper',
        ], [
            'email.regex'       => 'Email wajib menggunakan domain @gmail.com (selain @gmail.com tidak diperbolehkan).',
            'postal_code.regex' => 'Kode pos wajib terdiri dari 5 digit angka.',
            'password.min'      => 'Kata sandi minimal terdiri dari 8 karakter.',
            'password.regex'    => 'Kata sandi wajib memuat huruf besar, huruf kecil, angka, dan karakter spesial.',
        ]);

        $accountName = ucwords(strtolower(trim($request->name)));
        $fullAddress = "{$request->street}, {$request->city}, {$request->province} {$request->postal_code}";
        $isJastiper  = ($request->role === 'jastiper');

        session([
            'is_logged_in'     => true,
            'is_jastiper'      => $isJastiper,
            'user_name'        => $accountName,
            'user_email'       => strtolower(trim($request->email)),
            'user_phone'       => trim($request->phone),
            'user_street'      => trim($request->street),
            'user_city'        => trim($request->city),
            'user_province'    => trim($request->province),
            'user_postal_code' => trim($request->postal_code),
            'user_address'     => $fullAddress,
            'user_birthdate'   => '14 Februari 2002',
            'user_gender'      => 'Perempuan',
            'user_avatar'      => 'https://api.dicebear.com/9.x/lorelei/svg?seed=' . urlencode($accountName) . '&backgroundColor=ffd5dc',
        ]);
        session()->save();

        return redirect()->route('location.setup', ['next' => $isJastiper ? 'jastiper' : 'buyer'])
            ->with('success', "Akun {$accountName} berhasil diverifikasi! Silakan konfirmasi titik lokasi.");
    }

    public function updateBuyerProfile(Request $request)
    {
        session([
            'user_name'        => $request->input('name', session('user_name')),
            'user_phone'       => $request->input('phone', session('user_phone')),
            'user_birthdate'   => $request->input('birthdate', session('user_birthdate')),
            'user_gender'      => $request->input('gender', session('user_gender')),
            'user_street'      => $request->input('street', session('user_street')),
            'user_city'        => $request->input('city', session('user_city')),
            'user_province'    => $request->input('province', session('user_province')),
            'user_postal_code' => $request->input('postal_code', session('user_postal_code')),
            'user_address'     => ($request->input('street', session('user_street'))) . ', ' . ($request->input('city', session('user_city'))) . ', ' . ($request->input('province', session('user_province'))) . ' ' . ($request->input('postal_code', session('user_postal_code'))),
        ]);

        if ($request->filled('avatar_choice')) {
            session(['user_avatar' => $request->avatar_choice]);
        }

        session()->save();
        return back()->with('success', 'Pengaturan profil Anda berhasil diperbarui!');
    }

    public function applyJastiperForm()
    {
        $user = $this->getDefaultUser();
        return view('shopper.apply', compact('user'));
    }

    public function applyJastiperSubmit(Request $request)
    {
        $request->validate([
            'name'               => 'required|string|max:100',
            'email'              => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/i'],
            'phone'              => 'required|string|max:30',
            'id_type'            => 'required|in:KTP,Paspor',
            'id_number'          => 'required|string|min:6|max:30',
            'full_address'       => 'required|string|max:255',
            'city'               => 'required|string|max:100',
            'province'           => 'required|string|max:100',
            'destination'        => 'required|string|max:80',
            'departure_date'     => 'required|date',
            'return_date'        => 'required|date|after_or_equal:departure_date',
            'capacity_kg'        => 'required|integer|min:1|max:50',
            'experience'         => 'required|string|max:100',
            'categories'         => 'required|array|min:1',
            'motivation'         => 'required|string|min:10',
            'bank_name'          => 'required|string|max:50',
            'bank_holder'        => 'required|string|max:100',
            'bank_account'       => 'required|string|min:6|max:30',
            'agree_customs'      => 'accepted',
        ], [
            'email.regex'            => 'Email Jastiper wajib menggunakan domain @gmail.com.',
            'categories.required'    => 'Pilih minimal satu kategori makanan yang dapat dibawa.',
            'agree_customs.accepted' => 'Anda wajib menyetujui pernyataan aturan Bea Cukai dan Karantina TitipGo.',
        ]);

        // Simpan rekening pencairan Jastiper secara otomatis
        BankAccount::create([
            'provider'       => $request->bank_name,
            'account_number' => $request->bank_account,
            'account_holder' => $request->bank_holder,
            'is_primary'     => true,
        ]);

        Notification::create([
            'role'    => 'jastiper',
            'title'   => 'Akun Partner Jastiper Aktif!',
            'message' => "Selamat {$request->name}, pengajuan rute {$request->destination} ({$request->capacity_kg} kg) telah diverifikasi. Anda kini dapat membuka Pre-Order!",
        ]);

        // Kunci peran secara permanen menjadi Jastiper
        session([
            'is_logged_in'  => true,
            'is_jastiper'   => true,
            'user_name'     => $request->name,
            'user_email'    => $request->email,
            'user_phone'    => $request->phone,
            'user_city'     => $request->city,
            'user_province' => $request->province,
            'user_address'  => $request->full_address,
        ]);
        session()->save();

        return redirect()->route('jastiper.dashboard')
            ->with('success', "Selamat! Pengajuan Jastiper Anda diterima dan akun kini beralih ke Mode Partner Jastiper.");
    }

    // 2. Halaman Menyalakan Lokasi Setelah Login -> Arahkan ke Welcome Screen
    public function locationSetup(Request $request)
    {
        $next = $request->get('next', session('is_jastiper') ? 'jastiper' : 'buyer');
        return view('location', compact('next'));
    }

    public function locationSave(Request $request)
    {
        $request->validate([
            'city'    => 'required|string|max:100',
            'address' => 'required|string|max:255',
        ]);

        session([
            'user_city'    => $request->city,
            'user_address' => $request->address,
            'location_on'  => true,
        ]);

        if ($request->next === 'jastiper') {
            session(['is_jastiper' => true]);
        }

        return redirect()->route('welcome.screen');
    }

    // 2B. Halaman Welcome Setelah Login & Lokasi Aktif Sebelum Masuk Dashboard
    public function welcomeScreen()
    {
        $user = $this->getDefaultUser();
        $isJastiper = (bool) session('is_jastiper', false);
        $destinationRoute = $isJastiper ? route('jastiper.dashboard') : route('buyer.dashboard');

        return view('welcome-screen', compact('user', 'isJastiper', 'destinationRoute'));
    }

    // 3. POV PEMBELI: Dashboard & Grafik Pengeluaran
    public function buyerDashboard()
    {
        if (session('is_jastiper')) return redirect()->route('jastiper.dashboard');

        $user = $this->getDefaultUser();
        $items = JastipItem::latest()->take(6)->get();
        $orders = Order::with('item')->latest()->get();
        $totalSpent = $orders->sum('total_price');
        $unreadNotif = Notification::where('role', 'buyer')->where('is_read', false)->count();

        return view('buyer.dashboard', compact('user', 'items', 'orders', 'totalSpent', 'unreadNotif'));
    }

    // 4. POV PEMBELI: Katalog Makanan Lolos Bea Cukai & Filter
    public function buyerCatalog(Request $request)
    {
        if (session('is_jastiper')) return redirect()->route('jastiper.dashboard');

        $query = JastipItem::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('origin_country', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'Semua') {
            $query->where('category', $request->category);
        }

        if ($request->filled('country') && $request->country !== 'Semua') {
            $query->where('origin_country', $request->country);
        }

        $items = $query->latest()->paginate(9)->withQueryString();
        $categories = [
            'Cokelat & Olahan Kakao',
            'Biskuit, Kue Kering & Pastry Tersegel',
            'Camilan Ringan Kemasan (Snacks & Chips)',
            'Permen & Manisan Kemasan',
            'Mie Instan & Bumbu/Saus Kemasan Segel',
            'Kopi, Teh & Bubuk Minuman',
            'Makanan Bayi & Suplemen Makanan',
        ];
        $countries = JastipItem::select('origin_country')->distinct()->pluck('origin_country');

        return view('buyer.catalog', compact('items', 'categories', 'countries'));
    }

    // 5. POV PEMBELI: Detail Produk & Buat Pesanan
    public function productDetail($id)
    {
        if (session('is_jastiper')) return redirect()->route('jastiper.dashboard');
        $item = JastipItem::findOrFail($id);
        $user = $this->getDefaultUser();
        return view('buyer.product-detail', compact('item', 'user'));
    }

    public function createOrder(Request $request, $id)
    {
        $request->validate([
            'quantity'         => 'required|integer|min:1',
            'buyer_name'       => 'nullable|string|max:100',
            'buyer_phone'      => 'required|string|max:30',
            'shipping_address' => 'required|string|max:255',
        ]);

        $buyerName  = trim((string) ($request->input('buyer_name') ?: session('user_name') ?: 'Pelanggan TitipGo'));
        $buyerEmail = session('user_email', 'pelanggan@titipgo.com');
        $qty        = (int) $request->input('quantity', 1);

        $order = null;
        try {
            DB::transaction(function () use ($request, $id, $buyerName, $buyerEmail, $qty, &$order) {
                $item = JastipItem::where('id', $id)->lockForUpdate()->firstOrFail();

                if ($item->quota < $qty) {
                    throw new \RuntimeException("Maaf, kuota bagasi hanya tersisa {$item->quota} slot.");
                }

                $item->decrement('quota', $qty);

                $subtotal = $item->price * $qty;
                $fee = 5000;

                $order = Order::create([
                    'jastip_item_id'   => $item->id,
                    'order_code'       => 'TG-' . strtoupper(Str::random(6)),
                    'buyer_name'       => $buyerName,
                    'buyer_email'      => $buyerEmail,
                    'buyer_phone'      => $request->input('buyer_phone'),
                    'shipping_address' => $request->input('shipping_address'),
                    'quantity'         => $qty,
                    'subtotal'         => $subtotal,
                    'service_fee'      => $fee,
                    'total_price'      => $subtotal + $fee,
                    'payment_method'   => 'Menunggu Pilihan',
                    'payment_status'   => 'Menunggu Pembayaran',
                    'step_status'      => 1,
                    'status_label'     => '1. Pesanan Diterima & Dibeli di Negara Asal',
                ]);

                Notification::create([
                    'role'    => 'jastiper',
                    'title'   => "Pesanan Baru #{$order->order_code}",
                    'message' => "{$buyerName} menitip {$qty}x {$item->name} ({$item->origin_country}).",
                ]);
            });

            if (!$order) {
                return back()->with('error', 'Gagal memproses pesanan. Silakan coba lagi.');
            }

            return redirect()->route('buyer.payment', $order->id)
                ->with('success', 'Pesanan berhasil dibuat! Silakan pilih metode pembayaran.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    // 6. POV PEMBELI: Pembayaran Setelah Pesanan Dibuat & Resi Digital
    public function paymentPage($orderId)
    {
        if (session('is_jastiper')) return redirect()->route('jastiper.dashboard');
        $order = Order::with('item')->findOrFail($orderId);
        return view('buyer.payment', compact('order'));
    }

    public function confirmPayment(Request $request, $orderId)
    {
        $request->validate([
            'payment_method' => 'required|in:PayPal,BRI,BNI,BCA,Amazon Wallet',
        ]);

        $order = Order::with('item')->findOrFail($orderId);
        $order->update([
            'payment_method' => $request->payment_method,
            'payment_status' => 'Lunas',
        ]);

        Notification::create([
            'role'    => 'buyer',
            'title'   => "Pembayaran Lunas #{$order->order_code}",
            'message' => "Pembayaran Rp " . number_format($order->total_price, 0, ',', '.') . " via {$request->payment_method} berhasil dikonfirmasi.",
        ]);

        return redirect()->route('buyer.receipt', $order->id)
            ->with('success', "Pembayaran via {$request->payment_method} berhasil! Berikut bukti resi digital Anda.");
    }

    public function receiptPage($orderId)
    {
        if (session('is_jastiper')) return redirect()->route('jastiper.dashboard');
        $order = Order::with('item')->findOrFail($orderId);
        return view('buyer.receipt', compact('order'));
    }

    // 7. POV PEMBELI: Lacak Pesanan (4 Tahap + Info Kendaraan Lokal) & Notifikasi & Profil
    public function trackOrders()
    {
        if (session('is_jastiper')) return redirect()->route('jastiper.dashboard');
        $orders = Order::with('item')->latest()->get();
        return view('buyer.track', compact('orders'));
    }

    public function buyerNotifications()
    {
        if (session('is_jastiper')) return redirect()->route('jastiper.dashboard');
        $notifications = Notification::where('role', 'buyer')->latest()->get();
        Notification::where('role', 'buyer')->update(['is_read' => true]);
        return view('buyer.notifications', compact('notifications'));
    }

    public function buyerProfile()
    {
        if (session('is_jastiper')) return redirect()->route('jastiper.dashboard');
        $user = $this->getDefaultUser();
        return view('buyer.profile', compact('user'));
    }


    // 8. POV JASTIPER: Dashboard, Performa Mingguan, & Pencairan Dana
    public function shopperDashboard()
    {
        if (!session('is_jastiper')) return redirect()->route('jastiper.apply');

        $user = $this->getDefaultUser();
        $items = JastipItem::latest()->get();
        $orders = Order::with('item')->latest()->get();
        $banks = BankAccount::all();
        $withdrawals = Withdrawal::latest()->get();

        $grossEarnings = $orders->where('payment_status', 'Lunas')->sum('subtotal');
        $withdrawnTotal = $withdrawals->sum('amount');
        $availableBalance = max(0, $grossEarnings - $withdrawnTotal);
        $unreadNotif = Notification::where('role', 'jastiper')->where('is_read', false)->count();

        return view('shopper.dashboard', compact(
            'user', 'items', 'orders', 'banks', 'withdrawals',
            'grossEarnings', 'availableBalance', 'unreadNotif'
        ));
    }

    public function withdrawFunds(Request $request)
    {
        if (!session('is_jastiper')) return redirect()->route('jastiper.apply');

        $request->validate([
            'bank_id' => 'required|exists:bank_accounts,id',
            'amount'  => 'required|integer|min:10000',
        ]);

        $bank = BankAccount::findOrFail($request->bank_id);

        Withdrawal::create([
            'provider'       => $bank->provider,
            'account_number' => $bank->account_number,
            'amount'         => $request->amount,
            'status'         => 'Berhasil Dicairkan',
        ]);

        Notification::create([
            'role'    => 'jastiper',
            'title'   => 'Pencairan Dana Berhasil',
            'message' => 'Dana sebesar Rp ' . number_format($request->amount, 0, ',', '.') . " telah ditransfer ke {$bank->provider} ({$bank->account_number}).",
        ]);

        return back()->with('success', "Dana Rp " . number_format($request->amount, 0, ',', '.') . " berhasil dicairkan ke {$bank->provider}!");
    }

    // 9. POV JASTIPER: Kelola Katalog & Buka PO Baru (Upload Gambar + Deskripsi)
    public function shopperCatalog()
    {
        if (!session('is_jastiper')) return redirect()->route('jastiper.apply');
        $items = JastipItem::latest()->get();
        $categories = [
            'Cokelat & Olahan Kakao',
            'Biskuit, Kue Kering & Pastry Tersegel',
            'Camilan Ringan Kemasan (Snacks & Chips)',
            'Permen & Manisan Kemasan',
            'Mie Instan & Bumbu/Saus Kemasan Segel',
            'Kopi, Teh & Bubuk Minuman',
            'Makanan Bayi & Suplemen Makanan',
        ];
        return view('shopper.catalog', compact('items', 'categories'));
    }

    public function storeCatalogItem(Request $request)
    {
        if (!session('is_jastiper')) return redirect()->route('jastiper.apply');

        $validated = $request->validate([
            'name'           => 'required|string|max:150',
            'origin_country' => 'required|string|max:80',
            'origin_city'    => 'required|string|max:100',
            'category'       => 'required|string|max:100',
            'price'          => 'required|integer|min:1000',
            'quota'          => 'required|integer|min:1',
            'weight'         => 'nullable|string|max:50',
            'po_close_date'  => 'nullable|date',
            'description'    => 'required|string',
            'image_file'     => 'nullable|image|max:2048',
            'image_url'      => 'nullable|url',
        ]);

        // Otomatis hitung estimasi pengiriman berdasarkan negara asal
        $country = strtolower($validated['origin_country']);
        if (str_contains($country, 'singapura') || str_contains($country, 'malaysia')) {
            $est = "2 - 3 Hari ({$validated['origin_country']} → Indonesia)";
        } elseif (str_contains($country, 'jepang') || str_contains($country, 'korea')) {
            $est = "4 - 6 Hari ({$validated['origin_country']} → Indonesia)";
        } else {
            $est = "6 - 9 Hari ({$validated['origin_country']} → Indonesia)";
        }

        $imagePath = 'https://images.unsplash.com/photo-1549007994-cb92caebd54b?w=600';
        if ($request->hasFile('image_file')) {
            $imagePath = $request->file('image_file')->store('jastip_items', 'public');
        } elseif (!empty($validated['image_url'])) {
            $imagePath = $validated['image_url'];
        }

        JastipItem::create([
            'name'               => $validated['name'],
            'origin_country'     => $validated['origin_country'],
            'origin_city'        => $validated['origin_city'],
            'category'           => $validated['category'],
            'estimated_shipping' => $est,
            'price'              => $validated['price'],
            'quota'              => $validated['quota'],
            'weight'             => $validated['weight'] ?? '250 gr',
            'po_close_date'      => $validated['po_close_date'],
            'jastiper_name'      => session('user_name', 'Rimanda Santa'),
            'description'        => $validated['description'],
            'image'              => $imagePath,
        ]);

        return back()->with('success', 'Produk & Open PO baru berhasil ditambahkan ke katalog!');
    }

    // 10. POV JASTIPER: Kelola 4 Tahap Status Pesanan Berurutan (1 -> 2 -> 3 -> 4)
    public function shopperOrders()
    {
        if (!session('is_jastiper')) return redirect()->route('jastiper.apply');
        $orders = Order::with('item')->latest()->get();
        return view('shopper.orders', compact('orders'));
    }

    public function advanceOrderStep(Request $request, $id)
    {
        if (!session('is_jastiper')) return redirect()->route('jastiper.apply');

        $order = Order::findOrFail($id);
        $nextStep = min(4, $order->step_status + 1);

        $labels = [
            1 => '1. Pesanan Diterima & Dibeli di Negara Asal',
            2 => '2. Lolos Bea Cukai & Penerbangan ke Indonesia',
            3 => '3. Kurir Lokal Menuju Alamat Pelanggan',
            4 => '4. Pesanan Selesai & Diterima Pelanggan',
        ];

        $updateData = [
            'step_status'  => $nextStep,
            'status_label' => $labels[$nextStep],
        ];

        // Jika masuk tahap 3 (kurir lokal mengantar), simpan kendaraan & plat nomor
        if ($nextStep === 3) {
            $updateData['courier_vehicle'] = $request->courier_vehicle ?: 'Motor Honda Scoopy Pink Coquette';
            $updateData['courier_plate']   = $request->courier_plate ?: 'BB 2026 RS';
        }

        $order->update($updateData);

        Notification::create([
            'role'    => 'buyer',
            'title'   => "Update Pesanan #{$order->order_code}",
            'message' => "Status pesanan Anda kini: {$labels[$nextStep]}",
        ]);

        return back()->with('success', "Pesanan #{$order->order_code} berlanjut ke tahap {$nextStep}!");
    }

    // 11. POV JASTIPER: Halaman Notifikasi Terpisah & Profil Interaktif + Rekening Bank
    public function shopperNotifications()
    {
        if (!session('is_jastiper')) return redirect()->route('jastiper.apply');
        $notifications = Notification::where('role', 'jastiper')->latest()->get();
        Notification::where('role', 'jastiper')->update(['is_read' => true]);
        return view('shopper.notifications', compact('notifications'));
    }

    public function shopperProfile()
    {
        if (!session('is_jastiper')) return redirect()->route('jastiper.apply');
        $user = $this->getDefaultUser();
        $banks = BankAccount::all();
        return view('shopper.profile', compact('user', 'banks'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:100',
            'email'        => 'required|email',
            'phone'        => 'required|string|max:30',
            'address'      => 'required|string|max:255',
            'avatar_choice'=> 'nullable|string',
            'avatar_file'  => 'nullable|image|max:2048',
        ]);

        $avatar = session('user_avatar');
        if ($request->hasFile('avatar_file')) {
            $path = $request->file('avatar_file')->store('avatars', 'public');
            $avatar = asset('storage/' . $path);
        } elseif ($request->filled('avatar_choice')) {
            $avatar = $request->avatar_choice;
        }

        session([
            'user_name'   => $request->name,
            'user_email'  => $request->email,
            'user_phone'  => $request->phone,
            'user_address'=> $request->address,
            'user_avatar' => $avatar,
        ]);

        return back()->with('success', 'Pengaturan akun & foto profil berhasil diperbarui!');
    }

    public function storeBank(Request $request)
    {
        $request->validate([
            'provider'       => 'required|in:BNI,BRI,PayPal,Amazon Wallet,BCA',
            'account_number' => 'required|string|max:100',
        ]);

        BankAccount::create([
            'provider'       => $request->provider,
            'account_number' => $request->account_number,
            'account_holder' => session('user_name', 'Rimanda Santa'),
            'is_primary'     => false,
        ]);

        return back()->with('success', "Rekening {$request->provider} berhasil ditambahkan!");
    }

    public function setPrimaryBank($id)
    {
        BankAccount::query()->update(['is_primary' => false]);
        BankAccount::where('id', $id)->update(['is_primary' => true]);
        return back()->with('success', 'Rekening utama pencairan diperbarui.');
    }

    public function destroyBank($id)
    {
        BankAccount::findOrFail($id)->delete();
        return back()->with('success', 'Rekening dihapus.');
    }
}