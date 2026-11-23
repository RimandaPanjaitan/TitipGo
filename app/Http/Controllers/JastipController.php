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

    // 1. Landing & Auth (Log In: Nama, Email @gmail.com, Password | Sign Up: Lengkap)
    public function landing()
    {
        $featuredItems = JastipItem::latest()->take(4)->get();
        return view('landing', compact('featuredItems'));
    }

    public function authForm(Request $request)
    {
        $role = $request->get('role', 'buyer');
        $mode = $request->get('mode', 'login');
        return view('auth', compact('role', 'mode'));
    }

    public function authSubmit(Request $request)
    {
        $mode = $request->input('mode', 'login');

        $rules = [
            'name'     => 'required|string|max:100',
            'email'    => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/i'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[\W_]/',
            ],
            'role'     => 'required|in:buyer,jastiper',
        ];

        if ($mode === 'signup') {
            $rules['phone']       = 'required|string|max:30';
            $rules['street']      = 'required|string|max:150';
            $rules['city']        = 'required|string|max:100';
            $rules['province']    = 'required|string|max:100';
            $rules['postal_code'] = ['required', 'regex:/^[0-9]{5}$/'];
        }

        $request->validate($rules, [
            'email.regex'       => 'Email wajib menggunakan domain @gmail.com (selain @gmail.com tidak diperbolehkan).',
            'postal_code.regex' => 'Kode pos wajib terdiri dari 5 digit angka.',
            'password.min'      => 'Kata sandi minimal terdiri dari 8 karakter.',
            'password.regex'    => 'Kata sandi wajib memuat huruf besar, huruf kecil, angka, dan karakter spesial.',
        ]);

        $accountName = ucwords(strtolower(trim($request->name)));
        $isJastiper  = ($request->role === 'jastiper');

        if ($mode === 'signup') {
            $phone       = trim($request->phone);
            $street      = trim($request->street);
            $city        = trim($request->city);
            $province    = trim($request->province);
            $postalCode  = trim($request->postal_code);
            $fullAddress = "{$street}, {$city}, {$province} {$postalCode}";
        } else {
            $phone       = session('user_phone', '0812-6489-2026');
            $street      = session('user_street', 'Sitoluama');
            $city        = session('user_city', 'Laguboti, Toba');
            $province    = session('user_province', 'Sumatera Utara');
            $postalCode  = session('user_postal_code', '22381');
            $fullAddress = session('user_address', 'Sitoluama, Laguboti, Toba, Sumatera Utara 22381');
        }

        session([
            'is_logged_in'     => true,
            'is_jastiper'      => $isJastiper,
            'user_name'        => $accountName,
            'user_email'       => strtolower(trim($request->email)),
            'user_phone'       => $phone,
            'user_street'      => $street,
            'user_city'        => $city,
            'user_province'    => $province,
            'user_postal_code' => $postalCode,
            'user_address'     => $fullAddress,
            'user_birthdate'   => '14 Februari 2002',
            'user_gender'      => 'Perempuan',
            'user_avatar'      => 'https://api.dicebear.com/9.x/lorelei/svg?seed=' . urlencode($accountName) . '&backgroundColor=ffd5dc',
        ]);
        session()->save();

        return redirect()->route('location.setup', ['next' => $isJastiper ? 'jastiper' : 'buyer'])
            ->with('success', "Selamat datang, {$accountName}! Silakan konfirmasi titik lokasi Anda.");
    }

    // 2. Halaman Menyalakan Lokasi & Welcome Screen
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
        session()->save();

        return redirect()->route('welcome.screen');
    }

    public function welcomeScreen()
    {
        $user = $this->getDefaultUser();
        $isJastiper = (bool) session('is_jastiper', false);
        $destinationRoute = $isJastiper ? route('jastiper.dashboard') : route('buyer.dashboard');

        return view('welcome-screen', compact('user', 'isJastiper', 'destinationRoute'));
    }

    // 3. POV PEMBELI: Dashboard, Katalog, Detail Produk, Pemesanan, Pembayaran, Resi, Pelacakan, Profil
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
        $buyerEmail = session('user_email', 'pelanggan@gmail.com');
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

    public function updateBuyerProfile(Request $request)
    {
        $request->validate([
            'avatar_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $avatar = session('user_avatar');
        if ($request->hasFile('avatar_file')) {
            $path   = $request->file('avatar_file')->store('avatars', 'public');
            $avatar = asset('storage/' . $path);
        } elseif ($request->filled('avatar_choice')) {
            $avatar = $request->input('avatar_choice');
        }

        $street     = $request->input('street', session('user_street', ''));
        $city       = $request->input('city', session('user_city', ''));
        $province   = $request->input('province', session('user_province', ''));
        $postalCode = $request->input('postal_code', session('user_postal_code', ''));

        session([
            'user_name'        => $request->input('name', session('user_name')),
            'user_phone'       => $request->input('phone', session('user_phone')),
            'user_birthdate'   => $request->input('birthdate', session('user_birthdate')),
            'user_gender'      => $request->input('gender', session('user_gender')),
            'user_street'      => $street,
            'user_city'        => $city,
            'user_province'    => $province,
            'user_postal_code' => $postalCode,
            'user_address'     => trim("{$street}, {$city}, {$province} {$postalCode}", ', '),
            'user_avatar'      => $avatar,
        ]);

        session()->save();
        return back()->with('success', 'Profil dan foto akun Pembeli berhasil diperbarui!');
    }

    // 4. ALUR SATU ARAH: Pembeli Mendaftar Jadi Jastiper (3 Bagian Form Partner Jastiper)
    public function applyJastiperForm()
    {
        if (session('is_jastiper')) {
            return redirect()->route('jastiper.dashboard');
        }
        $user = $this->getDefaultUser();
        return view('shopper.apply', compact('user'));
    }

    public function applyJastiperSubmit(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:100',
            'email'          => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/i'],
            'phone'          => 'required|string|max:30',
            'id_type'        => 'required|in:KTP,Paspor',
            'id_number'      => 'required|string|min:4|max:30',
            'full_address'   => 'required|string|max:255',
            'city'           => 'required|string|max:100',
            'province'       => 'required|string|max:100',
            'destination'    => 'required|string|max:80',
            'departure_date' => 'required|date',
            'return_date'    => 'required|date|after_or_equal:departure_date',
            'capacity_kg'    => 'required|integer|min:1|max:100',
            'experience'     => 'required|string|max:100',
            'categories'     => 'required|array|min:1',
            'motivation'     => 'required|string|min:3',
            'bank_name'      => 'required|string|max:50',
            'bank_holder'    => 'required|string|max:100',
            'bank_account'   => 'required|string|min:4|max:30',
            'agree_customs'  => 'accepted',
        ], [
            'email.regex'                => 'Email Jastiper wajib menggunakan domain @gmail.com.',
            'return_date.after_or_equal' => 'Tanggal kembali tidak boleh lebih awal dari tanggal berangkat.',
            'categories.required'        => 'Pilih minimal satu kategori makanan yang dapat dibawa.',
            'agree_customs.accepted'     => 'Centang kotak persetujuan aturan Bea Cukai dan Karantina.',
        ]);

        try {
            $bank = new BankAccount();
            $bank->provider       = $request->bank_name;
            $bank->account_number = $request->bank_account;
            $bank->account_holder = $request->bank_holder;
            $bank->is_primary     = true;
            $bank->save();

            $notif = new Notification();
            $notif->role    = 'jastiper';
            $notif->title   = 'Akun Partner Jastiper Aktif!';
            $notif->message = "Selamat {$request->name}, rute {$request->destination} ({$request->capacity_kg} kg) siap digunakan untuk membuka PO.";
            $notif->save();
        } catch (\Throwable $e) {
            // Tetap lanjutkan aktivasi sesi Jastiper meskipun tabel log opsional mengalami kendala
        }

        session([
            'is_logged_in'      => true,
            'is_jastiper'       => true,
            'user_name'         => trim($request->name),
            'user_email'        => strtolower(trim($request->email)),
            'user_phone'        => trim($request->phone),
            'user_city'         => trim($request->city),
            'user_province'     => trim($request->province),
            'user_address'      => trim($request->full_address),
            'jastiper_route'    => $request->destination,
            'jastiper_capacity' => $request->capacity_kg,
            'jastiper_id_info'  => "{$request->id_type} • {$request->id_number}",
        ]);
        session()->save();

        return redirect()->route('jastiper.dashboard')
            ->with('success', "Selamat {$request->name}! Akun Anda telah resmi aktif sebagai Partner Jastiper.");
    }

    // 5. POV JASTIPER: Dashboard, Grafik, & Pencairan Dana
    public function shopperDashboard()
    {
        if (!session('is_jastiper')) return redirect()->route('jastiper.apply');

        $user         = $this->getDefaultUser();
        $items        = JastipItem::latest()->get();
        $orders       = Order::with('item')->latest()->get();
        $bankAccounts = BankAccount::latest()->get();
        $withdrawals  = Withdrawal::latest()->get();

        $totalRevenue     = Order::where('payment_status', 'Lunas')->sum('subtotal');
        $totalWithdrawn   = Withdrawal::sum('amount');
        $availableBalance = max(0, $totalRevenue - $totalWithdrawn);
        $unreadNotif      = Notification::where('role', 'jastiper')->where('is_read', false)->count();

        $chartLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $chartData   = [2, 4, 3, 5, 4, 6, max(1, $orders->count())];

        return view('shopper.dashboard', compact(
            'user',
            'items',
            'orders',
            'bankAccounts',
            'withdrawals',
            'totalRevenue',
            'totalWithdrawn',
            'availableBalance',
            'unreadNotif',
            'chartLabels',
            'chartData'
        ));
    }

    public function withdrawFunds(Request $request)
    {
        if (!session('is_jastiper')) return redirect()->route('jastiper.apply');

        $request->validate([
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'amount'          => 'required|integer|min:10000',
        ]);

        $bank = BankAccount::findOrFail($request->bank_account_id);

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

        return back()->with('success', 'Dana Rp ' . number_format($request->amount, 0, ',', '.') . " berhasil dicairkan ke {$bank->provider}!");
    }

    // 6. POV JASTIPER: Kelola Katalog & Buka PO Baru
    public function shopperCatalog()
    {
        if (!session('is_jastiper')) return redirect()->route('jastiper.apply');
        $items = JastipItem::latest()->get();
        return view('shopper.catalog', compact('items'));
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
            'image'          => 'nullable|image|max:2048',
        ]);

        $country = strtolower($validated['origin_country']);
        if (str_contains($country, 'singapura') || str_contains($country, 'malaysia')) {
            $est = "2 - 3 Hari ({$validated['origin_country']} → Indonesia)";
        } elseif (str_contains($country, 'jepang') || str_contains($country, 'korea')) {
            $est = "4 - 6 Hari ({$validated['origin_country']} → Indonesia)";
        } else {
            $est = "6 - 9 Hari ({$validated['origin_country']} → Indonesia)";
        }

        $imagePath = 'https://images.unsplash.com/photo-1549007994-cb92caebd54b?w=600';
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('jastip_items', 'public');
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
            'po_close_date'      => $validated['po_close_date'] ?? now()->addDays(7),
            'jastiper_name'      => session('user_name', 'Partner Jastiper'),
            'description'        => $validated['description'],
            'image'              => $imagePath,
        ]);

        return back()->with('success', 'Produk & Open PO baru berhasil diterbitkan ke katalog pembeli!');
    }

    public function toggleCatalogItem($id)
    {
        if (!session('is_jastiper')) return redirect()->route('jastiper.apply');

        $item = JastipItem::findOrFail($id);
        $newQuota = $item->quota > 0 ? 0 : 10;
        $item->update(['quota' => $newQuota]);

        $statusText = $newQuota > 0 ? 'diaktifkan kembali (10 slot tersedia)' : 'dinonaktifkan sementara (0 slot)';
        return back()->with('success', "Item \"{$item->name}\" berhasil {$statusText}.");
    }

    public function updateCatalogItem(Request $request, $id)
    {
        if (!session('is_jastiper')) return redirect()->route('jastiper.apply');

        $item = JastipItem::findOrFail($id);
        $validated = $request->validate([
            'name'           => 'required|string|max:150',
            'origin_country' => 'required|string|max:80',
            'origin_city'    => 'required|string|max:100',
            'category'       => 'required|string|max:100',
            'price'          => 'required|integer|min:1000',
            'quota'          => 'required|integer|min:0|max:200',
            'po_close_date'  => 'nullable|date',
            'description'    => 'required|string',
        ]);

        $item->update($validated);

        return back()->with('success', "Perubahan pada \"{$item->name}\" berhasil disimpan!");
    }

    public function destroyCatalogItem($id)
    {
        if (!session('is_jastiper')) return redirect()->route('jastiper.apply');

        $item = JastipItem::findOrFail($id);
        $name = $item->name;
        $item->delete();

        return back()->with('success', "Item \"{$name}\" berhasil dihapus dari katalog.");
    }

    // 7. POV JASTIPER: Kelola Status Pesanan (4 Tahap)
    public function shopperOrders()
    {
        if (!session('is_jastiper')) return redirect()->route('jastiper.apply');
        $orders = Order::with('item')->latest()->get();
        return view('shopper.orders', compact('orders'));
    }

    public function updateOrderStatus(Request $request, $orderId)
    {
        if (!session('is_jastiper')) return redirect()->route('jastiper.apply');

        $order = Order::findOrFail($orderId);
        $step  = (int) $request->input('step_status', min(4, $order->step_status + 1));

        $labels = [
            1 => '1. Pesanan Diterima & Dibeli di Negara Asal',
            2 => '2. Lolos Bea Cukai & Penerbangan ke Indonesia',
            3 => '3. Diantar Kurir Lokal ke Alamat Pembeli',
            4 => '4. Pesanan Tiba & Selesai',
        ];

        $updateData = [
            'step_status'  => $step,
            'status_label' => $labels[$step] ?? $labels[1],
        ];

        if ($step === 3) {
            $updateData['courier_vehicle'] = $request->input('courier_vehicle') ?: 'Honda Vario 160 Hitam';
            $updateData['courier_plate']   = $request->input('courier_plate') ?: 'BB 4821 XW';
        }

        $order->update($updateData);

        Notification::create([
            'role'    => 'buyer',
            'title'   => "Update Pesanan #{$order->order_code}",
            'message' => "Status pesanan Anda kini: {$updateData['status_label']}",
        ]);

        return back()->with('success', "Status pesanan #{$order->order_code} berhasil diperbarui ke tahap {$step}!");
    }

    // 8. POV JASTIPER: Notifikasi, Profil, Foto & Rekening Bank
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
        $user         = $this->getDefaultUser();
        $bankAccounts = BankAccount::latest()->get();
        return view('shopper.profile', compact('user', 'bankAccounts'));
    }

    public function updateShopperProfile(Request $request)
    {
        if (!session('is_jastiper')) return redirect()->route('jastiper.apply');

        $request->validate([
            'avatar_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $avatar = session('user_avatar');
        if ($request->hasFile('avatar_file')) {
            $path   = $request->file('avatar_file')->store('avatars', 'public');
            $avatar = asset('storage/' . $path);
        } elseif ($request->filled('avatar_choice')) {
            $avatar = $request->input('avatar_choice');
        }

        session([
            'user_name'         => $request->input('name', session('user_name')),
            'user_phone'        => $request->input('phone', session('user_phone')),
            'user_city'         => $request->input('city', session('user_city')),
            'user_province'     => $request->input('province', session('user_province')),
            'user_address'      => $request->input('address', session('user_address')),
            'jastiper_route'    => $request->input('jastiper_route', session('jastiper_route', 'Jepang')),
            'jastiper_capacity' => $request->input('jastiper_capacity', session('jastiper_capacity', 10)),
            'user_avatar'       => $avatar,
        ]);

        session()->save();
        return back()->with('success', 'Profil & foto Partner Jastiper berhasil diperbarui!');
    }

    public function storeBankAccount(Request $request)
    {
        $request->validate([
            'provider'       => 'required|string|max:50',
            'account_number' => 'required|string|max:100',
            'account_holder' => 'required|string|max:100',
        ]);

        BankAccount::create([
            'provider'       => $request->provider,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'is_primary'     => false,
        ]);

        return back()->with('success', "Rekening {$request->provider} berhasil ditambahkan!");
    }
}