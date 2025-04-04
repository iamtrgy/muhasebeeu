<!-- Subscription Management -->
<div>
    @if(auth()->user()->is_admin)
        <!-- Admin users don't need subscriptions -->
        <section>
            <header>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Subscription Management') }}</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('As an administrator, you have full access to all features without requiring a subscription.') }}
                </p>
            </header>
        </section>
    @elseif(auth()->user()->subscribed('default'))
        @php 
            $subscription = auth()->user()->subscription('default');
            $onTrial = $subscription->onTrial();
            $onGracePeriod = $subscription->onGracePeriod();
            $canceled = $subscription->canceled();
            $endingDate = $onTrial ? $subscription->trial_ends_at : ($canceled ? $subscription->ends_at : null);
        @endphp
        <section>
            <header>
                <div class="flex flex-col md:flex-row justify-between border-b border-gray-200 dark:border-gray-600 pb-4 mb-4">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Current Subscription') }}</h2>
                        <p class="mt-1 text-base font-medium text-indigo-600 dark:text-indigo-400">
                            @php
                                $planName = match($subscription->stripe_price) {
                                    env('STRIPE_BASIC_PRICE_ID') => 'Basic Plan',
                                    env('STRIPE_PRO_PRICE_ID') => 'Pro Plan',
                                    env('STRIPE_ENTERPRISE_PRICE_ID') => 'Enterprise Plan',
                                    default => 'Unknown Plan'
                                };
                            @endphp
                            {{ $planName }}
                        </p>
                    </div>
                    <div>
                        <span class="px-3 py-1 rounded-full text-sm {{ $canceled ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' : 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' }}">
                            {{ $canceled ? __('Cancelled') : __('Active') }}
                        </span>
                    </div>
                </div>
            </header>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                @if($canceled && $onGracePeriod)
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Access Until</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        @if($subscription->ends_at)
                            {{ $subscription->ends_at->format('F j, Y') }}
                        @else
                            {{ __('Unknown date') }}
                        @endif
                    </p>
                    <p class="mt-1 text-sm text-yellow-600 dark:text-yellow-400">
                        {{ __('Your subscription has been cancelled but you still have access until this date.') }}
                    </p>
                </div>
                @endif
                @if(!$canceled && !$onGracePeriod)
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Next Billing</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        @if($onTrial)
                            @if($subscription->trial_ends_at)
                                {{ $subscription->trial_ends_at->format('F j, Y') }}
                            @else
                                {{ __('Unknown date') }}
                            @endif
                        @else
                            @if(auth()->user()->nextBillingDate())
                                {{ auth()->user()->nextBillingDate()->format('F j, Y') }}
                            @else
                                {{ __('Unknown date') }}
                            @endif
                        @endif
                    </p>
                </div>
                @endif
            </div>

            <div class="mt-6 flex flex-wrap gap-3 border-t border-gray-200 dark:border-gray-600 pt-4">
                @if(!$canceled)
                    <button type="button" onclick="openCancelModal()" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Cancel Subscription') }}
                    </button>
                @endif

                @if($canceled && $onGracePeriod)
                    <form method="POST" action="{{ route('user.subscription.resume') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Resume Subscription') }}
                        </button>
                    </form>
                @endif

                <a href="{{ route('user.subscription.plans') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Change Plan') }}
                </a>
                
                <a href="{{ route('user.subscription.billing.portal') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Billing Portal') }}
                </a>
            </div>
        </section>
    @else
        <section>
            <header>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Subscription Management') }}</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Subscribe to access premium features and functionality.') }}
                </p>
            </header>
            
            <div class="mt-6 text-center">
                <div class="rounded-full bg-gray-100 dark:bg-gray-600 inline-flex p-3 mx-auto mb-4">
                    <svg class="h-8 w-8 text-gray-600 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('No Active Subscription') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">{{ __('You don\'t have an active subscription. Subscribe to a plan to get access to premium features.') }}</p>
                <a href="{{ route('user.subscription.plans') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('View Plans') }}
                </a>
            </div>
        </section>
    @endif
</div>

<!-- Cancel Subscription Modal -->
<div id="cancelSubscriptionModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50 hidden items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl transform transition-all max-w-lg w-full mx-4 sm:mx-auto p-6">
        <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to cancel your subscription?') }}
            </h3>
            
            <div class="mt-2">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Your subscription will remain active until the end of your current billing period. After that, you will lose access to premium features.') }}
                </p>
            </div>
            
            <div class="mt-4">
                <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 text-left">
                    {{ __('Please tell us why you\'re canceling (optional):') }}
                </label>
                <select id="cancellation_reason" name="cancellation_reason" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm" style="text-overflow: ellipsis;">
                    <option value="">{{ __('Select a reason') }}</option>
                    <option value="too_expensive">{{ __('Too expensive') }}</option>
                    <option value="not_using">{{ __('Not using it enough') }}</option>
                    <option value="missing_features">{{ __('Missing features I need') }}</option>
                    <option value="found_alternative">{{ __('Found a better alternative') }}</option>
                    <option value="technical_issues">{{ __('Technical issues') }}</option>
                    <option value="temporary">{{ __('Temporary pause - will subscribe again later') }}</option>
                    <option value="other">{{ __('Other reason') }}</option>
                </select>
                
                <div id="other_reason_container" class="mt-2 hidden">
                    <textarea id="other_reason" name="other_reason" rows="2" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm" style="text-overflow: ellipsis;" placeholder="{{ __('Please specify') }}"></textarea>
                </div>
            </div>
            
            <div class="mt-6">
                <form id="cancelSubscriptionForm" method="POST" action="{{ route('user.subscription.cancel') }}">
                    @csrf
                    <input type="hidden" name="reason" id="reason_field">
                    <input type="hidden" name="other_reason_text" id="other_reason_field">
                    
                    <div class="flex justify-center gap-3">
                        <button type="button" onclick="closeCancelModal()" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-900 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Nevermind') }}
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Yes, Cancel Subscription') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openCancelModal() {
        const modal = document.getElementById('cancelSubscriptionModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
    }
    
    function closeCancelModal() {
        const modal = document.getElementById('cancelSubscriptionModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = ''; // Re-enable scrolling
    }
    
    // Setup event listeners when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Close modal when clicking outside of it
        document.getElementById('cancelSubscriptionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCancelModal();
            }
        });
        
        // Show/hide other reason textarea based on selection
        document.getElementById('cancellation_reason').addEventListener('change', function() {
            const otherContainer = document.getElementById('other_reason_container');
            if (this.value === 'other') {
                otherContainer.classList.remove('hidden');
            } else {
                otherContainer.classList.add('hidden');
            }
        });
        
        // Set reason values before form submission
        document.getElementById('cancelSubscriptionForm').addEventListener('submit', function(e) {
            const reasonSelect = document.getElementById('cancellation_reason');
            const otherReasonText = document.getElementById('other_reason');
            
            document.getElementById('reason_field').value = reasonSelect.value;
            document.getElementById('other_reason_field').value = reasonSelect.value === 'other' ? otherReasonText.value : '';
        });
    });
</script> 