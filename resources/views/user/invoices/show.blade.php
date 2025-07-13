<x-user.layout 
    title="" 
    :breadcrumbs="[
        ['title' => __('Home'), 'href' => route('user.dashboard'), 'first' => true],
        ['title' => __('Invoices'), 'href' => route('user.invoices.index')],
        ['title' => 'Invoice #' . $invoice->invoice_number]
    ]"
>
    <div class="space-y-6">
        @if(session('success'))
            <x-ui.alert variant="success">{{ session('success') }}</x-ui.alert>
        @endif

        <!-- Invoice Header with Actions -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Invoice #{{ $invoice->invoice_number }}
                        </h2>
                        <div class="mt-1 flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                            <span>{{ __('Created') }}: {{ $invoice->created_at->format('d.m.Y H:i') }}</span>
                            <span>•</span>
                            <span>
                                @if($invoice->status == 'draft')
                                    <x-ui.badge variant="secondary">{{ __('Draft') }}</x-ui.badge>
                                @elseif($invoice->status == 'sent')
                                    <x-ui.badge variant="primary">{{ __('Sent') }}</x-ui.badge>
                                @elseif($invoice->status == 'paid')
                                    <x-ui.badge variant="success">{{ __('Paid') }}</x-ui.badge>
                                @elseif($invoice->status == 'cancelled')
                                    <x-ui.badge variant="danger">{{ __('Cancelled') }}</x-ui.badge>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        @if($invoice->status == 'draft')
                            <x-ui.button.primary size="sm" x-data x-on:click="$dispatch('open-modal', 'send-invoice-modal')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ __('Send Invoice') }}
                            </x-ui.button.primary>
                        @elseif($invoice->status == 'sent')
                            <x-ui.button.secondary size="sm" x-data x-on:click="$dispatch('open-modal', 'send-invoice-modal')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                {{ __('Resend Invoice') }}
                            </x-ui.button.secondary>
                        @endif
                        <x-ui.button.secondary size="sm" href="{{ route('user.invoices.edit', $invoice) }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ __('Edit') }}
                        </x-ui.button.secondary>
                        <a href="{{ route('payment.show', $invoice) }}" 
                           target="_blank"
                           class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            {{ __('View Payment Page') }}
                        </a>
                        <x-ui.button.primary 
                            size="sm" 
                            href="{{ route('user.invoices.download-pdf', $invoice) }}"
                            download="invoice-{{ $invoice->invoice_number }}.pdf">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            {{ __('Download PDF') }}
                        </x-ui.button.primary>
                        <form action="{{ route('user.invoices.regenerate-pdf', $invoice) }}" method="POST" class="inline">
                            @csrf
                            <x-ui.button.secondary size="sm" type="submit">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                {{ __('Regenerate PDF') }}
                            </x-ui.button.secondary>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Invoice Information -->
            <x-ui.card.base>
                <x-ui.card.header>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Invoice Information') }}</h3>
                    </div>
                </x-ui.card.header>
                <x-ui.card.body>
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('Invoice Date') }}</dt>
                            <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $invoice->invoice_date->format('d.m.Y') }}</dd>
                        </div>
                        @if($invoice->due_date)
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('Due Date') }}</dt>
                            <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $invoice->due_date->format('d.m.Y') }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('Currency') }}</dt>
                            <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $invoice->currency }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('Invoice Language') }}</dt>
                            <dd class="font-medium text-gray-900 dark:text-gray-100">
                                @if($invoice->language_code == 'tr')
                                    Türkçe
                                @elseif($invoice->language_code == 'en')
                                    English
                                @elseif($invoice->language_code == 'de')
                                    Deutsch
                                @endif
                            </dd>
                        </div>
                    </dl>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Company Information -->
            <x-ui.card.base>
                <x-ui.card.header>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Company Information') }}</h3>
                    </div>
                </x-ui.card.header>
                <x-ui.card.body>
                    <div class="space-y-2">
                        <p class="font-bold text-gray-900 dark:text-gray-100">{{ $invoice->company->name }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Registry Number') }}: {{ $invoice->company->tax_number }}</p>
                        @if($invoice->company->vat_number)
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('VAT Number') }}: {{ $invoice->company->vat_number }}</p>
                        @endif
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $invoice->company->address }}</p>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Client Information -->
            <x-ui.card.base>
                <x-ui.card.header>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Client Information') }}</h3>
                    </div>
                </x-ui.card.header>
                <x-ui.card.body>
                    <div class="space-y-2">
                        @if($invoice->client_id)
                            @php
                                $client = \App\Models\UserClient::find($invoice->client_id);
                            @endphp
                            @if($client)
                                <p class="font-bold text-gray-900 dark:text-gray-100">{{ $client->name }}</p>
                                @if($client->vat_number)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('VAT Number') }}: {{ $client->vat_number }}</p>
                                @endif
                                @if($client->company_reg_number)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Company Reg. Number') }}: {{ $client->company_reg_number }}</p>
                                @endif
                                @if($client->email)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Email') }}: {{ $client->email }}</p>
                                @endif
                                @if($client->phone)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Phone') }}: {{ $client->phone }}</p>
                                @endif
                                @if($client->country)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Country') }}: {{ $client->country }}</p>
                                @endif
                                @if($client->address)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Address') }}: {{ $client->address }}</p>
                                @endif
                            @else
                                <p class="text-sm text-gray-500">{{ __('Client information not available.') }}</p>
                            @endif
                        @elseif($invoice->client_name)
                            <p class="font-bold text-gray-900 dark:text-gray-100">{{ $invoice->client_name }}</p>
                            @if($invoice->client_vat_number)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('VAT Number') }}: {{ $invoice->client_vat_number }}</p>
                            @endif
                            @if($invoice->client_company_reg_number)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Company Reg. Number') }}: {{ $invoice->client_company_reg_number }}</p>
                            @endif
                            @if($invoice->client_email)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Email') }}: {{ $invoice->client_email }}</p>
                            @endif
                            @if($invoice->client_phone)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Phone') }}: {{ $invoice->client_phone }}</p>
                            @endif
                            @if($invoice->client_country)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Country') }}: {{ $invoice->client_country }}</p>
                            @endif
                            @if($invoice->client_address)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Address') }}: {{ $invoice->client_address }}</p>
                            @endif
                        @else
                            <p class="text-sm text-gray-500">{{ __('No client information available.') }}</p>
                        @endif
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Totals -->
            <x-ui.card.base>
                <x-ui.card.header>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Total') }}</h3>
                    </div>
                </x-ui.card.header>
                <x-ui.card.body>
                    <div class="space-y-3">
                        <div class="flex justify-between py-2">
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Subtotal') }}:</span>
                            <span class="font-medium text-gray-900 dark:text-gray-100">{{ number_format($invoice->subtotal, 2, ',', '.') }} {{ $invoice->currency }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('VAT Total') }}:</span>
                            <span class="font-medium text-gray-900 dark:text-gray-100">{{ number_format($invoice->tax_amount, 2, ',', '.') }} {{ $invoice->currency }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-t border-gray-200 dark:border-gray-600">
                            <span class="font-bold text-gray-900 dark:text-gray-100">{{ __('Grand Total') }}:</span>
                            <span class="font-bold text-lg text-gray-900 dark:text-gray-100">{{ number_format($invoice->total, 2, ',', '.') }} {{ $invoice->currency }}</span>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        </div>

        <!-- Invoice Items -->
        <x-ui.card.base>
            <x-ui.card.header>
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V9a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Invoice Items') }}</h3>
                </div>
            </x-ui.card.header>
            <x-ui.card.body>
                <div class="overflow-x-auto">
                    <x-ui.table.base>
                        <x-slot name="head">
                            <x-ui.table.head-cell>{{ __('Description') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell align="right">{{ __('Quantity') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell align="right">{{ __('Unit Price') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell align="right">{{ __('VAT %') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell align="right">{{ __('VAT Amount') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell align="right">{{ __('Total') }}</x-ui.table.head-cell>
                        </x-slot>
                        <x-slot name="body">
                            @foreach($invoice->items()->orderBy('sort_order')->get() as $item)
                                <tr>
                                    <x-ui.table.cell>
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $item->description }}</div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell align="right">
                                        <span class="text-sm text-gray-900 dark:text-gray-100">{{ number_format($item->quantity, 2, ',', '.') }}</span>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell align="right">
                                        <span class="text-sm text-gray-900 dark:text-gray-100">{{ number_format($item->unit_price, 2, ',', '.') }} {{ $invoice->currency }}</span>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell align="right">
                                        <span class="text-sm text-gray-900 dark:text-gray-100">{{ number_format($item->tax_rate, 0) }}%</span>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell align="right">
                                        <span class="text-sm text-gray-900 dark:text-gray-100">{{ number_format($item->tax_amount, 2, ',', '.') }} {{ $invoice->currency }}</span>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell align="right">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ number_format($item->total, 2, ',', '.') }} {{ $invoice->currency }}</span>
                                    </x-ui.table.cell>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-ui.table.base>
                </div>
            </x-ui.card.body>
        </x-ui.card.base>

        <!-- Notes -->
        @if($invoice->notes)
            <x-ui.card.base>
                <x-ui.card.header>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Notes') }}</h3>
                    </div>
                </x-ui.card.header>
                <x-ui.card.body>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $invoice->notes }}</p>
                </x-ui.card.body>
            </x-ui.card.base>
        @endif

        <!-- PDF File Information -->
        @if($invoice->pdf_path)
            <x-ui.card.base>
                <x-ui.card.header>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('File Information') }}</h3>
                    </div>
                </x-ui.card.header>
                <x-ui.card.body>
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-10 h-10 text-red-600 dark:text-red-400 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 2v7a2 2 0 002 2h4" />
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ __('Invoice PDF') }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $invoice->pdf_path }}</p>
                            </div>
                        </div>
                        <x-ui.button.primary 
                            size="sm" 
                            href="{{ route('user.invoices.download-pdf', $invoice) }}"
                            download="invoice-{{ $invoice->invoice_number }}.pdf">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            {{ __('Download') }}
                        </x-ui.button.primary>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        @endif
        
        <!-- Email History -->
        @php
            $emailLogs = \App\Models\InvoiceEmailLog::where('invoice_id', $invoice->id)
                ->orderBy('sent_at', 'desc')
                ->take(5)
                ->get();
        @endphp
        @if($emailLogs->count() > 0)
            <x-ui.card.base>
                <x-ui.card.header>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Email History') }}</h3>
                    </div>
                </x-ui.card.header>
                <x-ui.card.body>
                    <div class="space-y-3">
                        @foreach($emailLogs as $log)
                            <div class="flex items-start justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        @if($log->status == 'sent')
                                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        @endif
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('Sent to') }}: {{ $log->recipient_email }}
                                            @if($log->cc_email)
                                                <span class="text-gray-500">(CC: {{ $log->cc_email }})</span>
                                            @endif
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $log->sent_at->format('d.m.Y H:i') }} - {{ $log->sent_at->diffForHumans() }}
                                    </p>
                                    @if($log->status == 'failed' && $log->error_message)
                                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">
                                            {{ __('Error') }}: {{ $log->error_message }}
                                        </p>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500">
                                    @if($log->status == 'sent')
                                        <x-ui.badge variant="success" size="sm">{{ __('Sent') }}</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="danger" size="sm">{{ __('Failed') }}</x-ui.badge>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        @endif
    </div>
    
    <!-- Send Invoice Modal -->
    @php
        // Get last sent email for pre-filling resend
        $lastSentEmail = \App\Models\InvoiceEmailLog::where('invoice_id', $invoice->id)
            ->where('status', 'sent')
            ->orderBy('sent_at', 'desc')
            ->first();
    @endphp
    <x-ui.modal.base name="send-invoice-modal" maxWidth="lg">
        <form action="{{ route('user.invoices.send', $invoice) }}" method="POST" 
              x-data="{ 
                  sending: false,
                  error: null,
                  async submitForm(e) {
                      e.preventDefault();
                      this.sending = true;
                      this.error = null;
                      
                      try {
                          const formData = new FormData(e.target);
                          const response = await fetch(e.target.action, {
                              method: 'POST',
                              body: formData,
                              headers: {
                                  'X-Requested-With': 'XMLHttpRequest',
                                  'Accept': 'application/json'
                              }
                          });
                          
                          const data = await response.json();
                          
                          if (response.ok && data.success) {
                              // Success - redirect to show page with success message
                              window.location.href = data.redirect;
                          } else {
                              // Show error in modal
                              this.error = data.message || 'Failed to send invoice. Please try again.';
                              this.sending = false;
                          }
                      } catch (error) {
                          console.error('Error:', error);
                          this.error = 'An error occurred. Please check your connection and try again.';
                          this.sending = false;
                      }
                  }
              }" 
              x-on:submit="submitForm($event)">
            @csrf
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Send Invoice via Email') }}</h3>
                
                <!-- Error Alert -->
                <div x-show="error" x-cloak class="mb-4">
                    <x-ui.alert variant="danger" x-text="error"></x-ui.alert>
                </div>
                
                <div class="space-y-4">
                    <x-ui.form.group>
                        <x-ui.form.input 
                            name="recipient_email" 
                            label="{{ __('Recipient Email') }}" 
                            type="email"
                            value="{{ old('recipient_email', $lastSentEmail ? $lastSentEmail->recipient_email : ($invoice->client_email ?? ($invoice->client ? $invoice->client->email : ''))) }}"
                            placeholder="customer@example.com"
                            required
                        />
                    </x-ui.form.group>
                    
                    <x-ui.form.group>
                        <x-ui.form.input 
                            name="cc_email" 
                            label="{{ __('CC Email (Optional)') }}" 
                            type="email"
                            value="{{ old('cc_email', $lastSentEmail ? $lastSentEmail->cc_email : auth()->user()->email) }}"
                            placeholder="cc@example.com"
                        />
                    </x-ui.form.group>
                    
                    <x-ui.form.group>
                        <x-ui.form.input 
                            name="subject" 
                            label="{{ __('Email Subject') }}" 
                            value="{{ old('subject', $lastSentEmail ? $lastSentEmail->subject : __('Invoice') . ' #' . $invoice->invoice_number . ' - ' . $invoice->company->name) }}"
                            required
                        />
                    </x-ui.form.group>
                    
                    <x-ui.form.group>
                        <x-ui.form.textarea 
                            name="message" 
                            label="{{ __('Email Message') }}" 
                            rows="6"
                            required
                        >{{ old('message', $lastSentEmail ? $lastSentEmail->message : "Hello,

I hope this email finds you well.

Please find attached invoice #{$invoice->invoice_number} for " . number_format($invoice->total, 2, ',', '.') . " {$invoice->currency}.

Invoice Details:
- Invoice Number: {$invoice->invoice_number}
- Invoice Date: " . $invoice->invoice_date->format('d.m.Y') . "
- Due Date: " . ($invoice->due_date ? $invoice->due_date->format('d.m.Y') : 'Upon receipt') . "
- Total Amount: " . number_format($invoice->total, 2, ',', '.') . " {$invoice->currency}

Please ensure payment is made by the due date. If you have any questions regarding this invoice, please don't hesitate to contact me.

Thank you for your business.

Best regards,
{$invoice->company->name}") }}</x-ui.form.textarea>
                    </x-ui.form.group>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <svg class="inline w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            @if($invoice->pdf_path || $invoice->pdf_url)
                                {{ __('The invoice PDF will be automatically attached to the email. The invoice status will be updated to "Sent" after sending.') }}
                            @else
                                <span class="text-amber-600 dark:text-amber-400">{{ __('Warning: No PDF file is available for this invoice. The email will be sent without an attachment.') }}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 dark:bg-gray-800 px-6 py-3 flex items-center justify-end gap-3">
                <x-ui.button.secondary type="button" x-on:click="$dispatch('close-modal', 'send-invoice-modal')" x-bind:disabled="sending">
                    {{ __('Cancel') }}
                </x-ui.button.secondary>
                <x-ui.button.primary type="submit" x-bind:disabled="sending">
                    <template x-if="!sending">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ __('Send Invoice') }}
                        </span>
                    </template>
                    <template x-if="sending">
                        <span class="flex items-center">
                            <svg class="animate-spin h-4 w-4 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __('Sending...') }}
                        </span>
                    </template>
                </x-ui.button.primary>
            </div>
        </form>
    </x-ui.modal.base>
</x-user.layout>