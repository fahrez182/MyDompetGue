<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Recurring Transaction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('recurring-transactions.update', $recurringTransaction) }}" class="space-y-6">
                        @csrf
                        @method('patch')

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" :value="old('description', $recurringTransaction->description)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="amount" :value="__('Amount')" />
                            <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full" :value="old('amount', $recurringTransaction->amount)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('amount')" />
                        </div>

                        <div>
                            <x-input-label for="currency" :value="__('Currency')" />
                            <x-text-input id="currency" name="currency" type="text" class="mt-1 block w-full" :value="old('currency', $recurringTransaction->currency)" required maxlength="3" />
                            <x-input-error class="mt-2" :messages="$errors->get('currency')" />
                        </div>

                        <div>
                            <x-input-label for="type" :value="__('Transaction Type')" />
                            <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" required>
                                <option value="income" {{ old('type', $recurringTransaction->type) == 'income' ? 'selected' : '' }}>{{ __('Income') }}</option>
                                <option value="expense" {{ old('type', $recurringTransaction->type) == 'expense' ? 'selected' : '' }}>{{ __('Expense') }}</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('type')" />
                        </div>

                        <div>
                            <x-input-label for="category_id" :value="__('Category (Optional)')" />
                            <select id="category_id" name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                <option value="">{{ __('Select a Category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $recurringTransaction->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }} ({{ ucfirst($category->type) }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        <div>
                            <x-input-label for="frequency" :value="__('Frequency')" />
                            <select id="frequency" name="frequency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" required>
                                <option value="daily" {{ old('frequency', $recurringTransaction->frequency) == 'daily' ? 'selected' : '' }}>{{ __('Daily') }}</option>
                                <option value="weekly" {{ old('frequency', $recurringTransaction->frequency) == 'weekly' ? 'selected' : '' }}>{{ __('Weekly') }}</option>
                                <option value="monthly" {{ old('frequency', $recurringTransaction->frequency) == 'monthly' ? 'selected' : '' }}>{{ __('Monthly') }}</option>
                                <option value="yearly" {{ old('frequency', $recurringTransaction->frequency) == 'yearly' ? 'selected' : '' }}>{{ __('Yearly') }}</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('frequency')" />
                        </div>

                        <div>
                            <x-input-label for="start_date" :value="__('Start Date')" />
                            <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="old('start_date', $recurringTransaction->start_date->format('Y-m-d'))" required />
                            <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                        </div>

                        <div>
                            <x-input-label for="end_date" :value="__('End Date (Optional)')" />
                            <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" :value="old('end_date', $recurringTransaction->end_date ? $recurringTransaction->end_date->format('Y-m-d') : '')" />
                            <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>
                            <a href="{{ route('recurring-transactions.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
