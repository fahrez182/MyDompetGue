<section>
    <header class="mb-5">
        <h2 class="text-sm font-bold text-white flex items-center">
            <x-heroicon-s-key class="w-4 h-4 mr-1.5 text-amber-500" />
            {{ __('Update Password') }}
        </h2>
        <p class="mt-1 text-xs text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4 space-y-4">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div>
            <x-input-label for="update_password_current_password" class="text-gray-400 font-medium flex items-center text-xs">
                <x-heroicon-o-lock-closed class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                {{ __('Current Password') }}
            </x-input-label>
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5 text-xs text-rose-400" />
        </div>

        <!-- New Password -->
        <div>
            <x-input-label for="update_password_password" class="text-gray-400 font-medium flex items-center text-xs">
                <x-heroicon-o-shield-check class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                {{ __('New Password') }}
            </x-input-label>
            <x-text-input id="update_password_password" name="password" type="password" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5 text-xs text-rose-400" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="update_password_password_confirmation" class="text-gray-400 font-medium flex items-center text-xs">
                <x-heroicon-o-check-badge class="w-3.5 h-3.5 mr-1.5 text-gray-500" />
                {{ __('Confirm Password') }}
            </x-input-label>
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block mt-1.5 w-full bg-[#11141d] border-gray-700 text-white text-xs focus:border-[#3b82f6] focus:ring-[#3b82f6] rounded-xl px-3 py-2 shadow-sm focus:outline-none" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5 text-xs text-rose-400" />
        </div>

        <!-- Tombol Aksi & Notifikasi Sukses -->
        <div class="flex items-center gap-3 border-t border-gray-800/60 pt-4 mt-2">
            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl focus:outline-none transition shadow-lg shadow-amber-500/10">
                <x-heroicon-o-check-circle class="w-4 h-4 mr-1.5" />
                {{ __('Update Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs font-semibold text-emerald-400 flex items-center gap-1"
                >
                    <x-heroicon-s-check class="w-3.5 h-3.5" />
                    {{ __('Password updated.') }}
                </p>
            @endif
        </div>
    </form>
</section>
