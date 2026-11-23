@extends('layouts.app')
@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-[2rem] border border-[#F8BBD0] shadow-sm space-y-6">
    <div class="grid grid-cols-2 gap-2 p-1.5 rounded-full bg-[#FCE4EC]">
        <a href="{{ route('auth', ['role' => 'buyer', 'mode' => $mode]) }}" class="py-2 text-center text-xs font-extrabold rounded-full transition {{ $role === 'buyer' ? 'bg-[#A64D69] text-white shadow-sm' : 'text-[#7A2E47]' }}">
            Akun Pembeli
        </a>
        <a href="{{ route('auth', ['role' => 'jastiper', 'mode' => $mode]) }}" class="py-2 text-center text-xs font-extrabold rounded-full transition {{ $role === 'jastiper' ? 'bg-[#88334E] text-white shadow-sm' : 'text-[#7A2E47]' }}">
            Akun Jastiper
        </a>
    </div>

    <div class="text-center space-y-1">
        <h1 class="font-serif-title text-2xl font-bold text-[#4A1D2C]">
            {{ $mode === 'signup' ? 'Daftar Akun TitipGo' : 'Masuk ke TitipGo' }}
        </h1>
        <p class="text-xs text-[#8E5668]">Lengkapi identitas, alamat lengkap, dan kata sandi terverifikasi untuk melanjutkan</p>
    </div>

    <form action="{{ route('auth.submit') }}" method="POST" id="strictAuthForm" class="space-y-4 text-xs">
        @csrf
        <input type="hidden" name="role" value="{{ $role }}">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block font-bold mb-1 text-[#4A1D2C]">Nama Lengkap *</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Risa Panjaitan" required class="w-full border border-[#F8BBD0] focus:border-[#A64D69] focus:outline-none rounded-2xl px-4 py-2.5">
            </div>
            <div>
                <label class="block font-bold mb-1 text-[#4A1D2C]">Nomor Telepon / WA *</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+62 812-xxxx-xxxx" required class="w-full border border-[#F8BBD0] focus:border-[#A64D69] focus:outline-none rounded-2xl px-4 py-2.5">
            </div>
        </div>

        <div>
            <label class="block font-bold mb-1 text-[#4A1D2C]">Email (Wajib @gmail.com) *</label>
            <input type="email" name="email" id="emailInput" value="{{ old('email') }}" pattern="^[a-zA-Z0-9._%+\-]+@gmail\.com$" title="Email wajib menggunakan domain @gmail.com" placeholder="namaakun@gmail.com" required class="w-full border border-[#F8BBD0] focus:border-[#A64D69] focus:outline-none rounded-2xl px-4 py-2.5">
            <p id="emailHint" class="text-[11px] text-[#8E5668] mt-1">Hanya menerima alamat email dengan domain <strong>@gmail.com</strong>.</p>
        </div>

        <!-- Alamat Lengkap: Jalan, Kota, Provinsi, Kode Pos -->
        <div class="p-4 rounded-2xl bg-[#FFF7FA] border border-[#FCE4EC] space-y-3">
            <p class="font-extrabold text-[#7A2E47] flex items-center gap-1.5"><i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Alamat Lengkap Domisili *</p>
            <div>
                <label class="block font-bold mb-1">Nama Jalan, Nomor Rumah, Kecamatan *</label>
                <input type="text" name="street" value="{{ old('street') }}" placeholder="Jl. Sisingamangaraja No. 12, Kec. Laguboti" required class="w-full bg-white border border-[#F8BBD0] rounded-xl px-3.5 py-2">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                <div>
                    <label class="block font-bold mb-1">Kota / Kabupaten *</label>
                    <input type="text" name="city" value="{{ old('city') }}" placeholder="Kab. Toba" required class="w-full bg-white border border-[#F8BBD0] rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block font-bold mb-1">Provinsi *</label>
                    <input type="text" name="province" value="{{ old('province') }}" placeholder="Sumatera Utara" required class="w-full bg-white border border-[#F8BBD0] rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block font-bold mb-1">Kode Pos (5 Digit) *</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code') }}" pattern="[0-9]{5}" maxlength="5" placeholder="22381" required class="w-full bg-white border border-[#F8BBD0] rounded-xl px-3 py-2">
                </div>
            </div>
        </div>

        <!-- Kata Sandi dengan Validasi Kompleks -->
        <div>
            <label class="block font-bold mb-1 text-[#4A1D2C]">Kata Sandi *</label>
            <input type="password" name="password" id="passwordInput" placeholder="Minimal 8 karakter kombinasi lengkap" required class="w-full border border-[#F8BBD0] focus:border-[#A64D69] focus:outline-none rounded-2xl px-4 py-2.5">

            <div class="grid grid-cols-2 gap-1.5 mt-2.5 p-3 rounded-2xl bg-[#FFF7FA] border border-[#FCE4EC] text-[11px]">
                <span id="ruleLen" class="text-rose-500 font-semibold">• Minimal 8 karakter (8–10+)</span>
                <span id="ruleUpper" class="text-rose-500 font-semibold">• Minimal 1 Huruf Besar (A-Z)</span>
                <span id="ruleLower" class="text-rose-500 font-semibold">• Minimal 1 Huruf Kecil (a-z)</span>
                <span id="ruleNum" class="text-rose-500 font-semibold">• Minimal 1 Angka (0-9)</span>
                <span id="ruleSpec" class="text-rose-500 font-semibold col-span-2">• Minimal 1 Karakter Spesial (!@#$%^&*)</span>
            </div>
        </div>

        <button type="submit" id="submitAuthBtn" class="w-full py-3.5 bg-[#A64D69] hover:bg-[#88334E] text-white font-extrabold text-xs rounded-full shadow transition">
            {{ $mode === 'signup' ? 'Daftar & Lanjutkan' : 'Masuk & Lanjutkan' }}
        </button>
    </form>

    <p class="text-center text-xs text-[#8E5668]">
        @if($mode === 'login')
            Belum punya akun? <a href="{{ route('auth', ['role' => $role, 'mode' => 'signup']) }}" class="font-extrabold underline text-[#A64D69]">Daftar Sekarang</a>
        @else
            Sudah punya akun? <a href="{{ route('auth', ['role' => $role, 'mode' => 'login']) }}" class="font-extrabold underline text-[#A64D69]">Masuk</a>
        @endif
    </p>
</div>

<script>
const pwdInput = document.getElementById('passwordInput');
const emailInput = document.getElementById('emailInput');
const form = document.getElementById('strictAuthForm');

function checkPasswordRules(val) {
    const checks = {
        ruleLen: val.length >= 8,
        ruleUpper: /[A-Z]/.test(val),
        ruleLower: /[a-z]/.test(val),
        ruleNum: /[0-9]/.test(val),
        ruleSpec: /[^A-Za-z0-9]/.test(val),
    };
    Object.entries(checks).forEach(([id, ok]) => {
        const el = document.getElementById(id);
        el.className = ok ? 'text-emerald-600 font-bold' : 'text-rose-500 font-semibold';
        if (id === 'ruleSpec') el.className += ' col-span-2';
    });
    return Object.values(checks).every(Boolean);
}

pwdInput.addEventListener('input', () => checkPasswordRules(pwdInput.value));

form.addEventListener('submit', function(e) {
    const emailOk = /^[a-zA-Z0-9._%+-]+@gmail\.com$/i.test(emailInput.value.trim());
    if (!emailOk) {
        e.preventDefault();
        alert('Email wajib menggunakan domain @gmail.com!');
        emailInput.focus();
        return;
    }
    if (!checkPasswordRules(pwdInput.value)) {
        e.preventDefault();
        alert('Kata sandi belum memenuhi syarat: minimal 8 karakter, mengandung huruf besar, huruf kecil, angka, dan karakter spesial.');
        pwdInput.focus();
    }
});
</script>
@endsection