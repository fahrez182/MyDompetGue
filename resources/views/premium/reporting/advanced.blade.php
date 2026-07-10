<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-1.5">
            <h2 class="font-bold text-lg text-white leading-tight flex items-center gap-2">
                <x-heroicon-s-presentation-chart-line class="w-4 h-4 text-amber-400" />
                {{ __('Advanced Reporting') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10 bg-[#090a0f] min-h-screen text-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-[#0f172a] border border-gray-800 rounded-2xl p-5 sm:p-6 shadow-lg shadow-black/10">
                <h3 class="text-sm font-bold text-white flex items-center mb-1">
                    <x-heroicon-s-adjustments-horizontal class="w-4 h-4 mr-1.5 text-[#3b82f6]" />
                    Filter Reports
                </h3>
                <p class="text-xs text-gray-400">
                    Use the filters below to refine your financial reports.
                </p>

                <form action="{{ route('premium.reporting.advanced') }}" method="GET" class="mt-5 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="start_date" class="block text-xs font-semibold text-gray-400 mb-1.5">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="block w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none">
                        </div>
                        <div>
                            <label for="end_date" class="block text-xs font-semibold text-gray-400 mb-1.5">End Date</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="block w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none">
                        </div>
                        <div>
                            <label for="type" class="block text-xs font-semibold text-gray-400 mb-1.5">Transaction Type</label>
                            <select name="type" id="type" class="block w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none">
                                <option value="" class="bg-[#0f172a]">All Types</option>
                                <option value="income" {{ request('type') == 'income' ? 'selected' : '' }} class="bg-[#0f172a]">Income</option>
                                <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }} class="bg-[#0f172a]">Expense</option>
                            </select>
                        </div>
                        <div>
                            <label for="category_id" class="block text-xs font-semibold text-gray-400 mb-1.5">Category</label>
                            <select name="category_id" id="category_id" class="block w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none">
                                <option value="" class="bg-[#0f172a]">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }} class="bg-[#0f172a]">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 border-t border-gray-800/60 pt-4">
                        <a href="{{ route('premium.reporting.advanced') }}" class="inline-flex items-center justify-center px-4 py-2 bg-transparent border border-gray-700 hover:border-gray-500 text-gray-300 hover:text-white font-semibold text-xs rounded-xl transition">
                            <x-heroicon-o-arrow-path class="w-3.5 h-3.5 mr-1.5" />
                            Reset Filters
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-[#3b82f6] hover:bg-[#2563eb] text-white font-semibold text-xs rounded-xl transition shadow-lg shadow-[#3b82f6]/10">
                            <x-heroicon-o-funnel class="w-3.5 h-3.5 mr-1.5" />
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-[#0f172a] border border-gray-800 rounded-2xl p-4 shadow-lg flex items-center gap-3.5">
                    <div class="p-2.5 bg-emerald-500/10 rounded-xl text-emerald-400">
                        <x-heroicon-s-arrow-trending-up class="w-5 h-5" />
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Income (Filtered)</p>
                        <p class="text-lg font-black text-emerald-400 mt-0.5">{{ $currencySymbol }} {{ number_format($totalIncome, 2) }}</p>
                    </div>
                </div>
                <div class="bg-[#0f172a] border border-gray-800 rounded-2xl p-4 shadow-lg flex items-center gap-3.5">
                    <div class="p-2.5 bg-rose-500/10 rounded-xl text-rose-400">
                        <x-heroicon-s-arrow-trending-down class="w-5 h-5" />
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Expense (Filtered)</p>
                        <p class="text-lg font-black text-rose-400 mt-0.5">{{ $currencySymbol }} {{ number_format($totalExpense, 2) }}</p>
                    </div>
                </div>
                <div class="bg-[#0f172a] border border-gray-800 rounded-2xl p-4 shadow-lg flex items-center gap-3.5">
                    <div class="p-2.5 {{ $netBalance >= 0 ? 'bg-blue-500/10 text-blue-400' : 'bg-amber-500/10 text-amber-400' }} rounded-xl">
                        <x-heroicon-s-banknotes class="w-5 h-5" />
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Net Balance (Filtered)</p>
                        <p class="text-lg font-black mt-0.5 {{ $netBalance >= 0 ? 'text-blue-400' : 'text-rose-400' }}">
                            {{ $currencySymbol }} {{ number_format($netBalance, 2) }}
                        </p>
                    </div>
                </div>
                <div class="bg-[#0f172a] border border-gray-800 rounded-2xl p-4 shadow-lg flex items-center gap-3.5">
                    <div class="p-2.5 {{ $totalWalletBalance >= 0 ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400' }} rounded-xl">
                        <x-heroicon-s-wallet class="w-5 h-5" />
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Wallet Balance</p>
                        <p class="text-lg font-black mt-0.5 {{ $totalWalletBalance >= 0 ? 'text-green-400' : 'text-red-400' }}">
                            {{ $currencySymbol }} {{ number_format($totalWalletBalance, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-[#0f172a] border border-gray-800 rounded-2xl p-5 shadow-lg">
                    <h4 class="text-xs font-bold text-white mb-4 flex items-center">
                        <x-heroicon-s-chart-pie class="w-4 h-4 mr-1.5 text-rose-400" />
                        Expenses by Category
                    </h4>
                    <div class="max-w-[280px] mx-auto">
                        <canvas id="expenseChart"></canvas>
                    </div>
                </div>
                <div class="bg-[#0f172a] border border-gray-800 rounded-2xl p-5 shadow-lg">
                    <h4 class="text-xs font-bold text-white mb-4 flex items-center">
                        <x-heroicon-s-chart-pie class="w-4 h-4 mr-1.5 text-emerald-400" />
                        Income by Category
                    </h4>
                    <div class="max-w-[280px] mx-auto">
                        <canvas id="incomeChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-[#0f172a] border border-gray-800 rounded-2xl p-5 sm:p-6 shadow-lg">
                <h3 class="text-sm font-bold text-white flex items-center mb-1">
                    <x-heroicon-s-list-bullet class="w-4 h-4 mr-1.5 text-[#3b82f6]" />
                    Detailed Transactions
                </h3>
                <p class="text-xs text-gray-400 mb-5">
                    This section shows the detailed list of transactions filtered above.
                </p>

                @if ($transactions->isEmpty())
                    <div class="p-8 text-center border border-dashed border-gray-800 rounded-xl flex flex-col items-center justify-center">
                        <x-heroicon-o-document-magnifying-glass class="w-8 h-8 mb-2 text-gray-600" />
                        <p class="text-xs text-gray-400">{{ __('No transactions found matching the selected criteria.') }}</p>
                    </div>
                @else
                    <div class="overflow-x-auto border border-gray-800 rounded-xl">
                        <table class="min-w-full divide-y divide-gray-800/80">
                            <thead class="bg-[#111625]">
                            <tr class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                <th scope="col" class="px-5 py-3 text-left">Date</th>
                                <th scope="col" class="px-5 py-3 text-left">Description</th>
                                <th scope="col" class="px-5 py-3 text-left">Category</th>
                                <th scope="col" class="px-5 py-3 text-left">Type</th>
                                <th scope="col" class="px-5 py-3 text-right">Amount</th>
                            </tr>
                            </thead>
                            <tbody class="bg-[#0f172a] divide-y divide-gray-800/60 text-xs">
                            @foreach ($transactions as $transaction)
                                <tr class="hover:bg-[#151f38] transition-colors">
                                    <td class="px-5 py-3.5 whitespace-nowrap text-gray-400">
                                        {{ is_string($transaction->date) ? $transaction->date : ($transaction->date?->format('Y-m-d') ?? $transaction->transaction_date?->format('Y-m-d') ?? '-') }}
                                    </td>
                                    <td class="px-5 py-3.5 font-medium text-white max-w-[200px] truncate">
                                        {{ $transaction->description ?? '-' }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-gray-300">
                                            <span class="inline-flex items-center gap-1">
                                                <x-heroicon-o-tag class="w-3.5 h-3.5 text-gray-500" />
                                                {{ $transaction->category->name ?? 'General' }}
                                            </span>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap font-semibold">
                                            <span class="{{ $transaction->type === 'income' ? 'text-emerald-400' : 'text-rose-400' }} uppercase text-[10px] tracking-wider px-2 py-0.5 rounded-md bg-gray-800/40 border {{ $transaction->type === 'income' ? 'border-emerald-500/20' : 'border-rose-500/20' }}">
                                                {{ $transaction->type }}
                                            </span>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-right font-bold text-white">
                                        {{ $currencySymbol }} {{ number_format($transaction->amount, 2) }}
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

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Konfigurasi Default Font Label Chart agar Cocok dengan Tema Gelap
                Chart.defaults.color = '#9ca3af';
                Chart.defaults.font.family = 'ui-sans-serif, system-ui, sans-serif';

                // 1. Expense Pie Chart
                const expenseCtx = document.getElementById('expenseChart');
                if (expenseCtx) {
                    const categoryExpenses = @json($categoryExpenses);
                    new Chart(expenseCtx, {
                        type: 'pie',
                        data: {
                            labels: Object.keys(categoryExpenses),
                            datasets: [{
                                data: Object.values(categoryExpenses),
                                backgroundColor: [
                                    '#f43f5e', '#3b82f6', '#eab308', '#10b981', '#a855f7', '#f97316', '#64748b', '#ec4899', '#84cc16', '#06b6d4'
                                ],
                                borderWidth: 2,
                                borderColor: '#0f172a'
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15, font: { size: 11 } } }
                            }
                        }
                    });
                }

                // 2. Income Pie Chart
                const incomeCtx = document.getElementById('incomeChart');
                if (incomeCtx) {
                    const categoryIncomes = @json($categoryIncomes);
                    new Chart(incomeCtx, {
                        type: 'pie',
                        data: {
                            labels: Object.keys(categoryIncomes),
                            datasets: [{
                                data: Object.values(categoryIncomes),
                                backgroundColor: [
                                    '#10b981', '#06b6d4', '#eab308', '#6366f1', '#f97316', '#22c55e', '#ec4899', '#a855f7', '#3b82f6', '#ef4444'
                                ],
                                borderWidth: 2,
                                borderColor: '#0f172a'
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15, font: { size: 11 } } }
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
