@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Banner Profil Jastiper -->
    <div class="bg-gradient-to-r from-[#8E3B56] to-[#742D44] text-white rounded-[2rem] p-7 flex flex-col sm:flex-row items-center gap-5 shadow-sm">
        <img id="jastiperAvatarPreview" src="{{ $user['avatar'] }}" class="w-20 h-20 rounded-2xl object-cover border-2 border-white/60 bg-white">
        <div class="text-center sm:text-left space-y-1.5">
            <h1 class="text-2xl font-extrabold flex items-center justify-center sm:justify-start gap-1.5">
                {{ $user['name'] }} <i data-lucide="badge-check" class="w-5 h-5 text-pink-200"></i>
            </h1>
            <p class="text-xs opacity-90">{{ $user['email'] }} • {{ $user['phone'] }}</p>
            <div class="flex flex-wrap justify-center sm:justify-start gap-2 pt-1 text-[11px] font-bold">
                <span class="px-3 py-1 rounded-full bg-white/20">Partner Jastiper Terverifikasi</span>
                <span class="px-3 py-1 rounded-full bg-white/20">Rute: {{ session('jastiper_route', 'Jepang') }} ({{ session('jastiper_capacity', 10) }} kg)</span>
                <span class="px-3 py-1 rounded-full bg-white/20">{{ session('jastiper_id_info', 'Identitas Terverifikasi') }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 text-xs">
        <!-- Form Pengaturan Profil & Ganti Foto Jastiper -->
        <form action="{{ route('jastiper.profile.update') }}" method="POST" enctype="multipart/form-data" class="lg:col-span-2 bg-white p-6 sm:p-7 rounded-3xl border border-[#F8BBD0] space-y-4">
            @csrf
            <div class="flex items-center justify-between border-b border-[#FCE4EC] pb-3">
                <h2 class="font-extrabold text-base text-[#3D1824]">Pengaturan Akun & Foto Jastiper</h2>
                <button type="submit" class="px-5 py-2 bg-[#8E3B56] hover:bg-[#742D44] text-white font-extrabold rounded-full">Simpan Profil</button>
            </div>

            <!-- Upload Foto Profil Jastiper -->
            <div class="p-4 rounded-2xl bg-[#FFF7FA] border border-[#F8BBD0] space-y-3">
                <label class="block font-extrabold text-[#3D1824]">Ganti Foto Profil (Unggah Foto dari Perangkat)</label>
                <input type="file" name="avatar_file" accept="image/*" onchange="previewJastiperAvatar(this)" class="w-full bg-white border border-[#F8BBD0] rounded-xl px-3 py-2">
                <div class="flex gap-2.5 flex-wrap pt-1">
                    @foreach(['Risa', 'TravelQueen', 'TokyoBoss', 'SeoulTaste', 'ParisChef'] as $seed)
                        @php $url = "https://api.dicebear.com/9.x/lorelei/svg?seed={$seed}&backgroundColor=ffd5dc"; @endphp
                        <label class="cursor-pointer">
                            <input type="radio" name="avatar_choice" value="{{ $url }}" onchange="document.getElementById('jastiperAvatarPreview').src=this.value" class="hidden peer">
                            <img src="{{ $url }}" class="w-11 h-11 rounded-xl border-2 border-[#F8BBD0] peer-checked:border-[#8E3B56] transition">
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold mb-1">Nama Partner Jastiper</label>
                    <input type="text" name="name" value="{{ $user['name'] }}" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-semibold">
                </div>
                <div>
                    <label class="block font-bold mb-1">Nomor Telepon / WA</label>
                    <input type="text" name="phone" value="{{ $user['phone'] }}" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-semibold">
                </div>
                <div>
                    <label class="block font-bold mb-1">Rute Negara Aktif</label>
                    <input type="text" name="jastiper_route" value="{{ session('jastiper_route', 'Jepang') }}" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-semibold">
                </div>
                <div>
                    <label class="block font-bold mb-1">Kapasitas Bagasi (kg)</label>
                    <input type="number" name="jastiper_capacity" value="{{ session('jastiper_capacity', 10) }}" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-semibold">
                </div>
                <div>
                    <label class="block font-bold mb-1">Kota Domisili</label>
                    <input type="text" name="city" value="{{ session('user_city', $user['city']) }}" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-semibold">
                </div>
                <div>
                    <label class="block font-bold mb-1">Provinsi</label>
                    <input type="text" name="province" value="{{ session('user_province', 'Sumatera Utara') }}" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-semibold">
                </div>
            </div>

            <div>
                <label class="block font-bold mb-1">Alamat Lengkap</label>
                <input type="text" name="address" value="{{ $user['address'] }}" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5">
            </div>
        </form>

        <!-- Kelola Rekening Bank Pencairan -->
        <div class="bg-white p-6 rounded-3xl border border-[#F8BBD0] space-y-4 h-fit">
            <h2 class="font-extrabold text-base text-[#3D1824]">Rekening Bank Pencairan</h2>
            <form action="{{ route('jastiper.bank.store') }}" method="POST" class="space-y-3">
                @csrf
                <select name="provider" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-3.5 py-2.5 font-semibold">
                    <option value="BCA">BCA</option>
                    <option value="BNI">BNI</option>
                    <option value="BRI">BRI</option>
                    <option value="Mandiri">Mandiri</option>
                </select>
                <input type="text" name="account_number" placeholder="Nomor rekening baru" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-3.5 py-2.5">
                <input type="text" name="account_holder" value="{{ $user['name'] }}" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-3.5 py-2.5 font-semibold">
                <button type="submit" class="w-full py-2.5 bg-[#8E3B56] hover:bg-[#742D44] text-white font-extrabold rounded-full">
                    + Tambah Rekening
                </button>
            </form>

            <div class="space-y-2 pt-2 border-t border-[#FCE4EC]">
                @foreach($bankAccounts as $bank)
                    <div class="p-3 rounded-2xl bg-[#FFF7FA] border border-[#F8BBD0] flex items-center justify-between">
                        <div>
                            <p class="font-extrabold text-[#8E3B56]">{{ $bank->provider }}</p>
                            <p class="font-mono font-bold">{{ $bank->account_number }}</p>
                            <p class="text-[10px] opacity-75">a.n. {{ $bank->account_holder }}</p>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-extrabold text-[10px]">Aktif</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
function previewJastiperAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('jastiperAvatarPreview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection