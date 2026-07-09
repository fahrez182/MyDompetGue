<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-white leading-tight flex items-center">
            <x-heroicon-s-arrow-right-on-rectangle class="w-5 h-5 mr-2 text-[#3b82f6]" />
            {{ __('Log In') }}
        </h2>
    </x-slot>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

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
                          autofocus
                          autocomplete="username"
                          placeholder="Masukkan email aktif Anda" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
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
                          autocomplete="current-password"
                          placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4 mb-6">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded bg-[#11141d] border-gray-600 text-[#3b82f6] shadow-sm focus:ring-[#3b82f6] focus:ring-offset-[#1e2330]" name="remember">
                <span class="ms-2 text-sm text-gray-300 hover:text-white transition-colors">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-gray-700/50 pt-6 mt-6">

            <div class="w-full sm:w-auto text-center sm:text-left order-3 sm:order-1">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-400 hover:text-[#3b82f6] rounded-md focus:outline-none focus:ring-2 focus:ring-[#3b82f6] inline-flex items-center transition" href="{{ route('password.request') }}">
                        <x-heroicon-o-question-mark-circle class="w-4 h-4 mr-1 text-gray-500" />
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <div class="flex flex-col sm:flex-row items-center w-full sm:w-auto gap-3 order-2">

                <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 bg-transparent border border-gray-600 rounded-xl font-medium text-sm text-gray-300 hover:border-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-[#3b82f6] transition text-center">
                    <x-heroicon-o-user-plus class="w-4 h-4 mr-2 flex-shrink-0" />
                    {{ __('Register') }}
                </a>

                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-[#3b82f6] hover:bg-[#2563eb] text-white font-semibold text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3b82f6] shadow-lg shadow-[#3b82f6]/20 transition text-center">
                    <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4 mr-2 flex-shrink-0" />
                    {{ __('Log in') }}
                </button>

            </div>
        </div>
    </form>
</x-guest-layout>
