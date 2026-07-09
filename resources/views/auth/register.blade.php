<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-white leading-tight flex items-center">
            <x-heroicon-s-user-plus class="w-5 h-5 mr-2 text-[#3b82f6]" />
            {{ __('Register') }}
        </h2>
    </x-slot>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-5">
            <x-input-label for="name" class="flex items-center text-gray-200 font-semibold mb-1.5">
                <x-heroicon-o-user class="w-4 h-4 mr-1 text-[#3b82f6]" />
                {{ __('Name') }}
            </x-input-label>
            <x-text-input id="name" class="block mt-1 w-full bg-[#11141d] border-gray-600 text-white placeholder-gray-500 focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-4 py-3"
                          type="text"
                          name="name"
                          :value="old('name')"
                          required
                          autofocus
                          autocomplete="name"
                          placeholder="Nama lengkap Anda" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mb-5">
            <x-input-label for="email" class="flex items-center text-gray-200 font-semibold mb-1.5">
                <x-heroicon-o-envelope class="w-4 h-4 mr-1 text-[#3b82f6]" />
                {{ __('Email Address') }}
            </x-input-label>
            <x-text-input id="email" class="block mt-1 w-full bg-[#11141d] border-gray-600 text-white placeholder-gray-500 focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-4 py-3"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required
                          autocomplete="username"
                          placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mb-5">
            <x-input-label for="base_currency" class="flex items-center text-gray-200 font-semibold mb-1.5">
                <x-heroicon-o-banknotes class="w-4 h-4 mr-1 text-[#3b82f6]" />
                {{ __('Base Currency') }}
            </x-input-label>
            <select id="base_currency" name="base_currency" class="block mt-1 w-full bg-[#11141d] border-gray-600 text-white focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-4 py-3 shadow-sm" required>
                @php
                    $supportedCurrencies = ['USD', 'IDR', 'EUR', 'GBP', 'JPY', 'AUD', 'CAD', 'CHF', 'CNY', 'SGD'];
                @endphp
                @foreach ($supportedCurrencies as $currency)
                    <option value="{{ $currency }}" {{ old('base_currency', 'USD') == $currency ? 'selected' : '' }} class="bg-[#1e2330]">
                        {{ $currency }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('base_currency')" class="mt-2" />
        </div>

        <div class="mb-5">
            <x-input-label for="password" class="flex items-center text-gray-200 font-semibold mb-1.5">
                <x-heroicon-o-lock-closed class="w-4 h-4 mr-1 text-[#3b82f6]" />
                {{ __('Password') }}
            </x-input-label>
            <x-text-input id="password" class="block mt-1 w-full bg-[#11141d] border-gray-600 text-white placeholder-gray-500 focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-4 py-3"
                          type="password"
                          name="password"
                          required
                          autocomplete="new-password"
                          placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mb-6">
            <x-input-label for="password_confirmation" class="flex items-center text-gray-200 font-semibold mb-1.5">
                <x-heroicon-o-lock-closed class="w-4 h-4 mr-1 text-[#3b82f6]" />
                {{ __('Confirm Password') }}
            </x-input-label>
            <x-text-input id="password_confirmation" class="block mt-1 w-full bg-[#11141d] border-gray-600 text-white placeholder-gray-500 focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-4 py-3"
                          type="password"
                          name="password_confirmation"
                          required
                          autocomplete="new-password"
                          placeholder="Ulangi password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-gray-700/50 pt-6 mt-6">

            <div class="w-full sm:w-auto text-center sm:text-left order-3 sm:order-1">
                <a class="underline text-sm text-gray-400 hover:text-[#3b82f6] rounded-md focus:outline-none focus:ring-2 focus:ring-[#3b82f6] inline-flex items-center transition" href="{{ route('login') }}">
                    <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4 mr-1 text-gray-500" />
                    {{ __('Already registered?') }}
                </a>
            </div>

            <div class="flex flex-col sm:flex-row items-center w-full sm:w-auto gap-3 order-2">

                <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 bg-transparent border border-gray-600 rounded-xl font-medium text-sm text-gray-300 hover:border-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-[#3b82f6] transition text-center">
                    {{ __('Cancel') }}
                </a>

                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-[#3b82f6] hover:bg-[#2563eb] text-white font-semibold text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3b82f6] shadow-lg shadow-[#3b82f6]/20 transition text-center">
                    <x-heroicon-o-user-plus class="w-4 h-4 mr-2 flex-shrink-0" />
                    {{ __('Register') }}
                </button>

            </div>
        </div>
    </form>
</x-guest-layout>
