<x-app-layout>
    <!-- Header Minimalis Sesuai Tema Dashboard Premium -->
    <x-slot name="header">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-1.5">
            <h2 class="font-bold text-lg text-white leading-tight flex items-center gap-2">
                <x-heroicon-s-pencil-square class="w-4 h-4 text-amber-400" />
                {{ __('Edit Recurring Transaction') }}
            </h2>
        </div>
    </x-slot>

    <!-- Pembungkus Form Terpusat -->
    <div class="py-6 sm:py-10 bg-[#090a0f] min-h-screen text-gray-200">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#0f172a] border border-gray-800 rounded-2xl overflow-hidden shadow-lg shadow-black/20">
                <div class="p-5 sm:p-6">
                    <h3 class="text-sm font-bold text-white mb-5 flex items-center">
                        <x-heroicon-s-clock class="w-4 h-4 mr-1.5 text-[#3b82f6]" />
                        {{ __('Modify Automation Schedule') }}
                    </h3>

                    <form method="POST" action="{{ route('recurring-transactions.update', $recurringTransaction) }}" class="space-y-4">
                        @csrf
                        @method('patch')

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" class="text-gray-400 font-medium flex items-center text-xs">
                                <x-heroicon-o-document-text class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                {{ __('Description / Label') }}
                            </x-input-label>
                            <x-text-input id="description" name="description" type="text" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" :value="old('description', $recurringTransaction->description)" required autofocus />
                            <x-input-error class="mt-1.5 text-xs text-rose-400" :messages="$errors->get('description')" />
                        </div>

                        <!-- Grid Finansial (Amount & Currency) -->
                        <div class="grid grid-cols-3 gap-4">
                            <!-- Amount (2/3 width) -->
                            <div class="col-span-2">
                                <x-input-label for="amount" class="text-gray-400 font-medium flex items-center text-xs">
                                    <x-heroicon-o-banknotes class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                    {{ __('Amount') }}
                                </x-input-label>
                                <x-text-input id="amount" name="amount" type="number" step="0.01" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" :value="old('amount', $recurringTransaction->amount)" required />
                                <x-input-error class="mt-1.5 text-xs text-rose-400" :messages="$errors->get('amount')" />
                            </div>

                            <!-- Currency (1/3 width) -->
                            <div>
                                <x-input-label for="currency" class="text-gray-400 font-medium flex items-center text-xs">
                                    <x-heroicon-o-globe-alt class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                    {{ __('Currency') }}
                                </x-input-label>
                                <select id="currency" name="currency" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" required>
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency }}" {{ old('currency', $recurringTransaction->currency) == $currency ? 'selected' : '' }} class="bg-[#0f172a]">
                                            {{ $currency }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-1.5 text-xs text-rose-400" :messages="$errors->get('currency')" />
                            </div>
                        </div>

                        <!-- Grid Tipe & Kategori -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Transaction Type -->
                            <div>
                                <x-input-label for="type" class="text-gray-400 font-medium flex items-center text-xs">
                                    <x-heroicon-o-arrows-right-left class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                    {{ __('Transaction Type') }}
                                </x-input-label>
                                <select id="type" name="type" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" required>
                                    <option value="expense" {{ old('type', $recurringTransaction->type) == 'expense' ? 'selected' : '' }} class="bg-[#0f172a]">{{ __('Expense (Pengeluaran)') }}</option>
                                    <option value="income" {{ old('type', $recurringTransaction->type) == 'income' ? 'selected' : '' }} class="bg-[#0f172a]">{{ __('Income (Pemasukan)') }}</option>
                                </select>
                                <x-input-error class="mt-1.5 text-xs text-rose-400" :messages="$errors->get('type')" />
                            </div>

                            <!-- Category -->
                            <div>
                                <x-input-label for="category_id" class="text-gray-400 font-medium flex items-center text-xs">
                                    <x-heroicon-o-tag class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                    {{ __('Category (Optional)') }}
                                </x-input-label>
                                <select id="category_id" name="category_id" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none">
                                    <option value="" class="bg-[#0f172a]">{{ __('Select a Category') }}</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $recurringTransaction->category_id) == $category->id ? 'selected' : '' }} class="bg-[#0f172a]">
                                            {{ $category->name }} ({{ ucfirst($category->type) }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-1.5 text-xs text-rose-400" :messages="$errors->get('category_id')" />
                            </div>
                        </div>

                        <!-- Interval / Frequency -->
                        <div>
                            <x-input-label for="frequency" class="text-gray-400 font-medium flex items-center text-xs">
                                <x-heroicon-o-arrow-path class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                {{ __('Recurrence Frequency') }}
                            </x-input-label>
                            <select id="frequency" name="frequency" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" required>
                                <option value="daily" {{ old('frequency', $recurringTransaction->frequency) == 'daily' ? 'selected' : '' }} class="bg-[#0f172a]">{{ __('Daily (Harian)') }}</option>
                                <option value="weekly" {{ old('frequency', $recurringTransaction->frequency) == 'weekly' ? 'selected' : '' }} class="bg-[#0f172a]">{{ __('Weekly (Mingguan)') }}</option>
                                <option value="monthly" {{ old('frequency', $recurringTransaction->frequency) == 'monthly' ? 'selected' : '' }} class="bg-[#0f172a]">{{ __('Monthly (Bulanan)') }}</option>
                                <option value="yearly" {{ old('frequency', $recurringTransaction->frequency) == 'yearly' ? 'selected' : '' }} class="bg-[#0f172a]">{{ __('Yearly (Tahunan)') }}</option>
                            </select>
                            <x-input-error class="mt-1.5 text-xs text-rose-400" :messages="$errors->get('frequency')" />
                        </div>

                        <!-- Grid Durasi (Start & End Date) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Start Date -->
                            <div>
                                <x-input-label for="start_date" class="text-gray-400 font-medium flex items-center text-xs">
                                    <x-heroicon-o-calendar-days class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                    {{ __('First Execution Date') }}
                                </x-input-label>
                                <x-text-input id="start_date" name="start_date" type="date" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" :value="old('start_date', (is_string($recurringTransaction->start_date) ? $recurringTransaction->start_date : $recurringTransaction->start_date?->format('Y-m-d')))" required />
                                <x-input-error class="mt-1.5 text-xs text-rose-400" :messages="$errors->get('start_date')" />
                            </div>

                            <!-- End Date -->
                            <div>
                                <x-input-label for="end_date" class="text-gray-400 font-medium flex items-center text-xs">
                                    <x-heroicon-o-calendar class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                    {{ __('End Date (Optional)') }}
                                </x-input-label>
                                <x-text-input id="end_date" name="end_date" type="date" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" :value="old('end_date', $recurringTransaction->end_date ? (is_string($recurringTransaction->end_date) ? $recurringTransaction->end_date : $recurringTransaction->end_date->format('Y-m-d')) : '')" />
                                <x-input-error class="mt-1.5 text-xs text-rose-400" :messages="$errors->get('end_date')" />
                            </div>
                        </div>

                        <!-- Tombol Aksi Bawah -->
                        <div class="flex items-center justify-end gap-2 border-t border-gray-800/80 pt-4 mt-2">
                            <a href="{{ route('recurring-transactions.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-transparent border border-gray-700 hover:border-gray-500 text-gray-300 hover:text-white font-semibold text-xs rounded-xl transition">
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
