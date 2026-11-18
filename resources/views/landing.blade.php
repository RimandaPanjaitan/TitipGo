@extends('layouts.app')
@section('content')
<div class="space-y-10">
    <section class="bg-gradient-to-br from-[#FFE4D6] via-[#FFD6E0] to-[#FFF0E8] border border-[#F5C6CE] rounded-[2rem] p-8 sm:p-12 shadow-sm grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
        <div class="space-y-5">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-white/80 text-[#C94A6E] text-xs font-bold border border-[#F5B8C8]">
                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> Lolos Bea Cukai & Karantina Indonesia
            </span>
            <h1 class="font-coquette text-4xl sm:text-5xl font-bold text-[#4A2530] leading-tight">
                Titip Makanan Mancanegara dengan Sentuhan Manis
            </h1>
            <p class="text-sm text-[#7A4B56] leading-relaxed">
                Pilih peran Anda untuk masuk: nikmati jajanan tersegel dari Jepang, Korea, Eropa & Singapura sebagai Pembeli, atau kelola PO perjalanan Anda sebagai Jastiper.
            </p>
            <div class="flex flex-wrap gap-3 pt-2">
                <a href="{{ route('auth', ['role' => 'buyer', 'mode' => 'login']) }}" class="px-6 py-3.5 bg-[#E88565] hover:bg-[#D97252] text-white font-extrabold text-xs rounded-full shadow-md transition">
                    Masuk / Daftar Pembeli
                </a>
                <a href="{{ route('auth', ['role' => 'jastiper', 'mode' => 'login']) }}" class="px-6 py-3.5 bg-[#E16B8C] hover:bg-[#C94A6E] text-white font-extrabold text-xs rounded-full shadow-md transition">
                    Masuk / Daftar Jastiper
                </a>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            @foreach($featured as $item)
                <a href="{{ route('buyer.item.show', $item->id) }}" class="bg-white/85 backdrop-blur p-3.5 rounded-3xl border border-[#F7D6C4] hover:shadow-md transition">
                    <img src="{{ Str::startsWith($item->image, 'http') ? $item->image : asset('storage/'.$item->image) }}" class="w-full h-28 object-cover rounded-2xl mb-2.5">
                    <span class="text-[10px] font-bold text-[#D46A4C]">{{ $item->origin_country }}</span>
                    <h3 class="text-xs font-extrabold truncate">{{ $item->name }}</h3>
                    <p class="text-xs font-black text-[#C94A6E] mt-0.5">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                </a>
            @endforeach
        </div>
    </section>
</div>
@endsection