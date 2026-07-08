<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Advanced Reporting') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Filter Reports
                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Use the filters below to refine your financial reports.
                    </p>

                    <form action="{{ route('premium.reporting.advanced') }}" method="GET" class="mt-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            </div>
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Transaction Type</label>
                                <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                    <option value="">All Types</option>
                                    <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Income</option>
                                    <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                                <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Apply Filters
                            </button>
                            <a href="{{ route('premium.reporting.advanced') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">
                                Reset Filters
                            </a>
                        </div>
                    </form>

                    <hr class="my-8 border-gray-200 dark:border-gray-700">

                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Summary
                    </h3>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Income</p>
                            <p class="text-xl font-semibold text-green-600 dark:text-green-400">Rp {{ number_format($totalIncome, 2) }}</p>
                        </div>
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Expense</p>
                            <p class="text-xl font-semibold text-red-600 dark:text-red-400">Rp {{ number_format($totalExpense, 2) }}</p>
                        </div>
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Net Balance</p>
                            <p class="text-xl font-semibold @if($netBalance >= 0) text-blue-600 dark:text-blue-400 @else text-red-600 dark:text-red-400 @endif">Rp {{ number_format($netBalance, 2) }}</p>
                        </div>
                    </div>

                    <hr class="my-8 border-gray-200 dark:border-gray-700">

                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        Visualizations
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">Expenses by Category</h4>
                            <canvas id="expenseChart"></canvas>
                        </div>
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">Income by Category</h4>
                            <canvas id="incomeChart"></canvas>
                        </div>
                    </div>

                    <hr class="my-8 border-gray-200 dark:border-gray-700">

                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Detailed Transactions
                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        This section shows the detailed list of transactions.
                    </p>

                    <div class="mt-6">
                        @if ($transactions->isEmpty())
                            <p>No transactions found for advanced reporting with the selected filters.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Date
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Description
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Amount
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Type
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Category
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($transactions as $transaction)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $transaction->date }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $transaction->description }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $transaction->amount }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $transaction->type }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $transaction->category->name ?? 'N/A' }}
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

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Expense Chart
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
                                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9900', '#C9CBCF', '#7B68EE', '#FFD700', '#ADFF2F'
                                ],
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: false,
                                    text: 'Expenses by Category'
                                }
                            }
                        }
                    });
                }

                // Income Chart
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
                                    '#28a745', '#17a2b8', '#ffc107', '#6f42c1', '#fd7e14', '#20c997', '#e83e8c', '#6610f2', '#007bff', '#dc3545'
                                ],
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: false,
                                    text: 'Income by Category'
                                }
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
