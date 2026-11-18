@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Pengaturan Akun & Foto Profil -->
    <div class="bg-white p-7 rounded-[2rem] border border-[#F5B8C8] space-y-4">
        <div class="flex items-center gap-3">
            <img src="{{ $user['avatar'] }}" class="w-14 h-14 rounded-full object-cover border-2 border-[#E16B8C]">
            <div>
                <div class="flex items-center gap-1">
                    <h2 class="font-coquette text-xl font-bold">{{ $user['name'] }}</h2>
                    <i data-lucide="badge-check" class="w-5 h-5 text-[#E16B8C]"></i>
                </div>
                <p class="text-xs opacity-75">{{ $user['email'] }}</p>
            </div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs">
            @csrf
            <div>
                <label class="block font-bold mb-1.5">Pilih Avatar</label>
                <div class="flex gap-2.5">
                    @foreach(['Rimanda', 'BlushQueen', 'PinkRibbon', 'PeachDoll'] as $seed)
                        @php $url = "https://api.dicebear.com/9.x/lorelei/svg?seed={$seed}&backgroundColor=ffd5dc"; @endphp
                        <label class="cursor-pointer">
                            <input type="radio" name="avatar_choice" value="{{ $url }}" class="hidden peer">
                            <img src="{{ $url }}" class="w-11 h-11 rounded-full border-2 border-transparent peer-checked:border-[#E16B8C]">
                        </label>
                    @endforeach
                </div>
            </div>
            <div><label class="block font-bold mb-1">Upload Galeri Foto</label><input type="file" name="avatar_file" accept="image/*"></div>
            <div><label class="block font-bold mb-1">Nama Jastiper</label><input type="text" name="name" value="{{ $user['name'] }}" class="w-full border border-[#F5B8C8] rounded-xl px-3 py-2"></div>
            <div><label class="block font-bold mb-1">Email</label><input type="email" name="email" value="{{ $user['email'] }}" class="w-full border border-[#F5B8C8] rounded-xl px-3 py-2"></div>
            <div><label class="block font-bold mb-1">Nomor Telepon</label><input type="text" name="phone" value="{{ $user['phone'] }}" class="w-full border border-[#F5B8C8] rounded-xl px-3 py-2"></div>
            <div><label class="block font-bold mb-1">Alamat Jastiper</label><input type="text" name="address" value="{{ $user['address'] }}" class="w-full border border-[#F5B8C8] rounded-xl px-3 py-2"></div>
            <button type="submit" class="w-full py-3 bg-[#E16B8C] text-white font-extrabold rounded-full">Simpan Pengaturan Akun</button>
        </form>
    </div>

    <!-- Manajemen Rekening Bank & Wallet Interaktif -->
    <div class="bg-white p-7 rounded-[2rem] border border-[#F5B8C8] space-y-5 h-fit">
        <h2 class="font-coquette text-xl font-bold">Rekening Bank & E-Wallet</h2>

        <div class="space-y-2.5">
            @foreach($banks as $b)
                <div class="p-3.5 rounded-2xl border {{ $b->is_primary ? 'bg-[#FFF0F4] border-[#E16B8C]' : 'border-[#F5B8C8]' }} flex items-center justify-between text-xs">
                    <div>
                        <span class="font-extrabold">{{ $b->provider }}</span>
                        @if($b->is_primary) <span class="ml-1 px-2 py-0.5 bg-[#E16B8C] text-white text-[10px] rounded-full">Utama</span> @endif
                        <p class="opacity-75 mt-0.5">{{ $b->account_number }} ({{ $b->account_holder }})</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if(!$b->is_primary)
                            <form action="{{ route('jastiper.bank.primary', $b->id) }}" method="POST">@csrf @method('PATCH')<button class="text-[#C94A6E] font-bold underline">Pilih Utama</button></form>
                        @endif
                        <form action="{{ route('jastiper.bank.destroy', $b->id) }}" method="POST">@csrf @method('DELETE')<button class="text-red-500 font-bold">Hapus</button></form>
                    </div>
                </div>
            @endforeach
        </div>

        <form action="{{ route('jastiper.bank.store') }}" method="POST" class="space-y-3 pt-4 border-t border-[#FFE8EE] text-xs">
            @csrf
            <h3 class="font-bold">Tambah Rekening Baru</h3>
            <select name="provider" class="w-full border border-[#F5B8C8] rounded-xl px-3 py-2 bg-white">
                <option value="BNI">Bank BNI</option>
                <option value="BRI">Bank BRI</option>
                <option value="BCA">Bank BCA</option>
                <option value="PayPal">PayPal</option>
                <option value="Amazon Wallet">Amazon Wallet</option>
            </select>
            <input type="text" name="account_number" placeholder="Nomor Rekening / Email PayPal / ID Wallet" required class="w-full border border-[#F5B8C8] rounded-xl px-3 py-2">
            <button type="submit" class="w-full py-2.5 bg-[#4A2530] text-white font-extrabold rounded-full">+ Tambah Rekening</button>
        </form>
    </div>
</div>
@endsection