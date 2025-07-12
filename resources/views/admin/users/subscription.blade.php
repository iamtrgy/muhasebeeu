<x-admin.layout 
    title="{{ __('Manage Subscription') }} - {{ $user->name }}"
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('admin.dashboard'), 'first' => true],
        ['title' => __('Users'), 'href' => route('admin.users.index')],
        ['title' => $user->name, 'href' => route('admin.users.show', $user)],
        ['title' => __('Subscription')]
    ]"
>
    <div class="space-y-6">
        @if(session('success'))
            <x-ui.alert variant="success">{{ session('success') }}</x-ui.alert>
        @endif

        @if(session('error'))
            <x-ui.alert variant="danger">{{ session('error') }}</x-ui.alert>
        @endif

        @if($errors->any())
            <x-ui.alert variant="danger">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-ui.alert>
        @endif
        <!-- User Profile Header -->
        <x-ui.card.base>
            <x-ui.card.body>
                <div class="flex items-center space-x-4">
                    <x-ui.avatar name="{{ $user->name }}" size="md" />
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $user->name }}</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                    </div>
                </div>
            </x-ui.card.body>
        </x-ui.card.base>

        <!-- Current Subscription Status -->
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Current Subscription') }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Manage the user\'s subscription status and billing information.') }}</p>
            </x-ui.card.header>
            <x-ui.card.body>
                    
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
                            
                            // Check Stripe status first
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
                            
                            // Determine status based on Stripe status and local state
                            if ($stripeStatus === 'canceled') {
                                $status = 'Canceled';
                            } elseif ($stripeStatus === 'incomplete' || $stripeStatus === 'incomplete_expired') {
                                $status = 'Incomplete';
                            } elseif ($subscription->canceled()) {
                                $status = 'Canceled';
                            } elseif ($subscription->onTrial()) {
                                $status = 'Trial';
                            } elseif ($subscription->onGracePeriod()) {
                                $status = 'Grace Period';
                            } else {
                                $status = 'Active';
                            }
                        @endphp
                        
                        <div class="flex items-center gap-2 mb-6">
                            @php
                                $statusVariant = match($status) {
                                    'Active' => 'success',
                                    'Trial' => 'primary', 
                                    'Grace Period' => 'warning',
                                    'Incomplete' => 'warning',
                                    default => 'danger',
                                };
                            @endphp
                            <x-ui.badge variant="{{ $statusVariant }}">{{ $status }}</x-ui.badge>
                            <x-ui.badge variant="secondary">{{ $planName }} Plan</x-ui.badge>
                        </div>
                        
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Started') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $subscription->created_at->format('M d, Y') }}
                                </dd>
                            </div>
                            
                            @if($subscription->onTrial())
                            <div>
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
                            <div>
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
                            <div>
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
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Next Billing Date') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $user->nextBillingDate() ? $user->nextBillingDate()->format('M d, Y') : 'N/A' }}
                                </dd>
                            </div>
                            @endif
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Stripe Subscription ID') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">
                                    {{ $subscription->stripe_id }}
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Stripe Customer ID') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">
                                    {{ $user->stripe_id }}
                                </dd>
                            </div>
                        </dl>
                        
                        <!-- Status Information Box -->
                        @php
                            $statusBoxBgClass = 'rounded-lg mt-4 p-4 ';
                            if ($stripeStatus === 'canceled' || ($subscription->canceled() && !$subscription->onGracePeriod())) {
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
                                if ($stripeStatus === 'canceled' || ($subscription->canceled() && !$subscription->onGracePeriod())) {
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
                                if ($stripeStatus === 'canceled' || ($subscription->canceled() && !$subscription->onGracePeriod())) {
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
                                @if($stripeStatus === 'canceled' || ($subscription->canceled() && !$subscription->onGracePeriod()))
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
                                
                                @if($stripeStatus && $stripeStatus !== 'active' && $stripeStatus !== 'trialing')
                                    <br><br>
                                    <strong>{{ __('Stripe Status') }}:</strong> {{ ucfirst($stripeStatus) }}
                                @endif
                            </p>
                        </div>
                        
                        <div class="mt-6 flex flex-col sm:flex-row gap-3">
                            @if($stripeStatus === 'canceled' || ($subscription->canceled() && !$subscription->onGracePeriod()))
                                {{-- Fully canceled subscription - cannot resume --}}
                            @elseif($subscription->canceled() && $subscription->onGracePeriod())
                                <form action="{{ route('admin.users.subscription.update', $user) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="resume">
                                    <x-ui.button.primary type="submit" class="bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500">
                                        {{ __('Resume Subscription') }}
                                    </x-ui.button.primary>
                                </form>
                            @elseif(!$subscription->canceled() && $stripeStatus !== 'canceled')
                                <form action="{{ route('admin.users.subscription.update', $user) }}" method="POST" onsubmit="this.querySelector('button').disabled = true; this.querySelector('button').innerHTML = '{{ __('Canceling...') }}';">
                                    @csrf
                                    <input type="hidden" name="action" value="cancel">
                                    <x-ui.button.danger type="submit">
                                        {{ __('Cancel Subscription') }}
                                    </x-ui.button.danger>
                                </form>
                            @endif
                            
                            @if($stripeStatus === 'incomplete' || $stripeStatus === 'incomplete_expired')
                                <form action="{{ route('admin.users.subscription.update', $user) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="delete_incomplete">
                                    <x-ui.button.secondary type="submit" class="bg-amber-600 hover:bg-amber-700 focus:ring-amber-500 text-white">
                                        {{ __('Delete Incomplete Subscription') }}
                                    </x-ui.button.secondary>
                                </form>
                            @endif
                            
                            {{-- Show sync button if there's a mismatch --}}
                            @if($subscription && $stripeStatus && 
                                (($stripeStatus === 'canceled' && !$subscription->canceled()) || 
                                 ($stripeStatus === 'active' && $subscription->canceled())))
                                <form action="{{ route('admin.users.subscription.update', $user) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="sync">
                                    <x-ui.button.secondary type="submit">
                                        {{ __('Sync with Stripe') }}
                                    </x-ui.button.secondary>
                                </form>
                            @endif
                            
                            {{-- Show delete button if subscription doesn't exist in Stripe --}}
                            @if($subscription && !$stripeStatus)
                                <form action="{{ route('admin.users.subscription.update', $user) }}" method="POST" 
                                      onsubmit="return confirm('This will delete the local subscription record. Are you sure?');">
                                    @csrf
                                    <input type="hidden" name="action" value="delete_orphaned">
                                    <x-ui.button.danger type="submit">
                                        {{ __('Delete Orphaned Subscription') }}
                                    </x-ui.button.danger>
                                </form>
                            @endif
                        </div>
                        
                        {{-- Show create new subscription option for fully canceled subscriptions --}}
                        @if($stripeStatus === 'canceled' || ($subscription->canceled() && !$subscription->onGracePeriod()))
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                                <h4 class="text-base font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Create New Subscription') }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">{{ __('This subscription is fully canceled. You can create a new subscription with a trial period.') }}</p>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    @foreach($plans as $planId => $planData)
                                        @php
                                            $planKey = str_replace('price_', '', $planId);
                                        @endphp
                                        <x-ui.card.base class="flex flex-col h-full">
                                            <x-ui.card.body class="flex flex-col h-full">
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
                                                    <x-ui.form.select
                                                        name="trial_days"
                                                        id="trial_days_{{ $planKey }}"
                                                        label="Trial Period (Days)"
                                                        value="30"
                                                    >
                                                        <option value="7">7 days</option>
                                                        <option value="14">14 days</option>
                                                        <option value="30" selected>30 days</option>
                                                        <option value="60">60 days</option>
                                                        <option value="90">90 days</option>
                                                    </x-ui.form.select>
                                                </div>

                                                <x-ui.button.primary type="submit" fullWidth>
                                                    {{ __('Start New Trial') }}
                                                </x-ui.button.primary>
                                            </form>
                                            </x-ui.card.body>
                                        </x-ui.card.base>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No Active Subscription') }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('This user does not have an active subscription.') }}</p>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                            <h4 class="text-base font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Available Plans') }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($plans as $planId => $planData)
                                @php
                                    $planKey = str_replace('price_', '', $planId);
                                @endphp
                                <x-ui.card.base class="flex flex-col h-full">
                                    <x-ui.card.body class="flex flex-col h-full">
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
                                            <x-ui.form.select
                                                name="trial_days"
                                                id="trial_days_{{ $planKey }}"
                                                label="Trial Period (Days)"
                                                value="30"
                                            >
                                                <option value="7">7 days</option>
                                                <option value="14">14 days</option>
                                                <option value="30">30 days</option>
                                                <option value="60">60 days</option>
                                                <option value="90">90 days</option>
                                            </x-ui.form.select>
                                        </div>

                                        <x-ui.button.primary type="submit" fullWidth>
                                            {{ __('Start Trial') }}
                                        </x-ui.button.primary>
                                    </form>
                                    </x-ui.card.body>
                                </x-ui.card.base>
                            @endforeach
                        </div>
                        </div>
                    @endif
            </x-ui.card.body>
        </x-ui.card.base>
    </div>
</x-admin.layout> 