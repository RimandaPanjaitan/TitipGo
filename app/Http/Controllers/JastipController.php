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
        session()->forget(['is_logged_in', 'user_name', 'user_email', 'user_phone', 'user_address', 'user_avatar']);
        $role = $request->get('role', 'buyer');
        $mode = $request->get('mode', 'login');
        return view('auth', compact('role', 'mode'));
    }

   public function authSubmit(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string|max:120',
            'name'       => 'nullable|string|max:100',
            'email'      => 'nullable|email|max:120',
            'phone'      => 'nullable|string|max:30',
            'address'    => 'nullable|string|max:255',
            'role'       => 'required|in:buyer,jastiper',
        ]);

        $rawInput = trim($request->identifier);

        if ($request->filled('name')) {
            $accountName = ucwords(strtolower(trim($request->name)));
        } elseif (filter_var($rawInput, FILTER_VALIDATE_EMAIL)) {
            $accountName = ucwords(str_replace(['.', '_', '-'], ' ', explode('@', $rawInput)[0]));
        } else {
            $accountName = ucwords(strtolower($rawInput));
        }

        $accountEmail = $request->email
            ?: (filter_var($rawInput, FILTER_VALIDATE_EMAIL) ? $rawInput : strtolower(str_replace(' ', '', $accountName)) . '@gmail.com');

        $accountPhone = $request->phone
            ?: (preg_match('/^[0-9+\-\s]{8,}$/', $rawInput) ? $rawInput : '0812-6489-2026');

        $accountAddress = $request->address ?: session('user_address', 'Sitoluama, Laguboti, Toba, Sumatera Utara');

        session([
            'user_name'    => $accountName,
            'user_email'   => $accountEmail,
            'user_phone'   => $accountPhone,
            'user_address' => $accountAddress,
            'user_avatar'  => 'https://api.dicebear.com/9.x/lorelei/svg?seed=' . urlencode($accountName) . '&backgroundColor=ffd5dc',
        ]);

        if ($request->role === 'jastiper') {
            session(['is_jastiper' => true]);
            return redirect()->route('location.setup', ['next' => 'jastiper'])
                ->with('success', "Selamat datang di Portal Jastiper, {$accountName}!");
        }

        session(['is_jastiper' => false]);
        return redirect()->route('location.setup', ['next' => 'buyer'])
            ->with('success', "Halo {$accountName}! Silakan aktifkan lokasi Anda.");
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
        $validated = $request->validate([
            'quantity'         => 'required|integer|min:1',
            'buyer_name'       => 'required|string|max:100',
            'buyer_phone'      => 'required|string|max:30',
            'shipping_address' => 'required|string|max:255',
        ]);

        $order = null;
        try {
            DB::transaction(function () use ($validated, $id, &$order) {
                $item = JastipItem::where('id', $id)->lockForUpdate()->firstOrFail();
                if ($item->quota < $validated['quantity']) {
                    throw new \RuntimeException("Kuota bagasi tersisa {$item->quota} slot.");
                }
                $item->decrement('quota', $validated['quantity']);

                $subtotal = $item->price * $validated['quantity'];
                $fee = 5000;

                $order = Order::create([
                    'jastip_item_id'   => $item->id,
                    'order_code'       => 'TG-' . strtoupper(Str::random(6)),
                    'buyer_name'       => $validated['buyer_name'],
                    'buyer_email'      => session['user_email'],
                    'buyer_phone'      => $validated['buyer_phone'],
                    'shipping_address' => $validated['shipping_address'],
                    'quantity'         => $validated['quantity'],
                    'subtotal'         => $subtotal,
                    'service_fee'      => $fee,
                    'total_price'      => $subtotal + $fee,
                    'payment_status'   => 'Menunggu Pembayaran',
                    'step_status'      => 1,
                    'status_label'     => '1. Pesanan Diterima & Dibeli di Negara Asal',
                ]);

                Notification::create([
                    'role'    => 'jastiper',
                    'title'   => "Pesanan Baru #{$order->order_code}",
                    'message' => "{$validated['buyer_name']} menitip {$validated['quantity']}x {$item->name} ({$item->origin_country}).",
                ]);
            });

            return redirect()->route('buyer.payment', $order->id)
                ->with('success', 'Pesanan berhasil dibuat! Silakan pilih metode pembayaran untuk menyelesaikan transaksi.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
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

    // 8. PENGHUBUNG SATU ARAH: Form Daftar Jadi Jastiper (Tanpa Kendaraan/Plat Nomor)
    public function applyJastiperForm()
    {
        if (session('is_jastiper')) return redirect()->route('jastiper.dashboard');
        $user = $this->getDefaultUser();
        return view('shopper.apply', compact('user'));
    }

    public function applyJastiperSubmit(Request $request)
    {
        $request->validate([
            'jastiper_name'  => 'required|string|max:100',
            'jastiper_phone' => 'required|string|max:30',
            'jastiper_addr'  => 'required|string|max:255',
            'travel_route'   => 'required|string|max:120',
            'passport_id'    => 'required|string|max:50',
        ]);

        session([
            'is_logged_in' => true,
            'user_name'    => $accountName,
            'user_email'   => $accountEmail,
            'user_phone'   => $accountPhone,
            'user_address' => $accountAddress,
            'user_avatar'  => 'https://api.dicebear.com/9.x/lorelei/svg?seed=' . urlencode($accountName) . '&backgroundColor=ffd5dc',
        ]);

        return redirect()->route('jastiper.dashboard')
            ->with('success', 'Akun Anda resmi terverifikasi menjadi Jastiper! Mode Pembeli telah dinonaktifkan permanen.');
    }

    // 9. POV JASTIPER: Dashboard, Performa Mingguan, & Pencairan Dana
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

    // 10. POV JASTIPER: Kelola Katalog & Buka PO Baru (Upload Gambar + Deskripsi)
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

    // 11. POV JASTIPER: Kelola 4 Tahap Status Pesanan Berurutan (1 -> 2 -> 3 -> 4)
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

    // 12. POV JASTIPER: Halaman Notifikasi Terpisah & Profil Interaktif + Rekening Bank
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