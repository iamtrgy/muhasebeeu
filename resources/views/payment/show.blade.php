<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Pay Invoice') }} #{{ $invoice->invoice_number }} - {{ $invoice->company->name }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Invoice Payment') }}</h1>
                <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">{{ __('Pay your invoice securely online') }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Invoice Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Invoice Information -->
                    <x-ui.card.base>
                        <x-ui.card.header>
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Invoice') }} #{{ $invoice->invoice_number }}</h2>
                            </div>
                        </x-ui.card.header>
                        <x-ui.card.body>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ __('From') }}</h3>
                                    <div class="space-y-1">
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $invoice->company->name }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $invoice->company->address }}</p>
                                        @if($invoice->company->vat_number)
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('VAT') }}: {{ $invoice->company->vat_number }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ __('Bill To') }}</h3>
                                    <div class="space-y-1">
                                        @if($invoice->client)
                                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $invoice->client->name }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $invoice->client->address }}</p>
                                        @else
                                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $invoice->client_name }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $invoice->client_address }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6 grid grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">{{ __('Invoice Date') }}:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100 ml-2">{{ $invoice->invoice_date->format('d.m.Y') }}</span>
                                </div>
                                @if($invoice->due_date)
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">{{ __('Due Date') }}:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100 ml-2">{{ $invoice->due_date->format('d.m.Y') }}</span>
                                </div>
                                @endif
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">{{ __('Status') }}:</span>
                                    @if($invoice->status == 'paid')
                                        <x-ui.badge variant="success" size="sm" class="ml-2">{{ __('Paid') }}</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="warning" size="sm" class="ml-2">{{ __('Pending') }}</x-ui.badge>
                                    @endif
                                </div>
                            </div>
                        </x-ui.card.body>
                    </x-ui.card.base>

                    <!-- Invoice Items -->
                    <x-ui.card.base>
                        <x-ui.card.header>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Invoice Items') }}</h3>
                        </x-ui.card.header>
                        <x-ui.card.body>
                            <div class="overflow-x-auto">
                                <x-ui.table.base>
                                    <x-slot name="head">
                                        <x-ui.table.head-cell>{{ __('Description') }}</x-ui.table.head-cell>
                                        <x-ui.table.head-cell align="right">{{ __('Qty') }}</x-ui.table.head-cell>
                                        <x-ui.table.head-cell align="right">{{ __('Unit Price') }}</x-ui.table.head-cell>
                                        <x-ui.table.head-cell align="right">{{ __('Total') }}</x-ui.table.head-cell>
                                    </x-slot>
                                    <x-slot name="body">
                                        @foreach($invoice->items as $item)
                                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                                <x-ui.table.cell>
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $item->description }}</div>
                                                </x-ui.table.cell>
                                                <x-ui.table.cell align="right">
                                                    <span class="text-sm text-gray-900 dark:text-gray-100">{{ number_format($item->quantity, 0) }}</span>
                                                </x-ui.table.cell>
                                                <x-ui.table.cell align="right">
                                                    <span class="text-sm text-gray-900 dark:text-gray-100">{{ number_format($item->unit_price, 2) }} {{ $invoice->currency }}</span>
                                                </x-ui.table.cell>
                                                <x-ui.table.cell align="right">
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ number_format($item->total, 2) }} {{ $invoice->currency }}</span>
                                                </x-ui.table.cell>
                                            </tr>
                                        @endforeach
                                    </x-slot>
                                </x-ui.table.base>
                            </div>
                            
                            <!-- Totals -->
                            <div class="mt-6 space-y-2 max-w-sm ml-auto">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">{{ __('Subtotal') }}:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ number_format($invoice->subtotal, 2) }} {{ $invoice->currency }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">{{ __('VAT') }}:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ number_format($invoice->tax_amount, 2) }} {{ $invoice->currency }}</span>
                                </div>
                                <div class="flex justify-between text-lg font-bold border-t border-gray-200 dark:border-gray-600 pt-2">
                                    <span class="text-gray-900 dark:text-gray-100">{{ __('Total') }}:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ number_format($invoice->total, 2) }} {{ $invoice->currency }}</span>
                                </div>
                            </div>
                        </x-ui.card.body>
                    </x-ui.card.base>
                </div>

                <!-- Payment Options -->
                <div class="space-y-6">
                    <!-- Payment Methods -->
                    <x-ui.card.base>
                        <x-ui.card.header>
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Payment Methods') }}</h3>
                            </div>
                        </x-ui.card.header>
                        <x-ui.card.body>
                            @if($invoice->status !== 'paid')
                                <div class="space-y-4">
                                    @if($invoice->payment_url)
                                        <!-- Online Payment -->
                                        <div class="p-4 border border-emerald-200 dark:border-emerald-700 rounded-lg bg-emerald-50 dark:bg-emerald-900/20">
                                            <h4 class="font-medium text-emerald-900 dark:text-emerald-100 mb-2">{{ __('Pay Online') }}</h4>
                                            <p class="text-sm text-emerald-700 dark:text-emerald-300 mb-4">{{ __('Pay securely with credit card or bank transfer') }}</p>
                                            <a href="{{ $invoice->payment_url }}" 
                                               target="_blank"
                                               class="inline-flex items-center justify-center w-full px-4 py-3 border border-transparent text-base font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                                {{ __('Pay Now') }}
                                            </a>
                                        </div>
                                    @endif

                                    <!-- Bank Transfer -->
                                    <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                        <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('Bank Transfer') }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ __('Transfer money directly to our bank account') }}</p>
                                        
                                        @if($invoice->company->bank_name)
                                            <div class="space-y-2 text-sm">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500 dark:text-gray-400">{{ __('Bank') }}:</span>
                                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $invoice->company->bank_name }}</span>
                                                </div>
                                                @if($invoice->company->bank_account)
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-500 dark:text-gray-400">{{ __('Account') }}:</span>
                                                        <span class="font-mono text-gray-900 dark:text-gray-100">{{ $invoice->company->bank_account }}</span>
                                                    </div>
                                                @endif
                                                @if($invoice->company->bank_iban)
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-500 dark:text-gray-400">{{ __('IBAN') }}:</span>
                                                        <span class="font-mono text-gray-900 dark:text-gray-100">{{ $invoice->company->bank_iban }}</span>
                                                    </div>
                                                @endif
                                                @if($invoice->company->bank_swift)
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-500 dark:text-gray-400">{{ __('SWIFT') }}:</span>
                                                        <span class="font-mono text-gray-900 dark:text-gray-100">{{ $invoice->company->bank_swift }}</span>
                                                    </div>
                                                @endif
                                                <div class="flex justify-between border-t border-gray-200 dark:border-gray-600 pt-2">
                                                    <span class="text-gray-500 dark:text-gray-400">{{ __('Reference') }}:</span>
                                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $invoice->invoice_number }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Please contact us for bank transfer details.') }}</p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-lg font-medium text-emerald-900 dark:text-emerald-100">{{ __('Payment Received') }}</h3>
                                    <p class="mt-1 text-sm text-emerald-600 dark:text-emerald-400">{{ __('This invoice has been paid. Thank you!') }}</p>
                                </div>
                            @endif
                        </x-ui.card.body>
                    </x-ui.card.base>

                    <!-- Contact Information -->
                    <x-ui.card.base>
                        <x-ui.card.header>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Questions?') }}</h3>
                        </x-ui.card.header>
                        <x-ui.card.body>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ __('If you have any questions about this invoice, please contact us:') }}</p>
                            <div class="space-y-2 text-sm">
                                @if($invoice->company->email)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <a href="mailto:{{ $invoice->company->email }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">{{ $invoice->company->email }}</a>
                                    </div>
                                @endif
                                @if($invoice->company->phone)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <a href="tel:{{ $invoice->company->phone }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">{{ $invoice->company->phone }}</a>
                                    </div>
                                @endif
                            </div>
                        </x-ui.card.body>
                    </x-ui.card.base>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-12 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Powered by') }} <span class="font-medium">{{ config('app.name') }}</span>
                </p>
            </div>
        </div>
    </div>
</body>
</html>