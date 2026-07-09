<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#090a0f]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

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
<body class="antialiased text-gray-200 min-h-full bg-[#090a0f]">

<div class="min-h-screen flex flex-col justify-center items-center py-12 sm:py-16 bg-[#090a0f] px-4">

    <div class="mb-6 flex-shrink-0">
        <a href="/" class="flex items-center gap-2 group">
            <div class="bg-[#3b82f6] p-2 rounded-xl text-white shadow-lg shadow-[#3b82f6]/20 transition group-hover:bg-[#2563eb]">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                    <path d="M12 7.5a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z" />
                    <path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 14.625v-9.75ZM21 9.75H3V14.625c0 .207.168.375.375.375h17.25c.207 0 .375-.168.375-.375V9.75Z" clip-rule="evenodd" />
                    <path d="M1.5 18a.75.75 0 0 0 0 1.5h21a.75.75 0 0 0 0-1.5H1.5Z" />
                </svg>
            </div>
            <span class="text-white font-bold text-xl tracking-tight">My<span class="text-[#3b82f6]">Dompet</span>Gue</span>
        </a>
    </div>

    <div class="w-full sm:max-w-xl px-6 py-8 sm:px-8 bg-[#1e2330] border border-gray-700 shadow-2xl overflow-hidden sm:rounded-2xl">
        {{ $slot }}
    </div>

</div>
</body>
</html>
