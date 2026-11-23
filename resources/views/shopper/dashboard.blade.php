@extends('layouts.app')
@section('content')
<div class="space-y-6">
    <!-- Header Banner Jastiper -->
    <div class="bg-gradient-to-r from-[#8E3B56] to-[#742D44] text-white rounded-[2rem] p-7 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shadow-sm">
        <div class="space-y-1">
            <span class="px-3 py-1 rounded-full bg-white/20 text-[11px] font-extrabold">Partner Jastiper Terverifikasi</span>
            <h1 class="font-serif-title text-2xl sm:text-3xl font-bold mt-1">Dashboard Jastiper, {{ $user['name'] }}</h1>
            <p class="text-xs opacity-90">Kelola Pre-Order mancanegara, pantau 4 tahap pengiriman, dan cairkan penghasilan Anda.</p>
        </div>
        <a href="{{ route('jastiper.catalog') }}" class="px-5 py-3 bg-white text-[#742D44] font-extrabold text-xs rounded-full shadow hover:bg-[#FCE4EC] transition shrink-0">
            + Buka PO Baru
        </a>
    </div>

    <!-- 4 Kartu Ringkasan -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
        <div class="bg-white p-5 rounded-3xl border border-[#F8BBD0]">
            <p class="text-[#7A4958] font-bold">Total Pendapatan Kotor</p>
            <p class="text-xl font-black text-[#3D1824] mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-[#F8BBD0]">
            <p class="text-[#7A4958] font-bold">Sudah Dicairkan</p>
            <p class="text-xl font-black text-[#A64D69] mt-1">Rp {{ number_format($totalWithdrawn, 0, ',', '.') }}</p>
        </div>
        <div class="bg-gradient-to-br from-[#FCE4EC] to-white p-5 rounded-3xl border border-[#F48FB1]">
            <p class="text-[#88334E] font-extrabold">Saldo Siap Cair</p>
            <p class="text-xl font-black text-[#88334E] mt-1">Rp {{ number_format($availableBalance, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-[#F8BBD0]">
            <p class="text-[#7A4958] font-bold">Total Titipan Masuk</p>
            <p class="text-xl font-black text-[#3D1824] mt-1">{{ $orders->count() }} Pesanan</p>
        </div>
    </div>

    <!-- Grafik Performa & Form Pencairan Dana -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Grafik Mingguan -->
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-[#F8BBD0] space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="font-extrabold text-sm text-[#3D1824]">Grafik Titipan Masuk (7 Hari Terakhir)</h2>
                <span class="text-[11px] font-bold text-[#A64D69] bg-[#FCE4EC] px-3 py-1 rounded-full">Real-Time</span>
            </div>
            <canvas id="jastiperChart" height="120"></canvas>
        </div>

        <!-- Form Pencairan Saldo ke Rekening Bank -->
        <div class="bg-white p-6 rounded-3xl border border-[#F8BBD0] space-y-4 text-xs">
            <h2 class="font-extrabold text-sm text-[#3D1824] flex items-center gap-2">
                <i data-lucide="wallet" class="w-4 h-4 text-[#A64D69]"></i> Cairkan Penghasilan
            </h2>
            <form action="{{ route('jastiper.withdraw') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block font-bold mb-1">Pilih Rekening Tujuan</label>
                    <select name="bank_account_id" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-3.5 py-2.5 font-semibold">
                        @forelse($bankAccounts as $bank)
                            <option value="{{ $bank->id }}">{{ $bank->provider }} — {{ $bank->account_number }} ({{ $bank->account_holder }})</option>
                        @empty
                            <option value="">Belum ada rekening — tambahkan di Profil</option>
                        @endforelse
                    </select>
                </div>
                <div>
                    <label class="block font-bold mb-1">Nominal Pencairan (Min. Rp 10.000)</label>
                    <input type="number" name="amount" min="10000" max="{{ $availableBalance }}" value="{{ $availableBalance >= 10000 ? $availableBalance : '' }}" placeholder="Masukkan nominal" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-3.5 py-2.5 font-bold">
                </div>
                <button type="submit" class="w-full py-3 bg-[#8E3B56] hover:bg-[#742D44] text-white font-extrabold rounded-full shadow transition">
                    Cairkan ke Rekening &rarr;
                </button>
            </form>

            @if($withdrawals->count() > 0)
                <div class="pt-3 border-t border-[#FCE4EC] space-y-2">
                    <p class="font-extrabold text-[11px] text-[#7A4958]">Riwayat Pencairan Terakhir</p>
                    @foreach($withdrawals->take(3) as $wd)
                        <div class="flex justify-between items-center bg-[#FFF7FA] p-2.5 rounded-xl border border-[#FCE4EC]">
                            <div>
                                <p class="font-bold">{{ $wd->provider }} ({{ $wd->account_number }})</p>
                                <p class="text-[10px] opacity-70">{{ $wd->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <span class="font-black text-emerald-700">Rp {{ number_format($wd->amount, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('jastiperChart');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Jumlah Titipan',
                data: @json($chartData),
                backgroundColor: '#F48FB1',
                borderColor: '#A64D69',
                borderWidth: 1.5,
                borderRadius: 10
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });
}
</script>
@endsection