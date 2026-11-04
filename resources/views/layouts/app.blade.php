<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'TitipGo - Platform Jastip Nusantara' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans">
    <header class="sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="/" class="text-2xl font-extrabold text-indigo-600">Titip<span class="text-amber-500">Go</span></a>
            <div class="flex-1 max-w-md mx-8 hidden md:block">
                <form action="{{ route('home') }}" method="GET" class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari makanan khas, jastip kota..." class="w-full pl-10 pr-4 py-2 text-sm bg-slate-100 border-none rounded-full">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-2.5 text-slate-400"></i>
                </form>
            </div>
            <nav class="flex items-center gap-4">
                <a href="/" class="text-sm font-medium hover:text-indigo-600">Katalog</a>
                <a href="#" class="px-3 py-1.5 text-xs font-semibold bg-amber-500 text-white rounded-lg">Jadi Jastiper</a>
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8">
        @yield('content')
    </main>

    <script>lucide.createIcons();</script>
</body>
</html>