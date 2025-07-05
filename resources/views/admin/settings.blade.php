<x-admin.layout 
    title="Settings" 
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('admin.dashboard'), 'first' => true],
        ['title' => __('Settings')]
    ]"
>
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Manage your admin account settings and system preferences') }}</p>
            </div>
        </div>

        {{-- Settings Tabs --}}
        <x-ui.tabs.base defaultTab="profile">
            <x-ui.tabs.list>
                <x-ui.tabs.tab name="profile">
                    <x-slot name="icon">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </x-slot>
                    {{ __('Profile') }}
                </x-ui.tabs.tab>
                
                <x-ui.tabs.tab name="security">
                    <x-slot name="icon">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </x-slot>
                    {{ __('Security') }}
                </x-ui.tabs.tab>
                
                <x-ui.tabs.tab name="system">
                    <x-slot name="icon">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </x-slot>
                    {{ __('System') }}
                </x-ui.tabs.tab>
                
                <x-ui.tabs.tab name="account">
                    <x-slot name="icon">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </x-slot>
                    {{ __('Account') }}
                </x-ui.tabs.tab>
            </x-ui.tabs.list>
            
            <x-ui.tabs.panels>

                <x-ui.tabs.panel name="profile">
                    <x-ui.card.base>
                        <x-ui.card.header>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Profile Information') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Update your account profile information and email address') }}</p>
                        </x-ui.card.header>
                        <x-ui.card.body>
                            @include('admin.profile.partials.update-profile-information-form')
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
                            @include('admin.profile.partials.update-password-form')
                        </x-ui.card.body>
                    </x-ui.card.base>

                    {{-- Two-Factor Authentication --}}
                    <x-ui.card.base>
                        <x-ui.card.header>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Two-Factor Authentication') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Add an extra layer of security to your admin account') }}</p>
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
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Manage your active admin login sessions') }}</p>
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

                <x-ui.tabs.panel name="system">
                <div class="space-y-6">
                    {{-- System Preferences --}}
                    <x-ui.card.base>
                        <x-ui.card.header>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('System Preferences') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Configure global system settings and defaults') }}</p>
                        </x-ui.card.header>
                        <x-ui.card.body>
                            <form action="{{ route('admin.settings') }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PATCH')
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <x-ui.form.group>
                                        <x-ui.form.select name="default_language" label="{{ __('Default Language') }}">
                                            <option value="en">{{ __('English') }}</option>
                                            <option value="tr">{{ __('Turkish') }}</option>
                                            <option value="et">{{ __('Estonian') }}</option>
                                        </x-ui.form.select>
                                    </x-ui.form.group>
                                    
                                    <x-ui.form.group>
                                        <x-ui.form.select name="default_timezone" label="{{ __('Default Timezone') }}">
                                            <option value="Europe/Istanbul">{{ __('Europe/Istanbul') }}</option>
                                            <option value="Europe/Tallinn">{{ __('Europe/Tallinn') }}</option>
                                            <option value="UTC">{{ __('UTC') }}</option>
                                        </x-ui.form.select>
                                    </x-ui.form.group>
                                </div>
                                
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('User Registration') }}</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Allow new users to register accounts') }}</p>
                                        </div>
                                        <x-ui.form.toggle name="allow_registration" :checked="true" />
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Email Verification') }}</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Require email verification for new accounts') }}</p>
                                        </div>
                                        <x-ui.form.toggle name="require_email_verification" :checked="true" />
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Maintenance Mode') }}</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Put the system in maintenance mode') }}</p>
                                        </div>
                                        <x-ui.form.toggle name="maintenance_mode" :checked="false" />
                                    </div>
                                </div>
                                
                                <div class="flex justify-end">
                                    <x-ui.button.primary type="submit">
                                        {{ __('Save Settings') }}
                                    </x-ui.button.primary>
                                </div>
                            </form>
                        </x-ui.card.body>
                    </x-ui.card.base>

                    {{-- File System Settings --}}
                    <x-ui.card.base>
                        <x-ui.card.header>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('File System') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Configure file upload and storage settings') }}</p>
                        </x-ui.card.header>
                        <x-ui.card.body>
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Max Upload Size') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">10 MB</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Storage Provider') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        <x-ui.badge variant="primary">BunnyCDN</x-ui.badge>
                                    </dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Files') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $totalFiles ?? 'N/A' }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Storage') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $totalStorage ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </x-ui.card.body>
                    </x-ui.card.base>
                </div>
                </x-ui.tabs.panel>

                <x-ui.tabs.panel name="account">
                <div class="space-y-6">
                    {{-- Account Statistics --}}
                    <x-ui.card.base>
                        <x-ui.card.header>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('System Overview') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Admin account and system statistics') }}</p>
                        </x-ui.card.header>
                        <x-ui.card.body>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $totalUsers ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total Users') }}</div>
                                </div>
                                
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $totalCompanies ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Companies') }}</div>
                                </div>
                                
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $totalFolders ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Folders') }}</div>
                                </div>
                                
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $totalTasks ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Tax Tasks') }}</div>
                                </div>
                            </div>
                        </x-ui.card.body>
                    </x-ui.card.base>

                    {{-- Account Information --}}
                    <x-ui.card.base>
                        <x-ui.card.header>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Account Information') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Admin account details and access information') }}</p>
                        </x-ui.card.header>
                        <x-ui.card.body>
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Account Type') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        <x-ui.badge variant="danger">{{ __('Administrator') }}</x-ui.badge>
                                    </dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Admin Since') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ auth()->user()->created_at->format('M d, Y') }}</dd>
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
                            <p class="mt-1 max-w-2xl text-sm text-red-600 dark:text-red-400">{{ __('Irreversible actions that affect your admin account') }}</p>
                        </x-ui.card.header>
                        <x-ui.card.body>
                            @include('admin.profile.partials.delete-user-form')
                        </x-ui.card.body>
                    </x-ui.card.base>
                </div>
                </x-ui.tabs.panel>
            </x-ui.tabs.panels>
        </x-ui.tabs.base>
    </div>
</x-admin.layout>
