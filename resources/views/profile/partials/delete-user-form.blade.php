<section class="space-y-4">
    <header class="mb-4">
        <h2 class="text-sm font-bold text-white flex items-center">
            <x-heroicon-s-trash class="w-4 h-4 mr-1.5 text-rose-500" />
            {{ __('Delete Account') }}
        </h2>
        <p class="mt-1 text-xs text-gray-400 leading-relaxed">
            {{ __('Once your account is deleted, all of its resources and data will be permanently wiped from our systems. Before proceeding, please back up or download any statements you wish to retain.') }}
        </p>
    </header>

    <!-- Tombol Trigger Modal Penghapusan -->
    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center justify-center px-4 py-2 bg-rose-500/10 hover:bg-rose-500 border border-rose-500/30 text-rose-400 hover:text-white font-bold text-xs rounded-xl focus:outline-none transition shadow-lg shadow-rose-950/20"
    >
        <x-heroicon-o-exclamation-triangle class="w-4 h-4 mr-1.5" />
        {{ __('Delete Account') }}
    </button>

    <!-- Modal Konfirmasi Bergaya Premium -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-[#0f172a] border border-gray-800 rounded-2xl">
            @csrf
            @method('delete')

            <h2 class="text-base font-bold text-white flex items-center gap-2">
                <x-heroicon-s-shield-exclamation class="w-5 h-5 text-rose-500" />
                {{ __('Are you absolutely sure you want to delete your account?') }}
            </h2>

            <p class="mt-2 text-xs text-gray-400 leading-relaxed">
                {{ __('This action is completely irreversible. All ledger books, tracking histories, sub-wallets, and recurring configurations will be permanently destroyed. Please enter your account password to verify this operation.') }}
            </p>

            <!-- Input Lapisan Keamanan Kata Sandi -->
            <div class="mt-5">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full sm:w-3/4 bg-[#11141d] border-gray-700 text-white text-xs focus:border-rose-500 focus:ring-rose-500 rounded-xl px-3 py-2 shadow-sm focus:outline-none"
                    placeholder="{{ __('Verify your account password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5 text-xs text-rose-400" />
            </div>

            <!-- Tombol Aksi Bawah Modal -->
            <div class="mt-6 flex justify-end gap-2 border-t border-gray-800/60 pt-4">
                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="inline-flex items-center justify-center px-4 py-2.5 bg-transparent border border-gray-700 hover:border-gray-500 text-gray-300 hover:text-white font-semibold text-xs rounded-xl transition"
                >
                    {{ __('Cancel') }}
                </button>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl focus:outline-none transition shadow-lg shadow-rose-600/10"
                >
                    <x-heroicon-o-trash class="w-4 h-4 mr-1.5" />
                    {{ __('Permanently Delete') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
