<x-admin-layout>
    <x-slot name="header">
        <x-admin.page-title title="{{ __('Subscription Debug') }}"></x-admin.page-title>
    </x-slot>

    <div>
            <!-- Basic User Info -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('User Information') }}</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('User ID') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Name') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->created_at }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscription Info -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Subscription Status') }}</h2>
                    
                    @if($user->subscribed('default'))
                        <div class="mb-4 px-4 py-3 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 rounded-lg">
                            <p class="text-sm font-medium">User has an active subscription</p>
                        </div>
                    @else
                        <div class="mb-4 px-4 py-3 bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200 rounded-lg">
                            <p class="text-sm font-medium">User does not have an active subscription</p>
                        </div>
                    @endif
                    
                    <div class="mt-4">
                        <h3 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('Subscription Methods') }}</h3>
                        <div class="grid grid-cols-2 gap-4 mb-4 px-4 py-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">subscribed()</p>
                                <p class="mt-1 text-sm {{ $user->subscribed() ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $user->subscribed() ? 'true' : 'false' }}
                                </p>
                            </div>
                            
                            @if($user->subscription('default'))
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">onTrial()</p>
                                    <p class="mt-1 text-sm {{ $user->subscription('default')->onTrial() ? 'text-green-600 dark:text-green-400' : 'text-gray-900 dark:text-gray-300' }}">
                                        {{ $user->subscription('default')->onTrial() ? 'true' : 'false' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">canceled()</p>
                                    <p class="mt-1 text-sm {{ $user->subscription('default')->canceled() ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-gray-300' }}">
                                        {{ $user->subscription('default')->canceled() ? 'true' : 'false' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">onGracePeriod()</p>
                                    <p class="mt-1 text-sm {{ $user->subscription('default')->onGracePeriod() ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-900 dark:text-gray-300' }}">
                                        {{ $user->subscription('default')->onGracePeriod() ? 'true' : 'false' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">recurring()</p>
                                    <p class="mt-1 text-sm {{ $user->subscription('default')->recurring() ? 'text-green-600 dark:text-green-400' : 'text-gray-900 dark:text-gray-300' }}">
                                        {{ $user->subscription('default')->recurring() ? 'true' : 'false' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">ended()</p>
                                    <p class="mt-1 text-sm {{ $user->subscription('default')->ended() ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-gray-300' }}">
                                        {{ $user->subscription('default')->ended() ? 'true' : 'false' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">pastDue()</p>
                                    <p class="mt-1 text-sm {{ $user->subscription('default')->pastDue() ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-900 dark:text-gray-300' }}">
                                        {{ $user->subscription('default')->pastDue() ? 'true' : 'false' }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscription Details -->
            @if($user->subscription('default'))
                @php
                    $subscription = $user->subscription('default');
                @endphp
                
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Subscription Data') }}</h2>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Field</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Value</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">id</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $subscription->id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">stripe_id</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 font-mono">{{ $subscription->stripe_id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">stripe_price</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 font-mono">{{ $subscription->stripe_price }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">stripe_status</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $subscription->stripe_status }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">quantity</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $subscription->quantity }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">trial_ends_at</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $subscription->trial_ends_at ?? 'null' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">ends_at</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $subscription->ends_at ?? 'null' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">created_at</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $subscription->created_at }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">updated_at</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $subscription->updated_at }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Stripe API Check -->
                        <h3 class="text-md font-medium text-gray-900 dark:text-gray-100 mt-6 mb-2">{{ __('Stripe API Data') }}</h3>
                        <div class="px-4 py-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            @php
                                try {
                                    if ($subscription->stripe_id) {
                                        $stripe = new \Stripe\StripeClient(config('cashier.secret'));
                                        $stripeSubscription = $stripe->subscriptions->retrieve($subscription->stripe_id);
                                        $stripeStatus = $stripeSubscription->status;
                                        $stripeCurrentPeriodEnd = $stripeSubscription->current_period_end;
                                        $stripeCustomer = $stripeSubscription->customer;
                                        $stripePlanId = $stripeSubscription->items->data[0]->plan->id;
                                        $stripePlanAmount = $stripeSubscription->items->data[0]->plan->amount;
                                        $stripePlanCurrency = $stripeSubscription->items->data[0]->plan->currency;
                                        $stripePlanInterval = $stripeSubscription->items->data[0]->plan->interval;
                                        $stripeFound = true;
                                    } else {
                                        $stripeFound = false;
                                    }
                                } catch (\Exception $e) {
                                    $stripeFound = false;
                                    $stripeError = $e->getMessage();
                                }
                            @endphp
                            
                            @if(isset($stripeFound) && $stripeFound)
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</p>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $stripeStatus }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Current Period End</p>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ date('Y-m-d H:i:s', $stripeCurrentPeriodEnd) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer ID</p>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $stripeCustomer }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Plan ID</p>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $stripePlanId }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Price</p>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ number_format($stripePlanAmount / 100, 2) }} EUR / {{ $stripePlanInterval }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">View in Stripe</p>
                                        <p class="mt-1">
                                            <a href="https://dashboard.stripe.com/subscriptions/{{ $subscription->stripe_id }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 underline">
                                                Open Stripe Dashboard
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            @else
                                <div class="px-4 py-3 bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200 rounded-lg mt-2">
                                    <p class="text-sm font-medium">Could not retrieve Stripe data</p>
                                    @if(isset($stripeError))
                                        <p class="mt-1 text-sm">Error: {{ $stripeError }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Environment Variables -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Environment Configuration') }}</h2>
                        
                        <div class="grid grid-cols-1 gap-4 px-4 py-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">STRIPE_BASIC_PRICE_ID</p>
                                <p class="mt-1 text-sm font-mono {{ env('STRIPE_BASIC_PRICE_ID') ? 'text-gray-900 dark:text-gray-100' : 'text-red-600 dark:text-red-400' }}">
                                    {{ env('STRIPE_BASIC_PRICE_ID') ?: 'Not set' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">STRIPE_PRO_PRICE_ID</p>
                                <p class="mt-1 text-sm font-mono {{ env('STRIPE_PRO_PRICE_ID') ? 'text-gray-900 dark:text-gray-100' : 'text-red-600 dark:text-red-400' }}">
                                    {{ env('STRIPE_PRO_PRICE_ID') ?: 'Not set' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">STRIPE_ENTERPRISE_PRICE_ID</p>
                                <p class="mt-1 text-sm font-mono {{ env('STRIPE_ENTERPRISE_PRICE_ID') ? 'text-gray-900 dark:text-gray-100' : 'text-red-600 dark:text-red-400' }}">
                                    {{ env('STRIPE_ENTERPRISE_PRICE_ID') ?: 'Not set' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">CASHIER_CURRENCY</p>
                                <p class="mt-1 text-sm font-mono {{ config('cashier.currency') ? 'text-gray-900 dark:text-gray-100' : 'text-red-600 dark:text-red-400' }}">
                                    {{ config('cashier.currency') ?: 'Not set' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">CASHIER_CURRENCY_LOCALE</p>
                                <p class="mt-1 text-sm font-mono {{ config('cashier.currency_locale') ? 'text-gray-900 dark:text-gray-100' : 'text-red-600 dark:text-red-400' }}">
                                    {{ config('cashier.currency_locale') ?: 'Not set' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="px-4 py-3 bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 rounded-lg">
                            <p class="text-sm font-medium">No active subscription found for this user</p>
                            <p class="mt-1 text-sm">To see detailed subscription data, the user must have an active subscription.</p>
                        </div>
                        
                        <div class="mt-4">
                            <h3 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('Manage User') }}</h3>
                            <div class="flex space-x-4">
                                <a href="{{ route('admin.users.subscription.manage', $user) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition">
                                    Create Subscription
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout> 