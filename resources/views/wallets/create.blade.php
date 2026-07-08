<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Wallet') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('wallets.store') }}">
                        @csrf

                        <!-- Wallet Name -->
                        <div>
                            <x-input-label for="name" :value="__('Wallet Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Initial Balance -->
                        <div class="mt-4">
                            <x-input-label for="balance" :value="__('Initial Balance')" />
                            <x-text-input id="balance" class="block mt-1 w-full" type="number" name="balance" :value="old('balance', 0.00)" step="0.01" required />
                            <x-input-error :messages="$errors->get('balance')" class="mt-2" />
                        </div>

                        <!-- Currency -->
                        <div class="mt-4">
                            <x-input-label for="currency" :value="__('Currency')" />
                            <x-text-input id="currency" class="block mt-1 w-full" type="text" name="currency" :value="old('currency', 'USD')" maxlength="3" required />
                            <x-input-error :messages="$errors->get('currency')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Create Wallet') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
