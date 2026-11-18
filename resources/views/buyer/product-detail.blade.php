@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-[2rem] border border-[#F7D6C4] overflow-hidden shadow-sm grid grid-cols-1 md:grid-cols-2">
    <img src="{{ Str::startsWith($item->image, 'http') ? $item->image : asset('storage/'.$item->image) }}" class="w-full h-80 md:h-full object-cover">
    <div class="p-7 flex flex-col justify-between space-y-5">
        <div class="space-y-3">
            <span class="px-3 py-1 bg-[#FFF0E6] text-[#D46A4C] text-xs font-extrabold rounded-full">{{ $item->category }}</span>
            <h1 class="font-coquette text-2xl font-bold">{{ $item->name }}</h1>
            <p class="text-2xl font-black text-[#C94A6E]">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
            <div class="bg-[#FFF9F5] p-3.5 rounded-2xl border border-[#F7D6C4] space-y-1 text-xs">
                <p><strong>Asal Negara:</strong> {{ $item->origin_city }}</p>
                <p class="text-[#C94A6E] font-bold">Estimasi Tiba: {{ $item->estimated_shipping }}</p>
                <p class="flex items-center gap-1"><strong>Jastiper:</strong> {{ $item->jastiper_name }} <i data-lucide="badge-check" class="w-4 h-4 text-[#E88565]"></i></p>
            </div>
            <p class="text-xs opacity-80 leading-relaxed">{{ $item->description }}</p>
        </div>

        <form action="{{ route('buyer.order.create', $item->id) }}" method="POST" class="space-y-3 pt-4 border-t border-[#FFF0E6]">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold mb-1">Jumlah Titip</label>
                    <input type="number" name="quantity" value="1" min="1" max="{{ $item->quota }}" required class="w-full border border-[#F5CBB7] rounded-xl px-3 py-2 text-xs font-bold">
                </div>
                <div>
                    <label class="block text-[11px] font-bold mb-1">No. Telepon</label>
                    <input type="text" name="buyer_phone" value="{{ $user['phone'] }}" required class="w-full border border-[#F5CBB7] rounded-xl px-3 py-2 text-xs">
                </div>
            </div>
            <input type="hidden" name="buyer_name" value="{{ $user['name'] }}">
            <div>
                <label class="block text-[11px] font-bold mb-1">Alamat Tujuan di Indonesia</label>
                <input type="text" name="shipping_address" value="{{ $user['address'] }}" required class="w-full border border-[#F5CBB7] rounded-xl px-3 py-2 text-xs">
            </div>
            <button type="submit" class="w-full py-3.5 bg-[#E88565] hover:bg-[#D97252] text-white font-extrabold text-xs rounded-full shadow">
                Buat Pesanan & Lanjut Bayar
            </button>
        </form>
    </div>
</div>
@endsection