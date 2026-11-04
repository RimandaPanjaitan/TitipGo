<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'TitipGo - Platform Jastip Nusantara' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans">

    <!-- Header Navigation TitipGo -->
    <header class="sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="text-2xl font-extrabold text-indigo-600 tracking-tight">Titip<span class="text-amber-500">Go</span></span>
            </a>
            
            <div class="flex-1 max-w-md mx-8 hidden md:block">
                <form action="{{ route('catalog.index') }}" method="GET" class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kuliner daerah, jastip kota..." class="w-full pl-10 pr-4 py-2 text-sm bg-slate-100 border-none rounded-full focus:ring-2 focus:ring-indigo-500 transition">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-2.5 text-slate-400"></i>
                </form>
            </div>

            <nav class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium hover:text-indigo-600 transition">Dashboard</a>
                    <a href="{{ route('jastip.apply') }}" class="px-3 py-1.5 text-xs font-semibold bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition">Jadi Jastiper</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 font-medium">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600">Masuk</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-semibold bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Daftar</a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Alert Success Notification -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>