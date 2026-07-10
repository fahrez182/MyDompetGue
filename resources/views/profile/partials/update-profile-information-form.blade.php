<section>
    <header class="mb-5">
        <h2 class="text-sm font-bold text-white flex items-center">
            <x-heroicon-s-user class="w-4 h-4 mr-1.5 text-[#3b82f6]" />
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-xs text-gray-400">
            {{ __("Update your account's identity details, email credentials, and system currency settings.") }}
        </p>
    </header>

    <!-- Form Tersembunyi untuk Verifikasi Ulang Email -->
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4 space-y-4">
        @csrf
        @method('patch')

        <!-- Full Name -->
        <div>
            <x-input-label for="name" class="text-gray-400 font-medium flex items-center text-xs">
                <x-heroicon-o-user class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                {{ __('Full Name') }}
            </x-input-label>
            <x-text-input id="name" name="name" type="text" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-1.5 text-xs text-rose-400" :messages="$errors->get('name')" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" class="text-gray-400 font-medium flex items-center text-xs">
                <x-heroicon-o-envelope class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                {{ __('Email Address') }}
            </x-input-label>
            <x-text-input id="email" name="email" type="email" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-1.5 text-xs text-rose-400" :messages="$errors->get('email')" />

            <!-- Handling Status Verifikasi Email -->
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-2.5 bg-amber-500/5 border border-amber-500/10 rounded-xl">
                    <p class="text-[11px] text-gray-400 flex items-center gap-1.5">
                        <x-heroicon-s-exclamation-triangle class="w-3.5 h-3.5 text-amber-500 flex-shrink-0" />
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="underline text-amber-400 hover:text-amber-300 font-semibold focus:outline-none ml-1">
                            {{ __('Resend Verification Email') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1.5 font-semibold text-[11px] text-emerald-400 flex items-center gap-1">
                            <x-heroicon-s-check-circle class="w-3.5 h-3.5" />
                            {{ __('A new verification link has been sent to your email.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Base Currency -->
        <div>
            <x-input-label for="base_currency" class="text-gray-400 font-medium flex items-center text-xs">
                <x-heroicon-o-globe-alt class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                {{ __('Base System Currency') }}
            </x-input-label>
            <select id="base_currency" name="base_currency" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none font-bold tracking-wider" required>
                @php
                    $supportedCurrencies = ['USD', 'IDR', 'EUR', 'GBP', 'JPY', 'AUD', 'CAD', 'CHF', 'CNY', 'SGD'];
                @endphp
                @foreach ($supportedCurrencies as $currency)
                    <option value="{{ $currency }}" {{ old('base_currency', $user->base_currency) == $currency ? 'selected' : '' }} class="bg-[#0f172a]">
                        {{ $currency }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('base_currency')" class="mt-1.5 text-xs text-rose-400" />
        </div>

        <!-- Aksi & Pemberitahuan Sukses Semat -->
        <div class="flex items-center gap-3 border-t border-gray-800/60 pt-4 mt-2">
            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-[#3b82f6] hover:bg-blue-600 text-white font-bold text-xs rounded-xl focus:outline-none transition shadow-lg shadow-blue-500/10">
                <x-heroicon-o-check-circle class="w-4 h-4 mr-1.5" />
                {{ __('Save Changes') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs font-semibold text-emerald-400 flex items-center gap-1"
                >
                    <x-heroicon-s-check class="w-3.5 h-3.5" />
                    {{ __('Saved successfully.') }}
                </p>
            @endif
        </div>
    </form>
</section>
