<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-6 sm:px-6 lg:px-8 py-1.5">
            <h2 class="font-bold text-lg text-white leading-tight flex items-center gap-2">
                <x-heroicon-s-clipboard-document-list class="w-4 h-4 text-amber-400" />
                {{ __('Budgets Management') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10 bg-[#090a0f] min-h-screen text-gray-200">
        <div class="max-w-7xl mx-auto px-6 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-[#0f172a] border border-gray-800 rounded-2xl overflow-hidden shadow-lg shadow-black/20">
                <div class="p-6">

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <div>
                            <h3 class="text-sm font-bold text-white flex items-center">
                                <x-heroicon-s-adjustments-vertical class="w-4 h-4 mr-1.5 text-amber-400" />
                                Active Limit Rules
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5">Monitor and manage limits across your transaction categories.</p>
                        </div>
                        <a href="{{ route('budgets.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl focus:outline-none transition shadow-lg shadow-amber-500/10 w-full sm:w-auto text-center">
                            <x-heroicon-o-plus-circle class="w-4 h-4 mr-1.5 flex-shrink-0" />
                            {{ __('Add New Budget') }}
                        </a>
                    </div>

                    @if (session('status'))
                        <div class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold rounded-xl flex items-center gap-2">
                            <x-heroicon-s-check-circle class="w-4 h-4" />
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="mt-4">
                        @if ($budgets->isEmpty())
                            <div class="p-10 text-center border border-dashed border-gray-800 rounded-2xl flex flex-col items-center justify-center">
                                <x-heroicon-o-clipboard-document-list class="w-10 h-10 mb-3 text-gray-600" />
                                <p class="text-sm font-semibold text-white">{{ __('No budgets configured.') }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ __('Set up limits to get notifications and warnings when spending high.') }}</p>
                                <a href="{{ route('budgets.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-transparent border border-gray-700 hover:border-gray-500 text-gray-300 hover:text-white font-semibold text-xs rounded-xl transition">
                                    <x-heroicon-o-plus-circle class="w-3.5 h-3.5 mr-1.5" />
                                    {{ __('Create First Budget') }}
                                </a>
                            </div>
                        @else
                            <div class="overflow-x-auto border border-gray-800 rounded-xl shadow-inner">
                                <table class="min-w-full divide-y divide-gray-800/80">
                                    <thead class="bg-[#111625]">
                                    <tr class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                        <th scope="col" class="px-6 py-3.5 text-left">{{ __('Category') }}</th>
                                        <th scope="col" class="px-6 py-3.5 text-left">{{ __('Period') }}</th>
                                        <th scope="col" class="px-6 py-3.5 text-left">{{ __('Duration') }}</th>
                                        <th scope="col" class="px-6 py-3.5 text-right">{{ __('Allocated') }}</th>
                                        <th scope="col" class="px-6 py-3.5 text-right">{{ __('Current Spent') }}</th>
                                        <th scope="col" class="px-6 py-3.5 text-left min-w-[160px]">{{ __('Progress') }}</th>
                                        <th scope="col" class="relative px-6 py-3.5 text-right">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-[#0f172a] divide-y divide-gray-800/60 text-xs">
                                    @foreach ($budgets as $budget)
                                        <tr class="hover:bg-[#151f38] transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap font-bold text-white">
                                                    <span class="inline-flex items-center gap-1.5">
                                                        <x-heroicon-s-tag class="w-3.5 h-3.5 text-[#3b82f6]" />
                                                        {{ $budget->category->name ?? 'All Categories' }}
                                                    </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 py-0.5 bg-gray-800 border border-gray-700 text-gray-300 text-[10px] rounded-md font-semibold tracking-wide uppercase">
                                                        {{ $budget->period }}
                                                    </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-400 text-[11px] leading-relaxed">
                                                <div>From: <span class="text-gray-300 font-medium">{{ is_string($budget->start_date) ? $budget->start_date : $budget->start_date?->format('Y-m-d') }}</span></div>
                                                <div class="text-[10px] text-gray-500">Until: <span class="text-gray-400">{{ $budget->end_date ? (is_string($budget->end_date) ? $budget->end_date : $budget->end_date->format('Y-m-d')) : 'Infinite' }}</span></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-white">
                                                {{ $budget->currency }} {{ number_format($budget->amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold {{ $budget->progress_percentage > 100 ? 'text-rose-400' : 'text-gray-300' }}">
                                                {{ $budget->currency }} {{ number_format($budget->current_spent, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center justify-between font-semibold mb-1 text-[10px] {{ $budget->progress_percentage > 100 ? 'text-rose-400' : ($budget->progress_percentage > 80 ? 'text-amber-400' : 'text-blue-400') }}">
                                                    <span>{{ $budget->progress_percentage }}% used</span>
                                                    @if($budget->progress_percentage > 100)
                                                        <span class="flex items-center gap-0.5"><x-heroicon-s-exclamation-triangle class="w-3 h-3 text-rose-400 animate-pulse" /> Over limit</span>
                                                    @endif
                                                </div>
                                                <div class="w-full bg-gray-800 rounded-full h-1.5 overflow-hidden">
                                                    <div class="h-1.5 rounded-full {{ $budget->progress_percentage > 100 ? 'bg-rose-500' : ($budget->progress_percentage > 80 ? 'bg-amber-500' : 'bg-blue-500') }}" style="width: {{ min(100, $budget->progress_percentage) }}%"></div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-[11px] font-semibold space-x-2">
                                                <a href="{{ route('budgets.edit', $budget) }}" class="text-[#3b82f6] hover:text-blue-400 inline-flex items-center transition">
                                                    <x-heroicon-o-pencil-square class="w-3.5 h-3.5 mr-0.5" />
                                                    {{ __('Edit') }}
                                                </a>
                                                <form action="{{ route('budgets.destroy', $budget) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this budget?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-400 hover:text-rose-300 inline-flex items-center transition bg-transparent border-0 p-0">
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

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
