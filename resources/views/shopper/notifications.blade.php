@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-12">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="font-serif-title text-2xl sm:text-3xl font-bold text-[#3D1824]">Notifikasi Partner Jastiper</h1>
                @if(session('is_new_account'))
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-extrabold">Akun Baru</span>
                @endif
            </div>
            <p class="text-xs text-[#7A4958]">Pantau pesanan masuk baru, status pencairan dana, dan informasi verifikasi rute.</p>
        </div>
        <a href="{{ route('jastiper.dashboard') }}" class="px-4 py-2 rounded-full bg-white border border-[#F8BBD0] text-[#8E3B56] font-extrabold text-xs hover:bg-[#FCE4EC] transition self-start sm:self-auto">
            ← Kembali ke Dashboard
        </a>
    </div>

    <div class="space-y-3">
        @forelse($notifications as $notif)
            <div class="bg-white p-5 rounded-3xl border {{ $notif->is_read ? 'border-[#F8BBD0]' : 'border-[#8E3B56] bg-[#FFF7FA]' }} shadow-2xs flex items-start gap-4">
                <div class="w-10 h-10 rounded-2xl bg-[#FCE4EC] text-[#8E3B56] flex items-center justify-center shrink-0">
                    <i data-lucide="bell-ring" class="w-5 h-5"></i>
                </div>
                <div class="flex-1 space-y-1 text-xs">
                    <div class="flex items-center justify-between gap-2">
                        <h3 class="font-extrabold text-sm text-[#3D1824]">{{ $notif->title }}</h3>
                        <span class="text-[11px] text-[#8E5668]">{{ $notif->created_at ? $notif->created_at->diffForHumans() : 'Baru saja' }}</span>
                    </div>
                    <p class="text-[#7A4958] leading-relaxed">{{ $notif->message }}</p>
                </div>
            </div>
        @empty
            <div class="bg-white p-12 rounded-3xl border border-[#F8BBD0] text-center text-xs text-[#7A4958] space-y-2.5">
                <div class="w-12 h-12 rounded-2xl bg-[#FFF7FA] border border-[#F8BBD0] text-[#A64D69] flex items-center justify-center mx-auto">
                    <i data-lucide="bell-off" class="w-6 h-6"></i>
                </div>
                <p class="font-extrabold text-sm text-[#3D1824]">Belum ada notifikasi di akun Jastiper ini</p>
                <p class="max-w-md mx-auto">
                    {{ session('is_new_account') ? 'Karena ini akun Partner Jastiper baru, kotak notifikasi masih kosong. Notifikasi akan otomatis masuk saat pembeli menitip produk dari katalog Anda.' : 'Belum ada aktivitas notifikasi terbaru saat ini.' }}
                </p>
            </div>
        @endforelse
    </div>
</div>
@endsection