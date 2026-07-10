<nav x-data="{ open: false }" class="bg-[#0f172a] border-b border-gray-800 sticky top-0 z-50 shadow-md">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo MyDompetGue -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                        <div class="bg-[#3b82f6] p-1.5 rounded-lg text-white transition group-hover:bg-[#2563eb]">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path d="M12 7.5a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z" />
                                <path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 14.625v-9.75ZM21 9.75H3V14.625c0 .207.168.375.375.375h17.25c.207 0 .375-.168.375-.375V9.75Z" clip-rule="evenodd" />
                                <path d="M1.5 18a.75.75 0 0 0 0 1.5h21a.75.75 0 0 0 0-1.5H1.5Z" />
                            </svg>
                        </div>
                        <span class="text-white font-bold text-base tracking-tight hidden md:inline-block">My<span class="text-[#3b82f6]">Dompet</span>Gws</span>
                    </a>
                </div>

                <!-- Navigation Links (Desktop dengan Ikon Kecil) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-300 hover:text-white inline-flex items-center gap-1.5">
                        <x-heroicon-o-squares-2x2 class="w-4 h-4 flex-shrink-0" />
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.index')" class="text-gray-300 hover:text-white inline-flex items-center gap-1.5">
                        <x-heroicon-o-tag class="w-4 h-4 flex-shrink-0" />
                        {{ __('Categories') }}
                    </x-nav-link>
                    <x-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.index')" class="text-gray-300 hover:text-white inline-flex items-center gap-1.5">
                        <x-heroicon-o-credit-card class="w-4 h-4 flex-shrink-0" />
                        {{ __('Transactions') }}
                    </x-nav-link>
                    @if (Auth::user()->role === 'premium')
                        <x-nav-link :href="route('budgets.index')" :active="request()->routeIs('budgets.index')" class="text-gray-300 hover:text-white inline-flex items-center gap-1.5">
                            <x-heroicon-o-chart-bar-square class="w-4 h-4 flex-shrink-0" />
                            {{ __('Budgets') }}
                        </x-nav-link>
                        <x-nav-link :href="route('recurring-transactions.index')" :active="request()->routeIs('recurring-transactions.index')" class="text-gray-300 hover:text-white inline-flex items-center gap-1.5">
                            <x-heroicon-o-arrow-path class="w-4 h-4 flex-shrink-0" />
                            {{ __('Recurring') }}
                        </x-nav-link>
                        <x-nav-link :href="route('premium.reporting.advanced')" :active="request()->routeIs('premium.reporting.advanced')" class="text-gray-300 hover:text-white inline-flex items-center gap-1.5">
                            <x-heroicon-o-presentation-chart-line class="w-4 h-4 flex-shrink-0" />
                            {{ __('Advanced Reporting') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-2">
                <!-- Dark Mode Toggle -->
                <button id="theme-toggle" type="button" class="text-gray-400 hover:text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-[#3b82f6] rounded-xl text-sm p-2">
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.05a1 1 0 001.414 0l.707-.707a1 1 0 00-1.414-1.414l-.707.707a1 1 0 000 1.414zm1.414 8.485a1 1 0 01-1.414 0l-.707-.707a1 1 0 011.414-1.414l.707.707a1 1 0 010 1.414zM3 11a1 1 0 100-2H2a1 1 0 000 2h1z"></path></svg>
                </button>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-xl text-gray-300 bg-transparent hover:text-white hover:bg-gray-800 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center gap-2">
                                <span>{{ Auth::user()->name }}</span>
                                @if (Auth::user()->role === 'premium')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                        Premium
                                    </span>
                                @endif
                            </div>

                            <div class="ms-1.5">
                                <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2">
                            <x-heroicon-o-user class="w-4 h-4 text-gray-400" />
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        @if (Auth::user()->role === 'basic')
                            <x-dropdown-link :href="route('premium.index')" class="text-[#3b82f6] font-semibold flex items-center gap-2">
                                <x-heroicon-o-sparkles class="w-4 h-4 text-[#3b82f6]" />
                                {{ __('Upgrade to Premium') }}
                            </x-dropdown-link>
                        @else
                            <x-dropdown-link :href="route('premium.index')" class="flex items-center gap-2">
                                <x-heroicon-o-sparkles class="w-4 h-4 text-amber-400" />
                                {{ __('Premium Features') }}
                            </x-dropdown-link>
                        @endif

                        <hr class="border-gray-800 my-1">

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault();
                                                this.closest('form').submit();" class="text-red-400 flex items-center gap-2">
                                <x-heroicon-o-arrow-left-on-rectangle class="w-4 h-4 text-red-400" />
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile Menu Button) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-400 hover:text-white hover:bg-gray-800 focus:outline-none focus:bg-gray-800 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile View Drawer) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#111524] border-t border-gray-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center gap-3">
                <x-heroicon-o-squares-2x2 class="w-5 h-5 opacity-80" />
                <span>{{ __('Dashboard') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.index')" class="flex items-center gap-3">
                <x-heroicon-o-tag class="w-5 h-5 opacity-80" />
                <span>{{ __('Categories') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.index')" class="flex items-center gap-3">
                <x-heroicon-o-credit-card class="w-5 h-5 opacity-80" />
                <span>{{ __('Transactions') }}</span>
            </x-responsive-nav-link>
            @if (Auth::user()->role === 'premium')
                <x-responsive-nav-link :href="route('budgets.index')" :active="request()->routeIs('budgets.index')" class="flex items-center gap-3">
                    <x-heroicon-o-chart-bar-square class="w-5 h-5 opacity-80" />
                    <span>{{ __('Budgets') }}</span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('recurring-transactions.index')" :active="request()->routeIs('recurring-transactions.index')" class="flex items-center gap-3">
                    <x-heroicon-o-arrow-path class="w-5 h-5 opacity-80" />
                    <span>{{ __('Recurring Transactions') }}</span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('premium.reporting.advanced')" :active="request()->routeIs('premium.reporting.advanced')" class="flex items-center gap-3">
                    <x-heroicon-o-presentation-chart-line class="w-5 h-5 opacity-80" />
                    <span>{{ __('Advanced Reporting') }}</span>
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-gray-800 bg-[#0c0e17]">
            <div class="px-4 flex items-center justify-between">
                <div>
                    <div class="font-bold text-base text-white flex items-center gap-2">
                        {{ Auth::user()->name }}
                        @if (Auth::user()->role === 'premium')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                Premium
                            </span>
                        @endif
                    </div>
                    <div class="font-medium text-sm text-gray-400 mt-0.5">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center gap-3">
                    <x-heroicon-o-user class="w-5 h-5 opacity-80" />
                    <span>{{ __('Profile') }}</span>
                </x-responsive-nav-link>

                @if (Auth::user()->role === 'basic')
                    <x-responsive-nav-link :href="route('premium.index')" class="text-[#3b82f6] font-semibold flex items-center gap-3">
                        <x-heroicon-o-sparkles class="w-5 h-5 text-[#3b82f6]" />
                        <span>{{ __('Upgrade to Premium ✨') }}</span>
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('premium.index')" class="flex items-center gap-3">
                        <x-heroicon-o-sparkles class="w-5 h-5 text-amber-400" />
                        <span>{{ __('Premium Features') }}</span>
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                                           onclick="event.preventDefault();
                                        this.closest('form').submit();" class="text-red-400 flex items-center gap-3">
                        <x-heroicon-o-arrow-left-on-rectangle class="w-5 h-5 text-red-400" />
                        <span>{{ __('Log Out') }}</span>
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
