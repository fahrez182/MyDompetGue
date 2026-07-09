<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#0b0f19]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>MyDompetGue - Personal Finance Management</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased text-gray-300 min-h-full flex flex-col justify-between">

<nav class="w-full max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
    <div class="flex items-center gap-2">
        <div class="bg-[#3b82f6] p-1.5 rounded-lg text-white">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                <path d="M12 7.5a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z" />
                <path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 14.625v-9.75ZM21 9.75H3V14.625c0 .207.168.375.375.375h17.25c.207 0 .375-.168.375-.375V9.75Z" clip-rule="evenodd" />
                <path d="M1.5 18a.75.75 0 0 0 0 1.5h21a.75.75 0 0 0 0-1.5H1.5Z" />
            </svg>
        </div>
        <span class="text-white font-bold text-lg tracking-tight">My<span class="text-[#3b82f6]">Dompet</span>Gue</span>
    </div>

    <div class="flex items-center gap-4">
        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-400 hover:text-white transition flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
            </svg>
            Log in
        </a>
        <a href="{{ route('register') }}" class="bg-[#3b82f6] hover:bg-[#2563eb] text-white text-sm font-semibold px-4 py-2 rounded-lg transition flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.5a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.75 11.25q0-1.607 1.258-2.584A6.746 6.746 0 0 1 12 7.25q3.003 0 4.264 1.416A6.75 6.75 0 0 1 19.25 11.25v1.5a.75.75 0 0 1-.75.75H5.5a.75.75 0 0 1-.75-.75v-1.5Z" />
            </svg>
            Register
        </a>
    </div>
</nav>

<main class="flex-1 flex flex-col items-center justify-center text-center px-6 max-w-4xl mx-auto my-12">

    <div class="inline-flex items-center gap-1.5 bg-[#1e293b] border border-[#3b82f6]/20 rounded-full px-3 py-1 text-xs text-[#3b82f6] font-medium mb-8">
        <span class="w-1.5 h-1.5 rounded-full bg-[#3b82f6]"></span>
        Pencatatan keuangan pribadi
    </div>

    <h1 class="text-white text-5xl md:text-6xl font-extrabold tracking-tight mb-4">
        Catat. Analisis.<br>
        <span class="text-[#3b82f6]">Kendalikan.</span>
    </h1>

    <p class="text-gray-400 text-base md:text-lg max-w-2xl mx-auto leading-relaxed mb-10">
        Lacak pemasukan & pengeluaran, atur budget, dan lihat ke mana uangmu pergi — semua dalam satu tempat.
    </p>

    <div class="flex flex-col sm:flex-row items-center gap-4 mb-16">
        <a href="{{ route('register') }}" class="w-full sm:w-auto bg-[#3b82f6] hover:bg-[#2563eb] text-white font-semibold px-6 py-3 rounded-xl transition flex items-center justify-center gap-2">
            Mulai gratis
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5l6 6m0 0l-6 6m6-6H3" />
            </svg>
        </a>
        <a href="{{ route('login') }}" class="w-full sm:w-auto bg-transparent border border-gray-700 hover:border-gray-500 text-gray-300 hover:text-white font-medium px-6 py-3 rounded-xl transition flex items-center justify-center gap-2">
            Sudah punya akun
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
            </svg>
        </a>
    </div>

    <div class="w-full max-w-3xl bg-[#0f172a] border border-gray-800 rounded-2xl grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-800 overflow-hidden shadow-2xl">
        <div class="p-6 text-left flex flex-col justify-between">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Saldo</span>
            <span class="text-2xl font-bold text-[#3b82f6]">Rp 4,2 jt</span>
        </div>
        <div class="p-6 text-left flex flex-col justify-between">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Pemasukan</span>
            <span class="text-2xl font-bold text-white">Rp 8,5 jt</span>
        </div>
        <div class="p-6 text-left flex flex-col justify-between">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Pengeluaran</span>
            <span class="text-2xl font-bold text-[#ef4444]">Rp 4,3 jt</span>
        </div>
    </div>

</main>

<footer class="w-full border-t border-gray-900 py-6 text-center text-xs text-gray-600">
    MyDompetGue · Take control of your finances
</footer>

</body>
</html>
