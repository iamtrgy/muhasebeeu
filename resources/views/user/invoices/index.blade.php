<x-user.layout 
    title="{{ __('Invoices') }}" 
    :breadcrumbs="[
        ['title' => __('Home'), 'href' => route('user.dashboard')],
        ['title' => __('Invoices'), 'active' => true]
    ]"
>
    <div class="space-y-6">
        @if(session('success'))
            <x-ui.alert variant="success">{{ session('success') }}</x-ui.alert>
        @endif

        @if(session('error'))
            <x-ui.alert variant="danger">{{ session('error') }}</x-ui.alert>
        @endif

        <!-- Page Header -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ __('Invoices') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Manage all invoices for') }} {{ $company->name }}
                        </p>
                    </div>
                    <x-ui.button.primary href="{{ route('user.invoices.create') }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('Create Invoice') }}
                    </x-ui.button.primary>
                </div>
            </div>
            
            <!-- Tabs -->
            <div class="border-t border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    <a href="{{ route('user.invoices.index', ['tab' => 'income']) }}" 
                       class="py-3 px-1 border-b-2 font-medium text-sm transition-colors
                           {{ $tab === 'income' 
                               ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' 
                               : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        {{ __('Income') }}
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                            {{ $systemInvoicesCount + $uploadedIncomeCount }}
                        </span>
                    </a>
                    <a href="{{ route('user.invoices.index', ['tab' => 'expense']) }}" 
                       class="py-3 px-1 border-b-2 font-medium text-sm transition-colors
                           {{ $tab === 'expense' 
                               ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' 
                               : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        {{ __('Expense') }}
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                            {{ $uploadedExpenseCount }}
                        </span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Invoice List -->
        <x-ui.card.base>
            <x-ui.card.body>
                @if($invoices->count() > 0)
                    <x-ui.table.base>
                        <x-slot name="head">
                            <x-ui.table.head-cell>{{ __('Type') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Invoice No') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Date') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ $tab === 'income' ? __('Customer') : __('Vendor') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Amount') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Status') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell align="right">{{ __('Actions') }}</x-ui.table.head-cell>
                        </x-slot>
                        <x-slot name="body">
                            @foreach($invoices as $invoice)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <x-ui.table.cell>
                                        @if($invoice['type'] === 'system')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                                </svg>
                                                {{ __('System') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                </svg>
                                                {{ __('Uploaded') }}
                                            </span>
                                        @endif
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <div>
                                                @if($invoice['type'] === 'system')
                                                    <a href="{{ route('user.invoices.show', $invoice['data']) }}" class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                                                        {{ $invoice['number'] }}
                                                    </a>
                                                @else
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ Str::limit($invoice['number'], 30) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                            {{ $invoice['date']->format('d.m.Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $invoice['date']->diffForHumans() }}
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                            {{ $tab === 'income' ? $invoice['client'] : $invoice['vendor'] }}
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        @if($invoice['amount'])
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ number_format($invoice['amount'], 2, ',', '.') }} {{ $invoice['currency'] }}
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                        @endif
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        @if($invoice['type'] === 'system')
                                            @if($invoice['status'] == 'draft')
                                                <x-ui.badge variant="secondary">{{ __('Draft') }}</x-ui.badge>
                                            @elseif($invoice['status'] == 'sent')
                                                <x-ui.badge variant="primary">{{ __('Sent') }}</x-ui.badge>
                                            @elseif($invoice['status'] == 'paid')
                                                <x-ui.badge variant="success">{{ __('Paid') }}</x-ui.badge>
                                            @elseif($invoice['status'] == 'cancelled')
                                                <x-ui.badge variant="danger">{{ __('Cancelled') }}</x-ui.badge>
                                            @endif
                                        @else
                                            <x-ui.badge variant="secondary">{{ __('Uploaded') }}</x-ui.badge>
                                        @endif
                                    </x-ui.table.cell>
                                    <x-ui.table.cell align="right">
                                        <div class="flex items-center justify-end space-x-2">
                                            @if($invoice['type'] === 'system')
                                                <a href="{{ route('user.invoices.show', $invoice['data']) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                                   title="{{ __('View') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                @if($invoice['data']->pdf_path)
                                                    <a href="{{ route('user.invoices.download-pdf', $invoice['data']) }}" 
                                                       class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300"
                                                       title="{{ __('Download PDF') }}">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                                        </svg>
                                                    </a>
                                                @endif
                                            @else
                                                <a href="{{ route('user.files.preview', $invoice['data']) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                                   title="{{ __('Preview') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('user.files.download', $invoice['data']) }}" 
                                                   class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300"
                                                   title="{{ __('Download') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </x-ui.table.cell>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-ui.table.base>
                    
                    {{ $invoices->links() }}
                @else
                    <x-ui.table.empty-state>
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            @if($tab === 'income')
                                {{ __('No income invoices found.') }}
                            @else
                                {{ __('No expense invoices found.') }}
                            @endif
                        </p>
                        @if($tab === 'income')
                            <x-ui.button.primary 
                                href="{{ route('user.invoices.create') }}"
                                size="sm"
                                class="mt-4"
                            >
                                {{ __('Create First Invoice') }}
                            </x-ui.button.primary>
                        @endif
                    </x-ui.table.empty-state>
                @endif
            </x-ui.card.body>
        </x-ui.card.base>
    </div>
</x-user.layout>