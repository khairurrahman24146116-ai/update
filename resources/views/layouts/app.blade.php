<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — SMA Madani</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#faf8ff] text-[#0F172A] font-sans">
    <nav class="sticky top-0 z-50 bg-white/85 backdrop-blur-md border-b-2 border-neu">
        <div class="max-w-[80rem] mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <h1 class="font-bold text-xl tracking-tighter text-[#00236f]">SMA MADANI AL-AZIZIYAH</h1>
                <a href="/portal" class="text-xs font-semibold text-[#1E3A8A] border border-[#1E3A8A] rounded px-3 py-1 hover:bg-[#1E3A8A] hover:text-white transition-colors">Portal Akademik →</a>
            </div>
            <div class="px-4 py-1 border-neu rounded-full text-xs font-mono font-semibold uppercase tracking-wider">
                [SYS: AKADEMIK V2]
            </div>
        </div>
    </nav>
    <main class="max-w-[80rem] mx-auto p-6">
        @yield('content')
    </main>
</body>
</html>
