@extends('layouts.app')
@section('content')
<div class="space-y-8">
    <div class="bg-gradient-to-r from-[#FFE4D6] to-[#FFD9E4] border border-[#F5C6CE] rounded-[2rem] p-6 sm:p-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-1.5">
                <h1 class="font-coquette text-2xl sm:text-3xl font-bold">Halo, {{ $user['name'] }}</h1>
                <i data-lucide="badge-check" class="w-6 h-6 text-[#E88565] fill-white"></i>
            </div>
            <p class="text-xs opacity-80 mt-1">{{ $user['email'] }} • Lokasi Aktif: <strong>{{ $user['city'] }}</strong></p>
        </div>
        <a href="{{ route('buyer.catalog') }}" class="px-5 py-3 bg-[#E88565] text-white font-extrabold text-xs rounded-full shadow">Jelajahi Katalog Makanan</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-[#F7D6C4] flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold uppercase opacity-60">Total Belanja Jastip</span>
                <p class="text-3xl font-black text-[#D46A4C] mt-1">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
                <p class="text-xs opacity-70 mt-2">Total {{ $orders->count() }} transaksi dari Jepang, Korea, Swiss, Prancis & Singapura.</p>
            </div>
            <a href="{{ route('buyer.track') }}" class="mt-4 inline-block text-xs font-extrabold text-[#C94A6E] underline">Lihat Semua Resi & Pelacakan &rarr;</a>
        </div>

        <!-- Grafik Pengeluaran Pembeli -->
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-[#F7D6C4]">
            <h3 class="text-sm font-extrabold mb-3">Grafik Pengeluaran Jastip Bulanan</h3>
            <canvas id="buyerChart" height="95"></canvas>
        </div>
    </div>
</div>
<script>
new Chart(document.getElementById('buyerChart'), {
    type: 'bar',
    data: {
        labels: ['Jul', 'Agu', 'Sep', 'Okt', 'Nov'],
        datasets: [{
            label: 'Total Belanja (Rp)',
            data: [320000, 540000, 410000, 780000, {{ $totalSpent ?: 1410000 }}],
            backgroundColor: ['#F8C8D4', '#F6B89E', '#F497B0', '#E88565', '#E16B8C'],
            borderRadius: 12
        }]
    },
    options: { plugins: { legend: { display: false } } }
});
</script>
@endsection