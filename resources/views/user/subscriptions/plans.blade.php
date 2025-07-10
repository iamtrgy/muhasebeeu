<x-user.layout 
    title="Subscription Plans" 
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('user.dashboard'), 'first' => true],
        ['title' => __('Subscription Plans')]
    ]"
>
    <div class="space-y-6">

        <!-- Success/Error Messages -->
        @if(isset($currentPlan) && $canceled)
            <x-ui.card.base class="border-l-4 border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20">
                <x-ui.card.body>
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700 dark:text-yellow-200">
                                Your subscription has been canceled but will remain active until the end of your billing period. You can choose a new plan or resume your current plan.
                            </p>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        @elseif(isset($currentPlan))
            <x-ui.card.base class="border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900/20">
                <x-ui.card.body>
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700 dark:text-blue-200">
                                You're currently on the <span class="font-medium">{{ ucfirst($currentPlan) }}</span> plan. You can upgrade or downgrade at any time.
                            </p>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        @endif

        <!-- Plan Comparison Table -->
        <x-ui.card.base>
            <x-ui.card.body class="p-0">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="text-lg font-semibold border-b dark:border-gray-700">
                            <th scope="col" class="px-6 py-8 bg-gray-50 dark:bg-gray-900">
                                <span class="sr-only">Features</span>
                            </th>
                            <!-- Basic Plan Header -->
                            <th scope="col" class="px-6 py-8 text-center {{ $currentPlan == 'basic' ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                <div class="flex flex-col items-center">
                                    @if($currentPlan == 'basic')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 mb-2">
                                            CURRENT PLAN
                                        </span>
                                    @endif
                                    <span class="text-2xl font-bold text-gray-900 dark:text-white">Basic</span>
                                    <p class="mt-2 flex items-baseline justify-center gap-x-1">
                                        <span class="text-4xl font-bold tracking-tight text-gray-900 dark:text-white">€9</span>
                                        <span class="text-sm font-semibold leading-6 text-gray-600 dark:text-gray-400">/month</span>
                                    </p>
                                </div>
                            </th>
                            <!-- Pro Plan Header -->
                            <th scope="col" class="px-6 py-8 text-center {{ $currentPlan == 'pro' ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                <div class="flex flex-col items-center">
                                    @if($currentPlan == 'pro')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 mb-2">
                                            CURRENT PLAN
                                        </span>
                                    @endif
                                    <div class="flex items-center gap-2">
                                        <span class="text-2xl font-bold text-gray-900 dark:text-white">Professional</span>
                                        <span class="inline-flex items-center rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 px-2.5 py-0.5 text-xs font-medium text-white">
                                            Popular
                                        </span>
                                    </div>
                                    <p class="mt-2 flex items-baseline justify-center gap-x-1">
                                        <span class="text-4xl font-bold tracking-tight text-gray-900 dark:text-white">€29</span>
                                        <span class="text-sm font-semibold leading-6 text-gray-600 dark:text-gray-400">/month</span>
                                    </p>
                                </div>
                            </th>
                            <!-- Enterprise Plan Header -->
                            <th scope="col" class="px-6 py-8 text-center {{ $currentPlan == 'enterprise' ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                <div class="flex flex-col items-center">
                                    @if($currentPlan == 'enterprise')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 mb-2">
                                            CURRENT PLAN
                                        </span>
                                    @endif
                                    <span class="text-2xl font-bold text-gray-900 dark:text-white">Enterprise</span>
                                    <p class="mt-2 flex items-baseline justify-center gap-x-1">
                                        <span class="text-4xl font-bold tracking-tight text-gray-900 dark:text-white">€49</span>
                                        <span class="text-sm font-semibold leading-6 text-gray-600 dark:text-gray-400">/month</span>
                                    </p>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 dark:text-gray-400">
                        <!-- Storage -->
                        <tr class="border-b dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium bg-gray-50 dark:bg-gray-900">
                                Storage Space
                            </th>
                            <td class="px-6 py-4 text-center">5GB</td>
                            <td class="px-6 py-4 text-center">50GB</td>
                            <td class="px-6 py-4 text-center">Unlimited</td>
                        </tr>
                        <!-- Team Members -->
                        <tr class="border-b dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium bg-gray-50 dark:bg-gray-900">
                                Team Members
                            </th>
                            <td class="px-6 py-4 text-center">Up to 10</td>
                            <td class="px-6 py-4 text-center">Up to 50</td>
                            <td class="px-6 py-4 text-center">Unlimited</td>
                        </tr>
                        <!-- Projects -->
                        <tr class="border-b dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium bg-gray-50 dark:bg-gray-900">
                                Projects
                            </th>
                            <td class="px-6 py-4 text-center">3 Projects</td>
                            <td class="px-6 py-4 text-center">15 Projects</td>
                            <td class="px-6 py-4 text-center">Unlimited</td>
                        </tr>
                        <!-- Support -->
                        <tr class="border-b dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium bg-gray-50 dark:bg-gray-900">
                                Support Level
                            </th>
                            <td class="px-6 py-4 text-center">Email Support</td>
                            <td class="px-6 py-4 text-center">Priority Support</td>
                            <td class="px-6 py-4 text-center">24/7 Dedicated Support</td>
                        </tr>
                        <!-- API Access -->
                        <tr class="border-b dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium bg-gray-50 dark:bg-gray-900">
                                API Access
                            </th>
                            <td class="px-6 py-4 text-center">
                                <svg class="w-5 h-5 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <svg class="w-5 h-5 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <svg class="w-5 h-5 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </td>
                        </tr>
                        <!-- Custom Domain -->
                        <tr class="border-b dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium bg-gray-50 dark:bg-gray-900">
                                Custom Domain
                            </th>
                            <td class="px-6 py-4 text-center">
                                <svg class="w-5 h-5 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <svg class="w-5 h-5 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <svg class="w-5 h-5 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </td>
                        </tr>
                        <!-- Analytics -->
                        <tr class="border-b dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium bg-gray-50 dark:bg-gray-900">
                                Advanced Analytics
                            </th>
                            <td class="px-6 py-4 text-center">
                                <svg class="w-5 h-5 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <svg class="w-5 h-5 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <svg class="w-5 h-5 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </td>
                        </tr>
                        <!-- Action Buttons -->
                        <tr>
                            <th scope="row" class="px-6 py-4 font-medium bg-gray-50 dark:bg-gray-900">
                                <span class="sr-only">Choose plan</span>
                            </th>
                            <td class="px-6 py-8 text-center">
                                <x-ui.button.secondary href="{{ route('user.subscription.payment.form', 'basic') }}">
                                    Choose Basic
                                </x-ui.button.secondary>
                            </td>
                            <td class="px-6 py-8 text-center">
                                <x-ui.button.primary href="{{ route('user.subscription.payment.form', 'pro') }}">
                                    Choose Pro
                                </x-ui.button.primary>
                            </td>
                            <td class="px-6 py-8 text-center">
                                <x-ui.button.secondary href="{{ route('user.subscription.payment.form', 'enterprise') }}">
                                    Choose Enterprise
                                </x-ui.button.secondary>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                </div>
            </x-ui.card.body>
        </x-ui.card.base>
    </div>
</x-user.layout>
