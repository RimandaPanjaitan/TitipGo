@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded-[2rem] border border-[#F5B8C8] space-y-5">
    <div>
        <span class="px-3 py-1 bg-[#FFE8EE] text-[#C94A6E] text-xs font-extrabold rounded-full">Aktivasi Satu Arah</span>
        <h1 class="font-coquette text-2xl font-bold mt-2">Formulir Pendaftaran Jastiper</h1>
        <p class="text-xs opacity-75">Setelah mendaftar menjadi Jastiper, akun Anda beralih eksklusif ke workspace Jastiper.</p>
    </div>

    <form action="{{ route('jastiper.apply.submit') }}" method="POST" class="space-y-4">
        @csrf
        <div><label class="block text-xs font-bold mb-1">Nama Jastiper</label><input type="text" name="jastiper_name" value="{{ $user['name'] }}" required class="w-full border border-[#F5B8C8] rounded-xl px-4 py-2.5 text-xs"></div>
        <div><label class="block text-xs font-bold mb-1">Nomor Telepon / WhatsApp</label><input type="text" name="jastiper_phone" value="{{ $user['phone'] }}" required class="w-full border border-[#F5B8C8] rounded-xl px-4 py-2.5 text-xs"></div>
        <div><label class="block text-xs font-bold mb-1">Alamat Domisili Jastiper</label><input type="text" name="jastiper_addr" value="{{ $user['address'] }}" required class="w-full border border-[#F5B8C8] rounded-xl px-4 py-2.5 text-xs"></div>
        <div><label class="block text-xs font-bold mb-1">Rute Perjalanan Utama</label><input type="text" name="travel_route" value="Tokyo / Seoul / Singapura → Indonesia" required class="w-full border border-[#F5B8C8] rounded-xl px-4 py-2.5 text-xs"></div>
        <div><label class="block text-xs font-bold mb-1">Nomor Paspor / Identitas</label><input type="text" name="passport_id" value="C8920144" required class="w-full border border-[#F5B8C8] rounded-xl px-4 py-2.5 text-xs"></div>
        <button type="submit" class="w-full py-3.5 bg-[#E16B8C] hover:bg-[#C94A6E] text-white font-extrabold text-xs rounded-full shadow">
            Aktifkan Akun Jastiper Sekarang
        </button>
    </form>
</div>
@endsection