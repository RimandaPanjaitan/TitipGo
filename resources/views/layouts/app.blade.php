<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'TitipGo - Platform Jastip Nusantara' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans">
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between gap-4">
            <a href="{{ session('is_jastiper') ? route('jastiper.dashboard') : route('home') }}" class="flex items-center gap-2 text-2xl font-extrabold text-indigo-600 shrink-0">
                <i data-lucide="shopping-bag" class="w-6 h-6 text-amber-500"></i>
                <span>Titip<span class="text-amber-500">Go</span></span>
                @if(session('is_jastiper'))
                    <span class="text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 bg-amber-100 text-amber-800 rounded-md border border-amber-200">Jastiper Hub</span>
                @endif
            </a>

            @if(!session('is_jastiper'))
                <!-- Pencarian Pembeli -->
                <div class="flex-1 max-w-md hidden md:block">
                    <form action="{{ route('home') }}" method="GET" class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari makanan impor, kategori, kota..." class="w-full pl-10 pr-4 py-2 text-sm bg-slate-100 border border-transparent focus:border-indigo-300 focus:bg-white rounded-full outline-none transition">
                        <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-2.5 text-slate-400"></i>
                    </form>
                </div>

                <!-- Navigasi Pembeli -->
                <nav class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="text-sm font-semibold {{ request()->routeIs('home') ? 'text-indigo-600' : 'text-slate-600 hover:text-indigo-600' }}">
                        Katalog
                    </a>
                    <a href="{{ route('orders.index') }}" class="text-sm font-semibold flex items-center gap-1.5 {{ request()->routeIs('orders.index') ? 'text-indigo-600' : 'text-slate-600 hover:text-indigo-600' }}">
                        <i data-lucide="receipt" class="w-4 h-4"></i>
                        <span>Titipan Saya</span>
                    </a>

                    <!-- Tombol Upgrade Satu Arah ke Jastiper -->
                    <form action="{{ route('jastiper.upgrade') }}" method="POST" onsubmit="return confirm('Perhatian: Setelah beralih menjadi Jastiper, akun kamu akan terkunci di mode Jastiper untuk membuka titipan dan tidak bisa kembali ke mode Pembeli. Lanjutkan?')">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-xs font-bold bg-amber-500 hover:bg-amber-600 text-white rounded-xl shadow-sm transition flex items-center gap-1.5">
                            <i data-lucide="plane-takeoff" class="w-3.5 h-3.5"></i>
                            <span>Jadi Jastiper</span>
                        </button>
                    </form>
                </nav>
            @else
                <!-- Navigasi Eksklusif Jastiper (Satu Arah) -->
                <nav class="flex items-center gap-4">
                    <a href="{{ route('jastiper.dashboard') }}" class="text-sm font-bold {{ request()->routeIs('jastiper.dashboard') ? 'text-amber-600' : 'text-slate-600 hover:text-amber-600' }}">
                        Dashboard & Pesanan
                    </a>
                    <a href="{{ route('jastiper.create') }}" class="px-4 py-2 text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-sm transition flex items-center gap-1.5">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        <span>Tambah Produk Katalog</span>
                    </a>
                </nav>
            @endif
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8">
        @yield('content')
    </main>

    <script>lucide.createIcons();</script>
</body>
</html>