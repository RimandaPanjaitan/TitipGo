@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded-[2rem] border border-[#F7D6C4] shadow-sm space-y-6">
    <div>
        <span class="px-3 py-1 bg-[#FFE8EE] text-[#C94A6E] text-xs font-extrabold rounded-full">Kode Pesanan: {{ $order->order_code }}</span>
        <h1 class="font-coquette text-2xl font-bold mt-2">Pilih Metode Pembayaran</h1>
        <p class="text-xs opacity-75">Selesaikan pembayaran untuk mengunci kuota bagasi {{ $order->item->origin_country }}.</p>
    </div>

    <div class="bg-[#FFF9F5] p-4 rounded-2xl border border-[#F7D6C4] space-y-1.5 text-xs">
        <div class="flex justify-between"><span>Produk:</span><strong>{{ $order->item->name }} ({{ $order->quantity }}x)</strong></div>
        <div class="flex justify-between"><span>Estimasi Pengiriman:</span><strong class="text-[#C94A6E]">{{ $order->item->estimated_shipping }}</strong></div>
        <div class="flex justify-between text-base font-black text-[#D46A4C] pt-2 border-t border-[#F7D6C4]">
            <span>Total Tagihan:</span>
            <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>
    </div>

    <form action="{{ route('buyer.payment.confirm', $order->id) }}" method="POST" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 gap-2.5">
            @foreach(['PayPal', 'BRI', 'BNI', 'BCA', 'Amazon Wallet'] as $method)
                <label class="flex items-center justify-between p-3.5 rounded-2xl border border-[#F5CBB7] hover:bg-[#FFF5EE] cursor-pointer">
                    <span class="text-xs font-extrabold">{{ $method }}</span>
                    <input type="radio" name="payment_method" value="{{ $method }}" {{ $loop->first ? 'checked' : '' }} class="accent-[#E88565]">
                </label>
            @endforeach
        </div>
        <button type="submit" class="w-full py-3.5 bg-[#E16B8C] hover:bg-[#C94A6E] text-white font-extrabold text-xs rounded-full shadow">
            Konfirmasi Pembayaran Sekarang
        </button>
    </form>
</div>
@endsection