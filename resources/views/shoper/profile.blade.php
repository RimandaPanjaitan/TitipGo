@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Banner Profil Jastiper -->
    <div class="bg-gradient-to-r from-[#8E3B56] to-[#742D44] text-white rounded-[2rem] p-7 flex flex-col sm:flex-row items-center gap-5 shadow-sm">
        <img src="{{ $user['avatar'] }}" class="w-20 h-20 rounded-2xl object-cover border-2 border-white/60 bg-white">
        <div class="text-center sm:text-left space-y-1">
            <h1 class="text-2xl font-extrabold flex items-center justify-center sm:justify-start gap-1.5">
                {{ $user['name'] }} <i data-lucide="badge-check" class="w-5 h-5 text-pink-200"></i>
            </h1>
            <p class="text-xs opacity-90">{{ $user['email'] }} • {{ $user['phone'] }}</p>
            <p class="text-xs opacity-80">{{ $user['address'] }}</p>
        </div>
    </div>

    <!-- Tambah & Daftar Rekening Bank Pencairan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
        <form action="{{ route('jastiper.bank.store') }}" method="POST" class="bg-white p-6 rounded-3xl border border-[#F8BBD0] space-y-4">
            @csrf
            <h2 class="font-extrabold text-base text-[#3D1824]">Tambah Rekening Pencairan</h2>
            <div>
                <label class="block font-bold mb-1">Bank / E-Wallet</label>
                <select name="provider" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-semibold">
                    <option value="BCA">BCA</option>
                    <option value="BNI">BNI</option>
                    <option value="BRI">BRI</option>
                    <option value="Mandiri">Mandiri</option>
                </select>
            </div>
            <div>
                <label class="block font-bold mb-1">Nomor Rekening</label>
                <input type="text" name="account_number" placeholder="Masukkan nomor rekening" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5">
            </div>
            <div>
                <label class="block font-bold mb-1">Nama Pemilik Rekening</label>
                <input type="text" name="account_holder" value="{{ $user['name'] }}" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-semibold">
            </div>
            <button type="submit" class="w-full py-3 bg-[#8E3B56] hover:bg-[#742D44] text-white font-extrabold rounded-full">
                Simpan Rekening Bank
            </button>
        </form>

        <div class="bg-white p-6 rounded-3xl border border-[#F8BBD0] space-y-3">
            <h2 class="font-extrabold text-base text-[#3D1824]">Rekening Terdaftar</h2>
            @forelse($bankAccounts as $bank)
                <div class="p-4 rounded-2xl bg-[#FFF7FA] border border-[#F8BBD0] flex items-center justify-between">
                    <div>
                        <p class="font-extrabold text-sm text-[#8E3B56]">{{ $bank->provider }}</p>
                        <p class="font-mono font-bold">{{ $bank->account_number }}</p>
                        <p class="text-[11px] opacity-75">a.n. {{ $bank->account_holder }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 font-extrabold text-[10px]">Terverifikasi</span>
                </div>
            @empty
                <p class="opacity-70">Belum ada rekening bank tersimpan.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection