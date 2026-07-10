<x-app-layout>
    <!-- Header Minimalis Sesuai Desain Dasbor Premium -->
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-6 sm:px-6 lg:px-8 py-1.5">
            <h2 class="font-bold text-lg text-white leading-tight flex items-center gap-2">
                <x-heroicon-s-arrow-path-rounded-square class="w-4 h-4 text-amber-400" />
                {{ __('Recurring Transactions') }}
            </h2>
        </div>
    </x-slot>

    <!-- Pembungkus Utama dengan Jarak Samping Aman -->
    <div class="py-6 sm:py-10 bg-[#090a0f] min-h-screen text-gray-200">
        <div class="max-w-7xl mx-auto px-6 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-[#0f172a] border border-gray-800 rounded-2xl overflow-hidden shadow-lg shadow-black/20">
                <div class="p-6">

                    <!-- Top Bar Bagian Atas -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <div>
                            <h3 class="text-sm font-bold text-white flex items-center">
                                <x-heroicon-s-clock class="w-4 h-4 mr-1.5 text-amber-400" />
                                Automated Schedules
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5">Manage your subscriptions, regular bills, and automated income distribution.</p>
                        </div>
                        <a href="{{ route('recurring-transactions.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl focus:outline-none transition shadow-lg shadow-amber-500/10 w-full sm:w-auto text-center">
                            <x-heroicon-o-plus-circle class="w-4 h-4 mr-1.5 flex-shrink-0" />
                            {{ __('Add New Automation') }}
                        </a>
                    </div>

                    <!-- Notifikasi Status Operasi -->
                    @if (session('status'))
                        <div class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold rounded-xl flex items-center gap-2">
                            <x-heroicon-s-check-circle class="w-4 h-4" />
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Area Konten / Tabel Utama -->
                    <div class="mt-4">
                        @if ($recurringTransactions->isEmpty())
                            <div class="p-10 text-center border border-dashed border-gray-800 rounded-2xl flex flex-col items-center justify-center">
                                <x-heroicon-o-arrow-path class="w-10 h-10 mb-3 text-gray-600" />
                                <p class="text-sm font-semibold text-white">{{ __('No automation schedules found.') }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ __('Save time by setting up recurring rules for routine monthly expenses or bills.') }}</p>
                                <a href="{{ route('recurring-transactions.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-transparent border border-gray-700 hover:border-gray-500 text-gray-300 hover:text-white font-semibold text-xs rounded-xl transition">
                                    <x-heroicon-o-plus-circle class="w-3.5 h-3.5 mr-1.5" />
                                    {{ __('Create Automation') }}
                                </a>
                            </div>
                        @else
                            <!-- Pembungkus Tabel dengan Padding Kiri-Kanan Lebar (px-6) -->
                            <div class="overflow-x-auto border border-gray-800 rounded-xl shadow-inner">
                                <table class="min-w-full divide-y divide-gray-800/80">
                                    <thead class="bg-[#111625]">
                                    <tr class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                        <th scope="col" class="px-6 py-3.5 text-left">{{ __('Description') }}</th>
                                        <th scope="col" class="px-6 py-3.5 text-left">{{ __('Type') }}</th>
                                        <th scope="col" class="px-6 py-3.5 text-left">{{ __('Category') }}</th>
                                        <th scope="col" class="px-6 py-3.5 text-left">{{ __('Frequency') }}</th>
                                        <th scope="col" class="px-6 py-3.5 text-left">{{ __('Next Run') }}</th>
                                        <th scope="col" class="px-6 py-3.5 text-right">{{ __('Amount') }}</th>
                                        <th scope="col" class="relative px-6 py-3.5 text-right">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-[#0f172a] divide-y divide-gray-800/60 text-xs">
                                    @foreach ($recurringTransactions as $recurringTransaction)
                                        <tr class="hover:bg-[#151f38] transition-colors">
                                            <!-- Deskripsi -->
                                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-white max-w-[180px] truncate">
                                                {{ $recurringTransaction->description }}
                                            </td>
                                            <!-- Tipe Transaksi (Income / Expense) -->
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="{{ $recurringTransaction->type === 'income' ? 'text-emerald-400 border-emerald-500/20' : 'text-rose-400 border-rose-500/20' }} uppercase text-[10px] tracking-wider px-2 py-0.5 rounded-md bg-gray-800/40 border">
                                                        {{ $recurringTransaction->type }}
                                                    </span>
                                            </td>
                                            <!-- Kategori -->
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-300">
                                                    <span class="inline-flex items-center gap-1">
                                                        <x-heroicon-o-tag class="w-3.5 h-3.5 text-gray-500" />
                                                        {{ $recurringTransaction->category->name ?? 'Uncategorized' }}
                                                    </span>
                                            </td>
                                            <!-- Frekuensi Pengulangan -->
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center gap-1 text-gray-300 font-medium capitalize">
                                                        <x-heroicon-o-arrow-path class="w-3.5 h-3.5 text-[#3b82f6]" />
                                                        {{ $recurringTransaction->frequency }}
                                                    </span>
                                            </td>
                                            <!-- Jadwal Eksekusi Berikutnya -->
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-400 text-[11px]">
                                                    <span class="inline-flex items-center gap-1">
                                                        <x-heroicon-o-calendar class="w-3.5 h-3.5 text-gray-500" />
                                                        {{ $recurringTransaction->next_run_date ? (is_string($recurringTransaction->next_run_date) ? $recurringTransaction->next_run_date : $recurringTransaction->next_run_date->format('Y-m-d')) : 'N/A' }}
                                                    </span>
                                            </td>
                                            <!-- Nominal Uang -->
                                            <td class="px-6 py-4 whitespace-nowrap text-right font-black text-white">
                                                {{ number_format($recurringTransaction->amount, 2) }} <span class="text-[10px] font-bold text-gray-400">{{ $recurringTransaction->currency }}</span>
                                            </td>
                                            <!-- Tombol Aksi Kanan -->
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-[11px] font-semibold space-x-2">
                                                <a href="{{ route('recurring-transactions.edit', $recurringTransaction) }}" class="text-[#3b82f6] hover:text-blue-400 inline-flex items-center transition">
                                                    <x-heroicon-o-pencil-square class="w-3.5 h-3.5 mr-0.5" />
                                                    {{ __('Edit') }}
                                                </a>
                                                <form action="{{ route('recurring-transactions.destroy', $recurringTransaction) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this recurring transaction?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-400 hover:text-rose-300 inline-flex items-center transition bg-transparent border-0 p-0 cursor-pointer">
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
