@extends('layouts.app')
@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-[2rem] border border-[#F8BBD0] shadow-sm space-y-5">
    <div class="text-center space-y-1">
        <div class="w-12 h-12 rounded-2xl bg-[#FCE4EC] text-[#D81B60] flex items-center justify-center mx-auto mb-2">
            <i data-lucide="map-pin" class="w-6 h-6"></i>
        </div>
        <h1 class="font-serif-title text-2xl font-bold text-[#5C1A36]">Aktifkan Lokasi Anda</h1>
        <p class="text-xs text-[#8E4A67]">Halo <strong>{{ session('user_name') }}</strong>, konfirmasi kota & alamat pengiriman Anda sebelum masuk ke dashboard.</p>
    </div>

    <form action="{{ route('location.save') }}" method="POST" class="space-y-4 text-xs">
        @csrf
        <input type="hidden" name="next" value="{{ $next ?? (session('is_jastiper') ? 'jastiper' : 'buyer') }}">

        <div>
            <label class="block font-bold mb-1 text-[#5C1A36]">Kota / Kabupaten *</label>
            <input type="text" name="city" value="{{ session('user_city', 'Laguboti, Toba') }}" required class="w-full border border-[#F8BBD0] focus:border-[#EC407A] focus:outline-none rounded-2xl px-4 py-2.5">
        </div>

        <div>
            <label class="block font-bold mb-1 text-[#5C1A36]">Alamat Lengkap *</label>
            <textarea name="address" rows="2" required class="w-full border border-[#F8BBD0] focus:border-[#EC407A] focus:outline-none rounded-2xl px-4 py-2.5">{{ session('user_address', 'Sitoluama, Laguboti, Toba, Sumatera Utara') }}</textarea>
        </div>

        <button type="submit" class="w-full py-3.5 bg-[#EC407A] hover:bg-[#D81B60] text-white font-extrabold text-xs rounded-full shadow transition">
            Simpan Lokasi & Lanjutkan &rarr;
        </button>
    </form>
</div>
@endsection