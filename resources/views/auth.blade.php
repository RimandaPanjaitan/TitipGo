@extends('layouts.app')
@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-[2rem] border border-[#F8BBD0] shadow-sm space-y-6">
    <!-- Tab Switcher Peran -->
    <div class="grid grid-cols-2 gap-2 p-1.5 rounded-full bg-[#FCE4EC]">
        <a href="{{ route('auth', ['role' => 'buyer', 'mode' => $mode]) }}" class="py-2 text-center text-xs font-extrabold rounded-full transition {{ $role === 'buyer' ? 'bg-[#F06292] text-white shadow-sm' : 'text-[#880E4F]' }}">
            Akun Pembeli
        </a>
        <a href="{{ route('auth', ['role' => 'jastiper', 'mode' => $mode]) }}" class="py-2 text-center text-xs font-extrabold rounded-full transition {{ $role === 'jastiper' ? 'bg-[#D81B60] text-white shadow-sm' : 'text-[#880E4F]' }}">
            Akun Jastiper
        </a>
    </div>

    <div class="text-center space-y-1">
        <h1 class="font-serif-title text-2xl font-bold text-[#5C1A36]">
            {{ $mode === 'signup' ? 'Daftar Akun Baru' : 'Masuk & Buat Akun Sesi' }}
        </h1>
        <p class="text-xs text-[#8E4A67]">Masukkan nama dan data diri Anda untuk membentuk akun aktif ({{ $role === 'jastiper' ? 'Jastiper' : 'Pembeli' }})</p>
    </div>

    <form action="{{ route('auth.submit') }}" method="POST" class="space-y-3.5 text-xs">
        @csrf
        <input type="hidden" name="role" value="{{ $role }}">

        <div>
            <label class="block font-bold mb-1 text-[#5C1A36]">Nama Lengkap (Menjadi Nama Akun) *</label>
            <input type="text" name="name" placeholder="Masukkan nama lengkap Anda" required class="w-full border border-[#F8BBD0] focus:border-[#EC407A] focus:outline-none rounded-2xl px-4 py-2.5">
        </div>

        <div>
            <label class="block font-bold mb-1 text-[#5C1A36]">Email *</label>
            <input type="email" name="email" placeholder="nama@email.com" required class="w-full border border-[#F8BBD0] focus:border-[#EC407A] focus:outline-none rounded-2xl px-4 py-2.5">
        </div>

        <div>
            <label class="block font-bold mb-1 text-[#5C1A36]">Nomor Telepon / WhatsApp *</label>
            <input type="text" name="phone" placeholder="0812-xxxx-xxxx" required class="w-full border border-[#F8BBD0] focus:border-[#EC407A] focus:outline-none rounded-2xl px-4 py-2.5">
        </div>

        <div>
            <label class="block font-bold mb-1 text-[#5C1A36]">Alamat Lengkap *</label>
            <input type="text" name="address" placeholder="Masukkan alamat kota/jalan Anda" required class="w-full border border-[#F8BBD0] focus:border-[#EC407A] focus:outline-none rounded-2xl px-4 py-2.5">
        </div>

        <div>
            <label class="block font-bold mb-1 text-[#5C1A36]">Kata Sandi *</label>
            <input type="password" placeholder="••••••••" required class="w-full border border-[#F8BBD0] focus:border-[#EC407A] focus:outline-none rounded-2xl px-4 py-2.5">
        </div>

        <button type="submit" class="w-full py-3.5 mt-2 {{ $role === 'jastiper' ? 'bg-[#D81B60] hover:bg-[#AD1457]' : 'bg-[#F06292] hover:bg-[#EC407A]' }} text-white font-extrabold text-xs rounded-full shadow transition">
            {{ $mode === 'signup' ? 'Daftar & Aktifkan Lokasi' : 'Masuk & Gunakan Akun Ini' }}
        </button>
    </form>

    <p class="text-center text-xs text-[#8E4A67]">
        @if($mode === 'login')
            Belum punya akun? <a href="{{ route('auth', ['role' => $role, 'mode' => 'signup']) }}" class="font-extrabold underline text-[#D81B60]">Sign Up</a>
        @else
            Sudah punya akun? <a href="{{ route('auth', ['role' => $role, 'mode' => 'login']) }}" class="font-extrabold underline text-[#D81B60]">Log In</a>
        @endif
    </p>
</div>
@endsection