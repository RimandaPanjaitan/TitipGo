@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-12">
    <!-- Bar Atas: Kembali & Indikator 3 Tahap -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
        <a href="{{ route('buyer.dashboard') }}" class="inline-flex items-center gap-1.5 font-bold text-[#7A4958] hover:text-[#A64D69]">
            <i data-lucide="chevron-left" class="w-4 h-4"></i> Kembali
        </a>
        <div class="flex items-center gap-3 font-extrabold">
            <span class="flex items-center gap-1.5 text-emerald-700"><span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[11px]">1</span> Akun</span>
            <span class="w-6 h-[1px] bg-[#F8BBD0]"></span>
            <span class="flex items-center gap-1.5 text-[#88334E]"><span class="w-6 h-6 rounded-full bg-[#88334E] text-white flex items-center justify-center text-[11px]">2</span> Verifikasi</span>
            <span class="w-6 h-[1px] bg-[#F8BBD0]"></span>
            <span class="flex items-center gap-1.5 text-[#7A4958]"><span class="w-6 h-6 rounded-full bg-[#FCE4EC] text-[#7A4958] flex items-center justify-center text-[11px]">3</span> Lokasi</span>
        </div>
    </div>

    <!-- Banner Header Rose-Mauve -->
    <div class="bg-gradient-to-r from-[#8E3B56] to-[#742D44] text-white rounded-[2rem] p-8 shadow-sm space-y-2">
        <p class="text-[11px] font-extrabold uppercase tracking-widest opacity-80">Partner Jastiper TitipGo</p>
        <h1 class="font-serif-title text-3xl sm:text-4xl font-bold">Lengkapi profil Jastipermu</h1>
        <p class="text-xs opacity-90 max-w-2xl leading-relaxed">Data ini membantu kami menjaga keamanan transaksi dan mencocokkan perjalananmu dengan kebutuhan Pembeli.</p>
    </div>

    <form action="{{ route('jastiper.apply.submit') }}" method="POST" class="space-y-6 text-xs">
        @csrf

        <!-- BAGIAN 1: Informasi Pribadi -->
        <div class="bg-white rounded-[2rem] border border-[#F8BBD0] p-6 sm:p-8 space-y-5 shadow-2xs">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-2xl bg-[#FCE4EC] text-[#A64D69] flex items-center justify-center shrink-0">
                    <i data-lucide="user" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-[#3D1824]">Informasi pribadi</h2>
                    <p class="text-xs text-[#7A4958]">Pastikan data sesuai dengan identitas resmi.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold mb-1.5 text-[#3D1824]">Nama Jastiper *</label>
                    <input type="text" name="name" value="{{ old('name', $user['name']) }}" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-3 font-semibold">
                </div>
                <div>
                    <label class="block font-bold mb-1.5 text-[#3D1824]">Email (@gmail.com) *</label>
                    <input type="email" name="email" value="{{ old('email', $user['email']) }}" pattern="^[a-zA-Z0-9._%+\-]+@gmail\.com$" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-3 font-semibold">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold mb-1.5 text-[#3D1824]">Nomor telepon *</label>
                    <input type="text" name="phone" value="{{ old('phone', $user['phone']) }}" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-3 font-semibold">
                </div>
                <div>
                    <label class="block font-bold mb-1.5 text-[#3D1824]">Identitas *</label>
                    <select name="id_type" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-3 font-semibold">
                        <option value="KTP">KTP</option>
                        <option value="Paspor">Paspor</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold mb-1.5 text-[#3D1824]">Nomor identitas *</label>
                    <input type="text" name="id_number" value="{{ old('id_number') }}" placeholder="Nomor KTP/Paspor" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-3">
                </div>
            </div>

            <div>
                <label class="block font-bold mb-1.5 text-[#3D1824]">Alamat lengkap Jastiper *</label>
                <textarea name="full_address" rows="2" placeholder="Nama jalan, nomor rumah, kecamatan, dan kode pos" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-3">{{ old('full_address', $user['address']) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold mb-1.5 text-[#3D1824]">Kota domisili *</label>
                    <input type="text" name="city" value="{{ old('city', session('user_city', $user['city'])) }}" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-3 font-semibold">
                </div>
                <div>
                    <label class="block font-bold mb-1.5 text-[#3D1824]">Provinsi *</label>
                    <input type="text" name="province" value="{{ old('province', session('user_province', 'Sumatera Utara')) }}" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-3 font-semibold">
                </div>
            </div>

            <div class="p-3.5 rounded-2xl bg-emerald-50/70 border border-emerald-200 text-emerald-800 flex items-center gap-2 text-[11px] font-semibold">
                <i data-lucide="lock" class="w-4 h-4 shrink-0"></i>
                <span>Data kendaraan dan plat nomor tidak diperlukan untuk mendaftar. Informasi kurir lokal hanya ditampilkan saat pesanan masuk tahap pengantaran.</span>
            </div>
        </div>

        <!-- BAGIAN 2: Rencana Perjalanan & Titipan -->
        <div class="bg-white rounded-[2rem] border border-[#F8BBD0] p-6 sm:p-8 space-y-5 shadow-2xs">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-2xl bg-[#FCE4EC] text-[#A64D69] flex items-center justify-center shrink-0">
                    <i data-lucide="globe" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-[#3D1824]">Rencana perjalanan & titipan</h2>
                    <p class="text-xs text-[#7A4958]">Tentukan negara cakupan dan kapasitas barang bawaan.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold mb-1.5 text-[#3D1824]">Negara tujuan *</label>
                    <select name="destination" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-3 font-semibold">
                        <option value="Jepang">Jepang</option>
                        <option value="Korea Selatan">Korea Selatan</option>
                        <option value="Singapura">Singapura</option>
                        <option value="Malaysia">Malaysia</option>
                        <option value="Eropa (Belanda/Prancis)">Eropa (Belanda/Prancis)</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold mb-1.5 text-[#3D1824]">Tanggal berangkat *</label>
                    <input type="date" name="departure_date" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-3">
                </div>
                <div>
                    <label class="block font-bold mb-1.5 text-[#3D1824]">Tanggal kembali *</label>
                    <input type="date" name="return_date" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-3">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold mb-1.5 text-[#3D1824]">Kapasitas titipan (kg) *</label>
                    <input type="number" name="capacity_kg" value="10" min="1" max="50" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-3 font-semibold">
                </div>
                <div>
                    <label class="block font-bold mb-1.5 text-[#3D1824]">Pengalaman menjadi Jastiper *</label>
                    <select name="experience" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-3 font-semibold">
                        <option value="Baru mulai">Baru mulai</option>
                        <option value="1 - 2 Tahun">1 - 2 Tahun</option>
                        <option value="Lebih dari 2 Tahun">Lebih dari 2 Tahun</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-bold mb-2 text-[#3D1824]">Kategori yang dapat dibawa *</label>
                <div class="flex flex-wrap gap-2">
                    @foreach(['Cokelat', 'Biskuit & Pastry', 'Snack Kemasan', 'Permen', 'Mie & Bumbu', 'Kopi & Teh', 'Bayi & Suplemen'] as $idx => $cat)
                        <label class="cursor-pointer">
                            <input type="checkbox" name="categories[]" value="{{ $cat }}" {{ $idx < 2 ? 'checked' : '' }} class="hidden peer">
                            <span class="inline-block px-4 py-2 rounded-full border border-[#F8BBD0] text-[#7A4958] font-bold peer-checked:bg-[#FCE4EC] peer-checked:border-[#A64D69] peer-checked:text-[#88334E] transition">
                                ✓ {{ $cat }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block font-bold mb-1.5 text-[#3D1824]">Motivasi dan pengalaman *</label>
                <textarea name="motivation" rows="3" placeholder="Ceritakan pengalaman perjalanan, pemahaman produk impor, dan alasan ingin bergabung..." required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-3"></textarea>
            </div>
        </div>

        <!-- BAGIAN 3: Rekening Pencairan -->
        <div class="bg-white rounded-[2rem] border border-[#F8BBD0] p-6 sm:p-8 space-y-5 shadow-2xs">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-2xl bg-[#FCE4EC] text-[#A64D69] flex items-center justify-center shrink-0">
                    <i data-lucide="credit-card" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-[#3D1824]">Rekening pencairan</h2>
                    <p class="text-xs text-[#7A4958]">Pendapatan hanya dicairkan ke rekening atas nama yang terverifikasi.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold mb-1.5 text-[#3D1824]">Bank *</label>
                    <select name="bank_name" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-3 font-semibold">
                        <option value="BCA">BCA</option>
                        <option value="BNI">BNI</option>
                        <option value="BRI">BRI</option>
                        <option value="Mandiri">Mandiri</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold mb-1.5 text-[#3D1824]">Nama pemilik rekening *</label>
                    <input type="text" name="bank_holder" value="{{ old('bank_holder', $user['name']) }}" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-3 font-semibold">
                </div>
                <div>
                    <label class="block font-bold mb-1.5 text-[#3D1824]">Nomor rekening *</label>
                    <input type="text" name="bank_account" value="{{ old('bank_account') }}" placeholder="Masukkan nomor rekening" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-3">
                </div>
            </div>
        </div>

        <!-- Persetujuan & Tombol Submit -->
        <div class="bg-white rounded-[2rem] border border-[#F8BBD0] p-6 space-y-4">
            <label class="flex items-start gap-3 cursor-pointer text-xs text-[#4A1D2C]">
                <input type="checkbox" name="agree_customs" value="1" required class="mt-0.5 accent-[#A64D69] w-4 h-4">
                <span>Saya menyatakan data yang diisi benar, memahami aturan bea cukai dan karantina, serta bersedia mengikuti proses verifikasi TitipGo.</span>
            </label>

            <button type="submit" class="w-full py-4 bg-[#8E3B56] hover:bg-[#742D44] text-white font-extrabold text-xs rounded-2xl shadow transition">
                Kirim pengajuan Jastiper &rarr;
            </button>
        </div>
    </form>
</div>
@endsection