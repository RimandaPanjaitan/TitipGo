@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <div>
        <h1 class="font-serif-title text-2xl sm:text-3xl font-bold text-[#3D1824]">Buka Pre-Order & Katalog Makanan</h1>
        <p class="text-xs text-[#7A4958]">Tambahkan produk makanan tersegel pabrik dari negara tujuan perjalananmu.</p>
    </div>

    <!-- Form Tambah PO Baru -->
    <form action="{{ route('jastiper.catalog.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 sm:p-8 rounded-[2rem] border border-[#F8BBD0] shadow-2xs space-y-4 text-xs">
        @csrf
        <h2 class="font-extrabold text-base text-[#3D1824] border-b border-[#FCE4EC] pb-3">Form Buka PO Baru</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold mb-1">Nama Makanan / Snack *</label>
                <input type="text" name="name" placeholder="Contoh: Royce Nama Chocolate Matcha" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5">
            </div>
            <div>
                <label class="block font-bold mb-1">Kategori Lolos Bea Cukai *</label>
                <select name="category" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-semibold">
                    <option value="Cokelat & Manisan">Cokelat & Manisan</option>
                    <option value="Biskuit & Pastry Kering">Biskuit & Pastry Kering</option>
                    <option value="Snack Kemasan Pabrik">Snack Kemasan Pabrik</option>
                    <option value="Kopi & Teh Kemasan">Kopi & Teh Kemasan</option>
                    <option value="Mie & Bumbu Instan">Mie & Bumbu Instan</option>
                    <option value="Makanan Bayi & Suplemen">Makanan Bayi & Suplemen</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block font-bold mb-1">Negara Asal *</label>
                <select name="origin_country" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-semibold">
                    <option value="Jepang">Jepang</option>
                    <option value="Korea Selatan">Korea Selatan</option>
                    <option value="Singapura">Singapura</option>
                    <option value="Malaysia">Malaysia</option>
                    <option value="Belanda">Belanda</option>
                    <option value="Prancis">Prancis</option>
                </select>
            </div>
            <div>
                <label class="block font-bold mb-1">Kota Pembelian *</label>
                <input type="text" name="origin_city" placeholder="Contoh: Tokyo, Jepang" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5">
            </div>
            <div>
                <label class="block font-bold mb-1">Tutup PO Tanggal *</label>
                <input type="date" name="po_close_date" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block font-bold mb-1">Harga Jastip (Rp) *</label>
                <input type="number" name="price" min="10000" placeholder="250000" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-bold">
            </div>
            <div>
                <label class="block font-bold mb-1">Kuota Bagasi (Slot) *</label>
                <input type="number" name="quota" min="1" max="100" value="10" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-bold">
            </div>
            <div>
                <label class="block font-bold mb-1">Berat Bersih Kemasan</label>
                <input type="text" name="weight" placeholder="Contoh: 250 gr" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold mb-1">Foto Produk (Opsional)</label>
                <input type="file" name="image" accept="image/*" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2">
            </div>
            <div>
                <label class="block font-bold mb-1">Deskripsi & Ketahanan Produk *</label>
                <input type="text" name="description" placeholder="Tersegel pabrik, masa simpan 3 bulan..." required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5">
            </div>
        </div>

        <button type="submit" class="px-7 py-3 bg-[#8E3B56] hover:bg-[#742D44] text-white font-extrabold rounded-full shadow transition">
            Terbitkan ke Katalog Pembeli &rarr;
        </button>
    </form>

    <!-- Daftar Katalog Aktif -->
    <div class="space-y-3">
        <h2 class="font-extrabold text-base text-[#3D1824]">Katalog Aktif Anda ({{ $items->count() }} Item)</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach($items as $item)
                <div class="bg-white rounded-3xl border border-[#F8BBD0] overflow-hidden text-xs">
                    <img src="{{ Str::startsWith($item->image, 'http') ? $item->image : asset('storage/'.$item->image) }}" class="w-full h-40 object-cover">
                    <div class="p-4 space-y-1.5">
                        <div class="flex justify-between items-center">
                            <span class="px-2.5 py-0.5 rounded-full bg-[#FCE4EC] text-[#9E3B5A] font-extrabold text-[10px]">{{ $item->origin_country }}</span>
                            <span class="font-extrabold text-[#8E3B56]">Sisa {{ $item->quota }} slot</span>
                        </div>
                        <h3 class="font-extrabold text-sm text-[#3D1824]">{{ $item->name }}</h3>
                        <p class="font-black text-[#A64D69] text-base">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        <p class="text-[11px] opacity-70">{{ $item->estimated_shipping }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection