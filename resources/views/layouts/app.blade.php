<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'TitipGo — Platform Jasa Titip Mancanegara' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;1,600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif-title { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="{{ session('is_jastiper') ? 'bg-[#FFF0F5] text-[#4A152C]' : 'bg-[#FFF7FA] text-[#522238]' }} min-h-screen flex flex-col">

    <!-- Navbar Minimalis Pink Soft -->
    <header class="sticky top-0 z-40 backdrop-blur-md border-b {{ session('is_jastiper') ? 'bg-[#FCE4EC]/90 border-[#F8BBD0]' : 'bg-[#FFF0F5]/90 border-[#FAD2E1]' }}">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between gap-4">
            <a href="{{ session('is_jastiper') ? route('jastiper.dashboard') : route('landing') }}" class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-2xl {{ session('is_jastiper') ? 'bg-gradient-to-tr from-[#D81B60] to-[#F06292]' : 'bg-gradient-to-tr from-[#F06292] to-[#F8BBD0]' }} text-white flex items-center justify-center shadow-sm">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                </div>
                <span class="font-serif-title text-2xl font-bold tracking-tight {{ session('is_jastiper') ? 'text-[#C2185B]' : 'text-[#E91E63]' }}">
                    TitipGo
                </span>
            </a>

            <div class="flex items-center gap-2.5">
                <a href="{{ session('is_jastiper') ? route('jastiper.profile') : route('buyer.profile') }}" class="flex items-center gap-1.5 bg-white/90 border border-[#F8BBD0] pl-1.5 pr-3 py-1 rounded-full shadow-2xs">
                    <img src="{{ session('user_avatar', 'https://api.dicebear.com/9.x/lorelei/svg?seed=Rimanda&backgroundColor=ffd5dc') }}" class="w-6 h-6 rounded-full object-cover">
                    <span class="text-xs font-bold">{{ session('user_name') }}</span>
                    <i data-lucide="badge-check" class="w-4 h-4 text-[#EC407A] fill-[#FCE4EC]"></i>
                </a>

                <!-- Tombol Buka Pop-Up Menu Sebelah Kanan -->
                <button type="button" onclick="toggleRightDrawer()" class="flex items-center gap-1.5 px-3.5 py-2 rounded-full text-xs font-extrabold text-white shadow-sm transition {{ session('is_jastiper') ? 'bg-[#D81B60] hover:bg-[#AD1457]' : 'bg-[#F06292] hover:bg-[#EC407A]' }}">
                    <i data-lucide="layout-grid" class="w-4 h-4"></i>
                    <span>Menu Fitur</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Backdrop Gelap Lembut -->
    <div id="drawerBackdrop" onclick="toggleRightDrawer()" class="fixed inset-0 bg-[#4A152C]/25 backdrop-blur-[2px] z-50 hidden transition-opacity"></div>

    <!-- Profil & Tombol Menu Fitur: HANYA MUNCUL JIKA SUDAH LOGIN -->
            @if(session('is_logged_in') && !request()->routeIs('landing', 'auth', 'location.setup', 'welcome.screen'))
                <div class="flex items-center gap-2.5">
                    <a href="{{ session('is_jastiper') ? route('jastiper.profile') : route('buyer.profile') }}" class="flex items-center gap-1.5 bg-white/90 border border-[#F8BBD0] pl-1.5 pr-3 py-1 rounded-full shadow-2xs">
                        <img src="{{ session('user_avatar', 'https://api.dicebear.com/9.x/lorelei/svg?seed=Rimanda&backgroundColor=ffd5dc') }}" class="w-6 h-6 rounded-full object-cover">
                        <span class="text-xs font-bold">{{ session('user_name') }}</span>
                        <i data-lucide="badge-check" class="w-4 h-4 text-[#EC407A] fill-[#FCE4EC]"></i>
                    </a>

                    <!-- Tombol Buka Pop-Up Menu Sebelah Kanan -->
                    <button type="button" onclick="toggleRightDrawer()" class="flex items-center gap-1.5 px-3.5 py-2 rounded-full text-xs font-extrabold text-white shadow-sm transition {{ session('is_jastiper') ? 'bg-[#D81B60] hover:bg-[#AD1457]' : 'bg-[#F06292] hover:bg-[#EC407A]' }}">
                        <i data-lucide="layout-grid" class="w-4 h-4"></i>
                        <span>Menu Fitur</span>
                    </button>
                </div>
            @endif
        </div>
    </header>

    @if(session('is_logged_in') && !request()->routeIs('landing', 'auth', 'location.setup', 'welcome.screen'))
        <!-- Backdrop Gelap Lembut -->
        <div id="drawerBackdrop" onclick="toggleRightDrawer()" class="fixed inset-0 bg-[#4A152C]/25 backdrop-blur-[2px] z-50 hidden transition-opacity"></div>

        <!-- POP-UP PANEL MENU SEBELAH KANAN (Hanya Ada Setelah Login) -->
        <aside id="rightFeatureDrawer" class="fixed top-4 right-4 w-80 max-w-[90vw] bg-white rounded-[2rem] border-2 border-[#F8BBD0] shadow-2xl z-50 transform translate-x-[120%] transition-transform duration-300 overflow-hidden">
            <!-- Bagian Atas Panel: Klik untuk Hide -->
            <div onclick="toggleRightDrawer()" title="Klik bagian atas ini untuk menyembunyikan menu" class="cursor-pointer bg-gradient-to-r {{ session('is_jastiper') ? 'from-[#F48FB1] to-[#D81B60]' : 'from-[#F8BBD0] to-[#F06292]' }} p-4 text-white flex items-center justify-between select-none">
                <div class="flex items-center gap-2">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                    <span class="text-xs font-extrabold tracking-wide uppercase">Menu {{ session('is_jastiper') ? 'Jastiper' : 'Pembeli' }}</span>
                </div>
                <div class="flex items-center gap-1 bg-white/25 px-2.5 py-1 rounded-full text-[10px] font-bold">
                    <span>Hide</span>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                </div>
            </div>

            <!-- Info Akun & Lokasi -->
            <div class="p-4 bg-[#FFF5F8] border-b border-[#FCE4EC] flex items-center justify-between text-xs">
                <div class="flex items-center gap-2 min-w-0">
                    <img src="{{ session('user_avatar', 'https://api.dicebear.com/9.x/lorelei/svg?seed=Rimanda&backgroundColor=ffd5dc') }}" class="w-9 h-9 rounded-full border border-[#F48FB1] shrink-0">
                    <div class="truncate">
                        <p class="font-extrabold flex items-center gap-1 truncate">{{ session('user_name') }} <i data-lucide="badge-check" class="w-3.5 h-3.5 text-[#EC407A] shrink-0"></i></p>
                        <p class="text-[11px] opacity-70 truncate">{{ session('user_city', 'user city') }}</p>
                    </div>
                </div>
                <a href="{{ route('location.setup') }}" class="text-[10px] font-extrabold text-[#D81B60] bg-white px-2.5 py-1 rounded-full border border-[#F8BBD0] shrink-0">Lokasi</a>
            </div>

            <!-- Daftar Fitur -->
            <nav class="p-3 space-y-1.5 text-xs font-bold">
                @if(!session('is_jastiper'))
                    <a href="{{ route('buyer.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('buyer.dashboard') ? 'bg-[#FCE4EC] text-[#C2185B]' : 'hover:bg-[#FFF5F8]' }}">
                        <i data-lucide="home" class="w-4 h-4 text-[#EC407A]"></i>
                        <span>Beranda & Grafik Belanja</span>
                    </a>
                    <a href="{{ route('buyer.catalog') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('buyer.catalog') ? 'bg-[#FCE4EC] text-[#C2185B]' : 'hover:bg-[#FFF5F8]' }}">
                        <i data-lucide="utensils" class="w-4 h-4 text-[#EC407A]"></i>
                        <span>Katalog Makanan Bea Cukai</span>
                    </a>
                    <a href="{{ route('buyer.track') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('buyer.track') ? 'bg-[#FCE4EC] text-[#C2185B]' : 'hover:bg-[#FFF5F8]' }}">
                        <i data-lucide="receipt" class="w-4 h-4 text-[#EC407A]"></i>
                        <span>Lacak Pesanan & Resi</span>
                    </a>
                    <a href="{{ route('buyer.notifications') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('buyer.notifications') ? 'bg-[#FCE4EC] text-[#C2185B]' : 'hover:bg-[#FFF5F8]' }}">
                        <i data-lucide="bell" class="w-4 h-4 text-[#EC407A]"></i>
                        <span>Notifikasi</span>
                    </a>
                    <a href="{{ route('buyer.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('buyer.profile') ? 'bg-[#FCE4EC] text-[#C2185B]' : 'hover:bg-[#FFF5F8]' }}">
                        <i data-lucide="user-cog" class="w-4 h-4 text-[#EC407A]"></i>
                        <span>Profil & Ganti Avatar</span>
                    </a>

                    <div class="pt-2 mt-2 border-t border-[#FCE4EC]">
                        <a href="{{ route('jastiper.apply') }}" class="flex items-center justify-between px-4 py-3 rounded-2xl bg-gradient-to-r from-[#F48FB1] to-[#EC407A] text-white shadow-sm">
                            <span class="flex items-center gap-2"><i data-lucide="plane-takeoff" class="w-4 h-4"></i> Daftar Jadi Jastiper</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                @else
                    <a href="{{ route('jastiper.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('jastiper.dashboard') ? 'bg-[#FCE4EC] text-[#C2185B]' : 'hover:bg-[#FFF5F8]' }}">
                        <i data-lucide="wallet" class="w-4 h-4 text-[#D81B60]"></i>
                        <span>Dashboard, Grafik & Cairkan</span>
                    </a>
                    <a href="{{ route('jastiper.catalog') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('jastiper.catalog') ? 'bg-[#FCE4EC] text-[#C2185B]' : 'hover:bg-[#FFF5F8]' }}">
                        <i data-lucide="plus-circle" class="w-4 h-4 text-[#D81B60]"></i>
                        <span>Buka PO & Tambah Katalog</span>
                    </a>
                    <a href="{{ route('jastiper.orders') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('jastiper.orders') ? 'bg-[#FCE4EC] text-[#C2185B]' : 'hover:bg-[#FFF5F8]' }}">
                        <i data-lucide="list-checks" class="w-4 h-4 text-[#D81B60]"></i>
                        <span>Kelola Pesanan (4 Tahap)</span>
                    </a>
                    <a href="{{ route('jastiper.notifications') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('jastiper.notifications') ? 'bg-[#FCE4EC] text-[#C2185B]' : 'hover:bg-[#FFF5F8]' }}">
                        <i data-lucide="bell" class="w-4 h-4 text-[#D81B60]"></i>
                        <span>Notifikasi</span>
                    </a>
                    <a href="{{ route('jastiper.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('jastiper.profile') ? 'bg-[#FCE4EC] text-[#C2185B]' : 'hover:bg-[#FFF5F8]' }}">
                        <i data-lucide="landmark" class="w-4 h-4 text-[#D81B60]"></i>
                        <span>Profil & Rekening Bank</span>
                    </a>
                @endif

                <div class="pt-2 border-t border-[#FCE4EC]">
                    <a href="{{ route('auth', ['role' => session('is_jastiper') ? 'jastiper' : 'buyer', 'mode' => 'login']) }}" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-[#AD1457] hover:bg-red-50">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        <span>Ganti Akun / Login Ulang</span>
                    </a>
                </div>
            </nav>
        </aside>
    @endif

        <!-- Daftar Fitur -->
        <nav class="p-3 space-y-1.5 text-xs font-bold">
            @if(!session('is_jastiper'))
                <a href="{{ route('buyer.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('buyer.dashboard') ? 'bg-[#FCE4EC] text-[#C2185B]' : 'hover:bg-[#FFF5F8]' }}">
                    <i data-lucide="home" class="w-4 h-4 text-[#EC407A]"></i>
                    <span>Beranda & Grafik Belanja</span>
                </a>
                <a href="{{ route('buyer.catalog') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('buyer.catalog') ? 'bg-[#FCE4EC] text-[#C2185B]' : 'hover:bg-[#FFF5F8]' }}">
                    <i data-lucide="utensils" class="w-4 h-4 text-[#EC407A]"></i>
                    <span>Katalog Makanan Bea Cukai</span>
                </a>
                <a href="{{ route('buyer.track') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('buyer.track') ? 'bg-[#FCE4EC] text-[#C2185B]' : 'hover:bg-[#FFF5F8]' }}">
                    <i data-lucide="receipt" class="w-4 h-4 text-[#EC407A]"></i>
                    <span>Lacak Pesanan & Resi</span>
                </a>
                <a href="{{ route('buyer.notifications') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('buyer.notifications') ? 'bg-[#FCE4EC] text-[#C2185B]' : 'hover:bg-[#FFF5F8]' }}">
                    <i data-lucide="bell" class="w-4 h-4 text-[#EC407A]"></i>
                    <span>Notifikasi</span>
                </a>
                <a href="{{ route('buyer.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('buyer.profile') ? 'bg-[#FCE4EC] text-[#C2185B]' : 'hover:bg-[#FFF5F8]' }}">
                    <i data-lucide="user-cog" class="w-4 h-4 text-[#EC407A]"></i>
                    <span>Profil & Ganti Avatar</span>
                </a>

                <div class="pt-2 mt-2 border-t border-[#FCE4EC]">
                    <a href="{{ route('jastiper.apply') }}" class="flex items-center justify-between px-4 py-3 rounded-2xl bg-gradient-to-r from-[#F48FB1] to-[#EC407A] text-white shadow-sm">
                        <span class="flex items-center gap-2"><i data-lucide="plane-takeoff" class="w-4 h-4"></i> Daftar Jadi Jastiper</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            @else
                <a href="{{ route('jastiper.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('jastiper.dashboard') ? 'bg-[#FCE4EC] text-[#C2185B]' : 'hover:bg-[#FFF5F8]' }}">
                    <i data-lucide="wallet" class="w-4 h-4 text-[#D81B60]"></i>
                    <span>Dashboard, Grafik & Cairkan</span>
                </a>
                <a href="{{ route('jastiper.catalog') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('jastiper.catalog') ? 'bg-[#FCE4EC] text-[#C2185B]' : 'hover:bg-[#FFF5F8]' }}">
                    <i data-lucide="plus-circle" class="w-4 h-4 text-[#D81B60]"></i>
                    <span>Buka PO & Tambah Katalog</span>
                </a>
                <a href="{{ route('jastiper.orders') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('jastiper.orders') ? 'bg-[#FCE4EC] text-[#C2185B]' : 'hover:bg-[#FFF5F8]' }}">
                    <i data-lucide="list-checks" class="w-4 h-4 text-[#D81B60]"></i>
                    <span>Kelola Pesanan (4 Tahap)</span>
                </a>
                <a href="{{ route('jastiper.notifications') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('jastiper.notifications') ? 'bg-[#FCE4EC] text-[#C2185B]' : 'hover:bg-[#FFF5F8]' }}">
                    <i data-lucide="bell" class="w-4 h-4 text-[#D81B60]"></i>
                    <span>Notifikasi</span>
                </a>
                <a href="{{ route('jastiper.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('jastiper.profile') ? 'bg-[#FCE4EC] text-[#C2185B]' : 'hover:bg-[#FFF5F8]' }}">
                    <i data-lucide="landmark" class="w-4 h-4 text-[#D81B60]"></i>
                    <span>Profil & Rekening Bank</span>
                </a>
            @endif

            <div class="pt-2 border-t border-[#FCE4EC]">
                <a href="{{ route('auth', ['role' => session('is_jastiper') ? 'jastiper' : 'buyer', 'mode' => 'login']) }}" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-[#AD1457] hover:bg-red-50">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    <span>Ganti Akun / Login Ulang</span>
                </a>
            </div>
        </nav>
    </aside>

    <!-- Notifikasi Flash -->
    <div class="max-w-7xl mx-auto px-4 pt-4 w-full">
        @if(session('success'))
            <div class="bg-[#FCE4EC] border border-[#F48FB1] text-[#880E4F] px-4 py-3 rounded-2xl text-xs font-bold flex items-center gap-2 shadow-2xs">
                <i data-lucide="heart" class="w-4 h-4 text-[#EC407A] fill-[#EC407A]"></i>
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

    <footer class="border-t {{ session('is_jastiper') ? 'bg-[#FCE4EC]/60 border-[#F8BBD0]' : 'bg-[#FFF0F5]/60 border-[#FAD2E1]' }} py-6 mt-12 text-center text-xs opacity-75">
    &copy; 2026 TitipGo {{ session('user_name') ? '• ' . session('user_name') . ' (' . session('user_email') . ')' : '' }}
</footer>

    <script>
        lucide.createIcons();

        function toggleRightDrawer() {
            const drawer = document.getElementById('rightFeatureDrawer');
            const backdrop = document.getElementById('drawerBackdrop');
            const isClosed = drawer.classList.contains('translate-x-[120%]');

            if (isClosed) {
                drawer.classList.remove('translate-x-[120%]');
                drawer.classList.add('translate-x-0');
                backdrop.classList.remove('hidden');
            } else {
                drawer.classList.add('translate-x-[120%]');
                drawer.classList.remove('translate-x-0');
                backdrop.classList.add('hidden');
            }
        }
    </script>
</body>
</html>