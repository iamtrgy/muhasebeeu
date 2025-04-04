<x-admin-layout>
    <x-slot name="header">
        <x-admin.page-title title="{{ __('Manage Subscription') }}"></x-admin.page-title>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
        <!-- User Profile Header -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center">
                                <span class="text-xl font-bold text-blue-700 dark:text-blue-300">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $user->name }}</h1>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('admin.users.subscription.debug', $user) }}" class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="h-5 w-5 mr-2 text-orange-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                {{ __('Debug Subscription') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Subscription Status -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Current Subscription') }}</h2>
                    
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
                            $status = $subscription->canceled() 
                                ? 'Canceled' 
                                : ($subscription->onTrial() 
                                    ? 'Trial' 
                                    : ($subscription->onGracePeriod() 
                                        ? 'Grace Period' 
                                        : 'Active'));
                        @endphp
                        
                        <div class="flex items-center mb-6">
                            @php
                                $statusClasses = match($status) {
                                    'Active' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                    'Trial' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                    'Grace Period' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                    default => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                };
                            @endphp
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full {{ $statusClasses }}">
                                {{ $status }}
                            </span>
                            <span class="ml-2 px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                {{ $planName }} Plan
                            </span>
                        </div>
                        
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Started') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $subscription->created_at->format('M d, Y') }}
                                </dd>
                            </div>
                            
                            @if($subscription->onTrial())
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Trial Ends') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    @if($subscription->trial_ends_at)
                                        {{ $subscription->trial_ends_at->format('M d, Y') }}
                                    @else
                                        {{ __('No trial end date') }}
                                    @endif
                                </dd>
                            </div>
                            @endif
                            
                            @if($subscription->canceled())
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Cancellation Date') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    @if($subscription->canceled_at)
                                        {{ $subscription->canceled_at->format('M d, Y') }}
                                    @else
                                        {{ now()->format('M d, Y') }}
                                        <span class="text-gray-500 dark:text-gray-400 text-xs">({{ __('No specific cancellation date recorded') }})</span>
                                    @endif
                                </dd>
                            </div>
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Access Until') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    @if($subscription->ends_at)
                                        {{ $subscription->ends_at->format('M d, Y') }}
                                    @else
                                        {{ __('Unknown') }}
                                    @endif
                                </dd>
                            </div>
                            @elseif(!$subscription->onTrial())
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Next Billing Date') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $user->nextBillingDate() ? $user->nextBillingDate()->format('M d, Y') : 'N/A' }}
                                </dd>
                            </div>
                            @endif
                            
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Stripe Subscription ID') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">
                                    {{ $subscription->stripe_id }}
                                </dd>
                            </div>
                            
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Stripe Customer ID') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">
                                    {{ $user->stripe_id }}
                                </dd>
                            </div>
                        </dl>
                        
                        <!-- Status Information Box -->
                        @php
                            $stripeStatus = null;
                            try {
                                if ($subscription->stripe_id) {
                                    $stripe = new \Stripe\StripeClient(config('cashier.secret'));
                                    $stripeSubscription = $stripe->subscriptions->retrieve($subscription->stripe_id);
                                    $stripeStatus = $stripeSubscription->status;
                                }
                            } catch (\Exception $e) {
                                // Silently handle errors
                            }
                        @endphp
                        
                        @php
                            $statusBoxBgClass = 'rounded-lg mt-4 p-4 ';
                            if ($subscription->canceled() && !$subscription->onGracePeriod()) {
                                $statusBoxBgClass .= 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700';
                            } elseif ($subscription->canceled() && $subscription->onGracePeriod()) {
                                $statusBoxBgClass .= 'bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700';
                            } elseif ($stripeStatus === 'incomplete' || $stripeStatus === 'incomplete_expired') {
                                $statusBoxBgClass .= 'bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-700';
                            } else {
                                $statusBoxBgClass .= 'bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700';
                            }
                        @endphp
                        <div class="{{ $statusBoxBgClass }}">
                            @php
                                $titleClass = 'text-sm font-medium mb-2 ';
                                if ($subscription->canceled() && !$subscription->onGracePeriod()) {
                                    $titleClass .= 'text-red-800 dark:text-red-200';
                                } elseif ($subscription->canceled() && $subscription->onGracePeriod()) {
                                    $titleClass .= 'text-yellow-800 dark:text-yellow-200';
                                } elseif ($stripeStatus === 'incomplete' || $stripeStatus === 'incomplete_expired') {
                                    $titleClass .= 'text-orange-800 dark:text-orange-200';
                                } else {
                                    $titleClass .= 'text-blue-800 dark:text-blue-200';
                                }
                            @endphp
                            <h3 class="{{ $titleClass }}">
                                {{ __('Subscription Status Information') }}
                            </h3>
                            
                            @php
                                $textClass = 'text-sm ';
                                if ($subscription->canceled() && !$subscription->onGracePeriod()) {
                                    $textClass .= 'text-red-700 dark:text-red-300';
                                } elseif ($subscription->canceled() && $subscription->onGracePeriod()) {
                                    $textClass .= 'text-yellow-700 dark:text-yellow-300';
                                } elseif ($stripeStatus === 'incomplete' || $stripeStatus === 'incomplete_expired') {
                                    $textClass .= 'text-orange-700 dark:text-orange-300';
                                } else {
                                    $textClass .= 'text-blue-700 dark:text-blue-300';
                                }
                            @endphp
                            <p class="{{ $textClass }}">
                                @if($subscription->canceled() && !$subscription->onGracePeriod())
                                    {{ __('This subscription has been fully canceled and ended. It cannot be resumed. You will need to create a new subscription with a new trial period if desired.') }}
                                @elseif($subscription->canceled() && $subscription->onGracePeriod())
                                    {{ __('This subscription has been canceled but is still in the grace period. The user still has access until the period ends. You can resume this subscription if needed.') }}
                                @elseif($stripeStatus === 'incomplete' || $stripeStatus === 'incomplete_expired')
                                    {{ __('This subscription is in an incomplete state, which typically happens when payment has failed. You should delete this subscription and create a new one.') }}
                                @elseif($subscription->onTrial())
                                    {{ __('This subscription is currently in a trial period.') }}
                                @else
                                    {{ __('This subscription is active and will automatically renew on the next billing date.') }}
                                @endif
                                
                                @if($stripeStatus && $stripeStatus !== 'active')
                                    <br><br>
                                    <strong>{{ __('Stripe Status') }}:</strong> {{ ucfirst($stripeStatus) }}
                                @endif
                            </p>
                        </div>
                        
                        <div class="mt-6 flex flex-col sm:flex-row gap-3">
                            @if($subscription->canceled() && $subscription->onGracePeriod())
                                <form action="{{ route('admin.users.subscription.update', $user) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="resume">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ __('Resume Subscription') }}
                                    </button>
                                </form>
                            @elseif(!$subscription->canceled())
                                <form action="{{ route('admin.users.subscription.update', $user) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="cancel">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ __('Cancel Subscription') }}
                                    </button>
                                </form>
                            @endif
                            
                            @if($stripeStatus === 'incomplete' || $stripeStatus === 'incomplete_expired')
                                <form action="{{ route('admin.users.subscription.update', $user) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="delete_incomplete">
                                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 dark:focus:ring-offset-gray-800">
                                        {{ __('Delete Incomplete Subscription') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    @else
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center">
                            <svg class="h-12 w-12 text-gray-400 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No Active Subscription') }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('This user does not have an active subscription.') }}</p>
                        </div>

                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 mt-6">{{ __('Change Subscription Plan') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($plans as $planId => $planData)
                                @php
                                    $planKey = str_replace('price_', '', $planId);
                                @endphp
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700 flex flex-col">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">{{ $planData['name'] }}</h3>
                                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400 mb-4">
                                            @foreach($planData['features'] as $feature)
                                            <li class="flex items-center">
                                                <svg class="h-5 w-5 text-green-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $feature }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <form action="{{ route('admin.users.subscription.update', $user) }}" method="POST" class="mt-auto">
                                        @csrf
                                        <input type="hidden" name="action" value="subscribe">
                                        <input type="hidden" name="plan" value="{{ $planKey }}">

                                        <div class="mb-4">
                                            <label for="trial_days_{{ $planKey }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trial Period (Days)</label>
                                            <select id="trial_days_{{ $planKey }}" name="trial_days" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-200">
                                                <option value="7">7 days</option>
                                                <option value="14">14 days</option>
                                                <option value="30" selected>30 days</option>
                                                <option value="60">60 days</option>
                                                <option value="90">90 days</option>
                                            </select>
                                        </div>

                                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            {{ __('Start Trial') }}
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        </div>
    </div>
</x-admin-layout> 