@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Banner Utama TitipGo -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 md:p-10 text-white shadow-lg">
        <h1 class="text-3xl md:text-4xl font-bold">Mau Titip Oleh-oleh dari Kota Mana?</h1>
        <p class="mt-2 text-indigo-100 max-w-xl">Temukan Jastiper terpercaya dari berbagai daerah di Indonesia yang siap membawakan makanan dan produk favoritmu.</p>
    </div>

    <!-- Filter & Grid Katalog Produk -->
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Sidebar Filter Pencarian -->
        <aside class="w-full md:w-64 space-y-4 bg-white p-5 rounded-xl border border-slate-200 h-fit">
            <h3 class="font-bold text-slate-900 border-b pb-2">Filter Titipan</h3>
            <form method="GET" action="{{ route('catalog.index') }}">
                <div class="space-y-3">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Cari Kata Kunci</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / kota..." class="w-full text-sm border-slate-300 rounded-lg focus:ring-indigo-500">
                </div>
                <button type="submit" class="w-full mt-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2 rounded-lg transition">
                    Terapkan Filter
                </button>
            </form>
        </aside>

        <!-- Daftar Kartu Produk (Grid) -->
        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($items as $item)
                <div class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-md transition group">
                    <div class="h-48 bg-slate-100 relative">
                        <img src="{{ $item->image ? asset('storage/'.$item->image) : 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=500' }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-indigo-600 font-semibold text-xs px-2.5 py-1 rounded-full border border-indigo-100">
                            {{ $item->origin_city }}
                        </span>
                    </div>
                    <div class="p-4 space-y-3">
                        <h4 class="font-bold text-slate-800 text-lg group-hover:text-indigo-600 transition">{{ $item->name }}</h4>
                        <p class="text-slate-500 text-sm line-clamp-2">{{ $item->description }}</p>
                        <div class="flex items-center justify-between border-t border-slate-100 pt-3">
                            <div>
                                <span class="text-xs text-slate-400 block">Harga Titip</span>
                                <span class="font-extrabold text-indigo-600 text-base">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            </div>
                            <!-- Form Pesan Titipan dengan Transaksi ACID -->
                            <form action="{{ route('order.store', $item->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="px-4 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-lg text-sm font-semibold transition">
                                    Titip Sekarang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-white rounded-xl border border-slate-200">
                    <i data-lucide="package-open" class="w-12 h-12 text-slate-300 mx-auto mb-3"></i>
                    <p class="text-slate-500 font-medium">Belum ada item jastip yang tersedia saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Paginasi -->
    <div class="mt-6">
        {{ $items->withQueryString()->links() }}
    </div>
</div>
@endsection