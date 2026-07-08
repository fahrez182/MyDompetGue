<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Premium Features') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($userRole === 'basic')
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Upgrade to Premium!
                        </h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Unlock advanced reporting, budgeting, and multiple wallets.
                        </p>
                        <div class="mt-6">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100">Our Plans:</h4>
                            <ul class="mt-2 list-disc list-inside">
                                <li>Basic: Free (Current Plan)</li>
                                <li>Premium: $9.99/month (Advanced Features)</li>
                            </ul>
                        </div>
                        <div class="mt-6">
                            <form method="POST" action="{{ route('premium.upgrade') }}">
                                @csrf
                                <x-primary-button>
                                    {{ __('Upgrade Now') }}
                                </x-primary-button>
                            </form>
                        </div>
                    @else
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Welcome, Premium User!
                        </h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Enjoy your exclusive advanced features.
                        </p>
                        <div class="mt-6">
                            {{-- Placeholder for actual premium features content --}}
                            <p>Here you will find advanced reports, budgeting tools, and options to manage multiple wallets.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
