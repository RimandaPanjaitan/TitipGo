@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div>
        <h1 class="font-serif-title text-3xl font-bold text-[#3D1824]">Profil Saya</h1>
        <p class="text-xs text-[#7A4958]">Kelola informasi akun, alamat pengiriman, dan preferensimu</p>
    </div>

    <!-- Banner Profil Rose-Mauve -->
    <div class="bg-gradient-to-r from-[#A64D69] to-[#8E3B56] text-white rounded-[2rem] p-6 sm:p-7 flex flex-col sm:flex-row items-center gap-5 shadow-sm">
        <img src="{{ $user['avatar'] }}" class="w-20 h-20 rounded-2xl object-cover border-2 border-white/60 bg-white">
        <div class="text-center sm:text-left space-y-1.5">
            <h2 class="text-xl font-extrabold flex items-center justify-center sm:justify-start gap-1.5">
                {{ $user['name'] }}
                <i data-lucide="badge-check" class="w-5 h-5 text-pink-200"></i>
            </h2>
            <p class="text-xs opacity-90">{{ $user['email'] }} • {{ $user['phone'] }}</p>
            <div class="flex flex-wrap justify-center sm:justify-start gap-2 pt-1 text-[11px] font-bold">
                <span class="px-3 py-1 rounded-full bg-white/20">Pembeli Aktif</span>
                <span class="px-3 py-1 rounded-full bg-white/20">{{ session('user_city', 'Domisili Terverifikasi') }}</span>
                <span class="px-3 py-1 rounded-full bg-white/20">Member 2026</span>
            </div>
        </div>
    </div>

    <!-- Grid Menu Samping + Form Pengaturan -->
    <form action="{{ route('buyer.profile.update') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @csrf
        <!-- Sidebar Tab Pengaturan -->
        <div class="bg-white rounded-3xl border border-[#F8BBD0] p-3 space-y-1 h-fit text-xs font-bold">
            @foreach([
                'pribadi'    => 'Informasi Pribadi',
                'alamat'     => 'Alamat',
                'pembayaran' => 'Pembayaran',
                'notifikasi' => 'Notifikasi',
                'akun'       => 'Akun & Avatar',
                'favorit'    => 'Makanan Favorit',
            ] as $tabKey => $tabLabel)
                <button type="button" onclick="switchProfileTab('{{ $tabKey }}')" id="tabBtn-{{ $tabKey }}"
                    class="w-full text-left px-4 py-3 rounded-2xl transition {{ $tabKey === 'pribadi' ? 'bg-[#FCE4EC] text-[#9E3B5A] font-extrabold' : 'text-[#7A4958] hover:bg-[#FFF7FA]' }}">
                    {{ $tabLabel }}
                </button>
            @endforeach
        </div>

        <!-- Panel Konten Pengaturan -->
        <div class="md:col-span-3 bg-white rounded-3xl border border-[#F8BBD0] p-6 sm:p-7 space-y-6 text-xs">
            <!-- TAB 1: Informasi Pribadi -->
            <div id="tabPanel-pribadi" class="profile-panel space-y-5">
                <div class="flex items-center justify-between border-b border-[#FCE4EC] pb-3">
                    <h3 class="text-base font-extrabold text-[#3D1824]">Informasi Pribadi</h3>
                    <button type="submit" class="px-5 py-2 bg-[#A64D69] hover:bg-[#88334E] text-white font-extrabold rounded-full">Simpan</button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold mb-1 text-[#7A4958]">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ $user['name'] }}" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-semibold">
                    </div>
                    <div>
                        <label class="block font-bold mb-1 text-[#7A4958]">Nomor HP</label>
                        <input type="text" name="phone" value="{{ $user['phone'] }}" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-semibold">
                    </div>
                    <div>
                        <label class="block font-bold mb-1 text-[#7A4958]">Email (@gmail.com)</label>
                        <input type="email" value="{{ $user['email'] }}" readonly class="w-full bg-gray-50 border border-[#F8BBD0] rounded-2xl px-4 py-2.5 opacity-75">
                    </div>
                    <div>
                        <label class="block font-bold mb-1 text-[#7A4958]">Tanggal Lahir</label>
                        <input type="text" name="birthdate" value="{{ session('user_birthdate', '14 Februari 2002') }}" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-semibold">
                    </div>
                    <div>
                        <label class="block font-bold mb-1 text-[#7A4958]">Jenis Kelamin</label>
                        <input type="text" name="gender" value="{{ session('user_gender', 'Perempuan') }}" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-semibold">
                    </div>
                    <div>
                        <label class="block font-bold mb-1 text-[#7A4958]">Kota</label>
                        <input type="text" name="city" value="{{ session('user_city', $user['city']) }}" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-semibold">
                    </div>
                </div>
            </div>

            <!-- TAB 2: Alamat Lengkap -->
            <div id="tabPanel-alamat" class="profile-panel hidden space-y-5">
                <div class="flex items-center justify-between border-b border-[#FCE4EC] pb-3">
                    <h3 class="text-base font-extrabold text-[#3D1824]">Alamat Pengiriman</h3>
                    <button type="submit" class="px-5 py-2 bg-[#A64D69] text-white font-extrabold rounded-full">Simpan</button>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block font-bold mb-1">Nama Jalan & Detail Lokasi</label>
                        <input type="text" name="street" value="{{ session('user_street', $user['address']) }}" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block font-bold mb-1">Kota / Kabupaten</label>
                            <input type="text" name="city" value="{{ session('user_city', $user['city']) }}" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5">
                        </div>
                        <div>
                            <label class="block font-bold mb-1">Provinsi</label>
                            <input type="text" name="province" value="{{ session('user_province', 'Sumatera Utara') }}" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5">
                        </div>
                        <div>
                            <label class="block font-bold mb-1">Kode Pos</label>
                            <input type="text" name="postal_code" value="{{ session('user_postal_code', '22381') }}" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5">
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: Pembayaran -->
            <div id="tabPanel-pembayaran" class="profile-panel hidden space-y-4">
                <h3 class="text-base font-extrabold text-[#3D1824] border-b border-[#FCE4EC] pb-3">Metode Pembayaran Tersedia</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach(['Transfer Bank BRI, BNI, BCA', 'PayPal Internasional', 'Amazon Wallet', 'QRIS & E-Wallet'] as $pay)
                        <div class="p-4 rounded-2xl bg-[#FFF7FA] border border-[#F8BBD0] flex items-center justify-between font-bold">
                            <span>{{ $pay }}</span>
                            <span class="text-[10px] px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700">Aktif</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- TAB 4: Notifikasi -->
            <div id="tabPanel-notifikasi" class="profile-panel hidden space-y-4">
                <h3 class="text-base font-extrabold text-[#3D1824] border-b border-[#FCE4EC] pb-3">Preferensi Notifikasi</h3>
                <label class="flex items-center justify-between p-4 rounded-2xl bg-[#FFF7FA] border border-[#F8BBD0]">
                    <span class="font-bold">Update 4 Tahap Pengiriman & Kurir Lokal</span>
                    <input type="checkbox" checked class="accent-[#A64D69] w-4 h-4">
                </label>
                <label class="flex items-center justify-between p-4 rounded-2xl bg-[#FFF7FA] border border-[#F8BBD0]">
                    <span class="font-bold">Notifikasi Bukti Resi & Promo Pre-Order Baru</span>
                    <input type="checkbox" checked class="accent-[#A64D69] w-4 h-4">
                </label>
            </div>

            <!-- TAB 5: Akun & Avatar -->
            <div id="tabPanel-akun" class="profile-panel hidden space-y-5">
                <div class="flex items-center justify-between border-b border-[#FCE4EC] pb-3">
                    <h3 class="text-base font-extrabold text-[#3D1824]">Pengaturan Avatar & Peran</h3>
                    <button type="submit" class="px-5 py-2 bg-[#A64D69] text-white font-extrabold rounded-full">Simpan Avatar</button>
                </div>
                <div class="flex gap-3 flex-wrap">
                    @foreach(['Risa', 'PeachGlow', 'RoseBerry', 'SweetMochi', 'TokyoSky'] as $seed)
                        @php $url = "https://api.dicebear.com/9.x/lorelei/svg?seed={$seed}&backgroundColor=ffd5dc"; @endphp
                        <label class="cursor-pointer">
                            <input type="radio" name="avatar_choice" value="{{ $url }}" class="hidden peer">
                            <img src="{{ $url }}" class="w-14 h-14 rounded-2xl border-2 border-[#F8BBD0] peer-checked:border-[#A64D69] peer-checked:scale-105 transition">
                        </label>
                    @endforeach
                </div>
                <div class="p-4 rounded-2xl bg-[#FCE4EC] flex items-center justify-between">
                    <div>
                        <p class="font-extrabold text-[#3D1824]">Ingin membuka Pre-Order sendiri?</p>
                        <p class="text-[11px] text-[#7A4958]">Ajukan verifikasi KTP/Paspor dan rute perjalanan Anda.</p>
                    </div>
                    <a href="{{ route('jastiper.apply') }}" class="px-4 py-2 bg-[#A64D69] text-white font-extrabold rounded-full">Jadi Jastiper</a>
                </div>
            </div>

            <!-- TAB 6: Makanan Favorit -->
            <div id="tabPanel-favorit" class="profile-panel hidden space-y-4">
                <h3 class="text-base font-extrabold text-[#3D1824] border-b border-[#FCE4EC] pb-3">Kategori Makanan Favorit</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach(['Cokelat & Matcha Jepang', 'Biskuit & Pastry Eropa', 'Snack Kemasan Korea', 'Kopi & Teh Spesialti', 'Mie & Bumbu Instan'] as $fav)
                        <span class="px-4 py-2 rounded-full bg-[#FCE4EC] text-[#9E3B5A] font-extrabold">{{ $fav }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function switchProfileTab(key) {
    document.querySelectorAll('.profile-panel').forEach(el => el.classList.add('hidden'));
    document.getElementById('tabPanel-' + key).classList.remove('hidden');

    document.querySelectorAll('[id^="tabBtn-"]').forEach(btn => {
        btn.className = 'w-full text-left px-4 py-3 rounded-2xl transition text-[#7A4958] hover:bg-[#FFF7FA]';
    });
    document.getElementById('tabBtn-' + key).className = 'w-full text-left px-4 py-3 rounded-2xl transition bg-[#FCE4EC] text-[#9E3B5A] font-extrabold';
}
</script>
@endsection