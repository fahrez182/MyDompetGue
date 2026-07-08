<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Wallet') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('wallets.update', $wallet) }}">
                        @csrf
                        @method('PUT')

                        <!-- Wallet Name -->
                        <div>
                            <x-input-label for="name" :value="__('Wallet Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $wallet->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Balance (read-only for now, updated by transactions) -->
                        <div class="mt-4">
                            <x-input-label for="balance" :value="__('Current Balance')" />
                            <x-text-input id="balance" class="block mt-1 w-full bg-gray-100" type="text" name="balance" :value="number_format($wallet->balance, 2)" disabled />
                            <small class="text-gray-600">{{ __('Balance is updated automatically by transactions.') }}</small>
                        </div>

                        <!-- Currency -->
                        <div class="mt-4">
                            <x-input-label for="currency" :value="__('Currency')" />
                            <x-text-input id="currency" class="block mt-1 w-full" type="text" name="currency" :value="old('currency', $wallet->currency)" maxlength="3" required />
                            <x-input-error :messages="$errors->get('currency')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Update Wallet') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
