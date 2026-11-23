@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-[2rem] border border-[#F8BBD0] overflow-hidden shadow-sm grid grid-cols-1 md:grid-cols-2">
    <img src="{{ Str::startsWith($item->image, 'http') ? $item->image : asset('storage/'.$item->image) }}" class="w-full h-80 md:h-full object-cover">
    <div class="p-7 flex flex-col justify-between space-y-5">
        <div class="space-y-3">
            <div class="flex items-center justify-between gap-2">
                <span class="px-3 py-1 bg-[#FCE4EC] text-[#C2185B] text-xs font-extrabold rounded-full">{{ $item->category }}</span>
                <span class="text-xs font-extrabold text-[#D81B60]">Sisa Kuota: {{ $item->quota }} slot</span>
            </div>
            <h1 class="font-serif-title text-2xl font-bold text-[#522238]">{{ $item->name }}</h1>
            <p class="text-2xl font-black text-[#D81B60]">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
            <div class="bg-[#FFF5F8] p-3.5 rounded-2xl border border-[#F8BBD0] space-y-1 text-xs">
                <p><strong>Asal Negara:</strong> {{ $item->origin_city }}</p>
                <p class="text-[#C2185B] font-bold">Estimasi Tiba: {{ $item->estimated_shipping }}</p>
                <p class="flex items-center gap-1"><strong>Jastiper:</strong> {{ $item->jastiper_name }} <i data-lucide="badge-check" class="w-4 h-4 text-[#EC407A]"></i></p>
            </div>
            <p class="text-xs opacity-80 leading-relaxed">{{ $item->description }}</p>
        </div>

        @if($item->quota > 0)
            <form action="{{ route('buyer.order.create', $item->id) }}" method="POST" class="space-y-3 pt-4 border-t border-[#FCE4EC] text-xs">
                @csrf
                <div>
                    <label class="block font-bold mb-1">Nama Pemesan *</label>
                    <input type="text" name="buyer_name" value="{{ session('user_name', $user['name'] ?? '') }}" placeholder="Masukkan nama pemesan" required class="w-full border border-[#F8BBD0] rounded-xl px-3 py-2 font-semibold">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold mb-1">Jumlah Titip *</label>
                        <input type="number" name="quantity" value="1" min="1" max="{{ $item->quota }}" required class="w-full border border-[#F8BBD0] rounded-xl px-3 py-2 font-bold">
                    </div>
                    <div>
                        <label class="block font-bold mb-1">No. Telepon *</label>
                        <input type="text" name="buyer_phone" value="{{ session('user_phone', $user['phone'] ?? '0812-6489-2026') }}" required class="w-full border border-[#F8BBD0] rounded-xl px-3 py-2">
                    </div>
                </div>
                <div>
                    <label class="block font-bold mb-1">Alamat Tujuan di Indonesia *</label>
                    <input type="text" name="shipping_address" value="{{ session('user_address', $user['address'] ?? 'Sitoluama, Laguboti, Toba, Sumatera Utara') }}" required class="w-full border border-[#F8BBD0] rounded-xl px-3 py-2">
                </div>
                <button type="submit" class="w-full py-3.5 bg-[#EC407A] hover:bg-[#D81B60] text-white font-extrabold text-xs rounded-full shadow transition">
                    Buat Pesanan & Lanjut Bayar &rarr;
                </button>
            </form>
        @else
            <div class="p-3 bg-[#FCE4EC] text-center text-xs font-extrabold text-[#C2185B] rounded-2xl">
                Kuota Bagasi Sudah Penuh
            </div>
        @endif
    </div>
</div>
@endsection