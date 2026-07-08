<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Quick Change Wallet Section --}}
            @if ($defaultWallet)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            {{ __('Current Wallet') }}: {{ $defaultWallet->name }}
                        </h3>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                            {{ number_format($defaultWallet->balance, 2, ',', '.') }} {{ $defaultWallet->currency }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 mb-4">
                            {{ __('This dashboard reflects transactions from your current default wallet.') }}
                        </p>

                        <form action="{{ route('wallets.set-default', ['wallet' => 'WALLET_ID_PLACEHOLDER']) }}" method="POST" id="quick-change-wallet-form">
                            @csrf
                            <x-input-label for="quick_change_wallet" :value="__('Quick Change Wallet')" class="dark:text-gray-200" />
                            <select id="quick_change_wallet" name="wallet_id" class="block mt-1 w-full sm:w-1/2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700" onchange="this.form.action = this.form.action.replace('WALLET_ID_PLACEHOLDER', this.value); this.form.submit();">
                                @foreach ($wallets as $wallet)
                                    <option value="{{ $wallet->id }}" {{ $defaultWallet->id == $wallet->id ? 'selected' : '' }}>
                                        {{ $wallet->name }} ({{ number_format($wallet->balance, 2) }} {{ $wallet->currency }})
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            @else
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ __('No default wallet set. Please go to your Profile settings to create or set a default wallet.') }}</span>
                    <a href="{{ route('profile.edit') }}" class="font-semibold underline ml-2">{{ __('Go to Profile') }}</a>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Financial Summary (in {{ $userBaseCurrency }})</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-green-100 dark:bg-green-800 p-4 rounded-lg shadow">
                            <p class="text-sm font-medium text-green-700 dark:text-green-200">Total Income</p>
                            <p class="text-2xl font-bold text-green-900 dark:text-green-50">{{ number_format($totalIncome, 2, ',', '.') }} {{ $userBaseCurrency }}</p>
                        </div>
                        <div class="bg-red-100 dark:bg-red-800 p-4 rounded-lg shadow">
                            <p class="text-sm font-medium text-red-700 dark:text-red-200">Total Expense</p>
                            <p class="text-2xl font-bold text-red-900 dark:text-red-50">{{ number_format($totalExpense, 2, ',', '.') }} {{ $userBaseCurrency }}</p>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-800 p-4 rounded-lg shadow">
                            <p class="text-sm font-medium text-blue-700 dark:text-blue-200">Balance</p>
                            <p class="text-2xl font-bold text-blue-900 dark:text-blue-50">{{ number_format($balance, 2, ',', '.') }} {{ $userBaseCurrency }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Budget Summary Section --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Your Budgets</h3>
                        <a href="{{ route('budgets.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-600">View All Budgets</a>
                    </div>
                    @if ($budgets->isEmpty())
                        <p>No budgets set up yet. <a href="{{ route('budgets.create') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-600">Create your first budget!</a></p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($budgets as $budget)
                                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow">
                                    <p class="text-md font-medium text-gray-900 dark:text-gray-100">{{ $budget->category->name ?? 'General Budget' }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ ucfirst($budget->period) }} Budget: Rp {{ number_format($budget->amount, 2) }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Spent: Rp {{ number_format($budget->current_spent, 2) }}
                                    </p>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2 dark:bg-gray-600">
                                        <div class="h-2.5 rounded-full {{ $budget->progress_percentage > 100 ? 'bg-red-600' : 'bg-blue-600' }}" style="width: {{ min(100, $budget->progress_percentage) }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $budget->progress_percentage }}% used
                                        @if ($budget->progress_percentage > 100)
                                            <span class="text-red-600 dark:text-red-400 font-semibold">(Over Budget!)</span>
                                        @elseif ($budget->progress_percentage > 80)
                                            <span class="text-orange-600 dark:text-orange-400 font-semibold">(Approaching Limit)</span>
                                        @endif
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Quick Actions Section --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Quick Actions</h3>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('transactions.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Add New Transaction
                        </a>
                        <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Manage Categories
                        </a>
                    </div>
                </div>
            </div>

            {{-- Financial Overview Chart --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Monthly Overview (from {{ $defaultWallet->name ?? 'Default Wallet' }} in {{ $userBaseCurrency }})</h3>
                    <div style="height: 300px;">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Recent Transactions (from {{ $defaultWallet->name ?? 'Default Wallet' }})</h3>
                    @if ($recentTransactions->isEmpty())
                        <p>No recent transactions found.</p>
                    @else
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($recentTransactions as $transaction)
                                <li class="py-3 flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $transaction->description }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transaction->type === 'income' ? '+' : '-' }} {{ number_format($transaction->converted_amount, 2, ',', '.') }} {{ $transaction->converted_currency }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">
                                            Original: {{ number_format($transaction->amount, 2, ',', '.') }} {{ $transaction->currency }}
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
                        label: 'Income ({{ $userBaseCurrency }})',
                        data: @json($monthlyIncome),
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Expense ({{ $userBaseCurrency }})',
                        data: @json($monthlyExpense),
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value, index, ticks) {
                                    return value.toLocaleString('en-US', { style: 'currency', currency: '{{ $userBaseCurrency }}' });
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y.toLocaleString('en-US', { style: 'currency', currency: '{{ $userBaseCurrency }}' });
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
