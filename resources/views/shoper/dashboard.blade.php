@extends('layouts.app')
@section('content')
<div class="space-y-8">
    <div class="bg-gradient-to-r from-[#F497B0] to-[#E16B8C] rounded-[2rem] p-6 sm:p-8 text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 shadow-sm">
        <div>
            <div class="flex items-center gap-1.5">
                <h1 class="font-coquette text-2xl sm:text-3xl font-bold">{{ $user['name'] }}</h1>
                <i data-lucide="badge-check" class="w-6 h-6 text-white fill-[#C94A6E]"></i>
            </div>
            <p class="text-xs text-pink-100 mt-1">{{ $user['email'] }} • Saldo Tersedia: <strong>Rp {{ number_format($availableBalance, 0, ',', '.') }}</strong></p>
        </div>
        <a href="{{ route('jastiper.catalog') }}" class="px-5 py-3 bg-white text-[#C94A6E] font-extrabold text-xs rounded-full shadow">+ Buka PO & Tambah Produk</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Pencairan Dana ke BNI, BRI, PayPal, Amazon Wallet -->
        <div class="bg-white p-6 rounded-3xl border border-[#F5B8C8] space-y-4">
            <h3 class="font-bold text-sm">Cairkan Dana Pendapatan</h3>
            <p class="text-2xl font-black text-[#C94A6E]">Rp {{ number_format($availableBalance, 0, ',', '.') }}</p>
            <form action="{{ route('jastiper.withdraw') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold mb-1">Pilih Rekening / Wallet Tujuan</label>
                    <select name="bank_id" class="w-full border border-[#F5B8C8] rounded-xl px-3 py-2 text-xs bg-white">
                        @foreach($banks as $b)
                            <option value="{{ $b->id }}">{{ $b->provider }} — {{ $b->account_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold mb-1">Nominal Pencairan (Rp)</label>
                    <input type="number" name="amount" value="100000" min="10000" max="{{ max(10000, $availableBalance) }}" required class="w-full border border-[#F5B8C8] rounded-xl px-3 py-2 text-xs font-bold">
                </div>
                <button type="submit" class="w-full py-2.5 bg-[#E16B8C] hover:bg-[#C94A6E] text-white font-extrabold text-xs rounded-full">
                    Tarik Dana Sekarang
                </button>
            </form>
        </div>

        <!-- Grafik Performa Mingguan & Pendapatan -->
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-[#F5B8C8]">
            <h3 class="font-bold text-sm mb-3">Grafik Performa & Pendapatan Mingguan</h3>
            <canvas id="shopperChart" height="100"></canvas>
        </div>
    </div>
</div>
<script>
new Chart(document.getElementById('shopperChart'), {
    type: 'line',
    data: {
        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
        datasets: [{
            label: 'Pendapatan PO (Rp)',
            data: [235000, 420000, 310000, 580000, 470000, 690000, {{ $grossEarnings ?: 1390000 }}],
            borderColor: '#E16B8C',
            backgroundColor: 'rgba(244, 151, 176, 0.25)',
            fill: true,
            tension: 0.4
        }]
    }
});
</script>
@endsection