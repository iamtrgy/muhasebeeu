<x-admin.layout 
    title="Users Management"
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('admin.dashboard'), 'first' => true],
        ['title' => __('Users')]
    ]"
>
    <div class="space-y-6">
        <!-- Page Header with Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Manage user accounts, subscriptions, and permissions.
                </p>
            </div>
            <div class="flex gap-3">
                <x-ui.button.secondary href="{{ route('admin.users.create') }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add User
                </x-ui.button.secondary>
                <x-ui.button.primary>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                    Export
                </x-ui.button.primary>
            </div>
        </div>

        <!-- Search and Filters Card -->
        <x-ui.card.base>
            <x-ui.card.body>
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <x-ui.form.input
                            type="search"
                            name="userSearch"
                            id="userSearch"
                            placeholder="Search by name or email..."
                            leadingIcon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>'
                        />
                    </div>
                    <div class="flex gap-2">
                        <x-ui.form.select name="statusFilter" id="statusFilter" placeholder="" value="all">
                            <option value="all">All Users</option>
                            <option value="subscribed">Subscribed</option>
                            <option value="unsubscribed">Not Subscribed</option>
                            <option value="admin">Admins</option>
                            <option value="accountant">Accountants</option>
                        </x-ui.form.select>
                        <x-ui.form.select name="verifiedFilter" id="verifiedFilter" placeholder="" value="all">
                            <option value="all">All Status</option>
                            <option value="verified">Verified</option>
                            <option value="unverified">Unverified</option>
                        </x-ui.form.select>
                    </div>
                </div>
            </x-ui.card.body>
        </x-ui.card.base>

        <!-- Users Table -->
        <x-ui.card.base>
            <x-ui.card.body>
                <x-ui.table.base id="usersTable">
                    <x-slot name="head">
                        <x-ui.table.head-cell>User</x-ui.table.head-cell>
                        <x-ui.table.head-cell>Role</x-ui.table.head-cell>
                        <x-ui.table.head-cell>Subscription</x-ui.table.head-cell>
                        <x-ui.table.head-cell>Companies</x-ui.table.head-cell>
                        <x-ui.table.head-cell>Joined</x-ui.table.head-cell>
                        <x-ui.table.head-cell align="right">Actions</x-ui.table.head-cell>
                    </x-slot>
                    <x-slot name="body">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors user-row"
                                data-name="{{ strtolower($user->name) }}"
                                data-email="{{ strtolower($user->email) }}"
                                data-is-admin="{{ $user->is_admin ? 'admin' : 'user' }}"
                                data-is-accountant="{{ $user->is_accountant ? 'accountant' : 'user' }}"
                                data-is-verified="{{ $user->email_verified_at ? 'verified' : 'unverified' }}"
                                data-is-subscribed="{{ $user->subscription('default') ? 'subscribed' : 'unsubscribed' }}">
                                
                                <x-ui.table.cell>
                                    <div class="flex items-center">
                                        <x-ui.avatar name="{{ $user->name }}" size="sm" />
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $user->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </x-ui.table.cell>
                                
                                <x-ui.table.cell>
                                    <div class="flex flex-col gap-1">
                                        @if($user->is_admin)
                                            <x-ui.badge variant="danger" size="sm">Admin</x-ui.badge>
                                        @elseif($user->is_accountant)
                                            <x-ui.badge variant="warning" size="sm">Accountant</x-ui.badge>
                                        @else
                                            <x-ui.badge variant="secondary" size="sm">User</x-ui.badge>
                                        @endif
                                        
                                        @if($user->email_verified_at)
                                            <span class="inline-flex items-center text-xs text-green-600 dark:text-green-400">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Verified
                                            </span>
                                        @else
                                            <span class="inline-flex items-center text-xs text-amber-600 dark:text-amber-400">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Unverified
                                            </span>
                                        @endif
                                    </div>
                                </x-ui.table.cell>
                                
                                <x-ui.table.cell>
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
                                        <div class="flex flex-col gap-1">
                                            <x-ui.badge variant="success" size="sm">{{ $planName }}</x-ui.badge>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                @if($subscription->onTrial())
                                                    Trial ends {{ $subscription->trial_ends_at ? $subscription->trial_ends_at->diffForHumans() : 'Unknown' }}
                                                @elseif($subscription->canceled())
                                                    Ends {{ $subscription->ends_at ? $subscription->ends_at->diffForHumans() : 'Unknown' }}
                                                @else
                                                    Renews {{ $user->nextBillingDate() ? $user->nextBillingDate()->diffForHumans() : 'N/A' }}
                                                @endif
                                            </span>
                                        </div>
                                    @else
                                        <x-ui.badge variant="secondary" size="sm">No Subscription</x-ui.badge>
                                    @endif
                                </x-ui.table.cell>
                                
                                <x-ui.table.cell>
                                    @php
                                        $companiesCount = $user->companies()->count();
                                    @endphp
                                    @if($companiesCount > 0)
                                        <span class="text-sm text-gray-900 dark:text-gray-100">{{ $companiesCount }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">
                                            {{ Str::plural('company', $companiesCount) }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500 dark:text-gray-400">None</span>
                                    @endif
                                </x-ui.table.cell>
                                
                                <x-ui.table.cell>
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $user->created_at->diffForHumans() }}
                                    </div>
                                </x-ui.table.cell>
                                
                                <x-ui.table.action-cell>
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                           title="View details">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                           title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <x-ui.dropdown.base align="right">
                                            <x-slot name="trigger">
                                                <button class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                    </svg>
                                                </button>
                                            </x-slot>
                                            
                                            <x-slot name="content">
                                                <x-ui.dropdown.item href="{{ route('admin.users.subscription.manage', $user) }}">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                    </svg>
                                                    Manage Subscription
                                                </x-ui.dropdown.item>
                                                
                                                <x-ui.dropdown.divider />
                                                
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-ui.dropdown.item tag="button" type="submit">
                                                        <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        <span class="text-red-600">Delete User</span>
                                                    </x-ui.dropdown.item>
                                                </form>
                                            </x-slot>
                                        </x-ui.dropdown.base>
                                    </div>
                                </x-ui.table.action-cell>
                            </tr>
                        @empty
                            <x-ui.table.empty-state 
                                colspan="6"
                                message="No users found. Try adjusting your search or filter to find what you're looking for."
                            />
                        @endforelse
                    </x-slot>
                </x-ui.table.base>
                
                <!-- Pagination -->
                @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator && $users->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $users->links() }}
                    </div>
                @endif
            </x-ui.card.body>
        </x-ui.card.base>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('userSearch');
            const statusFilter = document.getElementById('statusFilter');
            const verifiedFilter = document.getElementById('verifiedFilter');
            const userTable = document.getElementById('usersTable');
            const userRows = userTable ? userTable.querySelectorAll('tbody tr.user-row') : [];
            const allRows = userTable ? userTable.querySelectorAll('tbody tr') : [];
            let emptyStateRow = null;
            
            // Find the empty state row (the one that's not a user-row)
            allRows.forEach(row => {
                if (!row.classList.contains('user-row')) {
                    emptyStateRow = row;
                }
            });
            
            if (!searchInput && !statusFilter && !verifiedFilter) return;
            
            function filterUsers() {
                const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
                const statusValue = statusFilter ? statusFilter.value : 'all';
                const verifiedValue = verifiedFilter ? verifiedFilter.value : 'all';
                let visibleCount = 0;
                
                userRows.forEach(row => {
                    const name = row.getAttribute('data-name') || '';
                    const email = row.getAttribute('data-email') || '';
                    const isAdmin = row.getAttribute('data-is-admin') === 'admin';
                    const isAccountant = row.getAttribute('data-is-accountant') === 'accountant';
                    const isVerified = row.getAttribute('data-is-verified') === 'verified';
                    const isSubscribed = row.getAttribute('data-is-subscribed') === 'subscribed';
                    
                    // Check search match
                    const matchesSearch = !searchTerm || 
                        name.includes(searchTerm) || 
                        email.includes(searchTerm);
                    
                    // Check status filter
                    let matchesStatus = statusValue === 'all';
                    if (!matchesStatus) {
                        if (statusValue === 'admin') matchesStatus = isAdmin;
                        else if (statusValue === 'accountant') matchesStatus = isAccountant;
                        else if (statusValue === 'subscribed') matchesStatus = isSubscribed;
                        else if (statusValue === 'unsubscribed') matchesStatus = !isSubscribed;
                    }
                    
                    // Check verified filter
                    let matchesVerified = verifiedValue === 'all';
                    if (!matchesVerified) {
                        if (verifiedValue === 'verified') matchesVerified = isVerified;
                        else if (verifiedValue === 'unverified') matchesVerified = !isVerified;
                    }
                    
                    // Show/hide row
                    const visible = matchesSearch && matchesStatus && matchesVerified;
                    row.style.display = visible ? '' : 'none';
                    if (visible) visibleCount++;
                });
                
                // Show empty state if no results
                if (emptyStateRow) {
                    emptyStateRow.style.display = visibleCount === 0 ? '' : 'none';
                }
            }
            
            // Add event listeners
            if (searchInput) searchInput.addEventListener('input', filterUsers);
            if (statusFilter) statusFilter.addEventListener('change', filterUsers);
            if (verifiedFilter) verifiedFilter.addEventListener('change', filterUsers);
            
            // Initial filter
            filterUsers();
        });
    </script>
@endpush

</x-admin.layout> 