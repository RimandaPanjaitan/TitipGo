@extends('layouts.app')

@section('content')
<div class="space-y-8">

    <!-- Hero Banner -->
    <div class="relative overflow-hidden bg-gradient-to-r from-indigo-700 via-indigo-600 to-purple-700 rounded-3xl p-6 sm:p-10 text-white shadow-xl">
        <div class="relative z-10 max-w-2xl space-y-3">
            <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md text-amber-300 font-semibold text-xs rounded-full uppercase tracking-wider">
                Jastip Nusantara & Internasional
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                Mau Titip Oleh-oleh & Snack Dari Mana Saja?
            </h1>
            <p class="text-indigo-100 text-sm sm:text-base">
                Pilih kategori atau kota asal di samping untuk menemukan barang titipan dari Jepang, Korea, Singapura, hingga seluruh Indonesia.
            </p>
        </div>
        <div class="absolute -right-10 -bottom-10 opacity-20 pointer-events-none hidden md:block">
            <i data-lucide="plane-takeoff" class="w-80 h-80 text-white"></i>
        </div>
    </div>

    <!-- Bar Pilihan Kategori Cepat (Quick Filter Pills) -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2">
        <a href="{{ route('home', array_merge(request()->except('category', 'page'), ['category' => 'Semua'])) }}"
           class="px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition {{ !request('category') || request('category') === 'Semua' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:border-indigo-300' }}">
            Semua Kategori
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('home', array_merge(request()->except('category', 'page'), ['category' => $cat])) }}"
               class="px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition {{ request('category') === $cat ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:border-indigo-300' }}">
                {{ $cat }}
            </a>
        @endforeach
    </div>

    <!-- Main Content Area -->
    <div class="flex flex-col lg:flex-row gap-8">

        <!-- Sidebar Filter Interaktif -->
        <aside class="w-full lg:w-72 space-y-6 shrink-0">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i data-lucide="sliders-horizontal" class="w-4 h-4 text-indigo-600"></i>
                        Filter Titipan
                    </h3>
                    @if(request()->anyFilled(['search', 'category', 'origin_city', 'sort']))
                        <a href="{{ route('home') }}" class="text-xs font-bold text-red-500 hover:underline">Reset</a>
                    @endif
                </div>

                <form method="GET" action="{{ route('home') }}" class="space-y-4">
                    <!-- Input Kata Kunci -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kata Kunci</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama barang / rasa..." class="w-full pl-9 pr-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none transition">
                            <i data-lucide="search" class="w-4 h-4 absolute left-3 top-2.5 text-slate-400"></i>
                        </div>
                    </div>

                    <!-- Filter Kategori -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kategori</label>
                        <select name="category" onchange="this.form.submit()" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="Semua">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Kota / Negara Asal -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kota / Negara Asal</label>
                        <select name="origin_city" onchange="this.form.submit()" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="Semua">Semua Kota / Negara</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('origin_city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Urutkan Berdasarkan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Urutkan</label>
                        <select name="sort" onchange="this.form.submit()" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru Ditambahkan</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga: Termurah</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga: Termahal</option>
                            <option value="quota_desc" {{ request('sort') === 'quota_desc' ? 'selected' : '' }}>Kuota Terbanyak</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-sm transition">
                        Terapkan Filter
                    </button>
                </form>
            </div>
        </aside>

        <!-- Product Cards Grid -->
        <div class="flex-1 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Katalog Barang Jastip Aktif</h2>
                    @if(request('search') || (request('category') && request('category') !== 'Semua') || (request('origin_city') && request('origin_city') !== 'Semua'))
                        <p class="text-xs text-indigo-600 font-semibold mt-0.5">
                            Filter aktif:
                            {{ request('category') && request('category') !== 'Semua' ? '[' . request('category') . '] ' : '' }}
                            {{ request('origin_city') && request('origin_city') !== 'Semua' ? '[' . request('origin_city') . '] ' : '' }}
                            {{ request('search') ? '"' . request('search') . '"' : '' }}
                        </p>
                    @endif
                </div>
                <span class="text-xs text-slate-500 font-medium">Menampilkan {{ $items->count() }} dari {{ $items->total() }} barang</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($items as $item)
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col group">
                        <!-- Gambar & Badge -->
                        <a href="{{ route('catalog.show', $item->id) }}" class="h-48 bg-slate-100 relative overflow-hidden block">
                            <img src="{{ Str::startsWith($item->image, 'http') ? $item->image : asset('storage/'.$item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            
                            <span class="absolute top-3 left-3 bg-slate-900/80 backdrop-blur-md text-white text-xs font-medium px-3 py-1 rounded-full flex items-center gap-1">
                                <i data-lucide="map-pin" class="w-3 h-3 text-amber-400"></i>
                                {{ $item->origin_city }}
                            </span>

                            <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-md text-slate-700 text-xs font-semibold px-2.5 py-1 rounded-full shadow-sm">
                                Kuota: <strong class="{{ $item->quota < 5 ? 'text-red-600' : 'text-emerald-600' }}">{{ $item->quota }}</strong>
                            </span>
                        </a>

                        <!-- Info Produk -->
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">
                                    {{ $item->category ?? 'Makanan & Snack' }}
                                </span>
                                <a href="{{ route('catalog.show', $item->id) }}" class="block mt-2">
                                    <h3 class="font-bold text-slate-900 text-base leading-snug group-hover:text-indigo-600 transition">
                                        {{ $item->name }}
                                    </h3>
                                </a>
                                <p class="text-slate-500 text-xs mt-1.5 line-clamp-2 leading-relaxed">
                                    {{ $item->description }}
                                </p>
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Harga Titip</span>
                                    <span class="text-lg font-extrabold text-indigo-600">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <a href="{{ route('catalog.show', $item->id) }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                                        Detail
                                    </a>
                                    @if($item->quota > 0)
                                        <form action="{{ route('order.store', $item->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1 shadow-sm">
                                                <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i>
                                                Titip
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-3 py-2 bg-slate-100 text-slate-400 rounded-xl text-xs font-bold">Habis</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-slate-200 space-y-3">
                        <i data-lucide="package-search" class="w-12 h-12 text-slate-300 mx-auto"></i>
                        <h4 class="text-base font-bold text-slate-700">Barang titipan tidak ditemukan</h4>
                        <p class="text-slate-400 text-xs">Coba ubah kategori, kota asal, atau kata kunci pencarian.</p>
                        <a href="{{ route('home') }}" class="inline-block mt-2 px-4 py-2 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-xl hover:bg-indigo-100">
                            Tampilkan Semua Barang
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Paginasi -->
            <div class="pt-4">
                {{ $items->links() }}
            </div>
        </div>

    </div>

</div>
@endsection