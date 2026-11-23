@extends('layouts.app')
@section('content')
@php
    $totalItems    = $items->count();
    $activeItems   = $items->where('quota', '>', 0)->count();
    $soldOutItems  = $items->where('quota', '<=', 0)->count();
    $totalQuota    = $items->sum('quota');
@endphp

<div class="max-w-5xl mx-auto space-y-6 pb-12">
    <!-- Header & Tombol Aksi Utama -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex p-1 rounded-full bg-[#FCE4EC] mb-2 text-[11px] font-extrabold">
                <button type="button" onclick="switchCatalogView('katalog')" id="viewBtn-katalog" class="px-4 py-1.5 rounded-full bg-[#8E3B56] text-white shadow-2xs transition">
                    Kelola Katalog
                </button>
                <button type="button" onclick="switchCatalogView('preorder')" id="viewBtn-preorder" class="px-4 py-1.5 rounded-full text-[#7A2E47] hover:text-[#3D1824] transition">
                    Kelola Pre-Order
                </button>
            </div>
            <h1 id="pageHeading" class="font-serif-title text-2xl sm:text-3xl font-bold text-[#3D1824]">Kelola Katalog</h1>
            <p id="pageSubheading" class="text-xs text-[#7A4958]">Atur daftar makanan yang tersedia, stok kuota bagasi, dan status aktif Pre-Order.</p>
        </div>

        <button type="button" onclick="toggleAddForm()" id="btnToggleAdd" class="px-5 py-3 bg-[#8E3B56] hover:bg-[#742D44] text-white font-extrabold text-xs rounded-full shadow-sm transition inline-flex items-center gap-2 self-start sm:self-auto">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span id="btnToggleAddLabel">Tambah Item</span>
        </button>
    </div>

    <!-- Form Tambah Item / Buka PO (Muncul Interaktif saat Tombol Diklik) -->
    <div id="addCatalogCard" class="hidden bg-white p-6 sm:p-7 rounded-[2rem] border-2 border-[#A64D69]/40 shadow-sm space-y-4 text-xs transition-all">
        <div class="flex items-center justify-between border-b border-[#FCE4EC] pb-3">
            <h2 id="formCardTitle" class="font-extrabold text-base text-[#3D1824]">Tambah Item Baru</h2>
            <button type="button" onclick="toggleAddForm()" class="text-[#7A4958] hover:text-[#3D1824] font-bold">Tutup ✕</button>
        </div>

        <form action="{{ route('jastiper.catalog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold mb-1 text-[#3D1824]">Nama Makanan / Produk *</label>
                    <input type="text" name="name" placeholder="Contoh: Royce Nama Chocolate Matcha" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] focus:border-[#8E3B56] focus:outline-none rounded-2xl px-4 py-2.5">
                </div>
                <div>
                    <label class="block font-bold mb-1 text-[#3D1824]">Kategori Lolos Bea Cukai *</label>
                    <select name="category" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-semibold">
                        <option value="Cokelat & Olahan Kakao">Cokelat & Olahan Kakao</option>
                        <option value="Biskuit, Kue Kering & Pastry Tersegel">Biskuit, Kue Kering & Pastry Tersegel</option>
                        <option value="Camilan Ringan Kemasan (Snacks & Chips)">Camilan Ringan Kemasan (Snacks & Chips)</option>
                        <option value="Permen & Manisan Kemasan">Permen & Manisan Kemasan</option>
                        <option value="Mie Instan & Bumbu/Saus Kemasan Segel">Mie Instan & Bumbu/Saus Kemasan Segel</option>
                        <option value="Kopi, Teh & Bubuk Minuman">Kopi, Teh & Bubuk Minuman</option>
                        <option value="Makanan Bayi & Suplemen Makanan">Makanan Bayi & Suplemen Makanan</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold mb-1 text-[#3D1824]">Negara Asal *</label>
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
                    <label class="block font-bold mb-1 text-[#3D1824]">Kota Pembelian *</label>
                    <input type="text" name="origin_city" placeholder="Contoh: Tokyo, Jepang" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5">
                </div>
                <div>
                    <label class="block font-bold mb-1 text-[#3D1824]">Batas Tutup PO *</label>
                    <input type="date" name="po_close_date" value="{{ now()->addDays(7)->format('Y-m-d') }}" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold mb-1 text-[#3D1824]">Harga Jastip (Rp) *</label>
                    <input type="number" name="price" min="10000" placeholder="250000" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-bold">
                </div>
                <div>
                    <label class="block font-bold mb-1 text-[#3D1824]">Kuota Bagasi (Slot) *</label>
                    <input type="number" name="quota" min="1" max="100" value="10" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-bold">
                </div>
                <div>
                    <label class="block font-bold mb-1 text-[#3D1824]">Foto Produk (Opsional)</label>
                    <input type="file" name="image" accept="image/*" class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-3.5 py-2">
                </div>
            </div>

            <div>
                <label class="block font-bold mb-1 text-[#3D1824]">Deskripsi & Ketahanan Produk *</label>
                <input type="text" name="description" placeholder="Kemasan segel pabrik resmi, aman perjalanan udara..." required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5">
            </div>

            <div class="flex items-center gap-3 pt-1">
                <button type="submit" class="px-6 py-2.5 bg-[#8E3B56] hover:bg-[#742D44] text-white font-extrabold rounded-full shadow-2xs transition">
                    Simpan Item
                </button>
                <button type="button" onclick="toggleAddForm()" class="px-5 py-2.5 bg-[#FFF7FA] border border-[#F8BBD0] text-[#7A4958] font-bold rounded-full hover:bg-[#FCE4EC] transition">
                    Batal
                </button>
            </div>
        </form>
    </div>

    <!-- 4 Kartu Statistik Katalog (Seperti Desain Referensi) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 text-center text-xs">
        <div class="bg-white p-4 rounded-3xl border border-[#F8BBD0] shadow-2xs">
            <p class="text-xl font-black text-[#3D1824]">{{ $totalItems }}</p>
            <p class="text-[11px] font-bold text-[#7A4958] mt-0.5">Total Item</p>
        </div>
        <div class="bg-white p-4 rounded-3xl border border-[#F8BBD0] shadow-2xs">
            <p class="text-xl font-black text-emerald-600">{{ $activeItems }}</p>
            <p class="text-[11px] font-bold text-[#7A4958] mt-0.5">Tersedia</p>
        </div>
        <div class="bg-white p-4 rounded-3xl border border-[#F8BBD0] shadow-2xs">
            <p class="text-xl font-black text-rose-500">{{ $soldOutItems }}</p>
            <p class="text-[11px] font-bold text-[#7A4958] mt-0.5">Kuota Habis</p>
        </div>
        <div class="bg-white p-4 rounded-3xl border border-[#F8BBD0] shadow-2xs">
            <p class="text-xl font-black text-[#8E3B56]">{{ $totalQuota }}</p>
            <p class="text-[11px] font-bold text-[#7A4958] mt-0.5">Total Slot</p>
        </div>
    </div>

    <!-- PANEL 1: KELOLA KATALOG (List Horizontal Interaktif: Toggle Switch + Edit + Hapus) -->
    <div id="panel-katalog" class="space-y-3">
        @forelse($items as $item)
            @php $isActive = $item->quota > 0; @endphp
            <div class="bg-white p-4 rounded-3xl border border-[#F8BBD0] hover:border-[#A64D69] transition shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <img src="{{ Str::startsWith($item->image, 'http') ? $item->image : asset('storage/'.$item->image) }}" class="w-16 h-16 rounded-2xl object-cover border border-[#FCE4EC] shrink-0 {{ !$isActive ? 'grayscale opacity-60' : '' }}">
                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="font-extrabold text-sm text-[#3D1824]">{{ $item->name }}</h3>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold {{ $isActive ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $isActive ? 'Tersedia (' . $item->quota . ' slot)' : 'Nonaktif / Habis' }}
                            </span>
                        </div>
                        <p class="text-xs text-[#7A4958]">
                            {{ $item->origin_city }} • <strong class="text-[#8E3B56]">Rp {{ number_format($item->price, 0, ',', '.') }}</strong>
                        </p>
                        <p class="text-[11px] text-[#8E5668] line-clamp-1">{{ $item->description }}</p>
                    </div>
                </div>

                <!-- Kontrol Interaktif Kanan: Toggle Switch + Edit + Hapus -->
                <div class="flex items-center gap-2.5 self-end sm:self-center shrink-0">
                    <!-- Toggle Switch Aktif/Nonaktif -->
                    <form action="{{ route('jastiper.catalog.toggle', $item->id) }}" method="POST" title="Klik untuk aktif/nonaktifkan item">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-11 h-6 rounded-full p-0.5 transition flex items-center {{ $isActive ? 'bg-emerald-500 justify-end' : 'bg-gray-300 justify-start' }}">
                            <span class="w-5 h-5 rounded-full bg-white shadow-sm block"></span>
                        </button>
                    </form>

                    <!-- Tombol Edit (Buka Modal Edit) -->
                    <button type="button"
                        onclick='openEditModal(@json($item))'
                        title="Edit item"
                        class="w-9 h-9 rounded-xl bg-[#FFF7FA] hover:bg-[#FCE4EC] border border-[#F8BBD0] text-[#8E3B56] flex items-center justify-center transition">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                    </button>

                    <!-- Tombol Hapus -->
                    <form action="{{ route('jastiper.catalog.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus {{ $item->name }} dari katalog?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Hapus item" class="w-9 h-9 rounded-xl bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 flex items-center justify-center transition">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white p-10 rounded-3xl border border-[#F8BBD0] text-center text-xs text-[#7A4958] space-y-2">
                <p class="font-extrabold text-sm text-[#3D1824]">Belum ada item di katalog Anda</p>
                <p>Klik tombol <strong>+ Tambah Item</strong> di kanan atas untuk mulai membuka titipan makanan.</p>
            </div>
        @endforelse
    </div>

    <!-- PANEL 2: KELOLA PRE-ORDER (Progress Bar Slot & Status PO seperti Screenshot Kanan) -->
    <div id="panel-preorder" class="hidden space-y-3">
        @foreach($items as $item)
            @php
                $isActive   = $item->quota > 0;
                $maxSlot    = max(15, $item->quota + 5);
                $bookedSlot = max(0, $maxSlot - $item->quota);
                $percent    = min(100, round(($bookedSlot / $maxSlot) * 100));
            @endphp
            <div class="bg-white p-5 rounded-3xl border border-[#F8BBD0] shadow-2xs space-y-3 text-xs">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <img src="{{ Str::startsWith($item->image, 'http') ? $item->image : asset('storage/'.$item->image) }}" class="w-14 h-14 rounded-2xl object-cover border border-[#FCE4EC]">
                        <div>
                            <h3 class="font-extrabold text-sm text-[#3D1824]">{{ $item->name }}</h3>
                            <p class="text-[#7A4958]">{{ $item->origin_city }} • <strong class="text-[#8E3B56]">Rp {{ number_format($item->price, 0, ',', '.') }}</strong></p>
                            <p class="text-[11px] text-[#8E5668] mt-0.5">Tutup PO: {{ $item->po_close_date ? \Carbon\Carbon::parse($item->po_close_date)->format('d M Y') : 'Terbuka' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full text-[10px] font-extrabold {{ $isActive ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-600' }}">
                            {{ $isActive ? 'PO Dibuka' : 'PO Ditutup' }}
                        </span>
                        <form action="{{ route('jastiper.catalog.toggle', $item->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-11 h-6 rounded-full p-0.5 transition flex items-center {{ $isActive ? 'bg-emerald-500 justify-end' : 'bg-gray-300 justify-start' }}">
                                <span class="w-5 h-5 rounded-full bg-white shadow-sm block"></span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Progress Bar Kuota PO -->
                <div class="space-y-1">
                    <div class="flex justify-between text-[11px] font-bold text-[#7A4958]">
                        <span>Kuota Terisi: {{ $bookedSlot }} / {{ $maxSlot }} slot (Sisa {{ $item->quota }})</span>
                        <span class="text-[#8E3B56]">{{ $percent }}%</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-[#FCE4EC] overflow-hidden">
                        <div class="h-full bg-[#8E3B56] rounded-full transition-all" style="width: {{ $percent }}%"></div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-1">
                    <button type="button" onclick='openEditModal(@json($item))' class="px-3.5 py-1.5 rounded-full bg-[#FFF7FA] border border-[#F8BBD0] text-[#8E3B56] font-extrabold text-[11px] hover:bg-[#FCE4EC]">
                        Ubah Kuota / Harga
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- MODAL POP-UP EDIT ITEM KATALOG -->
<div id="editItemModal" class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-2xs flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-[2rem] border border-[#F8BBD0] p-6 sm:p-7 space-y-4 text-xs shadow-xl">
        <div class="flex items-center justify-between border-b border-[#FCE4EC] pb-3">
            <h3 class="font-extrabold text-base text-[#3D1824]">Edit Detail Item Katalog</h3>
            <button type="button" onclick="closeEditModal()" class="text-[#7A4958] hover:text-[#3D1824] font-bold">✕</button>
        </div>

        <form id="editItemForm" method="POST" class="space-y-3.5">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-bold mb-1">Nama Makanan *</label>
                <input type="text" name="name" id="editName" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-semibold">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold mb-1">Negara Asal *</label>
                    <input type="text" name="origin_country" id="editCountry" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5">
                </div>
                <div>
                    <label class="block font-bold mb-1">Kota Pembelian *</label>
                    <input type="text" name="origin_city" id="editCity" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold mb-1">Harga Jastip (Rp) *</label>
                    <input type="number" name="price" id="editPrice" min="1000" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-bold">
                </div>
                <div>
                    <label class="block font-bold mb-1">Sisa Kuota Slot *</label>
                    <input type="number" name="quota" id="editQuota" min="0" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5 font-bold">
                </div>
            </div>
            <input type="hidden" name="category" id="editCategory">
            <div>
                <label class="block font-bold mb-1">Deskripsi *</label>
                <textarea name="description" id="editDescription" rows="2" required class="w-full bg-[#FFF7FA] border border-[#F8BBD0] rounded-2xl px-4 py-2.5"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-full bg-[#FFF7FA] border border-[#F8BBD0] font-bold">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-full bg-[#8E3B56] hover:bg-[#742D44] text-white font-extrabold">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleAddForm() {
    const card = document.getElementById('addCatalogCard');
    card.classList.toggle('hidden');
}

function switchCatalogView(mode) {
    const isPO = mode === 'preorder';
    document.getElementById('panel-katalog').classList.toggle('hidden', isPO);
    document.getElementById('panel-preorder').classList.toggle('hidden', !isPO);

    document.getElementById('viewBtn-katalog').className = isPO
        ? 'px-4 py-1.5 rounded-full text-[#7A2E47] hover:text-[#3D1824] transition'
        : 'px-4 py-1.5 rounded-full bg-[#8E3B56] text-white shadow-2xs transition';
    document.getElementById('viewBtn-preorder').className = isPO
        ? 'px-4 py-1.5 rounded-full bg-[#8E3B56] text-white shadow-2xs transition'
        : 'px-4 py-1.5 rounded-full text-[#7A2E47] hover:text-[#3D1824] transition';

    document.getElementById('pageHeading').textContent = isPO ? 'Kelola Pre-Order' : 'Kelola Katalog';
    document.getElementById('btnToggleAddLabel').textContent = isPO ? 'Buka PO Baru' : 'Tambah Item';
    document.getElementById('formCardTitle').textContent = isPO ? 'Buka Pre-Order Baru' : 'Tambah Item Baru';
}

function openEditModal(item) {
    document.getElementById('editItemForm').action = '/jastiper/catalog/' + item.id;
    document.getElementById('editName').value = item.name || '';
    document.getElementById('editCountry').value = item.origin_country || '';
    document.getElementById('editCity').value = item.origin_city || '';
    document.getElementById('editPrice').value = item.price || 0;
    document.getElementById('editQuota').value = item.quota ?? 0;
    document.getElementById('editCategory').value = item.category || 'Cokelat & Olahan Kakao';
    document.getElementById('editDescription').value = item.description || '';
    document.getElementById('editItemModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editItemModal').classList.add('hidden');
}
</script>
@endsection