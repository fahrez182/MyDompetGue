<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Budgets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Your Budgets
                        </h3>
                        <a href="{{ route('budgets.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Add New Budget
                        </a>
                    </div>

                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($budgets->isEmpty())
                        <p>No budgets found. Start by adding a new budget!</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Category
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Amount
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Period
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Start Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            End Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Current Spent
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Progress
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($budgets as $budget)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $budget->category->name ?? 'All Categories' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                Rp {{ number_format($budget->amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ ucfirst($budget->period) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $budget->start_date }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $budget->end_date ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                Rp {{ number_format($budget->current_spent, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                                    <div class="h-2.5 rounded-full {{ $budget->progress_percentage > 100 ? 'bg-red-600' : 'bg-blue-600' }}" style="width: {{ min(100, $budget->progress_percentage) }}%"></div>
                                                </div>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $budget->progress_percentage }}%</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('budgets.edit', $budget) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">Edit</a>
                                                <form action="{{ route('budgets.destroy', $budget) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this budget?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600 ml-4">Delete</button>
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
</x-app-layout>
