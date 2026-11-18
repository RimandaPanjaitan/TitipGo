@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto space-y-4">
    <h1 class="font-coquette text-2xl font-bold">Notifikasi Jastiper</h1>
    @foreach($notifications as $n)
        <div class="bg-white p-4 rounded-2xl border border-[#F5B8C8] flex items-start gap-3">
            <i data-lucide="bell-ring" class="w-5 h-5 text-[#E16B8C] shrink-0 mt-0.5"></i>
            <div>
                <h4 class="text-xs font-extrabold">{{ $n->title }}</h4>
                <p class="text-xs opacity-75 mt-0.5">{{ $n->message }}</p>
            </div>
        </div>
    @endforeach
</div>
@endsection