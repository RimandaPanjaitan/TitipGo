@extends('layouts.app')
@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-[2rem] border {{ $role === 'jastiper' ? 'border-[#F5B8C8]' : 'border-[#F5CBB7]' }} shadow-sm space-y-6">
    <!-- Tab Switcher Peran -->
    <div class="grid grid-cols-2 gap-2 p-1.5 rounded-full {{ $role === 'jastiper' ? 'bg-[#FFE8EE]' : 'bg-[#FFF0E6]' }}">
        <a href="{{ route('auth', ['role' => 'buyer', 'mode' => $mode]) }}" class="py-2 text-center text-xs font-extrabold rounded-full transition {{ $role === 'buyer' ? 'bg-[#E88565] text-white shadow-sm' : 'text-[#7A5648]' }}">
            Akun Pembeli
        </a>
        <a href="{{ route('auth', ['role' => 'jastiper', 'mode' => $mode]) }}" class="py-2 text-center text-xs font-extrabold rounded-full transition {{ $role === 'jastiper' ? 'bg-[#E16B8C] text-white shadow-sm' : 'text-[#7D4757]' }}">
            Akun Jastiper
        </a>
    </div>

    <div class="text-center space-y-1">
        <h1 class="font-coquette text-2xl font-bold">{{ $mode === 'signup' ? 'Daftar Akun Baru' : 'Selamat Datang Kembali' }}</h1>
        <p class="text-xs opacity-75">Masuk menggunakan Email atau Nomor Telepon ({{ $role === 'jastiper' ? 'Portal Jastiper' : 'Portal Pembeli' }})</p>
    </div>

    <form action="{{ route('auth.submit') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="role" value="{{ $role }}">
        @if($mode === 'signup')
            <div>
                <label class="block text-xs font-bold mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="Rimanda Santa" required class="w-full border border-[#F5C6CE] rounded-2xl px-4 py-2.5 text-sm">
            </div>
        @endif
        <div>
            <label class="block text-xs font-bold mb-1">Email atau Nomor Telepon</label>
            <input type="text" name="identifier" value="rimandasnt@gmail.com" placeholder="rimandasnt@gmail.com atau 0812..." required class="w-full border border-[#F5C6CE] rounded-2xl px-4 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-xs font-bold mb-1">Kata Sandi</label>
            <input type="password" value="••••••••" required class="w-full border border-[#F5C6CE] rounded-2xl px-4 py-2.5 text-sm">
        </div>

        <button type="submit" class="w-full py-3.5 {{ $role === 'jastiper' ? 'bg-[#E16B8C] hover:bg-[#C94A6E]' : 'bg-[#E88565] hover:bg-[#D97252]' }} text-white font-extrabold text-xs rounded-full shadow transition">
            {{ $mode === 'signup' ? 'Daftar & Aktifkan Lokasi' : 'Masuk & Lanjut Atur Lokasi' }}
        </button>
    </form>

    <p class="text-center text-xs">
        @if($mode === 'login')
            Belum punya akun? <a href="{{ route('auth', ['role' => $role, 'mode' => 'signup']) }}" class="font-extrabold underline text-[#C94A6E]">Daftar (Sign Up)</a>
        @else
            Sudah punya akun? <a href="{{ route('auth', ['role' => $role, 'mode' => 'login']) }}" class="font-extrabold underline text-[#C94A6E]">Masuk (Log In)</a>
        @endif
    </p>
</div>
@endsection