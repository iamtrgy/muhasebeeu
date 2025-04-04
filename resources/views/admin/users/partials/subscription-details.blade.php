<!-- Subscription Info Card -->
<x-cards.info-card title="{{ __('Subscription Details') }}">
    @if($user->subscription('default'))
        @php
            $subscription = $user->subscription('default');
            $planId = $subscription->stripe_price;
            
            // Show available environment variables for debugging
            $basicPriceId = env('STRIPE_BASIC_PRICE_ID');
            $proPriceId = env('STRIPE_PRO_PRICE_ID');
            $enterprisePriceId = env('STRIPE_ENTERPRISE_PRICE_ID');
            
            // Determine plan name based on price ID
            $planName = 'Unknown Plan';
            if ($planId == $basicPriceId) {
                $planName = 'Basic Plan';
                $badgeType = 'info';
            } elseif ($planId == $proPriceId) {
                $planName = 'Pro Plan';
                $badgeType = 'primary';
            } elseif ($planId == $enterprisePriceId) {
                $planName = 'Enterprise Plan';
                $badgeType = 'success';
            }
            
            // Determine subscription status
            $status = 'Active';
            $statusBadgeType = 'success';
            
            if ($subscription->onGracePeriod()) {
                $status = 'Canceling';
                $statusBadgeType = 'warning';
            } elseif ($subscription->canceled()) {
                $status = 'Canceled';
                $statusBadgeType = 'danger';
            } elseif ($subscription->onTrial()) {
                $status = 'Trial';
                $statusBadgeType = 'info';
            } elseif ($subscription->hasIncompletePayment()) {
                $status = 'Incomplete';
                $statusBadgeType = 'danger';
            }
        @endphp
        
        <div class="mb-4">
            <x-ui.status-badge type="{{ $badgeType }}" size="md">{{ $planName }}</x-ui.status-badge>
            <x-ui.status-badge type="{{ $statusBadgeType }}" size="md" class="ml-2">{{ $status }}</x-ui.status-badge>
        </div>
        
        <dl class="grid grid-cols-1 gap-x-4 gap-y-6">
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Started') }}</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                    {{ $subscription->created_at ? $subscription->created_at->format('M d, Y') : 'N/A' }}
                </dd>
            </div>
            
            @if($subscription->trial_ends_at)
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Trial Ends') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ $subscription->trial_ends_at->format('M d, Y') }}
                        <span class="text-gray-500 dark:text-gray-400 text-xs">({{ $subscription->trial_ends_at->diffForHumans() }})</span>
                    </dd>
                </div>
            @endif
            
            @if($subscription->ends_at)
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Ends At') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ $subscription->ends_at->format('M d, Y') }}
                        <span class="text-gray-500 dark:text-gray-400 text-xs">({{ $subscription->ends_at->diffForHumans() }})</span>
                    </dd>
                </div>
            @endif
            
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Price ID') }}</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">
                    {{ $subscription->stripe_price ?? 'N/A' }}
                </dd>
            </div>
            
            <div class="pb-4">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Stripe ID') }}</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">
                    {{ $subscription->stripe_id ?? 'N/A' }}
                </dd>
            </div>
        </dl>
        
        <!-- Subscription Actions -->
        <div class="mt-4 flex flex-wrap gap-2">
            <a href="{{ route('admin.users.subscription.manage', $user) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ __('Manage') }}
            </a>
            
            @if($subscription->onGracePeriod())
                <form action="{{ route('admin.users.subscription.update', $user) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="resume">
                    <button type="submit" class="btn btn-primary bg-green-600 hover:bg-green-700 focus:ring-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        {{ __('Resume') }}
                    </button>
                </form>
            @elseif (!$subscription->canceled())
                <form action="{{ route('admin.users.subscription.update', $user) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="cancel">
                    <button type="submit" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('Cancel') }}
                    </button>
                </form>
            @endif
        </div>

        <!-- Subscription Debug Link -->
        <div class="mt-3 border-t border-gray-200 dark:border-gray-700 pt-3">
            <a href="{{ route('admin.users.subscription.debug', $user) }}" class="inline-flex items-center text-sm text-orange-600 hover:text-orange-800 dark:text-orange-400 dark:hover:text-orange-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                {{ __('Debug Subscription') }}
            </a>
        </div>
    @else
        <x-ui.empty-state 
            icon="document" 
            title="{{ __('No Active Subscription') }}" 
            message="{{ __('This user does not have an active subscription.') }}">
            <a href="{{ route('admin.users.subscription.create', $user) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ __('Create Subscription') }}
            </a>
        </x-ui.empty-state>
    @endif
</x-cards.info-card>
