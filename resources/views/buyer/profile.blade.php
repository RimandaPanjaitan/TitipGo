@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-[2rem] border border-[#F7D6C4] space-y-6">
    <div class="flex items-center gap-4">
        <img src="{{ $user['avatar'] }}" class="w-16 h-16 rounded-full object-cover border-2 border-[#E88565]">
        <div>
            <div class="flex items-center gap-1.5">
                <h1 class="font-coquette text-xl font-bold">{{ $user['name'] }}</h1>
                <i data-lucide="badge-check" class="w-5 h-5 text-[#E88565]"></i>
            </div>
            <p class="text-xs opacity-75">{{ $user['email'] }} • {{ $user['phone'] }}</p>
        </div>
    </div>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-bold mb-2">Pilih Avatar Coquette</label>
            <div class="flex gap-3">
                @foreach(['Rimanda', 'CoquettePeach', 'PinkBow', 'SweetBerry'] as $seed)
                    @php $url = "https://api.dicebear.com/9.x/lorelei/svg?seed={$seed}&backgroundColor=ffd5dc"; @endphp
                    <label class="cursor-pointer">
                        <input type="radio" name="avatar_choice" value="{{ $url }}" class="hidden peer">
                        <img src="{{ $url }}" class="w-12 h-12 rounded-full border-2 border-transparent peer-checked:border-[#E88565]">
                    </label>
                @endforeach
            </div>
        </div>
        <div>
            <label class="block text-xs font-bold mb-1">Atau Upload dari Galeri Foto</label>
            <input type="file" name="avatar_file" accept="image/*" class="text-xs">
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div><label class="block text-xs font-bold mb-1">Nama</label><input type="text" name="name" value="{{ $user['name'] }}" class="w-full border rounded-xl px-3 py-2 text-xs"></div>
            <div><label class="block text-xs font-bold mb-1">Email</label><input type="email" name="email" value="{{ $user['email'] }}" class="w-full border rounded-xl px-3 py-2 text-xs"></div>
        </div><div>
            <label class="block text-xs font-bold mb-2">Pilih Avatar</label>
            <div class="flex gap-3">
                @foreach(['Rimanda', 'PeachGlow', 'PinkBow', 'SweetBerry'] as $seed)
                    @php $url = "https://api.dicebear.com/9.x/lorelei/svg?seed={$seed}&backgroundColor=ffd5dc"; @endphp
                    <label class="cursor-pointer">
                        <input type="radio" name="avatar_choice" value="{{ $url }}" class="hidden peer">
                        <img src="{{ $url }}" class="w-12 h-12 rounded-full border-2 border-transparent peer-checked:border-[#E88565]">
                    </label>
                @endforeach
            </div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div><label class="block text-xs font-bold mb-1">No. Telepon</label><input type="text" name="phone" value="{{ $user['phone'] }}" class="w-full border rounded-xl px-3 py-2 text-xs"></div>
            <div><label class="block text-xs font-bold mb-1">Alamat</label><input type="text" name="address" value="{{ $user['address'] }}" class="w-full border rounded-xl px-3 py-2 text-xs"></div>
        </div>
        <button type="submit" class="w-full py-3 bg-[#E88565] text-white font-extrabold text-xs rounded-full">Simpan Perubahan Profil</button>
    </form>
</div>
@endsection