@extends('layouts.app')
@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-[2rem] border-2 border-dashed border-[#F497B0] shadow-sm space-y-5">
    <div class="text-center border-b border-dashed border-[#F5C6CE] pb-4">
        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-[11px] font-extrabold rounded-full">PEMBAYARAN LUNAS</span>
        <h1 class="font-coquette text-2xl font-bold mt-2">Bukti Resi TitipGo</h1>
        <p class="text-xs font-mono opacity-75 mt-1">{{ $order->order_code }} • {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
    </div>

    <div class="space-y-2.5 text-xs">
        <div class="flex justify-between"><span class="opacity-70">Nama Pemesan:</span><strong>{{ $order->buyer_name }}</strong></div>
        <div class="flex justify-between"><span class="opacity-70">Email:</span><strong>{{ $order->buyer_email }}</strong></div>
        <div class="flex justify-between"><span class="opacity-70">Makanan Dipesan:</span><strong>{{ $order->item->name }} ({{ $order->quantity }}x)</strong></div>
        <div class="flex justify-between"><span class="opacity-70">Asal & Estimasi:</span><strong>{{ $order->item->estimated_shipping }}</strong></div>
        <div class="flex justify-between"><span class="opacity-70">Metode Pembayaran:</span><strong>{{ $order->payment_method }}</strong></div>
        <div class="flex justify-between text-base font-black text-[#C94A6E] pt-3 border-t border-[#F5C6CE]">
            <span>Total Dibayarkan:</span>
            <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="flex gap-3 pt-2">
        <button onclick="window.print()" class="flex-1 py-3 bg-[#FFF0F4] text-[#C94A6E] font-extrabold text-xs rounded-full border border-[#F5B8C8]">Simpan / Cetak Resi</button>
        <a href="{{ route('buyer.track') }}" class="flex-1 py-3 bg-[#E88565] text-white text-center font-extrabold text-xs rounded-full">Lacak Pesanan</a>
    </div>
</div>
@endsection