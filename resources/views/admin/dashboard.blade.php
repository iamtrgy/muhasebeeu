<x-admin.layout 
    title="Admin Dashboard"
    :breadcrumbs="[['title' => __('Dashboard'), 'first' => true]]"
>
<div x-data="{ fileData: null }">
    <!-- File Preview Modal -->
    <x-ui.modal.base name="file-preview" maxWidth="4xl">
        <div class="text-center" x-data="{ currentFile: null }" 
             x-on:file-preview-data.window="currentFile = $event.detail">
            <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100" x-text="currentFile?.name || 'File Preview'"></h3>
            
            {{-- Image Preview --}}
            <div x-show="currentFile && ['image/jpeg', 'image/png', 'image/gif'].includes(currentFile.type)">
                <div class="max-w-full max-h-96 overflow-auto">
                    <img x-bind:src="currentFile?.previewUrl" x-bind:alt="currentFile?.name" class="max-w-full h-auto rounded-lg">
                </div>
            </div>

            {{-- PDF Preview --}}
            <div x-show="currentFile && currentFile.type === 'application/pdf'">
                <div class="w-full h-96">
                    <iframe x-bind:src="currentFile?.previewUrl" class="w-full h-full border-0 rounded-lg"></iframe>
                </div>
            </div>

            {{-- Text Preview --}}
            <div x-show="currentFile && currentFile.type === 'text/plain'">
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 text-left max-h-96 overflow-auto">
                    <pre x-text="currentFile?.content || 'Loading...'" class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap"></pre>
                </div>
            </div>

            {{-- File Notes --}}
            <div x-show="currentFile?.notes" class="mt-4 text-left">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes:</h4>
                <p x-text="currentFile?.notes" class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 rounded-lg p-3"></p>
            </div>

            {{-- Actions --}}
            <div class="mt-6 flex justify-end gap-3">
                <x-ui.button.secondary x-on:click="$dispatch('close-modal', 'file-preview')">
                    Close
                </x-ui.button.secondary>
                <x-ui.button.primary x-bind:href="currentFile?.downloadUrl" tag="a" download>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download
                </x-ui.button.primary>
            </div>
        </div>
    </x-ui.modal.base>

    <div class="space-y-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Active Subscriptions Card -->
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="px-4 pb-4 pt-2" noPadding>
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Active Subscriptions
                            </div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                                {{ $stats['active_subscriptions'] }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $stats['subscription_rate'] }}% conversion rate
                            </div>
                        </div>
                        <div class="flex-shrink-0 bg-emerald-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Monthly Revenue Card -->
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="px-4 pb-4 pt-2" noPadding>
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Monthly Revenue
                            </div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                                ${{ number_format($stats['monthly_revenue'], 2) }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                ${{ $stats['avg_revenue_per_user'] }} avg per user
                            </div>
                        </div>
                        <div class="flex-shrink-0 bg-indigo-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Trial Users Card -->
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="px-4 pb-4 pt-2" noPadding>
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Trial Users
                            </div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                                {{ $stats['trial_subscriptions'] }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ count($stats['subscriptions_expiring_soon']) }} expiring soon
                            </div>
                        </div>
                        <div class="flex-shrink-0 bg-amber-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Total Users Card -->
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="px-4 pb-4 pt-2" noPadding>
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Total Users
                            </div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                                {{ $stats['total_users'] }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $stats['total_companies'] }} companies, {{ $stats['total_files'] }} files
                            </div>
                        </div>
                        <div class="flex-shrink-0 bg-gray-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        </div>

        <!-- Subscription Insights -->
        {{-- Always show subscription insights section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Subscriptions Expiring Soon -->
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.header>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-amber-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Expiring Soon</h3>
                    </div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Users with trials ending in 7 days</p>
                </x-ui.card.header>
                <x-ui.card.body>
                    @if(count($stats['subscriptions_expiring_soon']) > 0)
                    <div class="space-y-4">
                        @foreach($stats['subscriptions_expiring_soon']->take(5) as $user)
                            <div class="flex items-center justify-between p-3 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800">
                                <div class="flex items-center space-x-3">
                                    <x-ui.avatar name="{{ $user->name }}" size="sm" />
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @php $subscription = $user->subscriptions->first(); @endphp
                                    @if($subscription && $subscription->trial_ends_at)
                                        <p class="text-xs font-medium text-amber-600 dark:text-amber-400">
                                            {{ $subscription->trial_ends_at->diffForHumans() }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $subscription->trial_ends_at->format('M d, Y') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No expiring trials</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">All subscriptions are healthy.</p>
                    </div>
                    @endif
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Recent Subscription Activity -->
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.header>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Recent Activity</h3>
                    </div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Latest subscription changes</p>
                </x-ui.card.header>
                <x-ui.card.body>
                    @if(count($stats['recent_subscriptions']) > 0)
                    <div class="space-y-4">
                        @foreach($stats['recent_subscriptions']->take(5) as $subscription)
                            <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <x-ui.avatar name="{{ $subscription->user->name }}" size="sm" />
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $subscription->user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $subscription->user->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <x-ui.badge variant="{{ $subscription->stripe_status === 'active' ? 'success' : ($subscription->stripe_status === 'canceled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($subscription->stripe_status) }}
                                    </x-ui.badge>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $subscription->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No recent activity</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No subscription changes yet.</p>
                    </div>
                    @endif
                </x-ui.card.body>
            </x-ui.card.base>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Users -->
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.header>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Recent Users</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Latest user registrations</p>
                </x-ui.card.header>
                <x-ui.card.body>
                    @if(count($stats['recent_users']) > 0)
                        <div class="space-y-4">
                            @foreach($stats['recent_users'] as $user)
                                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <x-ui.avatar name="{{ $user->name }}" size="sm" />
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                                           title="View user">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <x-ui.button.secondary size="sm" href="{{ route('admin.users.index') }}" class="w-full">
                                View All Users
                            </x-ui.button.secondary>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No recent users</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No users have registered recently.</p>
                        </div>
                    @endif
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Recent Companies -->
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.header>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Recent Companies</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Latest company registrations</p>
                </x-ui.card.header>
                <x-ui.card.body>
                    @if(count($stats['recent_companies']) > 0)
                        <div class="space-y-4">
                            @foreach($stats['recent_companies'] as $company)
                                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 bg-emerald-100 dark:bg-emerald-800 rounded-lg p-2">
                                            <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $company->name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Owner: {{ $company->user->name }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $company->created_at->diffForHumans() }}</span>
                                        <a href="{{ route('admin.companies.show', $company) }}" 
                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                                           title="View company">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <x-ui.button.secondary size="sm" href="{{ route('admin.companies.index') }}" class="w-full">
                                View All Companies
                            </x-ui.button.secondary>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No recent companies</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No companies have been registered recently.</p>
                        </div>
                    @endif
                </x-ui.card.body>
            </x-ui.card.base>
        </div>
        
        <!-- Recent Files -->
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Recent Files</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Latest file uploads across the system ({{ count($stats['recent_files']) }} files)</p>
            </x-ui.card.header>
            <x-ui.card.body>
                @if(count($stats['recent_files']) > 0)
                    <x-ui.table.base>
                        <x-slot name="head">
                            <x-ui.table.head-cell>File Name</x-ui.table.head-cell>
                            <x-ui.table.head-cell>Folder</x-ui.table.head-cell>
                            <x-ui.table.head-cell>Company</x-ui.table.head-cell>
                            <x-ui.table.head-cell>Size</x-ui.table.head-cell>
                            <x-ui.table.head-cell>Date</x-ui.table.head-cell>
                            <x-ui.table.head-cell align="right">Actions</x-ui.table.head-cell>
                        </x-slot>
                        <x-slot name="body">
                            @foreach($stats['recent_files'] as $file)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <x-ui.table.cell>
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                @php
                                                    $previewableTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain'];
                                                    $isPreviewable = in_array($file->mime_type, $previewableTypes);
                                                @endphp
                                                @if($isPreviewable)
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 cursor-pointer" 
                                                         title="Click to preview ({{ $file->mime_type }})"
                                                         x-on:click="
                                                             $dispatch('file-preview-data', {
                                                                 name: '{{ $file->original_name }}',
                                                                 type: '{{ $file->mime_type }}',
                                                                 previewUrl: '{{ route('admin.files.preview', $file->id) }}',
                                                                 downloadUrl: '{{ route('admin.files.download', $file->id) }}',
                                                                 notes: '{{ addslashes($file->notes ?? '') }}'
                                                             });
                                                             $dispatch('open-modal', 'file-preview')
                                                         ">
                                                        {{ $file->original_name }}
                                                    </div>
                                                @else
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $file->original_name }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        @if($file->folder)
                                            <a href="{{ route('admin.folders.show', $file->folder) }}" class="text-sm text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                                                {{ $file->folder->name }}
                                            </a>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">(No folder)</span>
                                        @endif
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        @if($file->company)
                                            <a href="{{ route('admin.companies.show', $file->company) }}" class="text-sm text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                                                {{ $file->company->name }}
                                            </a>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">N/A</span>
                                        @endif
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($file->size / 1024, 2) }} KB</span>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $file->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $file->created_at->diffForHumans() }}
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.action-cell>
                                        @if(in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain']))
                                            <button type="button" 
                                                 class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                                 title="Preview"
                                                 x-on:click="
                                                     $dispatch('file-preview-data', {
                                                         name: '{{ $file->original_name }}',
                                                         type: '{{ $file->mime_type }}',
                                                         previewUrl: '{{ route('admin.files.preview', $file) }}',
                                                         downloadUrl: '{{ route('admin.files.download', $file) }}',
                                                         notes: '{{ addslashes($file->notes ?? '') }}'
                                                     });
                                                     $dispatch('open-modal', 'file-preview')
                                                 ">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        @endif
                                        <a href="{{ route('admin.files.download', $file) }}" 
                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                           title="Download">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </a>
                                    </x-ui.table.action-cell>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-ui.table.base>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No recent files found.</p>
                    </div>
                @endif
            </x-ui.card.body>
        </x-ui.card.base>
    </div>
</div> {{-- End Alpine.js context --}}
</x-admin.layout>