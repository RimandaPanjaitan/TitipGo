@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto space-y-4">
    <h1 class="font-serif-title text-2xl font-bold text-[#3D1824]">Notifikasi Partner Jastiper</h1>
    @forelse($notifications as $notif)
        <div class="bg-white p-5 rounded-3xl border border-[#F8BBD0] flex items-start gap-3 text-xs shadow-2xs">
            <div class="w-9 h-9 rounded-2xl bg-[#FCE4EC] text-[#A64D69] flex items-center justify-center shrink-0">
                <i data-lucide="bell" class="w-4 h-4"></i>
            </div>
            <div>
                <p class="font-extrabold text-[#3D1824]">{{ $notif->title }}</p>
                <p class="text-[#7A4958] mt-0.5">{{ $notif->message }}</p>
                <p class="text-[10px] opacity-60 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
            </div>
        </div>
    @empty
        <div class="bg-white p-8 rounded-3xl border border-[#F8BBD0] text-center text-xs text-[#7A4958]">
            Belum ada notifikasi baru.
        </div>
    @endforelse
</div>
@endsection