<section>
    <header class="mb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-sm font-bold text-white flex items-center">
                <x-heroicon-s-wallet class="w-4 h-4 mr-1.5 text-emerald-400" />
                {{ __('Manage Wallets') }}
            </h2>
            <p class="mt-1 text-xs text-gray-400">
                {{ __('Configure sub-wallets, track converted balances, and select your primary default gateway.') }}
            </p>
        </div>
        <a href="{{ route('wallets.create') }}" class="inline-flex items-center justify-center px-3.5 py-2 bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-bold text-xs rounded-xl focus:outline-none transition shadow-lg shadow-emerald-500/10 w-full sm:w-auto text-center">
            <x-heroicon-o-plus-circle class="w-4 h-4 mr-1.5 flex-shrink-0" />
            {{ __('Add New Wallet') }}
        </a>
    </header>

    <div class="mt-4 space-y-4">
        <!-- Notifikasi Status Operasi Success/Error -->
        @if (session('success'))
            <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold rounded-xl flex items-center gap-2">
                <x-heroicon-s-check-circle class="w-4 h-4" />
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-3 bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-semibold rounded-xl flex items-center gap-2">
                <x-heroicon-s-x-circle class="w-4 h-4" />
                {{ session('error') }}
            </div>
        @endif

        @if ($wallets->isEmpty())
            <div class="p-8 text-center border border-dashed border-gray-800 rounded-2xl flex flex-col items-center justify-center">
                <x-heroicon-o-wallet class="w-8 h-8 mb-2.5 text-gray-600" />
                <p class="text-xs font-semibold text-white">{{ __('You have not created any wallets yet.') }}</p>
            </div>
        @else
            <!-- Pembungkus Tabel Utama dengan Padding px-6 -->
            <div class="overflow-x-auto border border-gray-800 rounded-xl shadow-inner">
                <table class="min-w-full divide-y divide-gray-800/80">
                    <thead class="bg-[#111625]">
                    <tr class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                        <th scope="col" class="px-6 py-3 text-left">{{ __('Wallet Name') }}</th>
                        <th scope="col" class="px-6 py-3 text-left">{{ __('Converted Balance') }}</th>
                        <th scope="col" class="px-6 py-3 text-left">{{ __('System Currency') }}</th>
                        <th scope="col" class="px-6 py-3 text-left">{{ __('Status') }}</th>
                        <th scope="col" class="relative px-6 py-3 text-right">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-[#0f172a] divide-y divide-gray-800/60 text-xs">
                    @foreach ($wallets as $wallet)
                        <tr class="hover:bg-[#151f38] transition-colors">
                            <!-- Nama Dompet -->
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-white">
                                {{ $wallet->name }}
                            </td>
                            <!-- Saldo Terkonversi -->
                            <td class="px-6 py-4 whitespace-nowrap font-mono text-gray-200">
                                {{ number_format($wallet->converted_balance, 2) }}
                            </td>
                            <!-- Mata Uang Dasbor -->
                            <td class="px-6 py-4 whitespace-nowrap text-gray-400 font-bold tracking-wider text-[11px]">
                                {{ $userBaseCurrency }}
                            </td>
                            <!-- Status Penunjuk Default -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($wallet->is_default)
                                    <span class="inline-flex items-center gap-1 text-emerald-400 font-semibold text-[10px] uppercase tracking-wider bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded-md">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                            {{ __('Primary Default') }}
                                        </span>
                                @else
                                    <form action="{{ route('wallets.set-default', $wallet) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-[#3b82f6] hover:text-blue-400 text-[11px] font-bold transition hover:underline bg-transparent border-0 p-0 cursor-pointer">
                                            {{ __('Set as Default') }}
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <!-- Tombol Aksi Operasi Kanan -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-[11px] font-semibold space-x-2">
                                <a href="{{ route('wallets.edit', $wallet) }}" class="text-gray-400 hover:text-white inline-flex items-center transition">
                                    <x-heroicon-o-pencil-square class="w-3.5 h-3.5 mr-0.5" />
                                    {{ __('Edit') }}
                                </a>
                                <form action="{{ route('wallets.destroy', $wallet) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-400 hover:text-rose-300 inline-flex items-center transition bg-transparent border-0 p-0 cursor-pointer" onclick="return confirm('Are you sure you want to delete this wallet? All associated transactions will be affected.')">
                                        <x-heroicon-o-trash class="w-3.5 h-3.5 mr-0.5" />
                                        {{ __('Delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</section>
