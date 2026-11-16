@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Input Jastiper (2 Kolom) -->
    <div class="lg:col-span-2 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
            <div>
                <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">Eksklusif Jastiper</span>
                <h1 class="text-2xl font-extrabold text-slate-900 mt-2">Tambah Produk Katalog</h1>
                <p class="text-sm text-slate-500">Isi detail produk jastip beserta estimasi berat bagasi dan tanggal tiba.</p>
            </div>
            <a href="{{ route('jastiper.dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-indigo-600">Batal</a>
        </div>

        <form action="{{ route('jastiper.store') }}" method="POST" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Produk *</label>
                    <input type="text" id="nameInput" name="name" placeholder="Contoh: Royce Nama Chocolate" required class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori *</label>
                    <select id="categoryInput" name="category" required class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 bg-white">
                        <option value="Makanan & Snack">Makanan & Snack</option>
                        <option value="Skincare & Kosmetik">Skincare & Kosmetik</option>
                        <option value="Fashion & Aksesoris">Fashion & Aksesoris</option>
                        <option value="Suplemen & Vitamin">Suplemen & Vitamin</option>
                        <option value="Merchandise & Hobi">Merchandise & Hobi</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Negara / Kota Asal *</label>
                    <input type="text" id="cityInput" name="origin_city" placeholder="Contoh: Tokyo, Jepang" required class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Estimasi Tiba di Indonesia *</label>
                    <input type="date" id="dateInput" name="estimated_arrival" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Harga Titip (Rp) *</label>
                    <input type="number" id="priceInput" name="price" placeholder="250000" min="1000" required class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kuantitas / Kuota *</label>
                    <input type="number" id="quotaInput" name="quota" value="10" min="1" required class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Berat / Item (gr) *</label>
                    <input type="number" id="weightInput" name="weight_grams" value="500" min="50" required class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2 bg-white">
                </div>
            </div>

            <!-- Kalkulator Bagasi & Omzet Otomatis -->
            <div class="bg-indigo-50/80 border border-indigo-100 rounded-2xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-xs">
                <span class="text-indigo-800 font-semibold flex items-center gap-1.5">
                    <i data-lucide="calculator" class="w-4 h-4 text-indigo-600"></i>
                    Kalkulator Bagasi & Omzet:
                </span>
                <div class="flex items-center gap-4 font-bold text-indigo-950">
                    <span>Beban Bagasi: <strong id="totalBagasiText" class="text-indigo-600">5.0 kg</strong></span>
                    <span>Potensi Omzet: <strong id="totalOmzetText" class="text-emerald-600">Rp 0</strong></span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">URL Gambar Produk (Opsional)</label>
                <input type="url" id="imageInput" name="image" placeholder="https://images.unsplash.com/..." class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi & Catatan Jastip</label>
                <textarea id="descInput" name="description" rows="3" placeholder="Jelaskan varian rasa, masa kedaluwarsa, dan aturan pemesanan..." class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5"></textarea>
            </div>

            <button type="submit" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-md transition flex items-center justify-center gap-2">
                <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                Publikasikan ke Katalog TitipGo
            </button>
        </form>
    </div>

    <!-- Live Preview Kartu Katalog (1 Kolom) -->
    <div class="space-y-3">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
            <i data-lucide="eye" class="w-4 h-4 text-indigo-500"></i>
            Live Preview Katalog
        </span>
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-md sticky top-24">
            <div class="h-48 bg-slate-100 relative">
                <img id="previewImg" src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=600" class="w-full h-full object-cover">
                <span id="previewCity" class="absolute top-3 left-3 bg-slate-900/80 text-white text-xs font-medium px-3 py-1 rounded-full">
                    Kota Asal
                </span>
                <span class="absolute top-3 right-3 bg-white/90 text-slate-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                    Kuota: <strong id="previewQuota" class="text-emerald-600">10</strong>
                </span>
            </div>
            <div class="p-5 space-y-3">
                <span id="previewCategory" class="text-[10px] font-bold px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-md">Makanan & Snack</span>
                <h3 id="previewName" class="font-bold text-slate-900 text-base">Nama Produk Kamu</h3>
                <p id="previewDesc" class="text-slate-500 text-xs line-clamp-2">Deskripsi produk akan tampil di sini...</p>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Harga Titip</span>
                        <span id="previewPrice" class="text-lg font-extrabold text-indigo-600">Rp 0</span>
                    </div>
                    <span class="px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-xl text-xs font-bold">Titip</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const els = {
        name: document.getElementById('nameInput'),
        category: document.getElementById('categoryInput'),
        city: document.getElementById('cityInput'),
        price: document.getElementById('priceInput'),
        quota: document.getElementById('quotaInput'),
        weight: document.getElementById('weightInput'),
        image: document.getElementById('imageInput'),
        desc: document.getElementById('descInput'),
    };

    function syncPreview() {
        const price = parseInt(els.price.value) || 0;
        const quota = parseInt(els.quota.value) || 0;
        const weight = parseInt(els.weight.value) || 0;

        document.getElementById('totalBagasiText').textContent = `${((quota * weight) / 1000).toFixed(1)} kg`;
        document.getElementById('totalOmzetText').textContent = `Rp ${(price * quota).toLocaleString('id-ID')}`;

        document.getElementById('previewName').textContent = els.name.value || 'Nama Produk Kamu';
        document.getElementById('previewCategory').textContent = els.category.value;
        document.getElementById('previewCity').textContent = els.city.value || 'Kota Asal';
        document.getElementById('previewQuota').textContent = quota;
        document.getElementById('previewPrice').textContent = `Rp ${price.toLocaleString('id-ID')}`;
        document.getElementById('previewDesc').textContent = els.desc.value || 'Deskripsi produk akan tampil di sini...';
        if (els.image.value) document.getElementById('previewImg').src = els.image.value;
    }

    Object.values(els).forEach(el => el.addEventListener('input', syncPreview));
    syncPreview();
</script>
@endsection