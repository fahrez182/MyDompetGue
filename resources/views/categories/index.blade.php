<x-app-layout>
    <!-- Header Minimalis Sesuai Dashboard -->
    <x-slot name="header">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-1.5">
            <h2 class="font-bold text-lg text-white leading-tight flex items-center gap-2">
                <x-heroicon-s-tag class="w-4 h-4 text-[#3b82f6]" />
                {{ __('Categories') }}
            </h2>
        </div>
    </x-slot>

    <!-- Pembungkus Utama Menggunakan max-w-5xl Supaya Lebarnya Konsisten -->
    <div class="py-6 sm:py-10 bg-[#090a0f] min-h-screen text-gray-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            <div class="bg-[#0f172a] border border-gray-800 rounded-2xl overflow-hidden shadow-lg shadow-black/20">
                <div class="p-5 sm:p-6">

                    <!-- Bagian Atas Utama -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <h3 class="text-sm font-bold text-white flex items-center">
                            <x-heroicon-s-list-bullet class="w-4 h-4 mr-1.5 text-[#3b82f6]" />
                            Category Management
                        </h3>
                        <a href="{{ route('categories.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-[#3b82f6] hover:bg-[#2563eb] text-white font-semibold text-xs rounded-xl focus:outline-none transition shadow-lg shadow-[#3b82f6]/10 w-full sm:w-auto text-center">
                            <x-heroicon-o-plus-circle class="w-4 h-4 mr-1.5 flex-shrink-0" />
                            {{ __('Add New Category') }}
                        </a>
                    </div>

                    <!-- Area Data / Tabel -->
                    <div class="mt-4">
                        @if ($categories->isEmpty())
                            <div class="p-8 text-center border border-dashed border-gray-800 rounded-2xl flex flex-col items-center justify-center">
                                <x-heroicon-o-tag class="w-10 h-10 mb-3 text-gray-600" />
                                <p class="text-sm font-semibold text-white">{{ __('No categories found.') }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ __('Please add some categories to organize your transactions.') }}</p>
                                <a href="{{ route('categories.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-transparent border border-gray-700 hover:border-gray-500 text-gray-300 hover:text-white font-semibold text-xs rounded-xl transition">
                                    <x-heroicon-o-plus-circle class="w-3.5 h-3.5 mr-1.5" />
                                    {{ __('Add First Category') }}
                                </a>
                            </div>
                        @else
                            <!-- Pembungkus Tabel yang Mendukung Scrollbar Tipis -->
                            <div class="overflow-x-auto border border-gray-800 rounded-xl shadow-inner">
                                <table class="min-w-full divide-y divide-gray-800/80">
                                    <thead class="bg-[#111625]">
                                    <tr class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                        <th scope="col" class="px-5 py-3 text-left">
                                            <x-heroicon-o-bookmark class="w-3.5 h-3.5 inline-block mr-1 align-text-bottom" /> {{ __('Name') }}
                                        </th>
                                        <th scope="col" class="px-5 py-3 text-left">
                                            <x-heroicon-o-arrows-up-down class="w-3.5 h-3.5 inline-block mr-1 align-text-bottom" /> {{ __('Type') }}
                                        </th>
                                        <th scope="col" class="relative px-5 py-3">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-[#0f172a] divide-y divide-gray-800/60 text-xs">
                                    @foreach ($categories as $category)
                                        <tr class="hover:bg-[#151f38] transition-colors">
                                            <!-- Nama Kategori -->
                                            <td class="px-5 py-3.5 whitespace-nowrap font-medium text-white">
                                                {{ $category->name }}
                                            </td>
                                            <!-- Tipe Kategori (Expense / Income dengan Badge Berwarna) -->
                                            <td class="px-5 py-3.5 whitespace-nowrap">
                                                    <span class="inline-flex items-center font-semibold {{ $category->type === 'income' ? 'text-emerald-400' : 'text-rose-400' }}">
                                                        @if ($category->type === 'income')
                                                            <x-heroicon-s-arrow-up-circle class="w-4 h-4 mr-1 flex-shrink-0" />
                                                        @else
                                                            <x-heroicon-s-arrow-down-circle class="w-4 h-4 mr-1 flex-shrink-0" />
                                                        @endif
                                                        {{ ucfirst($category->type) }}
                                                    </span>
                                            </td>
                                            <!-- Tombol Aksi -->
                                            <td class="px-5 py-3.5 whitespace-nowrap text-right text-[11px] font-semibold space-x-2">
                                                <a href="{{ route('categories.edit', $category) }}" class="text-[#3b82f6] hover:text-blue-400 inline-flex items-center transition">
                                                    <x-heroicon-o-pencil-square class="w-3.5 h-3.5 mr-0.5" />
                                                    {{ __('Edit') }}
                                                </a>
                                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-400 hover:text-rose-300 inline-flex items-center transition bg-transparent border-0 p-0" onclick="return confirm('Are you sure you want to delete this category?')">
                                                        <x-heroicon-o-trash class="w-3.5 h-3.5 mr-0.5" />
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
