<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Add Accountant Style Breadcrumb Navigation -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-4 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center text-sm">
                         <x-subscription.breadcrumb />
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="py-12">
                <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
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
                                    <button type="submit" id="card-button" data-secret="{{ $intent->client_secret }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-300">
                                        Subscribe Now
                                    </button>
                                </div>
                            </form>
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