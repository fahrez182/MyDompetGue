<x-app-layout>
    <!-- Header Minimalis Sesuai Dashboard -->
    <x-slot name="header">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-1.5">
            <h2 class="font-bold text-lg text-white leading-tight flex items-center gap-2">
                <x-heroicon-s-arrows-right-left class="w-4 h-4 text-[#3b82f6]" />
                {{ __('Transactions') }}
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
                            Your Transactions
                        </h3>
                        <a href="{{ route('transactions.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-[#3b82f6] hover:bg-[#2563eb] text-white font-semibold text-xs rounded-xl focus:outline-none transition shadow-lg shadow-[#3b82f6]/10 w-full sm:w-auto text-center">
                            <x-heroicon-o-plus-circle class="w-4 h-4 mr-1.5 flex-shrink-0" />
                            {{ __('Add Transaction') }}
                        </a>
                    </div>

                    <!-- Wallet Filter (Premium Alert / Dropdown Sesuai Tema Baru) -->
                    <div class="mb-6 p-4 bg-[#111625] border border-gray-800/80 rounded-xl max-w-sm">
                        <form method="GET" action="{{ route('transactions.index') }}" class="w-full">
                            <div>
                                <x-input-label for="wallet_filter" class="text-gray-400 font-medium flex items-center text-xs mb-1.5">
                                    <x-heroicon-o-wallet class="w-3.5 h-3.5 mr-1.5 text-gray-400" />
                                    {{ __('Filter by Wallet') }}
                                </x-input-label>
                                @if (Auth::user()->role === 'premium')
                                    <select id="wallet_filter" name="wallet_id" class="block w-full bg-[#090a0f] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm" onchange="this.form.submit()">
                                        <option value="" class="bg-[#0f172a]">{{ __('All Wallets') }}</option>
                                        @foreach ($wallets as $wallet)
                                            <option value="{{ $wallet->id }}" {{ request('wallet_id') == $wallet->id ? 'selected' : '' }} class="bg-[#0f172a]">
                                                {{ $wallet->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <div class="flex items-center justify-between bg-[#090a0f] border border-gray-800 rounded-xl px-3 py-2 text-xs text-gray-400">
                                        <span class="flex items-center gap-1.5">
                                            <x-heroicon-s-star class="w-3.5 h-3.5 text-amber-400" />
                                            {{ __('Premium Feature') }}
                                        </span>
                                        <a href="{{ route('premium.index') }}" class="px-2 py-1 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-lg transition text-[10px]">
                                            {{ __('Upgrade') }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Area Data / Tabel -->
                    <div class="mt-4">
                        @if ($transactions->isEmpty())
                            <div class="p-8 text-center border border-dashed border-gray-800 rounded-2xl flex flex-col items-center justify-center">
                                <x-heroicon-o-document-magnifying-glass class="w-10 h-10 mb-3 text-gray-600" />
                                <p class="text-sm font-semibold text-white">{{ __('No transactions found.') }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ __('Start by adding a new transaction to see it listed here.') }}</p>
                                <a href="{{ route('transactions.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-transparent border border-gray-700 hover:border-gray-500 text-gray-300 hover:text-white font-semibold text-xs rounded-xl transition">
                                    <x-heroicon-o-plus-circle class="w-3.5 h-3.5 mr-1.5" />
                                    {{ __('Add New Transaction') }}
                                </a>
                            </div>
                        @else
                            <!-- Pembungkus Tabel yang Mendukung Scrollbar Tipis di app.css -->
                            <div class="overflow-x-auto border border-gray-800 rounded-xl shadow-inner">
                                <table class="min-w-full divide-y divide-gray-800/80">
                                    <thead class="bg-[#111625]">
                                    <tr class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                        <th scope="col" class="px-5 py-3 text-left">
                                            <x-heroicon-o-calendar class="w-3.5 h-3.5 inline-block mr-1 align-text-bottom" /> Date
                                        </th>
                                        <th scope="col" class="px-5 py-3 text-left">
                                            <x-heroicon-o-wallet class="w-3.5 h-3.5 inline-block mr-1 align-text-bottom" /> Wallet
                                        </th>
                                        <th scope="col" class="px-5 py-3 text-left">
                                            <x-heroicon-o-document-text class="w-3.5 h-3.5 inline-block mr-1 align-text-bottom" /> Description
                                        </th>
                                        <th scope="col" class="px-5 py-3 text-left">
                                            <x-heroicon-o-tag class="w-3.5 h-3.5 inline-block mr-1 align-text-bottom" /> Category
                                        </th>
                                        <th scope="col" class="px-5 py-3 text-left">
                                            <x-heroicon-o-arrows-up-down class="w-3.5 h-3.5 inline-block mr-1 align-text-bottom" /> Type
                                        </th>
                                        <th scope="col" class="px-5 py-3 text-right">
                                            <x-heroicon-o-currency-dollar class="w-3.5 h-3.5 inline-block mr-1 align-text-bottom" /> Amount (Original)
                                        </th>
                                        <th scope="col" class="px-5 py-3 text-right">
                                            <x-heroicon-o-currency-dollar class="w-3.5 h-3.5 inline-block mr-1 align-text-bottom" /> Converted ({{ $userBaseCurrency }})
                                        </th>
                                        <th scope="col" class="relative px-5 py-3">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-[#0f172a] divide-y divide-gray-800/60 text-xs">
                                    @foreach ($transactions as $transaction)
                                        <tr class="hover:bg-[#151f38] transition-colors">
                                            <td class="px-5 py-3.5 whitespace-nowrap text-gray-300">
                                                {{ $transaction->transaction_date->format('M d, Y') }}
                                            </td>
                                            <td class="px-5 py-3.5 whitespace-nowrap font-medium text-white">
                                                {{ $transaction->wallet->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-5 py-3.5 whitespace-nowrap text-gray-300 max-w-xs truncate">
                                                {{ $transaction->description }}
                                            </td>
                                            <td class="px-5 py-3.5 whitespace-nowrap text-gray-400">
                                                    <span class="px-2 py-0.5 bg-gray-800 border border-gray-700/60 rounded-md text-[11px]">
                                                        {{ $transaction->category->name ?? 'N/A' }}
                                                    </span>
                                            </td>
                                            <td class="px-5 py-3.5 whitespace-nowrap">
                                                    <span class="inline-flex items-center font-semibold {{ $transaction->type === 'income' ? 'text-emerald-400' : 'text-rose-400' }}">
                                                        @if ($transaction->type === 'income')
                                                            <x-heroicon-s-arrow-up-circle class="w-4 h-4 mr-1 flex-shrink-0" />
                                                        @else
                                                            <x-heroicon-s-arrow-down-circle class="w-4 h-4 mr-1 flex-shrink-0" />
                                                        @endif
                                                        {{ ucfirst($transaction->type) }}
                                                    </span>
                                            </td>
                                            <td class="px-5 py-3.5 whitespace-nowrap text-right font-medium text-gray-400">
                                                {{ number_format($transaction->amount, 2, ',', '.') }} <span class="text-[10px] text-gray-500 ml-0.5">{{ $transaction->currency }}</span>
                                            </td>
                                            <td class="px-5 py-3.5 whitespace-nowrap text-right font-bold {{ $transaction->type === 'income' ? 'text-emerald-400' : 'text-rose-400' }}">
                                                @if ($transaction->converted_amount !== null)
                                                    {{ number_format($transaction->converted_amount, 2, ',', '.') }} <span class="text-[10px] opacity-70 ml-0.5">{{ $transaction->converted_currency }}</span>
                                                @else
                                                    <span class="text-gray-600">N/A</span>
                                                @endif
                                            </td>
                                            <td class="px-5 py-3.5 whitespace-nowrap text-right text-[11px] font-semibold space-x-2">
                                                <a href="{{ route('transactions.edit', $transaction) }}" class="text-[#3b82f6] hover:text-blue-400 inline-flex items-center transition">
                                                    <x-heroicon-o-pencil-square class="w-3.5 h-3.5 mr-0.5" />
                                                    {{ __('Edit') }}
                                                </a>
                                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-400 hover:text-rose-300 inline-flex items-center transition bg-transparent border-0 p-0" onclick="return confirm('Are you sure you want to delete this transaction?')">
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

                            <!-- Bagian Navigasi Pagination -->
                            <div class="mt-4 dark-pagination">
                                {{ $transactions->links() }}
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
