<x-user.layout 
    title="Subscription Debug"
    :breadcrumbs="[
        ['title' => 'Dashboard', 'href' => route('user.dashboard'), 'first' => true],
        ['title' => 'Subscription Debug']
    ]"
>
    <div class="space-y-6">
        @php
            $user = auth()->user();
            $subscription = $user->subscription('default');
            $stripeStatus = null;
            $stripeError = null;
            
            // Try to get Stripe status
            if ($subscription && $subscription->stripe_id) {
                try {
                    $stripe = new \Stripe\StripeClient(config('cashier.secret'));
                    $stripeSubscription = $stripe->subscriptions->retrieve($subscription->stripe_id);
                    $stripeStatus = $stripeSubscription;
                } catch (\Exception $e) {
                    $stripeError = $e->getMessage();
                }
            }
        @endphp

        <!-- User Info -->
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg font-medium">User Information</h3>
            </x-ui.card.header>
            <x-ui.card.body>
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Stripe Customer ID</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $user->stripe_id ?: 'Not Set' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Is Admin</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->is_admin ? 'Yes' : 'No' }}</dd>
                    </div>
                </dl>
            </x-ui.card.body>
        </x-ui.card.base>

        <!-- Subscription Methods Debug -->
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg font-medium">Subscription Check Methods</h3>
            </x-ui.card.header>
            <x-ui.card.body>
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">hasActiveSubscription('default')</dt>
                        <dd class="mt-1">
                            @if($user->hasActiveSubscription('default'))
                                <x-ui.badge variant="success">TRUE</x-ui.badge>
                            @else
                                <x-ui.badge variant="danger">FALSE</x-ui.badge>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">subscribed('default')</dt>
                        <dd class="mt-1">
                            @if($user->subscribed('default'))
                                <x-ui.badge variant="success">TRUE</x-ui.badge>
                            @else
                                <x-ui.badge variant="danger">FALSE</x-ui.badge>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">onTrial('default')</dt>
                        <dd class="mt-1">
                            @if($user->onTrial('default'))
                                <x-ui.badge variant="primary">TRUE</x-ui.badge>
                            @else
                                <x-ui.badge variant="secondary">FALSE</x-ui.badge>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">onGracePeriod('default')</dt>
                        <dd class="mt-1">
                            @if($user->subscription('default') && $user->subscription('default')->onGracePeriod())
                                <x-ui.badge variant="warning">TRUE</x-ui.badge>
                            @else
                                <x-ui.badge variant="secondary">FALSE</x-ui.badge>
                            @endif
                        </dd>
                    </div>
                </dl>
            </x-ui.card.body>
        </x-ui.card.base>

        <!-- Local Subscription Data -->
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg font-medium">Local Database Subscription</h3>
            </x-ui.card.header>
            <x-ui.card.body>
                @if($subscription)
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $subscription->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Type/Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $subscription->type ?? 'Not Set' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Stripe Subscription ID</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $subscription->stripe_id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Stripe Price</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $subscription->stripe_price }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Stripe Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $subscription->stripe_status }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created At</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $subscription->created_at->format('Y-m-d H:i:s') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Trial Ends At</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $subscription->trial_ends_at ? $subscription->trial_ends_at->format('Y-m-d H:i:s') : 'Not on trial' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Ends At</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $subscription->ends_at ? $subscription->ends_at->format('Y-m-d H:i:s') : 'Active' }}</dd>
                        </div>
                    </dl>
                @else
                    <p class="text-gray-500">No subscription found in local database.</p>
                @endif
            </x-ui.card.body>
        </x-ui.card.base>

        <!-- Stripe Subscription Data -->
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg font-medium">Stripe Subscription Data</h3>
            </x-ui.card.header>
            <x-ui.card.body>
                @if($stripeError)
                    <x-ui.alert variant="danger">
                        Error fetching Stripe data: {{ $stripeError }}
                    </x-ui.alert>
                @elseif($stripeStatus)
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                @php
                                    $variant = match($stripeStatus->status) {
                                        'active' => 'success',
                                        'trialing' => 'primary',
                                        'canceled' => 'danger',
                                        'incomplete', 'incomplete_expired' => 'warning',
                                        default => 'secondary'
                                    };
                                @endphp
                                <x-ui.badge variant="{{ $variant }}">{{ $stripeStatus->status }}</x-ui.badge>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Current Period Start</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ date('Y-m-d H:i:s', $stripeStatus->current_period_start) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Current Period End</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ date('Y-m-d H:i:s', $stripeStatus->current_period_end) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Cancel At Period End</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $stripeStatus->cancel_at_period_end ? 'Yes' : 'No' }}</dd>
                        </div>
                        @if($stripeStatus->trial_end)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Trial End</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ date('Y-m-d H:i:s', $stripeStatus->trial_end) }}</dd>
                        </div>
                        @endif
                        @if($stripeStatus->canceled_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Canceled At</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ date('Y-m-d H:i:s', $stripeStatus->canceled_at) }}</dd>
                        </div>
                        @endif
                    </dl>
                @else
                    <p class="text-gray-500">No Stripe subscription data available.</p>
                @endif
            </x-ui.card.body>
        </x-ui.card.base>

        <!-- Status Comparison -->
        @if($subscription && $stripeStatus)
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg font-medium">Status Comparison</h3>
            </x-ui.card.header>
            <x-ui.card.body>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Local DB</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stripe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Match</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Status</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $subscription->stripe_status }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $stripeStatus->status }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($subscription->stripe_status === $stripeStatus->status)
                                    <x-ui.badge variant="success">✓</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger">✗</x-ui.badge>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Canceled</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $subscription->ends_at ? 'Yes' : 'No' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $stripeStatus->cancel_at_period_end ? 'Yes' : 'No' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(($subscription->ends_at !== null) === $stripeStatus->cancel_at_period_end)
                                    <x-ui.badge variant="success">✓</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger">✗</x-ui.badge>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>

                @if($subscription->stripe_status !== $stripeStatus->status || (($subscription->ends_at !== null) !== $stripeStatus->cancel_at_period_end))
                <div class="mt-4">
                    <form action="{{ route('admin.users.subscription.update', $user) }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="sync">
                        <x-ui.button.primary type="submit">
                            Sync with Stripe
                        </x-ui.button.primary>
                    </form>
                </div>
                @endif
            </x-ui.card.body>
        </x-ui.card.base>
        @endif

        <!-- Actions -->
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg font-medium">Debug Actions</h3>
            </x-ui.card.header>
            <x-ui.card.body>
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Test Subscription Methods</h4>
                        <p class="text-sm text-gray-500 mb-2">These buttons will test various subscription methods and show the results.</p>
                        <div class="flex flex-wrap gap-2">
                            <x-ui.button.secondary onclick="window.location.reload()">
                                Refresh Page
                            </x-ui.button.secondary>
                        </div>
                    </div>
                </div>
            </x-ui.card.body>
        </x-ui.card.base>
    </div>
</x-user.layout>