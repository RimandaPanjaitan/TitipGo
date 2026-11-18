@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <h1 class="font-coquette text-2xl font-bold">Lacak Pesanan & Bukti Resi</h1>
    @foreach($orders as $order)
        <div class="bg-white p-6 rounded-3xl border border-[#F7D6C4] space-y-4">
            <div class="flex flex-col sm:flex-row justify-between gap-2">
                <div>
                    <span class="text-xs font-extrabold text-[#C94A6E]">{{ $order->order_code }} • {{ $order->item->estimated_shipping }}</span>
                    <h3 class="font-bold text-base">{{ $order->item->name }} ({{ $order->quantity }}x)</h3>
                </div>
                <div class="flex items-center gap-2">
                    @if($order->payment_status === 'Menunggu Pembayaran')
                        <a href="{{ route('buyer.payment', $order->id) }}" class="px-3.5 py-1.5 bg-[#E16B8C] text-white text-xs font-bold rounded-full">Bayar Sekarang</a>
                    @else
                        <a href="{{ route('buyer.receipt', $order->id) }}" class="px-3.5 py-1.5 bg-[#FFF0E6] text-[#D46A4C] text-xs font-bold rounded-full">Lihat Resi</a>
                    @endif
                </div>
            </div>

            <!-- Stepper 4 Tahap -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-[11px]">
                @foreach([1 => '1. Dibeli di Asal', 2 => '2. Bea Cukai & Terbang', 3 => '3. Kurir Lokal Antar', 4 => '4. Selesai Diterima'] as $step => $label)
                    <div class="p-2.5 rounded-2xl border {{ $order->step_status >= $step ? 'bg-[#FFE8EE] border-[#F497B0] font-extrabold text-[#9E3352]' : 'bg-slate-50 border-slate-200 opacity-50' }}">
                        {{ $label }}
                    </div>
                @endforeach
            </div>

            <!-- Info Kendaraan & Plat Nomor Muncul saat Tahap 3 / 4 (Pengantaran ke Alamat Pelanggan) -->
            @if($order->step_status >= 3 && $order->courier_vehicle)
                <div class="bg-[#FFF5EE] border border-[#F5CBB7] p-3.5 rounded-2xl flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <i data-lucide="bike" class="w-5 h-5 text-[#D46A4C]"></i>
                        <div>
                            <p class="font-extrabold">Kurir Lokal Menuju Alamat Anda</p>
                            <p class="opacity-80">Kendaraan: <strong>{{ $order->courier_vehicle }}</strong> • Plat Nomor: <strong class="bg-white px-2 py-0.5 rounded border">{{ $order->courier_plate }}</strong></p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endforeach
</div>
@endsection