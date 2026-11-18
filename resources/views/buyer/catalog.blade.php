@extends('layouts.app')
@section('content')
<div class="space-y-6">
    <div class="bg-white p-6 rounded-3xl border border-[#F7D6C4] space-y-4">
        <div class="flex flex-col md:flex-row justify-between gap-4">
            <div>
                <h1 class="font-coquette text-2xl font-bold">Katalog Makanan Lolos Bea Cukai & Karantina</h1>
                <p class="text-xs opacity-75">Seluruh produk tersegel pabrik resmi dan estimasi tiba dihitung otomatis dari negara asal.</p>
            </div>
            <form method="GET" action="{{ route('buyer.catalog') }}" class="flex flex-wrap gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Royce, Tokyo Banana..." class="text-xs border border-[#F5CBB7] rounded-full px-4 py-2">
                <select name="country" onchange="this.form.submit()" class="text-xs border border-[#F5CBB7] rounded-full px-3 py-2 bg-white">
                    <option value="Semua">Semua Negara</option>
                    @foreach($countries as $c)
                        <option value="{{ $c }}" {{ request('country') === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-[#E88565] text-white text-xs font-bold rounded-full">Cari</button>
            </form>
        </div>

        <!-- Filter 7 Kategori -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1">
            <a href="{{ route('buyer.catalog') }}" class="px-3.5 py-1.5 rounded-full text-[11px] font-bold whitespace-nowrap {{ !request('category') || request('category') === 'Semua' ? 'bg-[#E88565] text-white' : 'bg-[#FFF5EE] text-[#7A5648]' }}">Semua</a>
            @foreach($categories as $cat)
                <a href="{{ route('buyer.catalog', ['category' => $cat]) }}" class="px-3.5 py-1.5 rounded-full text-[11px] font-bold whitespace-nowrap {{ request('category') === $cat ? 'bg-[#E88565] text-white' : 'bg-[#FFF5EE] text-[#7A5648]' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($items as $item)
            <div class="bg-white rounded-3xl border border-[#F7D6C4] overflow-hidden flex flex-col justify-between shadow-sm hover:shadow-md transition">
                <div>
                    <div class="h-44 relative">
                        <img src="{{ Str::startsWith($item->image, 'http') ? $item->image : asset('storage/'.$item->image) }}" class="w-full h-full object-cover">
                        <span class="absolute top-3 left-3 bg-white/95 text-[#C94A6E] text-[11px] font-extrabold px-3 py-1 rounded-full shadow-sm">{{ $item->origin_city }}</span>
                        <span class="absolute top-3 right-3 bg-[#4A2530]/85 text-white text-[11px] font-bold px-2.5 py-1 rounded-full">Kuota: {{ $item->quota }}</span>
                    </div>
                    <div class="p-5 space-y-2">
                        <span class="text-[10px] font-extrabold uppercase text-[#D46A4C] bg-[#FFF0E6] px-2.5 py-0.5 rounded-full">{{ $item->category }}</span>
                        <h3 class="font-bold text-base leading-snug">{{ $item->name }}</h3>
                        <p class="text-xs opacity-75 line-clamp-2">{{ $item->description }}</p>
                        <div class="flex items-center gap-1 text-[11px] font-semibold text-[#C94A6E] pt-1">
                            <i data-lucide="plane" class="w-3.5 h-3.5"></i>
                            <span>{{ $item->estimated_shipping }}</span>
                        </div>
                        <div class="flex items-center gap-1 text-[11px] opacity-80">
                            <span>Jastiper: <strong>{{ $item->jastiper_name }}</strong></span>
                            <i data-lucide="badge-check" class="w-3.5 h-3.5 text-[#E88565]"></i>
                        </div>
                    </div>
                </div>
                <div class="p-5 pt-3 border-t border-[#FFF0E6] flex items-center justify-between">
                    <span class="text-lg font-black text-[#D46A4C]">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    <a href="{{ route('buyer.item.show', $item->id) }}" class="px-4 py-2 bg-[#E88565] hover:bg-[#D97252] text-white text-xs font-extrabold rounded-full">Titip Sekarang</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection