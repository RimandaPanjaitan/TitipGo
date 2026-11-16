@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <span class="px-3 py-1 bg-amber-500/20 text-amber-300 border border-amber-400/30 text-xs font-bold rounded-full uppercase">Jastiper Seller Hub</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold mt-2">Workspace Jastiper Aktif</h1>
            <p class="text-slate-300 text-sm mt-1">Kelola etalase produk, kapasitas bagasi pesawat, dan status pesanan pembeli.</p>
        </div>
        <a href="{{ route('jastiper.create') }}" class="px-5 py-3 bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-sm rounded-xl shadow-md transition flex items-center gap-2 shrink-0">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            Tambah Produk Katalog
        </a>
    </div>

    <!-- Statistik & Indikator Bagasi -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase">Total Omzet Titipan</span>
            <p class="text-2xl font-black text-emerald-600 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase">Total Pesanan Masuk</span>
            <p class="text-2xl font-black text-indigo-600 mt-1">{{ $incomingOrders->count() }} Pesanan</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase">Beban Bagasi (Maks 20kg)</span>
                <span class="text-xs font-extrabold text-amber-600">{{ number_format($totalBagasiKg, 1) }} kg</span>
            </div>
            @php $bagasiPercent = min(100, ($totalBagasiKg / 20) * 100); @endphp
            <div class="w-full bg-slate-100 h-2.5 rounded-full mt-3 overflow-hidden">
                <div class="bg-amber-500 h-full rounded-full transition-all" style="width: {{ $bagasiPercent }}%"></div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase">Produk di Etalase</span>
            <p class="text-2xl font-black text-slate-900 mt-1">{{ $items->count() }} Item</p>
        </div>
    </div>

    <!-- Manajemen Etalase Barang Interaktif -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-slate-900 text-lg flex items-center gap-2">
                <i data-lucide="package" class="w-5 h-5 text-indigo-600"></i>
                Kelola Etalase & Aksi Cepat
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-5">
            @forelse($items as $item)
                <div class="border {{ $item->is_open ? 'border-slate-200' : 'border-amber-300 bg-amber-50/30' }} rounded-2xl p-4 flex flex-col justify-between gap-4 transition">
                    <div class="flex items-start gap-3">
                        <img src="{{ Str::startsWith($item->image, 'http') ? $item->image : asset('storage/'.$item->image) }}" class="w-16 h-16 rounded-xl object-cover bg-slate-100 shrink-0">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-1">
                                <span class="text-[10px] font-bold px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-md">{{ $item->category }}</span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $item->is_open ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                    {{ $item->is_open ? 'Open' : 'Paused' }}
                                </span>
                            </div>
                            <h4 class="font-bold text-slate-900 text-sm truncate mt-1">{{ $item->name }}</h4>
                            <p class="text-xs text-slate-500">{{ $item->origin_city }} • {{ $item->weight_grams }}g</p>
                            <p class="text-sm font-extrabold text-indigo-600 mt-1">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Kontrol Kuota & Aksi Interaktif -->
                    <div class="pt-3 border-t border-slate-100 space-y-2.5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500">Sisa Kuota:</span>
                            <div class="flex items-center gap-1.5">
                                <form action="{{ route('jastiper.item.quota', $item->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="action" value="decrease">
                                    <button type="submit" class="w-6 h-6 rounded-md bg-slate-100 hover:bg-slate-200 font-bold text-xs">-</button>
                                </form>
                                <span class="text-xs font-extrabold w-7 text-center">{{ $item->quota }}</span>
                                <form action="{{ route('jastiper.item.quota', $item->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="action" value="increase">
                                    <button type="submit" class="w-6 h-6 rounded-md bg-slate-100 hover:bg-slate-200 font-bold text-xs">+</button>
                                </form>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-2 pt-1">
                            <!-- Toggle Open / Pause -->
                            <form action="{{ route('jastiper.item.toggle', $item->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-[11px] font-bold px-2.5 py-1 rounded-lg border border-slate-200 hover:bg-slate-100 transition">
                                    {{ $item->is_open ? 'Jeda Trip' : 'Buka Lagi' }}
                                </button>
                            </form>

                            <!-- Duplikat Trip 1-Klik -->
                            <form action="{{ route('jastiper.item.duplicate', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                                    <i data-lucide="copy" class="w-3 h-3"></i> Duplikat
                                </button>
                            </form>

                            <!-- Hapus -->
                            <form action="{{ route('jastiper.item.delete', $item->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-[11px] font-bold text-red-500 hover:text-red-700 flex items-center gap-1">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center text-sm text-slate-400">
                    Etalase masih kosong. Klik <strong>Tambah Produk Katalog</strong> di atas!
                </div>
            @endforelse
        </div>
    </div>

    <!-- Daftar Pesanan Pembeli dengan Live Filter -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h2 class="font-bold text-slate-900 text-lg flex items-center gap-2">
                <i data-lucide="inbox" class="w-5 h-5 text-amber-500"></i>
                Daftar Pesanan Pembeli
            </h2>

            <div class="flex flex-wrap items-center gap-2">
                <input type="text" id="liveOrderSearch" placeholder="Cari nama pembeli / barang..." class="text-xs border border-slate-200 rounded-xl px-3 py-2 bg-slate-50 outline-none">
                <form method="GET" action="{{ route('jastiper.dashboard') }}">
                    <select name="status_filter" onchange="this.form.submit()" class="text-xs font-semibold border border-slate-200 rounded-xl px-3 py-2 bg-slate-50">
                        <option value="">Semua Status</option>
                        <option value="Menunggu Pembelian Jastiper" {{ request('status_filter') == 'Menunggu Pembelian Jastiper' ? 'selected' : '' }}>Menunggu Pembelian</option>
                        <option value="Sudah Dibeli di Negara Asal" {{ request('status_filter') == 'Sudah Dibeli di Negara Asal' ? 'selected' : '' }}>Sudah Dibeli</option>
                        <option value="Dalam Perjalanan ke Indonesia" {{ request('status_filter') == 'Dalam Perjalanan ke Indonesia' ? 'selected' : '' }}>Dalam Perjalanan</option>
                        <option value="Selesai" {{ request('status_filter') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </form>
            </div>
        </div>

        <div id="ordersListContainer" class="divide-y divide-slate-100">
            @forelse($incomingOrders as $order)
                <div class="order-row p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-extrabold text-indigo-600">#ORD-{{ $order->id }}</span>
                            <span class="text-xs text-slate-400">•</span>
                            <span class="text-xs font-bold text-slate-700">{{ $order->buyer_name }}</span>
                        </div>
                        <h3 class="font-bold text-slate-900">{{ $order->item->name }} ({{ $order->quantity }} pcs)</h3>
                        <p class="text-xs text-slate-500">Tujuan: {{ $order->shipping_address }} • Total: <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></p>
                    </div>

                    <form action="{{ route('jastiper.order.status', $order->id) }}" method="POST" class="flex items-center gap-2">
                        @csrf @method('PATCH')
                        <select name="status" class="text-xs font-semibold border border-slate-200 rounded-xl px-3 py-2 bg-slate-50">
                            <option value="Menunggu Pembelian Jastiper" {{ $order->status == 'Menunggu Pembelian Jastiper' ? 'selected' : '' }}>Menunggu Pembelian Jastiper</option>
                            <option value="Sudah Dibeli di Negara Asal" {{ $order->status == 'Sudah Dibeli di Negara Asal' ? 'selected' : '' }}>Sudah Dibeli di Negara Asal</option>
                            <option value="Dalam Perjalanan ke Indonesia" {{ $order->status == 'Dalam Perjalanan ke Indonesia' ? 'selected' : '' }}>Dalam Perjalanan ke Indonesia</option>
                            <option value="Dikirim ke Alamat Pembeli" {{ $order->status == 'Dikirim ke Alamat Pembeli' ? 'selected' : '' }}>Dikirim ke Alamat Pembeli</option>
                            <option value="Selesai" {{ $order->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition">
                            Update Status
                        </button>
                    </form>
                </div>
            @empty
                <div class="p-10 text-center text-sm text-slate-400">
                    Belum ada pesanan masuk.
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    document.getElementById('liveOrderSearch')?.addEventListener('input', function(e) {
        const keyword = e.target.value.toLowerCase();
        document.querySelectorAll('.order-row').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(keyword) ? '' : 'none';
        });
    });
</script>
@endsection