<x-app-layout>
    <x-unified-header />
    
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Payment Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form id="payment-form" action="{{ route('user.subscription.create') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan" value="{{ $plan }}">

                        <div class="mb-6">
                            <h3 class="text-lg font-medium">Payment Information</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Please provide your payment details to subscribe to the {{ ucfirst($plan) }} plan.
                            </p>
                        </div>

                        <div class="mb-6">
                            <label for="card-holder-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Card Holder Name
                            </label>
                            <input type="text" id="card-holder-name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                        </div>

                        <div class="mb-6">
                            <label for="card-element" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Credit or Debit Card
                            </label>
                            <div id="card-element" class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2">
                                <!-- Stripe Elements will be inserted here -->
                            </div>
                            <div id="card-errors" role="alert" class="mt-2 text-sm text-red-600"></div>
                        </div>

                        <div class="flex items-center justify-end">
                            @php
                                $planPrice = match($plan) {
                                    'basic' => '€9.99',
                                    'pro' => '€19.99',
                                    'enterprise' => '€49.99',
                                    default => '€0.00'
                                };
                            @endphp
                            <button type="submit" id="card-button" data-secret="{{ $intent->client_secret }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-300">
                                Subscribe Now - {{ $planPrice }}/month
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Plan Details -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Confirm Your {{ ucfirst($plan) }} Plan</h2>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">Review your plan details before proceeding to payment</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ ucfirst($plan) }} Plan
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Plan Features -->
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4 flex items-center">
                                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Plan Features
                            </h3>
                            <ul class="space-y-4">
                                @if($plan === 'basic')
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <div>
                                            <span class="font-medium">Up to 3 companies</span>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Manage multiple businesses with ease</p>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <div>
                                            <span class="font-medium">Basic file storage</span>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Store and organize your documents securely</p>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <div>
                                            <span class="font-medium">Standard support</span>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Get help when you need it</p>
                                        </div>
                                    </li>
                                @elseif($plan === 'pro')
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <div>
                                            <span class="font-medium">Up to 10 companies</span>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Scale your business operations</p>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <div>
                                            <span class="font-medium">Advanced file storage</span>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Enhanced storage with better organization</p>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <div>
                                            <span class="font-medium">Priority support</span>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Faster response times for your queries</p>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <div>
                                            <span class="font-medium">Advanced analytics</span>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Detailed insights into your business</p>
                                        </div>
                                    </li>
                                @else
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <div>
                                            <span class="font-medium">Unlimited companies</span>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Manage as many businesses as you need</p>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <div>
                                            <span class="font-medium">Premium file storage</span>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Maximum storage with advanced features</p>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <div>
                                            <span class="font-medium">24/7 dedicated support</span>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Round-the-clock assistance from our team</p>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <div>
                                            <span class="font-medium">Custom integrations</span>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Tailored solutions for your needs</p>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <div>
                                            <span class="font-medium">Advanced security features</span>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Enterprise-grade security for your data</p>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <!-- Billing Information -->
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4 flex items-center">
                                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Billing Information
                            </h3>
                            <ul class="space-y-4">
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-500 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <span class="font-medium">Monthly billing</span>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Flexible monthly payments with no long-term commitment</p>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-500 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <span class="font-medium">Cancel anytime</span>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">No hidden fees or cancellation charges</p>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-500 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    <div>
                                        <span class="font-medium">Secure payment processing</span>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Your payment information is encrypted and secure</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ config('cashier.key') }}');
        const elements = stripe.elements();
        const cardElement = elements.create('card');

        cardElement.mount('#card-element');

        const cardHolderName = document.getElementById('card-holder-name');
        const cardButton = document.getElementById('card-button');
        const clientSecret = cardButton.dataset.secret;
        const form = document.getElementById('payment-form');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            cardButton.disabled = true;
            cardButton.textContent = 'Processing...';

            const { setupIntent, error } = await stripe.confirmCardSetup(
                clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: { name: cardHolderName.value }
                    }
                }
            );

            if (error) {
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
                cardButton.disabled = false;
                cardButton.textContent = 'Subscribe Now';
            } else {
                const paymentMethodInput = document.createElement('input');
                paymentMethodInput.setAttribute('type', 'hidden');
                paymentMethodInput.setAttribute('name', 'payment_method');
                paymentMethodInput.setAttribute('value', setupIntent.payment_method);
                form.appendChild(paymentMethodInput);

                form.submit();
            }
        });
    </script>
    @endpush
</x-app-layout>
