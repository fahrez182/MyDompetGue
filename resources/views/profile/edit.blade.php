<x-app-layout>
    <!-- Header Minimalis Sesuai Tema Dashboard Premium -->
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-6 sm:px-6 lg:px-8 py-1.5">
            <h2 class="font-bold text-lg text-white leading-tight flex items-center gap-2">
                <x-heroicon-s-user-circle class="w-4 h-4 text-amber-400" />
                {{ __('Account Settings') }}
            </h2>
        </div>
    </x-slot>

    <!-- Pembungkus Utama Konten -->
    <div class="py-6 sm:py-10 bg-[#090a0f] min-h-screen text-gray-200">
        <div class="max-w-7xl mx-auto px-6 sm:px-6 lg:px-8 space-y-6">

            <!-- Blok Informasi Profil -->
            <div class="p-6 bg-[#0f172a] border border-gray-800 rounded-2xl shadow-lg shadow-black/20">
                <div class="max-w-xl">
                    <div class="flex items-center gap-2 mb-4 border-b border-gray-800/60 pb-3">
                        <x-heroicon-s-identification class="w-4 h-4 text-[#3b82f6]" />
                        <h3 class="text-sm font-bold text-white">{{ __('Profile Information') }}</h3>
                    </div>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Blok Ganti Kata Sandi -->
            <div class="p-6 bg-[#0f172a] border border-gray-800 rounded-2xl shadow-lg shadow-black/20">
                <div class="max-w-xl">
                    <div class="flex items-center gap-2 mb-4 border-b border-gray-800/60 pb-3">
                        <x-heroicon-s-key class="w-4 h-4 text-amber-500" />
                        <h3 class="text-sm font-bold text-white">{{ __('Update Password') }}</h3>
                    </div>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Manajemen Dompet / Wallet Section --}}
            @if (Auth::user()->role === 'premium')
                <div class="p-6 bg-[#0f172a] border border-gray-800 rounded-2xl shadow-lg shadow-black/20">
                    <div class="flex items-center gap-2 mb-4 border-b border-gray-800/60 pb-3">
                        <x-heroicon-s-wallet class="w-4 h-4 text-emerald-400" />
                        <h3 class="text-sm font-bold text-white">{{ __('Manage Active Wallets') }}</h3>
                    </div>
                    @include('profile.partials.manage-wallets')
                </div>
            @else
                <!-- Tampilan Ajakan Upgrade Premium Menarik (Banner Kontras) -->
                <div class="p-6 bg-gradient-to-r from-[#0f172a] via-[#161c30] to-[#121324] border border-amber-500/20 rounded-2xl shadow-xl shadow-amber-950/5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6 opacity-5 pointer-events-none">
                        <x-heroicon-s-star class="w-40 h-40 text-amber-400" />
                    </div>

                    <section class="relative z-10">
                        <header class="flex items-start gap-3">
                            <div class="p-2 bg-amber-500/10 border border-amber-500/20 rounded-xl mt-0.5">
                                <x-heroicon-s-star class="w-5 h-5 text-amber-400" />
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-white tracking-tight">
                                    {{ __('Unlock Multi-Wallet System') }}
                                </h2>
                                <p class="mt-1 text-xs text-gray-400 max-w-xl leading-relaxed">
                                    {{ __('Separate your business finances, savings targets, and daily expenses efficiently. Manage multiple local or foreign currency sub-wallets under one main master account.') }}
                                </p>
                            </div>
                        </header>

                        <div class="mt-5 flex items-center justify-start gap-3 pl-11">
                            <a href="{{ route('premium.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl focus:outline-none transition shadow-lg shadow-amber-500/10">
                                <x-heroicon-s-bolt class="w-3.5 h-3.5 mr-1" />
                                {{ __('Upgrade to Premium') }}
                            </a>
                        </div>
                    </section>
                </div>
            @endif

            <!-- Blok Hapus Akun -->
            <div class="p-6 bg-[#0f172a] border border-rose-950/40 rounded-2xl shadow-lg shadow-black/20">
                <div class="max-w-xl">
                    <div class="flex items-center gap-2 mb-4 border-b border-rose-950/60 pb-3">
                        <x-heroicon-s-trash class="w-4 h-4 text-rose-400" />
                        <h3 class="text-sm font-bold text-white">{{ __('Danger Zone') }}</h3>
                    </div>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
