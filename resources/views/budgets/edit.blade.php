<x-app-layout>
    <!-- Header Minimalis Bertema Premium -->
    <x-slot name="header">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-1.5">
            <h2 class="font-bold text-lg text-white leading-tight flex items-center gap-2">
                <x-heroicon-s-pencil-square class="w-4 h-4 text-amber-400" />
                {{ __('Edit Budget') }}
            </h2>
        </div>
    </x-slot>

    <!-- Pembungkus Form Terpusat -->
    <div class="py-6 sm:py-10 bg-[#090a0f] min-h-screen text-gray-200">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#0f172a] border border-gray-800 rounded-2xl overflow-hidden shadow-lg shadow-black/20">
                <div class="p-5 sm:p-6">
                    <h3 class="text-sm font-bold text-white mb-5 flex items-center">
                        <x-heroicon-s-adjustments-horizontal class="w-4 h-4 mr-1.5 text-[#3b82f6]" />
                        {{ __('Modify Budget Configuration') }}
                    </h3>

                    <form method="POST" action="{{ route('budgets.update', $budget) }}" class="space-y-4">
                        @csrf
                        @method('patch')

                        {{-- Wallet Selection (Premium Support dengan Tampilan Eksklusif) --}}
                        @if (Auth::user()->role === 'premium')
                            <div>
                                <x-input-label for="wallet_id" class="text-gray-400 font-medium flex items-center text-xs">
                                    <x-heroicon-o-wallet class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                    {{ __('Target Wallet') }}
                                </x-input-label>
                                <select id="wallet_id" name="wallet_id" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none">
                                    <option value="" class="bg-[#0f172a]">{{ __('All Wallets (Global Budget)') }}</option>
                                    @foreach ($wallets as $wallet)
                                        <option value="{{ $wallet->id }}" {{ old('wallet_id', $budget->wallet_id) == $wallet->id ? 'selected' : '' }} class="bg-[#0f172a]">
                                            {{ $wallet->name }} ({{ number_format($wallet->balance, 2) }} {{ $wallet->currency }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-1.5 text-xs text-rose-400" :messages="$errors->get('wallet_id')" />
                            </div>
                        @else
                            <div class="p-3 bg-amber-500/5 border border-amber-500/10 rounded-xl flex items-start gap-2.5 mb-2">
                                <x-heroicon-s-star class="w-4 h-4 text-amber-400 mt-0.5 flex-shrink-0" />
                                <p class="text-[11px] text-gray-400 leading-relaxed">
                                    <strong class="text-white font-semibold">{{ __('Multi-wallet budgeting') }}</strong> {{ __('is a premium feature. This budget rule will apply universally to all accumulated transactions across your account.') }}
                                </p>
                            </div>
                        @endif

                        <!-- Category Selection -->
                        <div>
                            <x-input-label for="category_id" class="text-gray-400 font-medium flex items-center text-xs">
                                <x-heroicon-o-tag class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                {{ __('Target Category') }}
                            </x-input-label>
                            <select id="category_id" name="category_id" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none">
                                <option value="" class="bg-[#0f172a]">{{ __('All Categories (General Budget)') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $budget->category_id) == $category->id ? 'selected' : '' }} class="bg-[#0f172a]">
                                        {{ $category->name }} ({{ ucfirst($category->type) }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-1.5 text-xs text-rose-400" :messages="$errors->get('category_id')" />
                        </div>

                        <!-- Amount Limit -->
                        <div>
                            <x-input-label for="amount" class="text-gray-400 font-medium flex items-center text-xs">
                                <x-heroicon-o-banknotes class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                {{ __('Amount Limit') }}
                            </x-input-label>
                            <x-text-input id="amount" name="amount" type="number" step="0.01" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" :value="old('amount', $budget->amount)" required autofocus />
                            <x-input-error class="mt-1.5 text-xs text-rose-400" :messages="$errors->get('amount')" />
                        </div>

                        {{-- Currency Dropdown --}}
                        <div>
                            <x-input-label for="currency" class="text-gray-400 font-medium flex items-center text-xs">
                                <x-heroicon-o-currency-dollar class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                {{ __('Currency') }}
                            </x-input-label>
                            <select id="currency" name="currency" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" required>
                                @php
                                    $currencies = ['USD', 'EUR', 'GBP', 'JPY', 'AUD', 'CAD', 'CHF', 'CNY', 'SEK', 'NZD', 'IDR'];
                                @endphp
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency }}" {{ old('currency', $budget->currency) == $currency ? 'selected' : '' }} class="bg-[#0f172a]">
                                        {{ $currency }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-1.5 text-xs text-rose-400" :messages="$errors->get('currency')" />
                        </div>

                        <!-- Period Cycle -->
                        <div>
                            <x-input-label for="period" class="text-gray-400 font-medium flex items-center text-xs">
                                <x-heroicon-o-arrow-path class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                {{ __('Recurrence Period') }}
                            </x-input-label>
                            <select id="period" name="period" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" required>
                                <option value="monthly" {{ old('period', $budget->period) == 'monthly' ? 'selected' : '' }} class="bg-[#0f172a]">{{ __('Monthly') }}</option>
                                <option value="yearly" {{ old('period', $budget->period) == 'yearly' ? 'selected' : '' }} class="bg-[#0f172a]">{{ __('Yearly') }}</option>
                            </select>
                            <x-input-error class="mt-1.5 text-xs text-rose-400" :messages="$errors->get('period')" />
                        </div>

                        <!-- Grid Tanggal Berjalan (Start & End Date) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Start Date -->
                            <div>
                                <x-input-label for="start_date" class="text-gray-400 font-medium flex items-center text-xs">
                                    <x-heroicon-o-calendar-days class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                    {{ __('Start Date') }}
                                </x-input-label>
                                <x-text-input id="start_date" name="start_date" type="date" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" :value="old('start_date', (is_string($budget->start_date) ? $budget->start_date : $budget->start_date?->format('Y-m-d')))" required />
                                <x-input-error class="mt-1.5 text-xs text-rose-400" :messages="$errors->get('start_date')" />
                            </div>

                            <!-- End Date -->
                            <div>
                                <x-input-label for="end_date" class="text-gray-400 font-medium flex items-center text-xs">
                                    <x-heroicon-o-calendar class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                    {{ __('End Date (Optional)') }}
                                </x-input-label>
                                <x-text-input id="end_date" name="end_date" type="date" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" :value="old('end_date', ($budget->end_date ? (is_string($budget->end_date) ? $budget->end_date : $budget->end_date->format('Y-m-d')) : ''))" />
                                <x-input-error class="mt-1.5 text-xs text-rose-400" :messages="$errors->get('end_date')" />
                            </div>
                        </div>

                        <!-- Tombol Aksi Bawah -->
                        <div class="flex items-center justify-end gap-2 border-t border-gray-800/80 pt-4 mt-2">
                            <a href="{{ route('budgets.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-transparent border border-gray-700 hover:border-gray-500 text-gray-300 hover:text-white font-semibold text-xs rounded-xl transition">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl focus:outline-none transition shadow-lg shadow-amber-500/10">
                                <x-heroicon-o-check-circle class="w-4 h-4 mr-1.5" />
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
