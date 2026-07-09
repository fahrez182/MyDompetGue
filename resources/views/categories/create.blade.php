<x-app-layout>
    <!-- Header Minimalis Sesuai Dashboard -->
    <x-slot name="header">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-1.5">
            <h2 class="font-bold text-lg text-white leading-tight flex items-center gap-2">
                <x-heroicon-s-plus-circle class="w-4 h-4 text-[#3b82f6]" />
                {{ __('Create Category') }}
            </h2>
        </div>
    </x-slot>

    <!-- Menggunakan max-w-xl Supaya Form Padat di Tengah & Lebih Pendek -->
    <div class="py-6 sm:py-10 bg-[#090a0f] min-h-screen text-gray-200">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#0f172a] border border-gray-800 rounded-2xl overflow-hidden shadow-lg shadow-black/20">
                <div class="p-5 sm:p-6">
                    <h3 class="text-sm font-bold text-white mb-5 flex items-center">
                        <x-heroicon-s-pencil-square class="w-4 h-4 mr-1.5 text-[#3b82f6]" />
                        {{ __('Category Details') }}
                    </h3>

                    <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
                        @csrf

                        <!-- Category Name -->
                        <div>
                            <x-input-label for="name" class="text-gray-400 font-medium flex items-center text-xs">
                                <x-heroicon-o-bookmark class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                {{ __('Category Name') }}
                            </x-input-label>
                            <x-text-input id="name" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-xs text-rose-400" />
                        </div>

                        <!-- Category Type -->
                        <div>
                            <x-input-label for="type" class="text-gray-400 font-medium flex items-center text-xs">
                                <x-heroicon-o-arrows-up-down class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                                {{ __('Category Type') }}
                            </x-input-label>
                            <select id="type" name="type" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" required>
                                <option value="" class="bg-[#0f172a]">{{ __('Select Type') }}</option>
                                <option value="income" {{ old('type') == 'income' ? 'selected' : '' }} class="bg-[#0f172a]">{{ __('Income') }}</option>
                                <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }} class="bg-[#0f172a]">{{ __('Expense') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-1.5 text-xs text-rose-400" />
                        </div>

                        <!-- Tombol Submit -->
                        <div class="flex items-center justify-end border-t border-gray-800/80 pt-4 mt-2">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-[#3b82f6] hover:bg-[#2563eb] text-white font-semibold text-xs rounded-xl focus:outline-none transition shadow-lg shadow-[#3b82f6]/10">
                                <x-heroicon-o-check-circle class="w-4 h-4 mr-1.5" />
                                {{ __('Create Category') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
