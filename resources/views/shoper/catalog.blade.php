@extends('layouts.app')
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="bg-white p-6 rounded-3xl border border-[#F5B8C8] space-y-4 h-fit">
        <h2 class="font-coquette text-xl font-bold">Buka PO & Tambah Produk</h2>
        <form action="{{ route('jastiper.catalog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs">
            @csrf
            <div><label class="block font-bold mb-1">Nama Makanan / Produk *</label><input type="text" name="name" required class="w-full border border-[#F5B8C8] rounded-xl px-3 py-2"></div>
            <div class="grid grid-cols-2 gap-2">
                <div><label class="block font-bold mb-1">Negara Asal *</label><input type="text" name="origin_country" placeholder="Jepang" required class="w-full border border-[#F5B8C8] rounded-xl px-3 py-2"></div>
                <div><label class="block font-bold mb-1">Kota Asal *</label><input type="text" name="origin_city" placeholder="Tokyo, Jepang" required class="w-full border border-[#F5B8C8] rounded-xl px-3 py-2"></div>
            </div>
            <div>
                <label class="block font-bold mb-1">Kategori Lolos Bea Cukai *</label>
                <select name="category" class="w-full border border-[#F5B8C8] rounded-xl px-3 py-2 bg-white">
                    @foreach($categories as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div><label class="block font-bold mb-1">Harga (Rp) *</label><input type="number" name="price" required class="w-full border border-[#F5B8C8] rounded-xl px-3 py-2"></div>
                <div><label class="block font-bold mb-1">Kuota Bagasi *</label><input type="number" name="quota" value="10" required class="w-full border border-[#F5B8C8] rounded-xl px-3 py-2"></div>
            </div>
            <div><label class="block font-bold mb-1">Upload Gambar Produk</label><input type="file" name="image_file" accept="image/*"></div>
            <div><label class="block font-bold mb-1">Atau URL Gambar</label><input type="url" name="image_url" placeholder="https://..." class="w-full border border-[#F5B8C8] rounded-xl px-3 py-2"></div>
            <div><label class="block font-bold mb-1">Deskripsi Produk *</label><textarea name="description" rows="3" required class="w-full border border-[#F5B8C8] rounded-xl px-3 py-2"></textarea></div>
            <button type="submit" class="w-full py-3 bg-[#E16B8C] text-white font-extrabold rounded-full">Publikasikan PO ke Katalog</button>
        </form>
    </div>

    <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4 h-fit">
        @foreach($items as $item)
            <div class="bg-white p-4 rounded-3xl border border-[#F5B8C8] flex gap-3.5">
                <img src="{{ Str::startsWith($item->image, 'http') ? $item->image : asset('storage/'.$item->image) }}" class="w-20 h-20 rounded-2xl object-cover shrink-0">
                <div class="min-w-0">
                    <span class="text-[10px] font-bold text-[#C94A6E]">{{ $item->origin_city }}</span>
                    <h4 class="font-bold text-sm truncate">{{ $item->name }}</h4>
                    <p class="text-[11px] opacity-70 line-clamp-2">{{ $item->description }}</p>
                    <p class="text-xs font-black text-[#D46A4C] mt-1">Rp {{ number_format($item->price, 0, ',', '.') }} • Kuota: {{ $item->quota }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection