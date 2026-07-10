<x-app-layout>
    <!-- Header Minimalis Sesuai Tema Dashboard Premium -->
    <x-slot name="header">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-1.5">
            <h2 class="font-bold text-lg text-white leading-tight flex items-center gap-2">
                <x-heroicon-s-plus-circle class="w-4 h-4 text-emerald-400" />
                {{ __('Create Wallet') }}
            </h2>
        </div>
    </x-slot>

    <!-- Pembungkus Form Terpusat -->
    <div class="py-6 sm:py-10 bg-[#090a0f] min-h-screen text-gray-200">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#0f172a] border border-gray-800 rounded-2xl overflow-hidden shadow-lg shadow-black/20">
                <div class="p-5 sm:p-6">
                    <h3 class="text-sm font-bold text-white mb-5 flex items-center">
                        <x-heroicon-s-wallet class="w-4 h-4 mr-1.5 text-emerald-400" />
                        {{ __('New Sub-Wallet Setup') }}
                    </h3>

                    <form method="POST" action="{{ route('wallets.store') }}" class="space-y-4">
                        @csrf

                        <!-- Wallet Name -->
                        <div>
                            <x-input-label for="name" class="text-gray-400 font-medium flex items-center text-xs">
                                <x-heroicon-o-credit-card class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                {{ __('Wallet Name') }}
                            </x-input-label>
                            <x-text-input id="name" name="name" type="text" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" :value="old('name')" placeholder="e.g., Business Account, Daily Savings" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-xs text-rose-400" />
                        </div>

                        <!-- Grid Finansial (Initial Balance & Currency) -->
                        <div class="grid grid-cols-3 gap-4">
                            <!-- Initial Balance (2/3 width) -->
                            <div class="col-span-2">
                                <x-input-label for="balance" class="text-gray-400 font-medium flex items-center text-xs">
                                    <x-heroicon-o-banknotes class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                    {{ __('Initial Balance') }}
                                </x-input-label>
                                <x-text-input id="balance" name="balance" type="number" step="0.01" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" :value="old('balance', '0.00')" required />
                                <x-input-error :messages="$errors->get('balance')" class="mt-1.5 text-xs text-rose-400" />
                            </div>

                            <!-- Currency (1/3 width) -->
                            <div>
                                <x-input-label for="currency" class="text-gray-400 font-medium flex items-center text-xs">
                                    <x-heroicon-o-globe-alt class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                    {{ __('Currency') }}
                                </x-input-label>
                                <select id="currency" name="currency" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none text-center font-bold tracking-wider" required>
                                    @foreach ($supportedCurrencies as $currencyCode)
                                        <option value="{{ $currencyCode }}" {{ old('currency', 'USD') == $currencyCode ? 'selected' : '' }}>
                                            {{ $currencyCode }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('currency')" class="mt-1.5 text-xs text-rose-400" />
                            </div>
                        </div>

                        <!-- Tombol Aksi Bawah -->
                        <div class="flex items-center justify-end gap-2 border-t border-gray-800/80 pt-4 mt-2">
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-transparent border border-gray-700 hover:border-gray-500 text-gray-300 hover:text-white font-semibold text-xs rounded-xl transition">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-bold text-xs rounded-xl focus:outline-none transition shadow-lg shadow-emerald-500/10">
                                <x-heroicon-o-check-circle class="w-4 h-4 mr-1.5" />
                                {{ __('Create Wallet') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
