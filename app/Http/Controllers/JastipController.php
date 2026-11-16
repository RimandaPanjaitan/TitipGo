<?php

namespace App\Http\Controllers;

use App\Models\JastipItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JastipController extends Controller
{
    // 1. Halaman Katalog & Filter Interaktif (Hanya untuk Pembeli)
    public function index(Request $request)
    {
        if (session('is_jastiper')) {
            return redirect()->route('jastiper.dashboard');
        }

        $query = JastipItem::query();

        // Filter Kata Kunci (Nama / Kota / Deskripsi)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('origin_city', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter Kategori
        if ($request->filled('category') && $request->category !== 'Semua') {
            $query->where('category', $request->category);
        }

        // Filter Kota / Negara Asal
        if ($request->filled('origin_city') && $request->origin_city !== 'Semua') {
            $query->where('origin_city', $request->origin_city);
        }

        // Pengurutan (Terbaru / Harga Termurah / Harga Termahal / Kuota Terbanyak)
        $sort = $request->get('sort', 'latest');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'quota_desc') {
            $query->orderBy('quota', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $items = $query->paginate(9)->withQueryString();

        // Ambil daftar kota & kategori unik dari database untuk pilihan filter otomatis
        $cities = JastipItem::select('origin_city')->distinct()->pluck('origin_city');
        $categories = ['Makanan & Snack', 'Kue & Pastry', 'Minuman & Kopi', 'Skincare & Kosmetik', 'Fashion & Merchandise'];

        return view('catalog.index', compact('items', 'cities', 'categories'));
    }

    // 2. Detail Produk (Hanya untuk Pembeli)
    public function show($id)
    {
        if (session('is_jastiper')) {
            return redirect()->route('jastiper.dashboard');
        }

        $item = JastipItem::findOrFail($id);
        return view('catalog.show', compact('item'));
    }

    // 3. Checkout Pesanan ACID (Hanya untuk Pembeli)
    public function storeOrder(Request $request, $id)
    {
        if (session('is_jastiper')) {
            return redirect()->route('jastiper.dashboard');
        }

        $validated = $request->validate([
            'quantity'         => 'required|integer|min:1',
            'buyer_name'       => 'nullable|string|max:100',
            'shipping_address' => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($validated, $id) {
                $item = JastipItem::where('id', $id)->lockForUpdate()->firstOrFail();

                if ($item->quota < $validated['quantity']) {
                    throw new \RuntimeException("Kuota titipan untuk {$item->name} tidak mencukupi!");
                }

                $item->decrement('quota', $validated['quantity']);

                Order::create([
                    'jastip_item_id'   => $item->id,
                    'buyer_name'       => $validated['buyer_name'] ?? 'Rimanda Panjaitan',
                    'buyer_phone'      => '0812-3456-7890',
                    'shipping_address' => $validated['shipping_address'] ?? 'Sitoluama, Laguboti, Toba',
                    'quantity'         => $validated['quantity'],
                    'total_price'      => $item->price * $validated['quantity'],
                    'status'           => 'Menunggu Pembelian Jastiper',
                ]);
            });

            return redirect()->route('orders.index')->with('success', 'Titipan berhasil dibuat! Kuota bagasi telah diamankan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // 4. Halaman Titipan Saya (Hanya untuk Pembeli)
    public function myOrders()
    {
        if (session('is_jastiper')) {
            return redirect()->route('jastiper.dashboard');
        }

        $orders = Order::with('item')->orderBy('created_at', 'desc')->get();
        return view('orders.index', compact('orders'));
    }

    // 5. Upgrade Satu Arah: Pembeli -> Jastiper Permanen
    public function upgradeToJastiper()
    {
        session(['is_jastiper' => true]);
        return redirect()->route('jastiper.dashboard')
            ->with('success', 'Selamat! Akun Anda resmi menjadi Jastiper. Fitur pengelolaan katalog & pesanan kini aktif.');
    }

    // 6. Dashboard Eksklusif Jastiper
    public function jastiperDashboard(Request $request)
    {
        if (!session('is_jastiper')) {
            return redirect()->route('home')->with('error', 'Akses ditolak! Silakan aktifkan akun Jastiper terlebih dahulu.');
        }

        $items = JastipItem::withCount('orders')->orderBy('created_at', 'desc')->get();

        $orderQuery = Order::with('item')->orderBy('created_at', 'desc');
        if ($request->filled('status_filter') && $request->status_filter !== 'Semua') {
            $orderQuery->where('status', $request->status_filter);
        }
        $incomingOrders = $orderQuery->get();

        $totalRevenue = Order::sum('total_price');
        $totalSoldQty = Order::sum('quantity');

        return view('jastiper.dashboard', compact('items', 'incomingOrders', 'totalRevenue', 'totalSoldQty'));
    }

    // 7. Form Tambah Katalog (Eksklusif Jastiper)
    public function createItem()
    {
        if (!session('is_jastiper')) {
            return redirect()->route('home')->with('error', 'Hanya Jastiper yang dapat menambahkan produk ke katalog.');
        }

        return view('jastiper.create');
    }

    // 8. Simpan Produk Baru ke Katalog (Eksklusif Jastiper)
    public function storeItem(Request $request)
    {
        if (!session('is_jastiper')) {
            return redirect()->route('home');
        }

        $validated = $request->validate([
            'name'         => 'required|string|max:150',
            'origin_city'  => 'required|string|max:100',
            'category'     => 'required|string|max:80',
            'price'        => 'required|integer|min:1000',
            'quota'        => 'required|integer|min:1',
            'weight'       => 'nullable|string|max:50',
            'arrival_date' => 'nullable|date',
            'description'  => 'nullable|string',
            'image_file'   => 'nullable|image|max:2048',
            'image_url'    => 'nullable|url',
        ]);

        $imagePath = 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=600';

        if ($request->hasFile('image_file')) {
            $imagePath = $request->file('image_file')->store('jastip_items', 'public');
        } elseif (!empty($validated['image_url'])) {
            $imagePath = $validated['image_url'];
        }

        JastipItem::create([
            'name'         => $validated['name'],
            'origin_city'  => $validated['origin_city'],
            'category'     => $validated['category'],
            'price'        => $validated['price'],
            'quota'        => $validated['quota'],
            'weight'       => $validated['weight'] ?? '500 gr',
            'arrival_date' => $validated['arrival_date'],
            'description'  => $validated['description'],
            'image'        => $imagePath,
        ]);

        return redirect()->route('jastiper.dashboard')->with('success', 'Produk baru berhasil ditambahkan ke katalog!');
    }

    // 9. Fitur Interaktif Jastiper: Update Kuota Cepat (+ / -)
    public function adjustQuota(Request $request, $id)
    {
        if (!session('is_jastiper')) {
            return redirect()->route('home');
        }

        $item = JastipItem::findOrFail($id);
        if ($request->action === 'inc') {
            $item->increment('quota');
        } elseif ($request->action === 'dec' && $item->quota > 0) {
            $item->decrement('quota');
        }

        return back()->with('success', "Kuota {$item->name} diperbarui menjadi {$item->quota} slot.");
    }

    // 10. Fitur Interaktif Jastiper: Hapus Produk dari Katalog
    public function destroyItem($id)
    {
        if (!session('is_jastiper')) {
            return redirect()->route('home');
        }

        $item = JastipItem::findOrFail($id);
        $item->delete();

        return back()->with('success', 'Produk berhasil dihapus dari etalase.');
    }

    // 11. Update Status Pesanan Masuk
    public function updateOrderStatus(Request $request, $id)
    {
        if (!session('is_jastiper')) {
            return redirect()->route('home');
        }

        $request->validate(['status' => 'required|string|max:100']);
        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return back()->with('success', "Status pesanan #ORD-{$order->id} diubah menjadi: {$request->status}");
    }
}