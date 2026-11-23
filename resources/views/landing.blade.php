@extends('layouts.app')
@section('content')
<div class="space-y-16 pb-10">
    <!-- Hero Section -->
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center pt-4">
        <div class="space-y-5">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#FCE4EC] text-[#9E3B5A] text-xs font-extrabold">
                Platform Jastip Makanan No. 1 Indonesia
            </span>
            <h1 class="font-serif-title text-4xl sm:text-5xl font-bold text-[#3D1824] leading-tight">
                Makanan Favoritmu, <span class="text-[#A64D69]">Diantarkan Untukmu</span>
            </h1>
            <p class="text-sm text-[#7A4958] leading-relaxed max-w-lg">
                Titipkan makanan khas dari kota mana pun kepada traveler terpercaya. Nikmati kuliner legendaris langsung di rumahmu tanpa harus bepergian!
            </p>
            <div class="flex flex-wrap gap-3 pt-1">
                <a href="{{ route('auth', ['role' => 'buyer', 'mode' => 'signup']) }}" class="px-6 py-3.5 bg-[#A64D69] hover:bg-[#88334E] text-white text-xs font-extrabold rounded-full shadow transition">
                    Mulai Pesan Sekarang
                </a>
                <a href="{{ route('auth', ['role' => 'jastiper', 'mode' => 'signup']) }}" class="px-6 py-3.5 bg-[#FCE4EC] hover:bg-[#F8BBD0] text-[#7A2E47] text-xs font-extrabold rounded-full transition">
                    Jadi Jastiper
                </a>
            </div>
            <div class="flex items-center gap-3 pt-2 text-xs text-[#7A4958]">
                <div class="flex -space-x-2">
                    @foreach(['Risa', 'Nadia', 'Budi', 'Sari'] as $seed)
                        <img src="https://api.dicebear.com/9.x/lorelei/svg?seed={{ $seed }}&backgroundColor=ffd5dc" class="w-8 h-8 rounded-full border-2 border-white">
                    @endforeach
                </div>
                <div>
                    <p class="font-extrabold text-[#3D1824]">★★★★★ 4.9</p>
                    <p class="text-[11px]">dari 12.400+ pengguna</p>
                </div>
            </div>
        </div>

        <!-- Visual Kanan Hero -->
        <div class="relative bg-gradient-to-br from-[#FCE4EC] to-[#FFF0F5] p-6 rounded-[2.5rem] border border-[#F8BBD0]">
            <img src="https://images.unsplash.com/photo-1563245372-f21724e3856d?auto=format&fit=crop&w=900&q=80" alt="Makanan Jastip" class="w-full h-72 object-cover rounded-[1.75rem] shadow-md">
            <div class="absolute top-10 right-10 bg-white px-4 py-2.5 rounded-2xl shadow-md text-center border border-[#F8BBD0]">
                <p class="text-base font-black text-[#A64D69]">98K+</p>
                <p class="text-[10px] font-bold text-[#7A4958]">Titipan Sukses</p>
            </div>
            <div class="mt-4 bg-white p-4 rounded-2xl border border-[#F8BBD0] flex items-center justify-between shadow-2xs">
                <div class="flex items-center gap-3">
                    <img src="https://api.dicebear.com/9.x/lorelei/svg?seed=Risa&backgroundColor=ffd5dc" class="w-10 h-10 rounded-full">
                    <div>
                        <p class="text-xs font-extrabold text-[#3D1824]">Risa S. • Jastiper Terverifikasi</p>
                        <p class="text-[11px] text-[#7A4958]">Tokyo &rarr; Jakarta • Tiba Hari Ini</p>
                    </div>
                </div>
                <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-extrabold">Dalam Perjalanan</span>
            </div>
        </div>
    </section>

    <!-- Strip Statistik Rose-Mauve -->
    <section class="bg-[#A64D69] text-white rounded-[2rem] py-8 px-6 grid grid-cols-2 md:grid-cols-4 gap-6 text-center shadow-sm">
        <div><p class="text-2xl sm:text-3xl font-black">50K+</p><p class="text-xs opacity-85 mt-1">Pengguna Aktif</p></div>
        <div><p class="text-2xl sm:text-3xl font-black">1.200+</p><p class="text-xs opacity-85 mt-1">Jastiper Terverifikasi</p></div>
        <div><p class="text-2xl sm:text-3xl font-black">98K+</p><p class="text-xs opacity-85 mt-1">Pesanan Sukses</p></div>
        <div><p class="text-2xl sm:text-3xl font-black">25+</p><p class="text-xs opacity-85 mt-1">Negara & Kota Tujuan</p></div>
    </section>

    <!-- Semudah 3 Langkah -->
    <section class="space-y-6 text-center">
        <div>
            <span class="text-[11px] font-extrabold uppercase tracking-widest text-[#A64D69]">Cara Kerja</span>
            <h2 class="font-serif-title text-3xl font-bold text-[#3D1824] mt-1">Semudah 3 Langkah</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-left">
            <div class="bg-white p-6 rounded-3xl border border-[#F8BBD0] space-y-2">
                <span class="text-2xl font-black text-[#F48FB1]">01</span>
                <h3 class="font-extrabold text-base text-[#3D1824]">Pilih Produk Jastip</h3>
                <p class="text-xs text-[#7A4958] leading-relaxed">Temukan makanan favoritmu dari katalog negara dan kota tujuan yang lolos standar Bea Cukai.</p>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-[#F8BBD0] space-y-2">
                <span class="text-2xl font-black text-[#F48FB1]">02</span>
                <h3 class="font-extrabold text-base text-[#3D1824]">Pesan ke Jastiper</h3>
                <p class="text-xs text-[#7A4958] leading-relaxed">Ajukan titipan ke jastiper terverifikasi yang sedang membuka Pre-Order dari lokasi tersebut.</p>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-[#F8BBD0] space-y-2">
                <span class="text-2xl font-black text-[#F48FB1]">03</span>
                <h3 class="font-extrabold text-base text-[#3D1824]">Lacak Hingga Tiba</h3>
                <p class="text-xs text-[#7A4958] leading-relaxed">Pantau perkembangan titipanmu secara bertahap hingga kurir lokal mengantar ke alamatmu.</p>
            </div>
        </div>
    </section>

    <!-- Kenapa Harus TitipGo? -->
    <section class="space-y-6 text-center">
        <div>
            <span class="text-[11px] font-extrabold uppercase tracking-widest text-[#A64D69]">Keunggulan</span>
            <h2 class="font-serif-title text-3xl font-bold text-[#3D1824] mt-1">Kenapa Harus TitipGo?</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 text-left">
            @foreach([
                ['Ragam Makanan Unik', 'Dapatkan kuliner khas dari mancanegara dan berbagai kota tanpa harus berangkat sendiri.'],
                ['Kurasi Terverifikasi', 'Seluruh produk dikurasi ketat agar tersegel pabrik dan lolos pemeriksaan Bea Cukai.'],
                ['Jastiper Terverifikasi', 'Setiap mitra jastiper melewati proses verifikasi identitas KTP/Paspor dan rute perjalanan.'],
                ['25+ Negara & Kota', 'Jangkauan mencakup Jepang, Korea, Singapura, Eropa, hingga kuliner nusantara.'],
                ['Pembayaran Aman', 'Dukungan transaksi digital terintegrasi dengan bukti resi resmi yang dapat diunduh.'],
                ['Pelacakan Real-Time', 'Pantau 4 tahapan pengiriman mulai pembelian di negara asal hingga kurir lokal.'],
            ] as $feat)
                <div class="bg-white p-6 rounded-3xl border border-[#F8BBD0] space-y-2">
                    <div class="w-9 h-9 rounded-2xl bg-[#FCE4EC] text-[#A64D69] flex items-center justify-center">
                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                    </div>
                    <h3 class="font-extrabold text-sm text-[#3D1824]">{{ $feat[0] }}</h3>
                    <p class="text-xs text-[#7A4958] leading-relaxed">{{ $feat[1] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Makanan Terpopuler -->
    <section class="space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-[11px] font-extrabold uppercase tracking-widest text-[#A64D69]">Populer</span>
                <h2 class="font-serif-title text-2xl font-bold text-[#3D1824]">Makanan Terpopuler</h2>
            </div>
            <a href="{{ route('auth', ['role' => 'buyer', 'mode' => 'login']) }}" class="text-xs font-extrabold text-[#A64D69] hover:underline">Lihat Semua &rarr;</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach($featuredItems->take(3) as $item)
                <div class="bg-white rounded-3xl border border-[#F8BBD0] overflow-hidden flex flex-col justify-between">
                    <img src="{{ Str::startsWith($item->image, 'http') ? $item->image : asset('storage/'.$item->image) }}" class="w-full h-44 object-cover">
                    <div class="p-5 space-y-3">
                        <div>
                            <span class="text-[10px] font-extrabold px-2.5 py-0.5 rounded-full bg-[#FCE4EC] text-[#9E3B5A]">{{ $item->origin_country }}</span>
                            <h3 class="font-extrabold text-sm text-[#3D1824] mt-1.5">{{ $item->name }}</h3>
                            <p class="text-base font-black text-[#A64D69] mt-1">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        <a href="{{ route('auth', ['role' => 'buyer', 'mode' => 'login']) }}" class="block w-full py-2.5 text-center bg-[#A64D69] hover:bg-[#88334E] text-white text-xs font-extrabold rounded-full">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Banner Ajakan Jadi Jastiper -->
    <section class="bg-gradient-to-r from-[#A64D69] to-[#88334E] text-white rounded-[2rem] p-8 sm:p-10 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2 max-w-xl">
            <span class="text-[11px] font-extrabold uppercase tracking-widest opacity-80">Jadi Jastiper</span>
            <h2 class="font-serif-title text-3xl font-bold">Jadikan Hobimu Sumber Penghasilan</h2>
            <p class="text-xs opacity-90 leading-relaxed">Sering bepergian ke luar negeri atau luar kota? Manfaatkan sisa bagasimu untuk membantu titipan pembeli dan dapatkan penghasilan tambahan.</p>
        </div>
        <a href="{{ route('auth', ['role' => 'jastiper', 'mode' => 'signup']) }}" class="px-7 py-3.5 bg-white text-[#88334E] font-extrabold text-xs rounded-full shadow shrink-0 hover:bg-[#FCE4EC] transition">
            Daftar Jadi Jastiper &rarr;
        </a>
    </section>
</div>
@endsection