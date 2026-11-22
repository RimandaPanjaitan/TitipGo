@extends('layouts.app')
@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4">
    <div class="max-w-xl w-full bg-white rounded-[2.25rem] border {{ $isJastiper ? 'border-[#F5B8C8]' : 'border-[#F7D6C4]' }} p-8 sm:p-10 shadow-sm text-center space-y-6 relative overflow-hidden">
        
        <!-- Dekorasi Latar Lembut -->
        <div class="absolute -top-12 -right-12 w-36 h-36 rounded-full {{ $isJastiper ? 'bg-[#FFE8EE]' : 'bg-[#FFF0E6]' }} opacity-70 pointer-events-none"></div>
        <div class="absolute -bottom-12 -left-12 w-32 h-32 rounded-full {{ $isJastiper ? 'bg-[#FFD6E2]' : 'bg-[#FFE4D6]' }} opacity-60 pointer-events-none"></div>

        <!-- Avatar & Lencana Terverifikasi -->
        <div class="relative inline-block">
            <img src="{{ $user['avatar'] }}" alt="Avatar" class="w-24 h-24 rounded-full object-cover border-4 {{ $isJastiper ? 'border-[#F497B0]' : 'border-[#F4A58A]' }} shadow-md mx-auto">
            <span class="absolute bottom-0 right-0 w-7 h-7 rounded-full bg-white flex items-center justify-center shadow">
                <i data-lucide="badge-check" class="w-5 h-5 {{ $isJastiper ? 'text-[#E16B8C]' : 'text-[#E88565]' }}"></i>
            </span>
        </div>

        <!-- Sambutan Personal -->
        <div class="space-y-2 relative">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-[11px] font-extrabold {{ $isJastiper ? 'bg-[#FFE8EE] text-[#C94A6E]' : 'bg-[#FFF0E6] text-[#D46A4C]' }}">
                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                {{ $isJastiper ? 'Workspace Jastiper Aktif' : 'Akun Pembeli Siap Digunakan' }}
            </span>

            <h1 class="font-serif-title text-3xl sm:text-4xl font-bold leading-tight">
                Selamat Datang, {{ $user['name'] }}!
            </h1>

            <p class="text-xs sm:text-sm opacity-75 max-w-md mx-auto leading-relaxed">
                @if($isJastiper)
                    Lokasi keberangkatan & domisili Anda di <strong>{{ $user['city'] }}</strong> telah terhubung. Siap membuka Pre-Order makanan mancanegara dan mengelola pesanan pembeli?
                @else
                    Lokasi pengiriman Anda di <strong>{{ $user['city'] }}</strong> sudah aktif. Jelajahi ragam makanan tersegel lolos Bea Cukai dari Jepang, Korea, Eropa, dan Singapura!
                @endif
            </p>
        </div>

        <!-- Ringkasan Ringkas Akun -->
        <div class="grid grid-cols-2 gap-3 text-left bg-[#FFF9F7] border {{ $isJastiper ? 'border-[#F8C8D4]' : 'border-[#F7D6C4]' }} p-4 rounded-2xl text-xs">
            <div>
                <span class="opacity-60 block text-[11px]">Email Terdaftar</span>
                <strong class="truncate block">{{ $user['email'] }}</strong>
            </div>
            <div>
                <span class="opacity-60 block text-[11px]">Titik Lokasi Aktif</span>
                <strong class="truncate block">{{ $user['city'] }}</strong>
            </div>
        </div>

        <!-- Tombol Aksi & Auto-Redirect -->
        <div class="space-y-3 pt-2 relative">
            <a href="{{ $destinationRoute }}" class="block w-full py-3.5 {{ $isJastiper ? 'bg-[#E16B8C] hover:bg-[#C94A6E]' : 'bg-[#E88565] hover:bg-[#D97252]' }} text-white font-extrabold text-xs rounded-full shadow-md transition">
                Masuk ke {{ $isJastiper ? 'Dashboard Jastiper' : 'Dashboard Pembeli' }} Sekarang &rarr;
            </a>

            <p class="text-[11px] opacity-65">
                Otomatis diarahkan ke dashboard dalam <strong id="countdown">5</strong> detik...
            </p>
        </div>
    </div>
</div>

<script>
    let seconds = 5;
    const el = document.getElementById('countdown');
    const timer = setInterval(() => {
        seconds--;
        if (el) el.textContent = seconds;
        if (seconds <= 0) {
            clearInterval(timer);
            window.location.href = "{{ $destinationRoute }}";
        }
    }, 1000);
</script>
@endsection