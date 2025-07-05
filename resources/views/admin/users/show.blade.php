<x-admin.layout 
    title="{{ $user->name }}"
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('admin.dashboard'), 'first' => true],
        ['title' => __('Users'), 'href' => route('admin.users.index')],
        ['title' => $user->name]
    ]"
>
    <div class="space-y-6">
        {{-- User Header Card --}}
        <x-ui.card.base>
            <x-ui.card.body>
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4">
                        <x-ui.avatar name="{{ $user->name }}" size="lg" />
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ $user->name }}
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->email }}
                            </p>
                            <div class="mt-2 flex items-center gap-2">
                                @if($user->is_admin)
                                    <x-ui.badge variant="secondary" size="sm">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        {{ __('Admin') }}
                                    </x-ui.badge>
                                @endif
                                @if($user->is_accountant)
                                    <x-ui.badge variant="success" size="sm">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        {{ __('Accountant') }}
                                    </x-ui.badge>
                                @endif
                                @if(!$user->is_admin && !$user->is_accountant)
                                    <x-ui.badge variant="secondary" size="sm">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ __('User') }}
                                    </x-ui.badge>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-ui.dropdown.base align="right">
                            <x-slot name="trigger">
                                <x-ui.button.secondary size="sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                    {{ __('Actions') }}
                                </x-ui.button.secondary>
                            </x-slot>
                            
                            <x-ui.dropdown.item href="{{ route('admin.users.edit', $user) }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __('Edit User') }}
                            </x-ui.dropdown.item>
                            
                            @if(!$user->email_verified_at)
                                <form action="{{ route('admin.users.verify', $user) }}" method="POST">
                                    @csrf
                                    <x-ui.dropdown.item tag="button" type="submit">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        {{ __('Verify Email') }}
                                    </x-ui.dropdown.item>
                                </form>
                            @endif
                            
                            <x-ui.dropdown.divider />
                            
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <x-ui.dropdown.item tag="button" type="submit">
                                    <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span class="text-red-600">{{ __('Delete User') }}</span>
                                </x-ui.dropdown.item>
                            </form>
                        </x-ui.dropdown.base>
                        
                        <x-ui.button.primary size="sm" href="{{ route('admin.users.subscription.manage', $user) }}">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            {{ __('Manage Subscription') }}
                        </x-ui.button.primary>
                    </div>
                </div>
            </x-ui.card.body>
        </x-ui.card.base>

        {{-- Tabs Section --}}
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
                
                <x-ui.tabs.tab name="folders">
                    <x-slot name="icon">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    </x-slot>
                    <x-slot name="badge">
                        <x-ui.badge size="sm" variant="secondary">{{ $folders->count() }}</x-ui.badge>
                    </x-slot>
                    {{ __('Folders') }}
                </x-ui.tabs.tab>
                
                <x-ui.tabs.tab name="companies">
                    <x-slot name="icon">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </x-slot>
                    <x-slot name="badge">
                        <x-ui.badge size="sm" variant="secondary">{{ $user->companies->count() }}</x-ui.badge>
                    </x-slot>
                    {{ __('Companies') }}
                </x-ui.tabs.tab>
                
                @if($user->is_accountant)
                    <x-ui.tabs.tab name="assignments">
                        <x-slot name="icon">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </x-slot>
                        {{ __('Accountant Assignments') }}
                    </x-ui.tabs.tab>
                @endif
            </x-ui.tabs.list>
            
            <x-ui.tabs.panels>
                {{-- Profile Tab --}}
                <x-ui.tabs.panel name="profile">
                    <div class="space-y-6">
                        {{-- User Information Card --}}
                        <x-ui.card.base>
                            <x-ui.card.header>
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('User Information') }}
                                </h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Personal details and application status.') }}
                                </p>
                            </x-ui.card.header>
                            <x-ui.card.body>
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            {{ __('Full Name') }}
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->name }}
                                        </dd>
                                    </div>
                                    
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            {{ __('Email Address') }}
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->email }}
                                        </dd>
                                    </div>
                                    
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            {{ __('Email Verification') }}
                                        </dt>
                                        <dd class="mt-1">
                                            @if($user->email_verified_at)
                                                <x-ui.badge variant="success" size="sm">
                                                    {{ __('Verified') }}
                                                </x-ui.badge>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $user->email_verified_at->format('M d, Y H:i') }}
                                                </p>
                                            @else
                                                <x-ui.badge variant="danger" size="sm">
                                                    {{ __('Not Verified') }}
                                                </x-ui.badge>
                                            @endif
                                        </dd>
                                    </div>
                                    
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            {{ __('Country') }}
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->country->name ?? __('Not specified') }}
                                        </dd>
                                    </div>
                                    
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            {{ __('Member Since') }}
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->created_at->format('M d, Y') }}
                                            <span class="text-gray-500 dark:text-gray-400">
                                                ({{ $user->created_at->diffForHumans() }})
                                            </span>
                                        </dd>
                                    </div>
                                    
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            {{ __('Last Updated') }}
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->updated_at->format('M d, Y H:i') }}
                                        </dd>
                                    </div>
                                </dl>
                            </x-ui.card.body>
                        </x-ui.card.base>
                        
                        {{-- Account Status Card --}}
                        <x-ui.card.base>
                            <x-ui.card.header>
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Account Status') }}
                                </h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Subscription and onboarding information.') }}
                                </p>
                            </x-ui.card.header>
                            <x-ui.card.body>
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            {{ __('Subscription Status') }}
                                        </dt>
                                        <dd class="mt-1">
                                            @if($user->hasActiveSubscription())
                                                <x-ui.badge variant="success">
                                                    {{ __('Active') }}
                                                </x-ui.badge>
                                            @else
                                                <x-ui.badge variant="danger">
                                                    {{ __('Inactive') }}
                                                </x-ui.badge>
                                            @endif
                                        </dd>
                                    </div>
                                    
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            {{ __('Onboarding Status') }}
                                        </dt>
                                        <dd class="mt-1">
                                            @if($user->onboarding_completed)
                                                <x-ui.badge variant="success">
                                                    {{ __('Completed') }}
                                                </x-ui.badge>
                                            @else
                                                <x-ui.badge variant="warning">
                                                    {{ __('Step') }} {{ $user->onboarding_step ?? 0 }}
                                                </x-ui.badge>
                                            @endif
                                        </dd>
                                    </div>
                                    
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            {{ __('User Roles') }}
                                        </dt>
                                        <dd class="mt-1 flex flex-wrap gap-2">
                                            @if($user->is_admin)
                                                <x-ui.badge variant="secondary" size="sm">{{ __('Admin') }}</x-ui.badge>
                                            @endif
                                            @if($user->is_accountant)
                                                <x-ui.badge variant="success" size="sm">{{ __('Accountant') }}</x-ui.badge>
                                            @endif
                                            @if(!$user->is_admin && !$user->is_accountant)
                                                <x-ui.badge variant="secondary" size="sm">{{ __('User') }}</x-ui.badge>
                                            @endif
                                        </dd>
                                    </div>
                                    
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            {{ __('Account Status') }}
                                        </dt>
                                        <dd class="mt-1">
                                            <x-ui.badge variant="success">
                                                {{ __('Active') }}
                                            </x-ui.badge>
                                        </dd>
                                    </div>
                                </dl>
                            </x-ui.card.body>
                        </x-ui.card.base>
                    </div>
                </x-ui.tabs.panel>
                
                {{-- Folders Tab --}}
                <x-ui.tabs.panel name="folders">
                    <x-ui.card.base>
                        <x-ui.card.header>
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('User Folders') }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('Folders created by this user.') }}
                                    </p>
                                </div>
                            </div>
                        </x-ui.card.header>
                        <x-ui.card.body class="p-0">
                            @if(isset($folders) && $folders->count() > 0)
                                <x-ui.table.base>
                                    <x-slot name="head">
                                        <x-ui.table.head-cell>{{ __('Folder Name') }}</x-ui.table.head-cell>
                                        <x-ui.table.head-cell>{{ __('Files') }}</x-ui.table.head-cell>
                                        <x-ui.table.head-cell>{{ __('Created') }}</x-ui.table.head-cell>
                                        <x-ui.table.head-cell align="right">{{ __('Actions') }}</x-ui.table.head-cell>
                                    </x-slot>
                                    <x-slot name="body">
                                        @foreach($folders as $folder)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                <x-ui.table.cell>
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0">
                                                            <svg class="h-8 w-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                            </svg>
                                                        </div>
                                                        <div class="ml-3">
                                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                {{ $folder->name }}
                                                            </div>
                                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                                ID: {{ $folder->id }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </x-ui.table.cell>
                                                <x-ui.table.cell>
                                                    <x-ui.badge variant="secondary" size="sm">
                                                        {{ $folder->files->count() }} {{ __('files') }}
                                                    </x-ui.badge>
                                                </x-ui.table.cell>
                                                <x-ui.table.cell>
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $folder->created_at->format('M d, Y') }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $folder->created_at->diffForHumans() }}
                                                    </div>
                                                </x-ui.table.cell>
                                                <x-ui.table.action-cell>
                                                    <x-ui.button.secondary size="sm" href="{{ route('admin.folders.show', $folder) }}">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        {{ __('View') }}
                                                    </x-ui.button.secondary>
                                                </x-ui.table.action-cell>
                                            </tr>
                                        @endforeach
                                    </x-slot>
                                </x-ui.table.base>
                            @else
                                <x-ui.table.empty-state>
                                    <x-slot name="icon">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                        </svg>
                                    </x-slot>
                                    <x-slot name="title">{{ __('No Folders') }}</x-slot>
                                    <x-slot name="description">{{ __('This user has not created any folders yet.') }}</x-slot>
                                </x-ui.table.empty-state>
                            @endif
                        </x-ui.card.body>
                    </x-ui.card.base>
                </x-ui.tabs.panel>
                
                {{-- Companies Tab --}}
                <x-ui.tabs.panel name="companies">
                    <x-ui.card.base>
                        <x-ui.card.header>
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('User Companies') }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('Companies owned by this user.') }}
                                    </p>
                                </div>
                                <x-ui.button.primary size="sm" href="{{ route('admin.companies.create') }}?user_id={{ $user->id }}">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    {{ __('Add Company') }}
                                </x-ui.button.primary>
                            </div>
                        </x-ui.card.header>
                        <x-ui.card.body class="p-0">
                            @if($user->companies->count() > 0)
                                <x-ui.table.base>
                                    <x-slot name="head">
                                        <x-ui.table.head-cell>{{ __('Company') }}</x-ui.table.head-cell>
                                        <x-ui.table.head-cell>{{ __('Country') }}</x-ui.table.head-cell>
                                        <x-ui.table.head-cell>{{ __('Tax Number') }}</x-ui.table.head-cell>
                                        <x-ui.table.head-cell align="right">{{ __('Actions') }}</x-ui.table.head-cell>
                                    </x-slot>
                                    <x-slot name="body">
                                        @foreach($user->companies as $company)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                <x-ui.table.cell>
                                                    <div class="flex items-center">
                                                        <x-ui.avatar name="{{ $company->name }}" size="sm" />
                                                        <div class="ml-3">
                                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                {{ $company->name }}
                                                            </div>
                                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                                {{ $company->email }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </x-ui.table.cell>
                                                <x-ui.table.cell>
                                                    {{ $company->country->name ?? __('N/A') }}
                                                </x-ui.table.cell>
                                                <x-ui.table.cell>
                                                    <span class="font-mono text-sm">{{ $company->tax_number ?? __('N/A') }}</span>
                                                </x-ui.table.cell>
                                                <x-ui.table.action-cell>
                                                    <div class="flex items-center justify-end gap-2">
                                                        <a href="{{ route('admin.companies.show', $company) }}" 
                                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                                           title="{{ __('View') }}">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                        </a>
                                                        <a href="{{ route('admin.companies.edit', $company) }}" 
                                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                                           title="{{ __('Edit') }}">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </x-ui.table.action-cell>
                                            </tr>
                                        @endforeach
                                    </x-slot>
                                </x-ui.table.base>
                            @else
                                <x-ui.table.empty-state>
                                    <x-slot name="icon">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </x-slot>
                                    <x-slot name="title">{{ __('No Companies') }}</x-slot>
                                    <x-slot name="description">{{ __('This user has not created any companies yet.') }}</x-slot>
                                </x-ui.table.empty-state>
                            @endif
                        </x-ui.card.body>
                    </x-ui.card.base>
                </x-ui.tabs.panel>
                
                {{-- Accountant Assignments Tab --}}
                @if($user->is_accountant)
                    <x-ui.tabs.panel name="assignments">
                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                            {{-- Assigned Users Card --}}
                            <x-ui.card.base>
                                <x-ui.card.header>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                                                {{ __('Assigned Users') }}
                                            </h3>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                {{ __('Users this accountant can manage.') }}
                                            </p>
                                        </div>
                                        <x-ui.button.secondary size="sm" href="{{ route('admin.users.assign', $user) }}">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            {{ __('Assign') }}
                                        </x-ui.button.secondary>
                                    </div>
                                </x-ui.card.header>
                                <x-ui.card.body>
                                    @if($user->assignedUsers->count() > 0)
                                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($user->assignedUsers as $assignedUser)
                                                <li class="py-3">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center">
                                                            <x-ui.avatar name="{{ $assignedUser->name }}" size="sm" />
                                                            <div class="ml-3">
                                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                    {{ $assignedUser->name }}
                                                                </p>
                                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                    {{ $assignedUser->email }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <a href="{{ route('admin.users.show', $assignedUser) }}" 
                                                           class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                            {{ __('View') }}
                                                        </a>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-center py-8">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                                {{ __('No users assigned yet.') }}
                                            </p>
                                        </div>
                                    @endif
                                </x-ui.card.body>
                            </x-ui.card.base>
                            
                            {{-- Assigned Companies Card --}}
                            <x-ui.card.base>
                                <x-ui.card.header>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                                                {{ __('Assigned Companies') }}
                                            </h3>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                {{ __('Companies this accountant can manage.') }}
                                            </p>
                                        </div>
                                        <x-ui.button.secondary size="sm" href="{{ route('admin.users.assign-companies', $user) }}">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            {{ __('Assign') }}
                                        </x-ui.button.secondary>
                                    </div>
                                </x-ui.card.header>
                                <x-ui.card.body>
                                    @if($user->assignedCompanies->count() > 0)
                                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($user->assignedCompanies as $assignedCompany)
                                                <li class="py-3">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center">
                                                            <x-ui.avatar name="{{ $assignedCompany->name }}" size="sm" />
                                                            <div class="ml-3">
                                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                    {{ $assignedCompany->name }}
                                                                </p>
                                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                    {{ __('Owner') }}: {{ $assignedCompany->user->name }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <a href="{{ route('admin.companies.show', $assignedCompany) }}" 
                                                           class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                            {{ __('View') }}
                                                        </a>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-center py-8">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                                {{ __('No companies assigned yet.') }}
                                            </p>
                                        </div>
                                    @endif
                                </x-ui.card.body>
                            </x-ui.card.base>
                        </div>
                    </x-ui.tabs.panel>
                @endif
            </x-ui.tabs.panels>
        </x-ui.tabs.base>
    </div>
</x-admin.layout>