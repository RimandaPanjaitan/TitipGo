@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Hero Banner dari Figma AI -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 md:p-10 text-white shadow-lg">
        <h1 class="text-3xl md:text-4xl font-bold">Mau Titip Oleh-oleh dari Kota Mana?</h1>
        <p class="mt-2 text-indigo-100 max-w-xl">Temukan Jastiper terpercaya dari 38 provinsi yang siap membawakan pesananmu.</p>
    </div>

    <!-- Layout Grid Katalog dari Figma AI -->
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Sidebar Filter -->
        <aside class="w-full md:w-64 bg-white p-5 rounded-xl border border-slate-200 h-fit">
            <h3 class="font-bold text-slate-900 border-b pb-2">Filter Titipan</h3>
            <form method="GET" action="{{ route('home') }}" class="mt-4 space-y-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / kota..." class="w-full text-sm border-slate-300 rounded-lg">
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg text-sm font-semibold">Terapkan Filter</button>
            </form>
        </aside>

        <!-- Product Cards Grid -->
        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($items as $item)
                <div class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-md transition">
                    <div class="h-48 bg-slate-100 relative">
                        <img src="{{ $item->image ? asset('storage/'.$item->image) : 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=500' }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                        <span class="absolute top-3 right-3 bg-white/90 text-indigo-600 font-semibold text-xs px-2.5 py-1 rounded-full">
                            {{ $item->origin_city }}
                        </span>
                    </div>
                    <div class="p-4 space-y-3">
                        <h4 class="font-bold text-slate-800 text-lg">{{ $item->name }}</h4>
                        <p class="text-slate-500 text-sm line-clamp-2">{{ $item->description }}</p>
                        <div class="flex items-center justify-between border-t pt-3">
                            <div>
                                <span class="text-xs text-slate-400 block">Harga Titip</span>
                                <span class="font-extrabold text-indigo-600 text-base">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            </div>
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
                    <p class="text-slate-500">Belum ada item jastip yang tersedia.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection