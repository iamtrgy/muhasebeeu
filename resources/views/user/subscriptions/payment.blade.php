<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Complete Your Subscription') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Plan Details -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Plan Detail</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            @php
                                $planDetails = [
                                    'basic' => [
                                        'name' => 'Basic Plan',
                                        'price' => '€9.99',
                                        'period' => 'per month',
                                        'features' => [
                                            'Up to 5 companies',
                                            'Basic file storage',
                                            'Standard support',
                                            'Basic reporting'
                                        ]
                                    ],
                                    'pro' => [
                                        'name' => 'Pro Plan',
                                        'price' => '€19.99',
                                        'period' => 'per month',
                                        'features' => [
                                            'Up to 20 companies',
                                            'Advanced file storage',
                                            'Priority support',
                                            'Advanced reporting',
                                            'API access',
                                            'Custom integrations'
                                        ]
                                    ],
                                    'enterprise' => [
                                        'name' => 'Enterprise Plan',
                                        'price' => '€49.99',
                                        'period' => 'per month',
                                        'features' => [
                                            'Unlimited companies',
                                            'Unlimited file storage',
                                            '24/7 dedicated support',
                                            'Custom reporting',
                                            'Full API access',
                                            'Custom integrations',
                                            'Dedicated account manager'
                                        ]
                                    ]
                                ];
                                $selectedPlan = $planDetails[$plan];
                            @endphp

                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-xl font-bold">{{ $selectedPlan['name'] }}</h4>
                                <div class="text-right">
                                    <span class="text-2xl font-bold">{{ $selectedPlan['price'] }}</span>
                                    <span class="text-gray-500 dark:text-gray-400">{{ $selectedPlan['period'] }}</span>
                                </div>
                            </div>

                            <ul class="space-y-2">
                                @foreach($selectedPlan['features'] as $feature)
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <form id="payment-form" action="{{ route('user.subscription.create') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan" value="{{ $plan }}">
                        
                        <div class="mb-6">
                            <label for="card-element" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Credit or debit card
                            </label>
                            <div id="card-element" class="p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                                <!-- Stripe Card Element will be inserted here -->
                            </div>
                            <div id="card-errors" class="mt-2 text-sm text-red-600 dark:text-red-400" role="alert"></div>
                        </div>

                        <button type="submit" id="submit-button" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Subscribe Now
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements();
        const card = elements.create('card');
        
        card.mount('#card-element');
        
        card.addEventListener('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });
        
        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit-button');
        
        form.addEventListener('submit', async function(event) {
            event.preventDefault();
            
            submitButton.disabled = true;
            submitButton.textContent = 'Processing...';
            
            const { setupIntent, error } = await stripe.confirmCardSetup(
                '{{ $intent->client_secret }}',
                {
                    payment_method: {
                        card: card,
                        billing_details: {
                            name: '{{ auth()->user()->name }}',
                            email: '{{ auth()->user()->email }}'
                        }
                    }
                }
            );
            
            if (error) {
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
                submitButton.disabled = false;
                submitButton.textContent = 'Subscribe Now';
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