<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'TitipGo — Platform Jasa Titip Mancanegara' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;1,600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif-title { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="{{ session('is_jastiper') ? 'bg-[#FFF5F7] text-[#4A2530]' : 'bg-[#FFF9F5] text-[#4A3228]' }} min-h-screen flex flex-col">

    <!-- Navbar Berbeda Sesuai POV -->
    <header class="sticky top-0 z-50 backdrop-blur-md border-b {{ session('is_jastiper') ? 'bg-[#FFE8EE]/90 border-[#F8C8D4]' : 'bg-[#FFF0E6]/90 border-[#F7D6C4]' }}">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between gap-4">
            <a href="{{ session('is_jastiper') ? route('jastiper.dashboard') : route('landing') }}" class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-2xl {{ session('is_jastiper') ? 'bg-gradient-to-tr from-[#E16B8C] to-[#F497B0]' : 'bg-gradient-to-tr from-[#E88565] to-[#F4A58A]' }} text-white flex items-center justify-center shadow-sm">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                </div>
                <span class="font-serif-title text-2xl font-bold tracking-tight {{ session('is_jastiper') ? 'text-[#C94A6E]' : 'text-[#D46A4C]' }}">
                    TitipGo
                </span>
            </a>

            @if(!session('is_jastiper'))
                <!-- MENU POV PEMBELI -->
                <nav class="hidden md:flex items-center gap-6 text-xs font-bold">
                    <a href="{{ route('buyer.dashboard') }}" class="{{ request()->routeIs('buyer.dashboard') ? 'text-[#D46A4C] underline decoration-2 underline-offset-4' : 'text-[#7A5648] hover:text-[#D46A4C]' }}">Beranda</a>
                    <a href="{{ route('buyer.catalog') }}" class="{{ request()->routeIs('buyer.catalog') ? 'text-[#D46A4C] underline decoration-2 underline-offset-4' : 'text-[#7A5648] hover:text-[#D46A4C]' }}">Katalog Makanan</a>
                    <a href="{{ route('buyer.track') }}" class="{{ request()->routeIs('buyer.track') ? 'text-[#D46A4C] underline decoration-2 underline-offset-4' : 'text-[#7A5648] hover:text-[#D46A4C]' }}">Lacak & Resi</a>
                    <a href="{{ route('buyer.notifications') }}" class="relative flex items-center gap-1 {{ request()->routeIs('buyer.notifications') ? 'text-[#D46A4C]' : 'text-[#7A5648] hover:text-[#D46A4C]' }}">
                        <i data-lucide="bell" class="w-4 h-4"></i>
                        <span>Notifikasi</span>
                    </a>
                </nav>

                <div class="flex items-center gap-2.5">
                    <a href="{{ route('location.setup') }}" class="hidden sm:flex items-center gap-1 text-[11px] font-bold bg-white/80 border border-[#F5CBB7] text-[#B8573B] px-3 py-1.5 rounded-full">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                        <span>{{ session('user_city', 'Laguboti, Toba') }}</span>
                    </a>
                    <!-- Penghubung Satu Arah ke Jastiper -->
                    <a href="{{ route('jastiper.apply') }}" class="px-3.5 py-2 text-[11px] font-extrabold bg-[#F48FB1] hover:bg-[#EC709A] text-white rounded-full shadow-sm transition flex items-center gap-1">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                        <span>Jadi Jastiper</span>
                    </a>
                    <a href="{{ route('buyer.profile') }}" class="flex items-center gap-1.5 bg-white/90 border border-[#F5CBB7] pl-1.5 pr-3 py-1 rounded-full">
                        <img src="{{ session('user_avatar', 'https://api.dicebear.com/9.x/lorelei/svg?seed=Rimanda&backgroundColor=ffd5dc') }}" class="w-6 h-6 rounded-full object-cover">
                        <span class="text-xs font-bold">{{ session('user_name', 'Rimanda Santa') }}</span>
                        <i data-lucide="badge-check" class="w-4 h-4 text-[#E88565] fill-[#FFE4D9]"></i>
                    </a>
                </div>
            @else
                <!-- MENU POV EKSKLUSIF JASTIPER -->
                <nav class="hidden md:flex items-center gap-6 text-xs font-bold">
                    <a href="{{ route('jastiper.dashboard') }}" class="{{ request()->routeIs('jastiper.dashboard') ? 'text-[#C94A6E] underline decoration-2 underline-offset-4' : 'text-[#7D4757] hover:text-[#C94A6E]' }}">Dashboard & Cairkan</a>
                    <a href="{{ route('jastiper.catalog') }}" class="{{ request()->routeIs('jastiper.catalog') ? 'text-[#C94A6E] underline decoration-2 underline-offset-4' : 'text-[#7D4757] hover:text-[#C94A6E]' }}">Buka PO & Katalog</a>
                    <a href="{{ route('jastiper.orders') }}" class="{{ request()->routeIs('jastiper.orders') ? 'text-[#C94A6E] underline decoration-2 underline-offset-4' : 'text-[#7D4757] hover:text-[#C94A6E]' }}">Pesanan (4 Tahap)</a>
                    <a href="{{ route('jastiper.notifications') }}" class="flex items-center gap-1 {{ request()->routeIs('jastiper.notifications') ? 'text-[#C94A6E]' : 'text-[#7D4757] hover:text-[#C94A6E]' }}">
                        <i data-lucide="bell" class="w-4 h-4"></i>
                        <span>Notifikasi</span>
                    </a>
                </nav>

                <a href="{{ route('jastiper.profile') }}" class="flex items-center gap-1.5 bg-white/90 border border-[#F5B8C8] pl-1.5 pr-3 py-1 rounded-full">
                    <img src="{{ session('user_avatar', 'https://api.dicebear.com/9.x/lorelei/svg?seed=Rimanda&backgroundColor=ffd5dc') }}" class="w-6 h-6 rounded-full object-cover">
                    <span class="text-xs font-bold">{{ session('user_name', 'Rimanda Santa') }}</span>
                    <i data-lucide="badge-check" class="w-4 h-4 text-[#E16B8C] fill-[#FFD6E2]"></i>
                </a>
            @endif
        </div>
    </header>

    <!-- Notifikasi Flash -->
    <div class="max-w-7xl mx-auto px-4 pt-4 w-full">
        @if(session('success'))
            <div class="bg-[#FFF0F4] border border-[#F4A6BC] text-[#9E3352] px-4 py-3 rounded-2xl text-xs font-bold flex items-center gap-2 shadow-sm">
                <i data-lucide="heart" class="w-4 h-4 text-[#E16B8C] fill-[#E16B8C]"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-xs font-bold flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <main class="max-w-7xl mx-auto px-4 py-6 flex-1 w-full">
        @yield('content')
    </main>

    <footer class="border-t {{ session('is_jastiper') ? 'bg-[#FFE8EE]/60 border-[#F8C8D4]' : 'bg-[#FFF0E6]/60 border-[#F7D6C4]' }} py-6 mt-12 text-center text-xs opacity-75">
        &copy; 2026 TitipGo • Rimanda Santa (rimandasnt@gmail.com)
    </footer>

    <script>lucide.createIcons();</script>
</body>
</html>