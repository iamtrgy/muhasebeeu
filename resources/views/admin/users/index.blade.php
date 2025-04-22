<x-admin-layout>
    <x-slot name="header">
        <x-admin.page-title title="{{ __('Users Management') }}">
            <!-- Add actions if needed -->
        </x-admin.page-title>
    </x-slot>

    <div class="py-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    <!-- Search and Filter Controls -->
                    <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <x-icons name="search" class="w-4 h-4 text-gray-500 dark:text-gray-400" />
                            </div>
                            <input type="search" id="userSearch" placeholder="{{ __('Search users...') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <select id="statusFilter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="all">{{ __('All Users') }}</option>
                                <option value="subscribed">{{ __('Subscribed') }}</option>
                                <option value="unsubscribed">{{ __('Not Subscribed') }}</option>
                                <option value="admin">{{ __('Admins') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Users Table -->
                    <x-admin.table id="usersTable">
                        <x-slot name="header">
                            <x-admin.table.tr>
                                <x-admin.table.th>{{ __('User') }}</x-admin.table.th>
                                <x-admin.table.th>{{ __('Email / Status') }}</x-admin.table.th>
                                <x-admin.table.th>{{ __('Subscription') }}</x-admin.table.th>
                                <x-admin.table.th>{{ __('Created') }}</x-admin.table.th>
                                <x-admin.table.th class="relative">
                                    <span class="sr-only">{{ __('Actions') }}</span>
                                </x-admin.table.th>
                            </x-admin.table.tr>
                        </x-slot>

                        @foreach($users as $user)
                            <x-admin.table.tr
                                class="user-row"
                                data-name="{{ strtolower($user->name) }}"
                                data-email="{{ strtolower($user->email) }}"
                                data-is-admin="{{ $user->is_admin ? 'admin' : 'user' }}"
                                data-is-subscribed="{{ $user->subscription('default') ? 'subscribed' : 'unsubscribed' }}">

                                <x-admin.table.td>
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center">
                                            <span class="text-lg font-medium text-blue-700 dark:text-blue-300">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100 user-name">
                                                {{ $user->name }}
                                            </div>
                                            @if($user->is_admin)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 user-admin-badge">
                                                    {{ __('Admin') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </x-admin.table.td>
                                <x-admin.table.td>
                                    <div class="text-sm text-gray-900 dark:text-gray-100 user-email">{{ $user->email }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        @if($user->email_verified_at)
                                                    <span class="inline-flex items-center">
                                                        <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                        {{ __('Verified') }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center">
                                                        <svg class="w-4 h-4 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                                        {{ __('Not Verified') }}
                                                    </span>
                                                @endif
                                            </div>
                                </x-admin.table.td>
                                <x-admin.table.td>
                                    @if($user->subscription('default'))
                                        @php
                                            $subscription = $user->subscription('default');
                                            $planId = $subscription->stripe_price;
                                            $planName = match($planId) {
                                                env('STRIPE_BASIC_PRICE_ID') => 'Basic',
                                                env('STRIPE_PRO_PRICE_ID') => 'Pro',
                                                env('STRIPE_ENTERPRISE_PRICE_ID') => 'Enterprise',
                                                default => 'Unknown'
                                            };
                                        @endphp
                                        <div>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 user-subscription-badge">
                                                {{ $planName }}
                                            </span>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                @if($subscription->onTrial())
                                                    {{ __('Trial ends') }}: 
                                                    @if($subscription->trial_ends_at)
                                                        {{ $subscription->trial_ends_at->format('M d, Y') }}
                                                    @else
                                                        {{ __('Unknown') }}
                                                    @endif
                                                @elseif($subscription->canceled())
                                                    {{ __('Ends') }}: 
                                                    @if($subscription->ends_at)
                                                        {{ $subscription->ends_at->format('M d, Y') }}
                                                    @else
                                                        {{ __('Unknown') }}
                                                    @endif
                                                @else
                                                    {{ __('Next billing') }}: {{ $user->nextBillingDate() ? $user->nextBillingDate()->format('M d, Y') : 'N/A' }}
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            {{ __('No Active Subscription') }}
                                        </span>
                                    @endif
                                </x-admin.table.td>
                                <x-admin.table.td>
                                    {{ $user->created_at->format('M d, Y') }}
                                    <div class="text-xs">{{ $user->created_at->diffForHumans() }}</div>
                                </x-admin.table.td>
                                <x-admin.table.td class="text-right">
                                    <div class="flex justify-end items-center space-x-3">
                                        <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            <x-icons name="view" class="h-5 w-5" />
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            <x-icons name="edit" class="h-5 w-5" />
                                        </a>
                                        <button onclick="openUserActionsMenu('{{ $user->id }}')" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300 focus:outline-none">
                                            <x-icons name="more" class="h-5 w-5" />
                                        </button>
                                    </div>
                                </x-admin.table.td>
                            </x-admin.table.tr>
                        @endforeach
                    </x-admin.table>

                        <!-- Empty State -->
                        <div id="noUsersFound" class="hidden py-8 text-center">
                            <x-icons name="user" class="mx-auto h-12 w-12 text-gray-400" />
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No users found') }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Try adjusting your search or filter to find what you\'re looking for.') }}</p>
                        </div>

                        @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="mt-4">
                                {{ $users->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Actions Menu (hidden by default) -->
    <div id="userActionsMenu" class="hidden fixed inset-0 z-30" x-data="{ open: false }" x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" @click="open = false"></div>
        <div class="fixed bottom-0 inset-x-0 pb-2 sm:pb-5">
            <div class="mx-auto max-w-md px-2 sm:px-4">
                <div class="rounded-lg bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-start justify-between">
                            <div class="ml-3 w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('User Actions') }}</p>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex">
                                <button @click="open = false" class="bg-white dark:bg-gray-800 rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <span class="sr-only">{{ __('Close') }}</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <a href="#" id="viewUserDetailsLink" class="rounded-md bg-white dark:bg-gray-700 px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                {{ __('View Details') }}
                            </a>
                            <a href="#" id="manageSubscriptionLink" class="rounded-md bg-blue-50 dark:bg-blue-900 px-4 py-3 text-sm font-medium text-blue-700 dark:text-blue-200 shadow-sm hover:bg-blue-100 dark:hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                {{ __('Manage Subscription') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    @push('scripts')
    <script>
        // Simplified user search that works with any structure
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Initializing user search...');
            
            // Store references to key elements
            let searchInput = null;
            let statusFilter = null;
            let userTable = null;
            let emptyStateMessage = null;
            let userRows = [];
            
            // 1. Find search input and status filter
            searchInput = document.querySelector('input[type="search"]') || 
                         document.querySelector('input[placeholder*="Search"]') ||
                         document.getElementById('userSearch');
                         
            statusFilter = document.getElementById('statusFilter');
            
            // 2. Find the users table
            userTable = document.getElementById('usersTable') ||
                       document.querySelector('table');
            
            // 3. Find all data rows
            if (userTable) {
                // Skip header row if it exists
                const allRows = userTable.querySelectorAll('tr');
                if (allRows.length > 0) {
                    // Check if first row is a header
                    const firstRow = allRows[0];
                    if (firstRow.querySelector('th')) {
                        // Skip header row
                        userRows = Array.from(allRows).slice(1);
                    } else {
                        userRows = Array.from(allRows);
                    }
                }
            }
            
            // 4. Find empty state message
            emptyStateMessage = document.querySelector('[id*="NoUsers"], [id*="noUsers"], .empty-state') ||
                               Array.from(document.querySelectorAll('div')).find(el => {
                                 const text = el.textContent.trim();
                                 return text === 'No users found' || text.includes('No users found');
                               });
            
            // Log what we found for debugging
            console.log('Search input found:', !!searchInput);
            console.log('Status filter found:', !!statusFilter);
            console.log('User table found:', !!userTable);
            console.log('User rows found:', userRows.length);
            console.log('Empty state found:', !!emptyStateMessage);
            
            // Hide empty state initially
            if (emptyStateMessage) {
                emptyStateMessage.style.display = 'none';
            }
            
            // Set up search functionality if we have both input and rows
            if ((searchInput || statusFilter) && userRows.length > 0) {
                // Add event listeners
                if (searchInput) {
                    ['input', 'search', 'keyup'].forEach(event => {
                        searchInput.addEventListener(event, doSearch);
                    });
                }
                
                if (statusFilter) {
                    statusFilter.addEventListener('change', doSearch);
                }
                
                // Search function
                function doSearch() {
                    const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
                    const statusValue = statusFilter ? statusFilter.value : 'all';
                    let visibleCount = 0;
                    
                    // Don't log every row, just the search term
                    console.log('Searching for:', searchTerm || '(empty)', 'Filter:', statusValue);
                    
                    // Loop through rows and filter
                    userRows.forEach(row => {
                        const rowText = row.textContent.toLowerCase();
                        
                        // Check if matches search
                        const matchesSearch = !searchInput || searchTerm === '' || rowText.includes(searchTerm);
                        
                        // Check if matches status filter
                        let matchesFilter = true;
                        if (statusFilter && statusValue !== 'all') {
                            const isAdmin = row.querySelector('[class*="admin-badge"]') !== null;
                            const hasSubscription = row.querySelector('[class*="subscription-badge"]') !== null;
                            
                            if (statusValue === 'admin') {
                                matchesFilter = isAdmin;
                            } else if (statusValue === 'subscribed') {
                                matchesFilter = hasSubscription;
                            } else if (statusValue === 'unsubscribed') {
                                matchesFilter = !hasSubscription;
                            }
                        }
                        
                        // Show/hide row based on both conditions
                        const visible = matchesSearch && matchesFilter;
                        row.style.display = visible ? '' : 'none';
                        
                        // Count visible rows
                        if (visible) visibleCount++;
                    });
                    
                    // Show/hide empty state message
                    if (emptyStateMessage) {
                        emptyStateMessage.style.display = visibleCount === 0 ? 'block' : 'none';
                    }
                    
                    console.log('Search complete. Visible users:', visibleCount);
                }
                
                // Initial search (in case there's text already in input)
                doSearch();
            }
        });
    </script>
    <script>
        // User actions menu functionality
        function openUserActionsMenu(userId) {
            const menu = document.getElementById('userActionsMenu');
            const viewDetailsLink = document.getElementById('viewUserDetailsLink');
            const manageSubscriptionLink = document.getElementById('manageSubscriptionLink');
            
            if (menu && viewDetailsLink && manageSubscriptionLink) {
                viewDetailsLink.href = `/admin/users/${userId}`;
                manageSubscriptionLink.href = `/admin/users/${userId}/subscription`;
                menu.__x.$data.open = true;
            }
        }
    </script>
    </script>
@endpush

</x-admin-layout> 