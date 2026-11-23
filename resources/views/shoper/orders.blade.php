@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div>
        <h1 class="font-serif-title text-2xl sm:text-3xl font-bold text-[#3D1824]">Kelola Pesanan Pembeli (4 Tahap)</h1>
        <p class="text-xs text-[#7A4958]">Perbarui progres pembelian dan pengantaran kurir lokal agar pembeli menerima notifikasi real-time.</p>
    </div>

    @forelse($orders as $order)
        <div class="bg-white p-6 rounded-3xl border border-[#F8BBD0] shadow-2xs space-y-4 text-xs">
            <div class="flex flex-col sm:flex-row justify-between gap-2 border-b border-[#FCE4EC] pb-3">
                <div>
                    <span class="font-extrabold text-[#A64D69]">{{ $order->order_code }} • {{ $order->payment_status }}</span>
                    <h3 class="text-base font-extrabold text-[#3D1824]">{{ $order->item->name }} ({{ $order->quantity }}x)</h3>
                    <p class="text-[#7A4958]">Pemesan: <strong>{{ $order->buyer_name }}</strong> ({{ $order->buyer_phone }}) — {{ $order->shipping_address }}</p>
                </div>
                <div class="sm:text-right">
                    <p class="font-black text-base text-[#8E3B56]">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    <span class="inline-block mt-1 px-3 py-1 rounded-full bg-[#FCE4EC] text-[#88334E] font-extrabold text-[11px]">{{ $order->status_label }}</span>
                </div>
            </div>

            <!-- Form Update Tahap Pengiriman -->
            <form action="{{ route('jastiper.orders.status', $order->id) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                @csrf
                @method('PATCH')
                <div class="sm:col-span-2">
                    <label class="block font-bold mb-1">Pilih Tahap Pengiriman Terbaru</label>
                    <select name="step_status" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-3.5 py-2.5 font-bold">
                        <option value="1" {{ $order->step_status == 1 ? 'selected' : '' }}>1. Pesanan Diterima & Dibeli di Negara Asal</option>
                        <option value="2" {{ $order->step_status == 2 ? 'selected' : '' }}>2. Lolos Bea Cukai & Penerbangan ke Indonesia</option>
                        <option value="3" {{ $order->step_status == 3 ? 'selected' : '' }}>3. Diantar Kurir Lokal ke Alamat Pembeli</option>
                        <option value="4" {{ $order->step_status == 4 ? 'selected' : '' }}>4. Pesanan Tiba & Selesai</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold mb-1">Kendaraan Kurir (Tahap 3)</label>
                    <input type="text" name="courier_vehicle" value="{{ $order->courier_vehicle ?? 'Honda Vario 160 Hitam' }}" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-3.5 py-2.5">
                </div>
                <div class="flex gap-2">
                    <div class="flex-1">
                        <label class="block font-bold mb-1">Plat Nomor</label>
                        <input type="text" name="courier_plate" value="{{ $order->courier_plate ?? 'BB 4821 XW' }}" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-3 py-2.5">
                    </div>
                    <button type="submit" class="px-4 py-2.5 bg-[#8E3B56] hover:bg-[#742D44] text-white font-extrabold rounded-2xl self-end">
                        Update
                    </button>
                </div>
            </form>
        </div>
    @empty
        <div class="bg-white p-8 rounded-3xl border border-[#F8BBD0] text-center text-xs text-[#7A4958]">
            Belum ada pesanan masuk saat ini.
        </div>
    @endforelse
</div>
@endsection