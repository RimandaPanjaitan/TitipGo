@extends('layouts.app')
@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-[2rem] border border-[#F5C6CE] shadow-sm space-y-6 text-center">
    <div class="w-16 h-16 mx-auto rounded-full bg-[#FFE8EE] text-[#E16B8C] flex items-center justify-center">
        <i data-lucide="navigation" class="w-8 h-8"></i>
    </div>
    <div class="space-y-1">
        <h1 class="font-coquette text-2xl font-bold">Nyalakan Lokasi Anda</h1>
        <p class="text-xs opacity-75">Aktifkan deteksi lokasi otomatis atau pilih kota untuk estimasi pengiriman internasional ke Indonesia.</p>
    </div>

    <button type="button" onclick="detectGPS()" class="w-full py-3 bg-[#FFF0F4] border border-[#F5B8C8] text-[#C94A6E] font-extrabold text-xs rounded-full flex items-center justify-center gap-2 hover:bg-[#FFE3EB] transition">
        <i data-lucide="crosshair" class="w-4 h-4"></i>
        <span id="gpsBtnText">Deteksi Lokasi Otomatis (GPS)</span>
    </button>

    <form action="{{ route('location.save') }}" method="POST" class="space-y-4 text-left">
        @csrf
        <input type="hidden" name="next" value="{{ $next }}">
        <div>
            <label class="block text-xs font-bold mb-1">Kota / Kabupaten Tujuan</label>
            <input type="text" id="locCity" name="city" value="{{ session('user_city', 'Laguboti, Toba') }}" required class="w-full border border-[#F5C6CE] rounded-2xl px-4 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-xs font-bold mb-1">Detail Alamat Lengkap</label>
            <textarea id="locAddr" name="address" rows="2" required class="w-full border border-[#F5C6CE] rounded-2xl px-4 py-2.5 text-sm">{{ session('user_address', 'Sitoluama, Laguboti, Toba, Sumatera Utara') }}</textarea>
        </div>
        <button type="submit" class="w-full py-3.5 bg-[#E88565] hover:bg-[#D97252] text-white font-extrabold text-xs rounded-full shadow">
            Simpan & Lanjutkan
        </button>
    </form>
</div>
<script>
function detectGPS() {
    document.getElementById('gpsBtnText').textContent = 'Lokasi Ditemukan: Laguboti, Toba ✓';
    document.getElementById('locCity').value = 'Laguboti, Toba';
    document.getElementById('locAddr').value = 'Jl. Sisingamangaraja, Sitoluama, Laguboti, Toba, Sumatera Utara';
}
</script>
@endsection