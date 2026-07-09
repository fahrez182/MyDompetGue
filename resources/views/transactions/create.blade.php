<x-app-layout>
    <x-slot name="header">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-1.5">
            <h2 class="font-bold text-lg text-white leading-tight flex items-center gap-2">
                <x-heroicon-s-plus-circle class="w-4 h-4 text-[#3b82f6]" />
                {{ __('Create New Transaction') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10 bg-[#090a0f] min-h-screen text-gray-200">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#0f172a] border border-gray-800 rounded-2xl overflow-hidden shadow-lg shadow-black/20">
                <div class="p-5 sm:p-6">
                    <h3 class="text-sm font-bold text-white mb-5 flex items-center">
                        <x-heroicon-s-pencil-square class="w-4 h-4 mr-1.5 text-[#3b82f6]" />
                        {{ __('Transaction Details') }}
                    </h3>

                    <form method="POST" action="{{ route('transactions.store') }}" class="space-y-4">
                        @csrf

                        <!-- Amount -->
                        <div>
                            <x-input-label for="amount" class="text-gray-400 font-medium flex items-center text-xs">
                                <x-heroicon-o-currency-dollar class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                {{ __('Amount') }}
                            </x-input-label>
                            <x-text-input id="amount" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" type="number" step="0.01" name="amount" :value="old('amount')" required autofocus />
                            <x-input-error :messages="$errors->get('amount')" class="mt-1.5 text-xs text-rose-400" />
                        </div>

                        <!-- Dua Kolom untuk Currency dan Type agar Form Lebih Pendek -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Currency -->
                            <div>
                                <x-input-label for="currency" class="text-gray-400 font-medium flex items-center text-xs">
                                    <x-heroicon-o-banknotes class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                    {{ __('Currency') }}
                                </x-input-label>
                                <select id="currency" name="currency" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" required>
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency }}" {{ old('currency', $userBaseCurrency) == $currency ? 'selected' : '' }} class="bg-[#0f172a]">
                                            {{ $currency }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('currency')" class="mt-1.5 text-xs text-rose-400" />
                            </div>

                            <!-- Type -->
                            <div>
                                <x-input-label for="type" class="text-gray-400 font-medium flex items-center text-xs">
                                    <x-heroicon-o-arrows-up-down class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                    {{ __('Type') }}
                                </x-input-label>
                                <select id="type" name="type" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" required>
                                    <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }} class="bg-[#0f172a]">{{ __('Expense') }}</option>
                                    <option value="income" {{ old('type') == 'income' ? 'selected' : '' }} class="bg-[#0f172a]">{{ __('Income') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-1.5 text-xs text-rose-400" />
                            </div>
                        </div>

                        <!-- Category -->
                        <div>
                            <x-input-label for="category_id" class="text-gray-400 font-medium flex items-center text-xs">
                                <x-heroicon-o-tag class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                {{ __('Category') }}
                            </x-input-label>
                            <select id="category_id" name="category_id" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none">
                                <option value="" class="bg-[#0f172a]">{{ __('Select a category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }} class="bg-[#0f172a]">
                                        {{ $category->name }} ({{ ucfirst($category->type) }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-1.5 text-xs text-rose-400" />
                        </div>

                        <!-- Transaction Date -->
                        <div>
                            <x-input-label for="transaction_date" class="text-gray-400 font-medium flex items-center text-xs">
                                <x-heroicon-o-calendar-days class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                {{ __('Transaction Date') }}
                            </x-input-label>
                            <x-text-input id="transaction_date" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" type="date" name="transaction_date" :value="old('transaction_date', date('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('transaction_date')" class="mt-1.5 text-xs text-rose-400" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" class="text-gray-400 font-medium flex items-center text-xs">
                                <x-heroicon-o-document-text class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                {{ __('Description') }}
                            </x-input-label>
                            <textarea id="description" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2.5 shadow-sm focus:outline-none h-20 min-h-[40px]" name="description">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-1.5 text-xs text-rose-400" />
                        </div>

                        <!-- Tombol Submit -->
                        <div class="flex items-center justify-end border-t border-gray-800/80 pt-4 mt-2">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-[#3b82f6] hover:bg-[#2563eb] text-white font-semibold text-xs rounded-xl focus:outline-none transition shadow-lg shadow-[#3b82f6]/10">
                                <x-heroicon-o-check-circle class="w-4 h-4 mr-1.5" />
                                {{ __('Save Transaction') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
