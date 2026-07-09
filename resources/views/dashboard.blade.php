<x-app-layout>
    <x-slot name="header">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-1.5">
            <h2 class="font-bold text-lg text-white leading-tight flex items-center gap-2">
                <x-heroicon-s-squares-2x2 class="w-4 h-4 text-[#3b82f6]" />
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10 bg-[#090a0f] min-h-screen text-gray-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- Quick Change Wallet Section --}}
            @if ($defaultWallet)
                <div class="bg-[#0f172a] border border-gray-800 rounded-2xl overflow-hidden shadow-lg shadow-black/20">
                    <div class="p-5 sm:p-6">
                        <h3 class="text-xs font-semibold text-gray-400 mb-1 flex items-center">
                            <x-heroicon-s-wallet class="w-4 h-4 mr-1.5 text-[#3b82f6]" />
                            {{ __('Current Wallet') }}: <span class="text-white ml-1 font-bold">{{ $defaultWallet->name }}</span>
                        </h3>
                        <p class="text-2xl sm:text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-[#3b82f6]">
                            {{ number_format($defaultWallet->balance, 2, ',', '.') }} {{ $userBaseCurrency }}
                        </p>
                        <p class="text-[11px] text-gray-400 mt-0.5 mb-4">
                            {{ __('This dashboard reflects transactions from your current default wallet.') }}
                        </p>

                        <form action="{{ route('wallets.set-default', ['wallet' => 'WALLET_ID_PLACEHOLDER']) }}" method="POST" id="quick-change-wallet-form" class="border-t border-gray-800/60 pt-4">
                            @csrf
                            <x-input-label for="quick_change_wallet" class="text-gray-400 font-medium flex items-center text-xs">
                                <x-heroicon-o-arrows-right-left class="w-3.5 h-3.5 mr-1.5" />
                                {{ __('Quick Change Wallet') }}
                            </x-input-label>
                            <select id="quick_change_wallet" name="wallet_id" class="block mt-1.5 w-full sm:w-64 bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm" onchange="this.form.action = this.form.action.replace('WALLET_ID_PLACEHOLDER', this.value); this.form.submit();">
                                @foreach ($wallets as $wallet)
                                    <option value="{{ $wallet->id }}" {{ $defaultWallet->id == $wallet->id ? 'selected' : '' }} class="bg-[#0f172a]">
                                        {{ $wallet->name }} ({{ number_format($wallet->converted_balance, 2) }} {{ $userBaseCurrency }})
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            @else
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-xl flex items-center justify-between text-xs shadow-sm" role="alert">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-exclamation-triangle class="w-4 h-4 flex-shrink-0" />
                        <span>{{ __('No default wallet set. Please go to your Profile settings.') }}</span>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="font-semibold text-red-400 underline hover:text-red-300 ml-4 flex-shrink-0">{{ __('Go to Profile') }}</a>
                </div>
            @endif

            {{-- Financial Summary Section --}}
            <div class="bg-[#0f172a] border border-gray-800 rounded-2xl overflow-hidden shadow-lg shadow-black/20">
                <div class="p-5 sm:p-6">
                    <div class="flex items-center justify-between gap-4 mb-4">
                        <h3 class="text-sm font-bold text-white flex items-center">
                            <x-heroicon-s-scale class="w-4 h-4 mr-1.5 text-[#3b82f6]" />
                            Financial Summary <span class="text-gray-400 font-normal text-xs ml-1 hidden sm:inline">({{ $userBaseCurrency }})</span>
                        </h3>
                        <form action="{{ route('dashboard.recalculate-balances') }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 border border-gray-700 hover:border-gray-500 text-[11px] font-semibold rounded-lg text-gray-300 hover:text-white bg-transparent transition">
                                <x-heroicon-o-arrow-path class="w-3.5 h-3.5 mr-1" />
                                Refresh
                            </button>
                        </form>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-emerald-500/5 border border-emerald-500/15 p-4 rounded-xl flex items-center">
                            <div class="p-2.5 bg-emerald-500/15 rounded-lg mr-3 text-emerald-400">
                                <x-heroicon-s-arrow-trending-up class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-[10px] font-medium text-emerald-400 uppercase tracking-wider">Total Income</p>
                                <p class="text-lg font-black text-emerald-300 mt-0.5">{{ number_format($totalIncome, 2, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="bg-rose-500/5 border border-rose-500/15 p-4 rounded-xl flex items-center">
                            <div class="p-2.5 bg-rose-500/15 rounded-lg mr-3 text-rose-400">
                                <x-heroicon-s-arrow-trending-down class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-[10px] font-medium text-rose-400 uppercase tracking-wider">Total Expense</p>
                                <p class="text-lg font-black text-rose-300 mt-0.5">{{ number_format($totalExpense, 2, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="bg-blue-500/5 border border-blue-500/15 p-4 rounded-xl flex items-center">
                            <div class="p-2.5 bg-blue-500/15 rounded-lg mr-3 text-blue-400">
                                <x-heroicon-s-scale class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-[10px] font-medium text-blue-400 uppercase tracking-wider">Balance</p>
                                <p class="text-lg font-black text-blue-300 mt-0.5">{{ number_format($balance, 2, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dua Kolom untuk Budget dan Quick Actions (Supaya Desktop Padat, Tidak Memanjang Kebawah) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                {{-- Kolom Kiri Bagian Budgets (Mengambil Ruang Lebih Lebar) --}}
                <div class="md:col-span-2 bg-[#0f172a] border border-gray-800 rounded-2xl p-5 shadow-lg flex flex-col justify-between relative overflow-hidden">

                    @if (Auth::user()->role === 'premium')
                        {{-- ==================================================================
                           TAMPILAN JIKA USER ADALAH PREMIUM
                           ================================================================== --}}
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-sm font-bold text-white flex items-center">
                                    <x-heroicon-s-clipboard-document-list class="w-4 h-4 mr-1.5 text-[#3b82f6]" />
                                    Budgets
                                </h3>
                                <a href="{{ route('budgets.index') }}" class="text-[11px] font-semibold text-[#3b82f6] hover:text-blue-400 inline-flex items-center transition">
                                    View All
                                    <x-heroicon-o-arrow-right class="w-3 h-3 ml-0.5" />
                                </a>
                            </div>

                            @if ($budgets->isEmpty())
                                <div class="text-center py-4 border border-dashed border-gray-800 rounded-xl">
                                    <p class="text-gray-400 text-xs">No budgets set up yet.</p>
                                </div>
                            @else
                                <div class="space-y-3">
                                    @foreach ($budgets->take(2) as $budget)
                                        <div class="bg-[#111625] border border-gray-800/60 p-3 rounded-xl">
                                            <div class="flex justify-between text-xs font-bold text-white">
                            <span class="flex items-center">
                                <x-heroicon-s-tag class="w-3.5 h-3.5 mr-1 text-[#3b82f6]" />
                                {{ $budget->category->name ?? 'General' }}
                            </span>
                                                <span class="text-gray-400 font-normal">{{ $budget->progress_percentage }}% used</span>
                                            </div>
                                            <div class="w-full bg-gray-800 rounded-full h-1.5 mt-2 overflow-hidden">
                                                <div class="h-1.5 rounded-full {{ $budget->progress_percentage > 100 ? 'bg-rose-500' : ($budget->progress_percentage > 80 ? 'bg-amber-500' : 'bg-blue-500') }}" style="width: {{ min(100, $budget->progress_percentage) }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                    @else
                        {{-- ==================================================================
                           TAMPILAN JIKA USER NON-PREMIUM (DIBLOCK/DI-BLUR DENGAN PAYWALL)
                           ================================================================== --}}
                        <div class="w-full pointer-events-none select-none">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-sm font-bold text-white flex items-center opacity-40">
                                    <x-heroicon-s-clipboard-document-list class="w-4 h-4 mr-1.5 text-[#3b82f6]" />
                                    Budgets
                                </h3>
                                <span class="text-[11px] font-semibold text-gray-600 inline-flex items-center">
                    View All <x-heroicon-o-arrow-right class="w-3 h-3 ml-0.5" />
                </span>
                            </div>

                            {{-- Mockup Dummy Data yang Di-blur untuk Memancing Ketertarikan Fitur --}}
                            <div class="space-y-3 blur-[3px] opacity-20">
                                <div class="bg-[#111625] border border-gray-800 p-3 rounded-xl">
                                    <div class="flex justify-between text-xs font-bold text-white">
                                        <span class="flex items-center"><x-heroicon-s-tag class="w-3.5 h-3.5 mr-1 text-[#3b82f6]" /> Food & Drinks</span>
                                        <span class="text-gray-400 font-normal">45% used</span>
                                    </div>
                                    <div class="w-full bg-gray-800 rounded-full h-1.5 mt-2">
                                        <div class="bg-blue-500 h-1.5 rounded-full w-[45%]"></div>
                                    </div>
                                </div>
                                <div class="bg-[#111625] border border-gray-800 p-3 rounded-xl">
                                    <div class="flex justify-between text-xs font-bold text-white">
                                        <span class="flex items-center"><x-heroicon-s-tag class="w-3.5 h-3.5 mr-1 text-[#3b82f6]" /> Entertainment</span>
                                        <span class="text-gray-400 font-normal">85% used</span>
                                    </div>
                                    <div class="w-full bg-gray-800 rounded-full h-1.5 mt-2">
                                        <div class="bg-amber-500 h-1.5 rounded-full w-[85%]"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Lapisan Overlay Paywall Tengah Objek --}}
                        <div class="absolute inset-0 flex flex-col items-center justify-center bg-gradient-to-t from-[#0f172a] via-[#0f172a]/95 to-[#0f172a]/70 p-4 text-center">
                            <div class="p-2 bg-amber-500/10 border border-amber-500/20 rounded-xl mb-2">
                                <x-heroicon-s-star class="w-5 h-5 text-amber-400 animate-pulse" />
                            </div>
                            <h4 class="text-xs font-bold text-white uppercase tracking-wider">{{ __('Premium Budgeting') }}</h4>
                            <p class="text-[11px] text-gray-400 mt-1 max-w-[240px] leading-relaxed">
                                Set limit rules on your expense categories to prevent overspending.
                            </p>
                            <a href="{{ route('premium.index') }}" class="mt-3.5 inline-flex items-center px-3.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-[11px] rounded-xl transition shadow-lg shadow-amber-500/10 pointer-events-auto">
                                {{ __('Unlock Now') }}
                            </a>
                        </div>
                    @endif

                </div>

                {{-- Kolom Kanan Bagian Quick Actions (Pas di Samping Budget) --}}
                <div class="bg-[#0f172a] border border-gray-800 rounded-2xl p-5 shadow-lg flex flex-col justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-white mb-3 flex items-center">
                            <x-heroicon-s-bolt class="w-4 h-4 mr-1.5 text-[#3b82f6]" />
                            Quick Actions
                        </h3>
                        <div class="space-y-2">
                            <a href="{{ route('transactions.create') }}" class="flex items-center justify-center gap-1.5 w-full py-2 bg-[#3b82f6] hover:bg-[#2563eb] text-white font-semibold text-xs rounded-xl transition shadow-sm">
                                <x-heroicon-o-plus-circle class="w-3.5 h-3.5" />
                                New Transaction
                            </a>
                            <a href="{{ route('categories.index') }}" class="flex items-center justify-center gap-1.5 w-full py-2 bg-transparent border border-gray-700 hover:border-gray-500 text-gray-300 hover:text-white font-semibold text-xs rounded-xl transition">
                                <x-heroicon-o-tag class="w-3.5 h-3.5" />
                                Categories
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Financial Overview Chart --}}
            <div class="bg-[#0f172a] border border-gray-800 rounded-2xl overflow-hidden shadow-lg shadow-black/20">
                <div class="p-5 sm:p-6">
                    <h3 class="text-sm font-bold text-white mb-3 flex items-center">
                        <x-heroicon-s-chart-bar class="w-4 h-4 mr-1.5 text-[#3b82f6]" />
                        Monthly Overview
                    </h3>
                    <div class="relative w-full" style="height: 240px;"> <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Recent Transactions List --}}
            <div class="bg-[#0f172a] border border-gray-800 rounded-2xl overflow-hidden shadow-lg shadow-black/20">
                <div class="p-5 sm:p-6">
                    <h3 class="text-sm font-bold text-white mb-3 flex items-center">
                        <x-heroicon-s-clock class="w-4 h-4 mr-1.5 text-[#3b82f6]" />
                        Recent Transactions
                    </h3>
                    @if ($recentTransactions->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-gray-400 text-xs">No recent transactions found.</p>
                        </div>
                    @else
                        <ul class="divide-y divide-gray-800/60">
                            @foreach ($recentTransactions->take(5) as $transaction) {{-- Batasi 5 item teratas --}}
                            <li class="py-3 flex justify-between items-center gap-4 text-xs">
                                <div class="flex items-center min-w-0">
                                    @if ($transaction->type === 'income')
                                        <x-heroicon-s-arrow-up-circle class="w-4 h-4 mr-2.5 text-emerald-400 flex-shrink-0" />
                                    @else
                                        <x-heroicon-s-arrow-down-circle class="w-4 h-4 mr-2.5 text-rose-400 flex-shrink-0" />
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-semibold text-white truncate">{{ $transaction->description }}</p>
                                        <p class="text-[10px] text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="font-bold {{ $transaction->type === 'income' ? 'text-emerald-400' : 'text-rose-400' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }} {{ number_format($transaction->converted_amount, 2, ',', '.') }} {{ $transaction->converted_currency }}
                                    </p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('myChart');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($months),
                    datasets: [{
                        label: 'Income',
                        data: @json($monthlyIncome),
                        backgroundColor: 'rgba(52, 211, 153, 0.85)',
                        borderColor: 'rgb(52, 211, 153)',
                        borderWidth: 1,
                        borderRadius: 4,
                    }, {
                        label: 'Expense',
                        data: @json($monthlyExpense),
                        backgroundColor: 'rgba(244, 63, 94, 0.85)',
                        borderColor: 'rgb(244, 63, 94)',
                        borderWidth: 1,
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                font: { size: 11, family: "'Instrument Sans', sans-serif" },
                                color: 'rgb(156, 163, 175)',
                            }
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            borderColor: 'rgb(31, 41, 55)',
                            borderWidth: 1,
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y.toLocaleString('id-ID', { style: 'currency', currency: '{{ $userBaseCurrency }}', maximumFractionDigits: 0 });
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 10, family: "'Instrument Sans', sans-serif" },
                                color: 'rgb(156, 163, 175)',
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(75, 85, 99, 0.1)' },
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('id-ID', { style: 'currency', currency: '{{ $userBaseCurrency }}', maximumFractionDigits: 0 });
                                },
                                font: { size: 10, family: "'Instrument Sans', sans-serif" },
                                color: 'rgb(156, 163, 175)',
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
