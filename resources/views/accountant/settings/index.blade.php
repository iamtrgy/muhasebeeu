<x-accountant.layout 
    title="Settings" 
    :breadcrumbs="[
        ['title' => __('Home'), 'href' => route('accountant.dashboard'), 'first' => true],
        ['title' => __('Settings')]
    ]"
>
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Manage your account preferences and notification settings') }}</p>
            </div>
        </div>

        {{-- Settings Tabs --}}
        <x-ui.tabs.base defaultTab="notifications">
            <x-ui.tabs.list>
                <x-ui.tabs.tab name="notifications">
                    <x-slot name="icon">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </x-slot>
                    {{ __('Notifications') }}
                </x-ui.tabs.tab>
                
                <x-ui.tabs.tab name="security">
                    <x-slot name="icon">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </x-slot>
                    {{ __('Security') }}
                </x-ui.tabs.tab>
                
                <x-ui.tabs.tab name="appearance">
                    <x-slot name="icon">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z" />
                        </svg>
                    </x-slot>
                    {{ __('Appearance') }}
                </x-ui.tabs.tab>
                
                <x-ui.tabs.tab name="account">
                    <x-slot name="icon">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </x-slot>
                    {{ __('Account') }}
                </x-ui.tabs.tab>
            </x-ui.tabs.list>
            
            <x-ui.tabs.panels>

                <x-ui.tabs.panel name="notifications">
                <x-ui.card.base>
                    <x-ui.card.header>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Notification Preferences') }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Choose how you want to be notified about reviews and updates') }}</p>
                    </x-ui.card.header>
                    <x-ui.card.body>
                        <form action="{{ route('accountant.settings.notifications') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PATCH')
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Email Notifications') }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Receive general email notifications') }}</p>
                                    </div>
                                    <x-ui.form.toggle name="email_notifications" :checked="true" />
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Review Notifications') }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Get notified when new reviews are submitted') }}</p>
                                    </div>
                                    <x-ui.form.toggle name="review_notifications" :checked="true" />
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Daily Summary') }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Receive daily summary of pending reviews') }}</p>
                                    </div>
                                    <x-ui.form.toggle name="daily_summary" :checked="false" />
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Task Reminders') }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Get reminded about overdue reviews') }}</p>
                                    </div>
                                    <x-ui.form.toggle name="task_reminders" :checked="true" />
                                </div>
                            </div>
                            
                            <div class="flex justify-end">
                                <x-ui.button.primary type="submit">
                                    {{ __('Save Preferences') }}
                                </x-ui.button.primary>
                            </div>
                        </form>
                    </x-ui.card.body>
                </x-ui.card.base>
                </x-ui.tabs.panel>

                <x-ui.tabs.panel name="security">
                <div class="space-y-6">
                    {{-- Password Change --}}
                    <x-ui.card.base>
                        <x-ui.card.header>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Change Password') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Update your password to keep your account secure') }}</p>
                        </x-ui.card.header>
                        <x-ui.card.body>
                            @include('accountant.profile.partials.update-password-form')
                        </x-ui.card.body>
                    </x-ui.card.base>

                    {{-- Two-Factor Authentication --}}
                    <x-ui.card.base>
                        <x-ui.card.header>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Two-Factor Authentication') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Add an extra layer of security to your account') }}</p>
                        </x-ui.card.header>
                        <x-ui.card.body>
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Status') }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Two-factor authentication is not enabled') }}</p>
                                </div>
                                <x-ui.button.secondary>
                                    {{ __('Enable 2FA') }}
                                </x-ui.button.secondary>
                            </div>
                        </x-ui.card.body>
                    </x-ui.card.base>

                    {{-- Active Sessions --}}
                    <x-ui.card.base>
                        <x-ui.card.header>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Active Sessions') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Manage your active login sessions') }}</p>
                        </x-ui.card.header>
                        <x-ui.card.body>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Current Session') }}</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('This device') }} • {{ __('Active now') }}</p>
                                        </div>
                                    </div>
                                    <x-ui.badge variant="success">{{ __('Current') }}</x-ui.badge>
                                </div>
                            </div>
                        </x-ui.card.body>
                    </x-ui.card.base>
                </div>
                </x-ui.tabs.panel>

                <x-ui.tabs.panel name="appearance">
                <x-ui.card.base>
                    <x-ui.card.header>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Appearance & Language') }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Customize how the application looks and behaves') }}</p>
                    </x-ui.card.header>
                    <x-ui.card.body>
                        <form action="{{ route('accountant.settings.appearance') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PATCH')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <x-ui.form.group>
                                    <x-ui.form.select name="theme" label="{{ __('Theme') }}">
                                        <option value="system">{{ __('System Default') }}</option>
                                        <option value="light">{{ __('Light') }}</option>
                                        <option value="dark">{{ __('Dark') }}</option>
                                    </x-ui.form.select>
                                </x-ui.form.group>
                                
                                <x-ui.form.group>
                                    <x-ui.form.select name="language" label="{{ __('Language') }}">
                                        <option value="en">{{ __('English') }}</option>
                                        <option value="tr">{{ __('Turkish') }}</option>
                                        <option value="et">{{ __('Estonian') }}</option>
                                    </x-ui.form.select>
                                </x-ui.form.group>
                                
                                <x-ui.form.group>
                                    <x-ui.form.select name="timezone" label="{{ __('Timezone') }}">
                                        <option value="Europe/Istanbul">{{ __('Europe/Istanbul') }}</option>
                                        <option value="Europe/Tallinn">{{ __('Europe/Tallinn') }}</option>
                                        <option value="UTC">{{ __('UTC') }}</option>
                                    </x-ui.form.select>
                                </x-ui.form.group>
                            </div>
                            
                            <div class="flex justify-end">
                                <x-ui.button.primary type="submit">
                                    {{ __('Save Preferences') }}
                                </x-ui.button.primary>
                            </div>
                        </form>
                    </x-ui.card.body>
                </x-ui.card.base>
                </x-ui.tabs.panel>

                <x-ui.tabs.panel name="account">
                <div class="space-y-6">
                    {{-- Account Statistics --}}
                    <x-ui.card.base>
                        <x-ui.card.header>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Account Statistics') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Your activity and performance overview') }}</p>
                        </x-ui.card.header>
                        <x-ui.card.body>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $assignedCompaniesCount }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Assigned Companies') }}</div>
                                </div>
                                
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $reviewStats['pending_reviews'] }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Pending Reviews') }}</div>
                                </div>
                                
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $reviewStats['completed_reviews'] }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Completed Reviews') }}</div>
                                </div>
                            </div>
                        </x-ui.card.body>
                    </x-ui.card.base>

                    {{-- Account Information --}}
                    <x-ui.card.base>
                        <x-ui.card.header>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Account Information') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Basic account details and registration information') }}</p>
                        </x-ui.card.header>
                        <x-ui.card.body>
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Account Type') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        <x-ui.badge variant="secondary">{{ __('Accountant') }}</x-ui.badge>
                                    </dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Member Since') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->created_at->format('M d, Y') }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Last Login') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ __('Just now') }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        <x-ui.badge variant="success">{{ __('Active') }}</x-ui.badge>
                                    </dd>
                                </div>
                            </dl>
                        </x-ui.card.body>
                    </x-ui.card.base>

                    {{-- Danger Zone --}}
                    <x-ui.card.base>
                        <x-ui.card.header>
                            <h3 class="text-lg leading-6 font-medium text-red-900 dark:text-red-100">{{ __('Danger Zone') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-red-600 dark:text-red-400">{{ __('Irreversible actions that affect your account') }}</p>
                        </x-ui.card.header>
                        <x-ui.card.body>
                            @include('accountant.profile.partials.delete-user-form')
                        </x-ui.card.body>
                    </x-ui.card.base>
                </div>
                </x-ui.tabs.panel>
            </x-ui.tabs.panels>
        </x-ui.tabs.base>
    </div>
</x-accountant.layout>