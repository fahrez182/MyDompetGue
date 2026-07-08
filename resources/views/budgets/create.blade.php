<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Budget') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('budgets.store') }}" class="space-y-6">
                        @csrf

                        {{-- Wallet Selection (Premium Only) --}}
                        @if (Auth::user()->role === 'premium')
                            <div>
                                <x-input-label for="wallet_id" :value="__('Wallet')" />
                                <select id="wallet_id" name="wallet_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                    <option value="">{{ __('All Wallets (Applies to all)') }}</option>
                                    @foreach ($wallets as $wallet)
                                        <option value="{{ $wallet->id }}" {{ old('wallet_id') == $wallet->id ? 'selected' : '' }}>
                                            {{ $wallet->name }} ({{ number_format($wallet->balance, 2) }} {{ $wallet->currency }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('wallet_id')" />
                            </div>
                        @else
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Multi-wallet budgeting is a premium feature. Budgets will apply to all your transactions.') }}
                            </p>
                        @endif

                        <div>
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                <option value="">{{ __('All Categories (General Budget)') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }} ({{ ucfirst($category->type) }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        <div>
                            <x-input-label for="amount" :value="__('Amount')" />
                            <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full" :value="old('amount')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('amount')" />
                        </div>

                        <div>
                            <x-input-label for="period" :value="__('Period')" />
                            <select id="period" name="period" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" required>
                                <option value="monthly" {{ old('period') == 'monthly' ? 'selected' : '' }}>{{ __('Monthly') }}</option>
                                <option value="yearly" {{ old('period') == 'yearly' ? 'selected' : '' }}>{{ __('Yearly') }}</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('period')" />
                        </div>

                        <div>
                            <x-input-label for="start_date" :value="__('Start Date')" />
                            <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="old('start_date')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                        </div>

                        <div>
                            <x-input-label for="end_date" :value="__('End Date (Optional)')" />
                            <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" :value="old('end_date')" />
                            <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save Budget') }}</x-primary-button>
                            <a href="{{ route('budgets.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
