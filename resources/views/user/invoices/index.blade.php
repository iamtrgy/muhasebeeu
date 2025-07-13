<x-user.layout 
    title="" 
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
                    <div class="flex items-center space-x-3">
                        @if($selectedMonthFolder && $selectedMonthFolder->exists)
                            <x-ui.button.secondary 
                                href="{{ route('user.folders.show', $selectedMonthFolder) }}"
                                target="_blank"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                {{ __('Upload') }}
                            </x-ui.button.secondary>
                        @else
                            <x-ui.button.secondary 
                                href="{{ route('user.folders.index') }}"
                                target="_blank"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                {{ __('Upload to Files') }}
                            </x-ui.button.secondary>
                        @endif
                        <x-ui.button.primary href="{{ route('user.invoices.create') }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('Create Invoice') }}
                        </x-ui.button.primary>
                    </div>
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

        <!-- Year/Month Selector -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-4">
                    <!-- Year Selector -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">{{ __('Year:') }}</label>
                        <select 
                            onchange="window.location.href='{{ route('user.invoices.index') }}?tab={{ $tab }}&year=' + this.value + '&month={{ $selectedMonth }}'"
                            class="px-3 py-1 pr-8 border border-gray-300 dark:border-gray-600 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white appearance-none bg-white dark:bg-gray-700 bg-no-repeat bg-right bg-[length:16px_16px] bg-[position:right_8px_center]"
                            style="background-image: url('data:image/svg+xml,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 20 20%27%3e%3cpath stroke=%27%236b7280%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%271.5%27 d=%27M6 8l4 4 4-4%27/%3e%3c/svg%3e');"
                        >
                            @foreach($years as $year)
                                <option value="{{ $year->name }}" {{ $year->name == $selectedYear ? 'selected' : '' }}>
                                    {{ $year->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Month Tabs -->
                    <div class="flex-1 flex space-x-1 overflow-x-auto">
                        @php
                            $monthNames = [
                                1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                                5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
                                9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
                            ];
                        @endphp
                        @for($m = 1; $m <= 12; $m++)
                            @php
                                $monthFolder = $months->firstWhere('name', Carbon\Carbon::createFromDate(null, $m, 1)->format('F'));
                                $uploadedFilesCount = $monthFolder ? $monthFolder->files()->count() : 0;
                                // System invoices only count for income tab
                                $systemInvoicesCount = ($tab === 'income') ? ($monthlySystemInvoiceCounts[$m] ?? 0) : 0;
                                $totalCount = $uploadedFilesCount + $systemInvoicesCount;
                                $hasItems = $totalCount > 0;
                            @endphp
                            <a 
                                href="{{ route('user.invoices.index', ['tab' => $tab, 'year' => $selectedYear, 'month' => $m]) }}"
                                class="px-3 py-2 text-sm font-medium rounded-md transition-colors
                                    {{ $m == $selectedMonth 
                                        ? 'bg-indigo-600 text-white' 
                                        : ($hasItems 
                                            ? 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' 
                                            : 'text-gray-400 dark:text-gray-500') 
                                    }}"
                            >
                                {{ $monthNames[$m] }}
                                @if($hasItems)
                                    <span class="ml-1 inline-flex items-center justify-center w-4 h-4 text-xs bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded-full">
                                        {{ $totalCount }}
                                    </span>
                                @endif
                            </a>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice List -->
        <x-ui.card.base>
            <x-ui.card.body :noPadding="true">
                @if($invoices->count() > 0)
                    <!-- Card-based Invoice List -->
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($invoices as $invoice)
                            <div class="flex items-center justify-between px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <!-- Left: Type Indicator + Invoice Info -->
                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                    <!-- Type Indicator -->
                                    <div class="flex-shrink-0">
                                        @if($invoice['type'] === 'system')
                                            <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Main Invoice Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2">
                                            @if($invoice['type'] === 'system')
                                                <a href="{{ route('user.invoices.show', $invoice['data']) }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 truncate">
                                                    {{ $invoice['number'] }}
                                                </a>
                                            @else
                                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate">
                                                    {{ Str::limit($invoice['number'], 30) }}
                                                </h3>
                                            @endif
                                            
                                            <!-- Status Badge -->
                                            @if($invoice['type'] === 'system')
                                                @if($invoice['status'] == 'draft')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                        {{ __('Draft') }}
                                                    </span>
                                                @elseif($invoice['status'] == 'sent')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        {{ __('Sent') }}
                                                    </span>
                                                @elseif($invoice['status'] == 'paid')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                                        {{ __('Paid') }}
                                                    </span>
                                                @elseif($invoice['status'] == 'cancelled')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        {{ __('Cancelled') }}
                                                    </span>
                                                @endif
                                            @else
                                                @if($invoice['status'] == 'analyzed')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200"
                                                        title="{{ __('AI analyzed with') }} {{ $invoice['confidence'] }}% {{ __('confidence') }}">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        {{ __('Analyzed') }}
                                                    </span>
                                                @elseif($invoice['status'] == 'partial')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200"
                                                        title="{{ __('AI analyzed with') }} {{ $invoice['confidence'] }}% {{ __('confidence - may need review') }}">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                                        </svg>
                                                        {{ __('Partial') }}
                                                    </span>
                                                @elseif($invoice['status'] == 'review')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200"
                                                        title="{{ __('AI analysis confidence:') }} {{ $invoice['confidence'] }}% {{ __('- needs manual review') }}">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        {{ __('Review') }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200"
                                                        title="{{ __('Document uploaded but not yet analyzed by AI') }}">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        {{ __('Pending') }}
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                        
                                        <!-- Secondary Info -->
                                        <div class="flex items-center space-x-3 text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ $invoice['date']->format('d.m.Y') }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                                {{ $tab === 'income' ? $invoice['client'] : $invoice['vendor'] }}
                                            </span>
                                            @if($invoice['amount'])
                                                <span class="flex items-center font-medium text-gray-900 dark:text-gray-100">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                                    </svg>
                                                    {{ number_format($invoice['amount'], 2, ',', '.') }} {{ $invoice['currency'] }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex items-center space-x-1">
                                    @if($invoice['type'] === 'system')
                                        <a href="{{ route('user.invoices.show', $invoice['data']) }}" 
                                           class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                                           title="{{ __('View') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        @if($invoice['data']->pdf_path)
                                            <a href="{{ route('user.invoices.download-pdf', $invoice['data']) }}" 
                                               class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                                               title="{{ __('Download PDF') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                                </svg>
                                            </a>
                                        @endif
                                    @else
                                        <button onclick="previewFile({{ json_encode($invoice['data']->name) }}, {{ json_encode($invoice['data']->mime_type) }}, {{ json_encode($invoice['data']->url) }})"
                                                class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                                                title="{{ __('Preview') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        @if(in_array($invoice['status'], ['analyzed', 'partial', 'review']))
                                            <button onclick="showAISuggestionModal({{ $invoice['data']->id }})"
                                                    class="p-1.5 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 transform hover:scale-105"
                                                    title="{{ __('View AI Analysis Details') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>
                                        @endif
                                        <button onclick="reanalyzeFile({{ $invoice['data']->id }})"
                                                class="p-1.5 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 transform hover:scale-105"
                                                title="{{ __('AI Re-analyze') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                            </svg>
                                        </button>
                                        <a href="{{ route('user.files.download', $invoice['data']) }}" 
                                           class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                                           title="{{ __('Download') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    {{ $invoices->links() }}
                @else
                    <div class="flex flex-col items-center justify-center py-12">
                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                            {{ $tab === 'income' ? __('No income invoices found.') : __('No expense invoices found.') }}
                        </p>
                        @if($tab === 'income')
                            <x-ui.button.primary 
                                href="{{ route('user.invoices.create') }}"
                                size="sm"
                            >
                                {{ __('Create First Invoice') }}
                            </x-ui.button.primary>
                        @endif
                    </div>
                @endif
            </x-ui.card.body>
        </x-ui.card.base>
    </div>

    <!-- File Preview Modal -->
    <div id="filePreviewModal" class="hidden fixed inset-0 z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" id="modal-backdrop"></div>

        <!-- Modal Content -->
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                    <div class="absolute top-0 right-0 pt-4 pr-4 z-10">
                        <button type="button" onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="w-full">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white mb-4 pt-4" id="preview-title"></h3>
                            <div id="preview-content" class="mt-2 max-h-[70vh] overflow-auto"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include AI Suggestion Modal -->
    <x-ai-suggestion-modal />

    @push('scripts')
    <script>
    // Make functions globally available
    window.previewFile = function(fileName, mimeType, url) {
        const modal = document.getElementById('filePreviewModal');
        const title = document.getElementById('preview-title');
        const content = document.getElementById('preview-content');
        
        title.textContent = fileName;
        content.innerHTML = '<div class="flex justify-center items-center h-32"><svg class="animate-spin h-8 w-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>';
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Reset modal padding first
        const modalContent = content.closest('.bg-white');
        modalContent.classList.remove('p-0');
        modalContent.classList.add('px-4', 'pb-4', 'pt-5', 'sm:p-6', 'sm:pb-4');
        
        if (mimeType.startsWith('image/')) {
            // Show title for images
            title.style.display = 'block';
            content.innerHTML = `<img src="${url}" alt="${fileName}" class="max-w-full h-auto mx-auto">`;
        } else if (mimeType === 'application/pdf') {
            // For PDFs, remove padding and make iframe full size
            modalContent.classList.remove('px-4', 'pb-4', 'pt-5', 'sm:p-6', 'sm:pb-4');
            modalContent.classList.add('p-0');
            
            // Hide the main title since we don't need it for PDFs
            title.style.display = 'none';
            
            content.innerHTML = `
                <div class="flex justify-between items-center px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">${fileName}</h3>
                </div>
                <iframe src="${url}" class="w-full h-[80vh]" frameborder="0"></iframe>
            `;
        } else {
            // Show title for other file types
            title.style.display = 'block';
            // For non-previewable files, show a download prompt
            content.innerHTML = `
                <div class="text-center p-8">
                    <p class="mb-4 text-gray-600 dark:text-gray-400">This file type cannot be previewed directly.</p>
                    <a href="${url}?download=${encodeURIComponent(fileName)}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download File
                    </a>
                </div>`;
        }
    }

    window.closePreviewModal = function() {
        const modal = document.getElementById('filePreviewModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Initialize event listeners when the document is ready
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('filePreviewModal');
        const backdrop = document.getElementById('modal-backdrop');
        
        // Close modal when clicking the backdrop
        backdrop.addEventListener('click', function(event) {
            if (event.target === backdrop) {
                closePreviewModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                closePreviewModal();
            }
        });
    });

    // AI Re-analyze function - use the same modal as AI History page
    function reanalyzeFile(fileId) {
        // ALWAYS show confirmation first
        window.showConfirmationModal({
            title: 'Re-analyze File',
            message: 'Are you sure you want to re-analyze this file? This will generate a new AI analysis.',
            confirmText: 'Re-analyze',
            cancelText: 'Cancel',
            onConfirm: function() {
                if (typeof window.showAISuggestionModal === 'function') {
                    // Show the full AI suggestion modal with results (force new analysis)
                    window.showAISuggestionModal(fileId, true);
                } else {
                    // Fallback: Make AJAX request instead of form submission
                    fetch(`/user/files/${fileId}/analyze`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            force: true
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (window.showSuccessToast) {
                                window.showSuccessToast('File re-analysis completed successfully!');
                            }
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            if (window.showErrorToast) {
                                window.showErrorToast(data.message || 'Failed to analyze file');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (window.showErrorToast) {
                            window.showErrorToast('An error occurred while analyzing the file');
                        }
                    });
                }
            }
        });
    }
    </script>
    @endpush
</x-user.layout>