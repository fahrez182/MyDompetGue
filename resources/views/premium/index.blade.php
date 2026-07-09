<x-app-layout>
    <!-- Header Minimalis Sesuai Dashboard -->
    <x-slot name="header">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-1.5">
            <h2 class="font-bold text-lg text-white leading-tight flex items-center gap-2">
                <x-heroicon-s-star class="w-4 h-4 text-amber-400" />
                {{ __('Premium Plans') }}
            </h2>
        </div>
    </x-slot>

    <!-- Pembungkus Utama Menggunakan max-w-5xl Supaya Lebarnya Konsisten -->
    <div class="py-6 sm:py-12 bg-[#090a0f] min-h-screen text-gray-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($userRole === 'basic')
                {{-- ==================================================================
                   TAMPILAN JIKA USER MASIH 'BASIC' (HALAMAN PENAWARAN/PRICING)
                   ================================================================== --}}
                <div class="text-center max-w-xl mx-auto mb-10">
                    <span class="px-3 py-1 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[10px] font-bold uppercase tracking-widest rounded-full">
                        Level Up Your Finance
                    </span>
                    <h3 class="text-2xl font-black text-white mt-3 tracking-tight">
                        Upgrade to Premium!
                    </h3>
                    <p class="mt-2 text-xs text-gray-400 leading-relaxed">
                        Unlock advanced reporting, precision budgeting tools, and unlimited smart multi-currency wallets.
                    </p>
                </div>

                <!-- Kontainer Dua Kartu Perbandingan (Basic vs Premium) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-3xl mx-auto items-stretch">

                    <!-- KARTU BASIC (PLAN SAAT INI) -->
                    <div class="bg-[#0f172a] border border-gray-800/80 rounded-2xl p-6 flex flex-col justify-between relative opacity-70">
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Basic Plan</h4>
                                <span class="px-2 py-0.5 bg-gray-800 border border-gray-700 text-gray-400 text-[10px] rounded-md font-medium">Active</span>
                            </div>
                            <div class="mb-5">
                                <span class="text-2xl font-black text-white">$0.00</span>
                                <span class="text-gray-500 text-xs">/ month</span>
                            </div>
                            <ul class="space-y-2.5 text-xs text-gray-300 border-t border-gray-800/60 pt-4">
                                <li class="flex items-center gap-2 text-gray-400">
                                    <x-heroicon-s-check-circle class="w-4 h-4 text-gray-600 flex-shrink-0" />
                                    Standard Transaction Logs
                                </li>
                                <li class="flex items-center gap-2 text-gray-400">
                                    <x-heroicon-s-check-circle class="w-4 h-4 text-gray-600 flex-shrink-0" />
                                    Single Wallet System
                                </li>
                                <li class="flex items-center gap-2 line-through text-gray-600">
                                    <x-heroicon-s-x-circle class="w-4 h-4 text-gray-700 flex-shrink-0" />
                                    Smart Budget Rules
                                </li>
                                <li class="flex items-center gap-2 line-through text-gray-600">
                                    <x-heroicon-s-x-circle class="w-4 h-4 text-gray-700 flex-shrink-0" />
                                    Advanced Multi-Currency Analytics
                                </li>
                            </ul>
                        </div>
                        <div class="mt-8">
                            <button disabled class="w-full text-center px-4 py-2.5 bg-gray-800 text-gray-500 font-semibold text-xs rounded-xl cursor-not-allowed">
                                Current Plan
                            </button>
                        </div>
                    </div>

                    <!-- KARTU PREMIUM (TARGET UPGRADE) -->
                    <div class="bg-[#0f172a] border-2 border-amber-500/40 rounded-2xl p-6 flex flex-col justify-between relative shadow-xl shadow-amber-500/[0.02]">
                        <div class="absolute -top-3 right-5 px-2.5 py-0.5 bg-amber-500 text-slate-950 text-[10px] font-black uppercase tracking-wider rounded-md shadow-md">
                            Recommended
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-xs font-bold text-amber-400 uppercase tracking-wider flex items-center gap-1">
                                    <x-heroicon-s-star class="w-3.5 h-3.5" /> Premium PRO
                                </h4>
                            </div>
                            <div class="mb-5">
                                <span class="text-2xl font-black text-white">$9.99</span>
                                <span class="text-gray-400 text-xs">/ month</span>
                            </div>
                            <ul class="space-y-2.5 text-xs text-gray-200 border-t border-gray-800/60 pt-4">
                                <li class="flex items-center gap-2">
                                    <x-heroicon-s-check-circle class="w-4 h-4 text-amber-400 flex-shrink-0" />
                                    Unlimited Multi-Currency Wallets
                                </li>
                                <li class="flex items-center gap-2">
                                    <x-heroicon-s-check-circle class="w-4 h-4 text-amber-400 flex-shrink-0" />
                                    Advanced Budgeting & Limits Alert
                                </li>
                                <li class="flex items-center gap-2">
                                    <x-heroicon-s-check-circle class="w-4 h-4 text-amber-400 flex-shrink-0" />
                                    Interactive Financial Reports & Export
                                </li>
                                <li class="flex items-center gap-2">
                                    <x-heroicon-s-check-circle class="w-4 h-4 text-amber-400 flex-shrink-0" />
                                    Priority Cloud Sync & AI Insights
                                </li>
                            </ul>
                        </div>
                        <div class="mt-8">
                            <form method="POST" action="{{ route('premium.upgrade') }}">
                                @csrf
                                <button type="submit" class="w-full text-center px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl transition shadow-lg shadow-amber-500/10 focus:outline-none">
                                    {{ __('Upgrade Now') }}
                                </button>
                            </form>
                        </div>
                    </div>

                </div>

            @else
                {{-- ==================================================================
                   TAMPILAN JIKA USER SUDAH 'PREMIUM' (PORTAL EXCLUSIVE)
                   ================================================================== --}}
                <div class="max-w-2xl mx-auto bg-[#0f172a] border border-gray-800 rounded-2xl overflow-hidden shadow-xl p-6 sm:p-8 relative">
                    {{-- Dekorasi Latar Belakang --}}
                    <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-amber-500/10 border border-amber-500/20 rounded-xl flex-shrink-0">
                            <x-heroicon-s-sparkles class="w-6 h-6 text-amber-400" />
                        </div>
                        <div>
                            <span class="px-2 py-0.5 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[10px] font-bold rounded-md uppercase tracking-wider">
                                Pro Member
                            </span>
                            <h3 class="text-lg font-bold text-white mt-2">
                                Welcome Back, Premium User!
                            </h3>
                            <p class="text-xs text-gray-400 mt-1 leading-relaxed">
                                Your account is fully upgraded. Enjoy complete access to advanced budgeting tools, extensive reports, and multi-wallet management across your dashboard.
                            </p>
                        </div>
                    </div>

                    <!-- Grid Penjelasan Singkat Fitur yang Sekarang Aktif -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-8 border-t border-gray-800/80 pt-6">
                        <div class="bg-[#111625] border border-gray-800/60 p-3 rounded-xl text-center">
                            <x-heroicon-o-wallet class="w-4 h-4 mx-auto text-[#3b82f6] mb-1.5" />
                            <h5 class="text-xs font-bold text-white">Multi Wallets</h5>
                            <p class="text-[10px] text-gray-500 mt-0.5">Manage separate assets</p>
                        </div>
                        <div class="bg-[#111625] border border-gray-800/60 p-3 rounded-xl text-center">
                            <x-heroicon-o-clipboard-document-list class="w-4 h-4 mx-auto text-amber-400 mb-1.5" />
                            <h5 class="text-xs font-bold text-white">Smart Budgets</h5>
                            <p class="text-[10px] text-gray-500 mt-0.5">Control overspending</p>
                        </div>
                        <div class="bg-[#111625] border border-gray-800/60 p-3 rounded-xl text-center">
                            <x-heroicon-o-presentation-chart-line class="w-4 h-4 mx-auto text-emerald-400 mb-1.5" />
                            <h5 class="text-xs font-bold text-white">PRO Reports</h5>
                            <p class="text-[10px] text-gray-500 mt-0.5">Deep financial analytics</p>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
