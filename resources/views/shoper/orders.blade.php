@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto space-y-5">
    <h1 class="font-coquette text-2xl font-bold">Kelola Tahapan Pesanan (1 → 2 → 3 → 4)</h1>
    @foreach($orders as $order)
        <div class="bg-white p-6 rounded-3xl border border-[#F5B8C8] space-y-4">
            <div class="flex flex-col sm:flex-row justify-between gap-2">
                <div>
                    <span class="text-xs font-extrabold text-[#C94A6E]">{{ $order->order_code }} • Pemesan: {{ $order->buyer_name }}</span>
                    <h3 class="font-bold text-base">{{ $order->item->name }} ({{ $order->quantity }}x)</h3>
                    <p class="text-xs opacity-75">Status Saat Ini: <strong>{{ $order->status_label }}</strong></p>
                </div>

                @if($order->step_status < 4)
                    <form action="{{ route('jastiper.orders.advance', $order->id) }}" method="POST" class="flex flex-wrap items-center gap-2">
                        @csrf @method('PATCH')
                        @if($order->step_status == 2)
                            <input type="text" name="courier_vehicle" placeholder="Jenis Kendaraan Lokal" required class="text-xs border border-[#F5B8C8] rounded-xl px-3 py-2">
                            <input type="text" name="courier_plate" placeholder="Plat Nomor" required class="text-xs border border-[#F5B8C8] rounded-xl px-3 py-2 w-28">
                        @endif
                        <button type="submit" class="px-4 py-2 bg-[#E16B8C] hover:bg-[#C94A6E] text-white text-xs font-extrabold rounded-full">
                            Lanjut ke Tahap {{ $order->step_status + 1 }} &rarr;
                        </button>
                    </form>
                @else
                    <span class="px-4 py-2 bg-emerald-50 text-emerald-700 text-xs font-extrabold rounded-full h-fit">Tahap 4 Selesai ✓</span>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection