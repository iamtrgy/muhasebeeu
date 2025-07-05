<x-accountant.layout 
    title="Profile" 
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('accountant.dashboard'), 'first' => true],
        ['title' => __('Profile')]
    ]"
>
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Manage your personal information and account details') }}</p>
            </div>
            <x-ui.button.secondary href="{{ route('accountant.settings') }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ __('Settings') }}
            </x-ui.button.secondary>
        </div>

        {{-- Profile Information --}}
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Profile Information') }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Update your account profile information and email address') }}</p>
            </x-ui.card.header>
            <x-ui.card.body>
                @include('accountant.profile.partials.update-profile-information-form')
            </x-ui.card.body>
        </x-ui.card.base>

        {{-- Professional Information --}}
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Professional Information') }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Your professional details and qualifications') }}</p>
            </x-ui.card.header>
            <x-ui.card.body>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Account Type') }}</dt>
                        <dd class="mt-1">
                            <x-ui.badge variant="secondary">{{ __('Accountant') }}</x-ui.badge>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Assigned Companies') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            {{ auth()->user()->assignedCompanies()->count() }} {{ __('companies') }}
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Member Since') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            {{ auth()->user()->created_at->format('M d, Y') }}
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</dt>
                        <dd class="mt-1">
                            <x-ui.badge variant="success">{{ __('Active') }}</x-ui.badge>
                        </dd>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('To update professional credentials or add specializations, please contact your administrator.') }}
                    </p>
                </div>
            </x-ui.card.body>
        </x-ui.card.base>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Security Settings') }}</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Manage password and 2FA') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <x-ui.button.secondary href="{{ route('accountant.settings') }}#security" class="w-full">
                            {{ __('Manage Security') }}
                        </x-ui.button.secondary>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-emerald-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Notifications') }}</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Configure alert preferences') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <x-ui.button.secondary href="{{ route('accountant.settings') }}#notifications" class="w-full">
                            {{ __('Manage Notifications') }}
                        </x-ui.button.secondary>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        </div>
    </div>
</x-accountant.layout>
