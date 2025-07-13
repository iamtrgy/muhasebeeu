<x-user.layout 
    title="{{ __('Statement Details') }}" 
    :breadcrumbs="[
        ['title' => __('Home'), 'href' => route('user.dashboard')],
        ['title' => __('Banks'), 'href' => route('user.banks.index')],
        ['title' => $file->original_name, 'active' => true]
    ]"
>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ $file->original_name }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Analyzed') }} {{ $file->statement_analysis_date->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button onclick="reanalyzeStatement({{ $file->id }})" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-amber-700 bg-amber-100 hover:bg-amber-200 dark:text-amber-300 dark:bg-amber-900 dark:hover:bg-amber-800">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            {{ __('Re-analyze') }}
                        </button>
                        <button onclick="previewFile({{ json_encode([
                            'id' => $file->id,
                            'name' => $file->original_name,
                            'mime_type' => $file->mime_type,
                            'url' => route('user.files.preview', $file)
                        ]) }})" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ __('View Statement') }}
                        </button>
                        <a href="{{ route('user.files.download', $file) }}" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                            </svg>
                            {{ __('Download') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Transactions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                        {{ __('Total Transactions') }}
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                        {{ $summary['transaction_count'] }}
                    </dd>
                </div>
            </div>

            <!-- Total Credits -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                        {{ __('Total Credits') }}
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-green-600 dark:text-green-400">
                        +{{ number_format($summary['total_credits'], 2) }} {{ $summary['currency'] }}
                    </dd>
                </div>
            </div>

            <!-- Total Debits -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                        {{ __('Total Debits') }}
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-red-600 dark:text-red-400">
                        -{{ number_format($summary['total_debits'], 2) }} {{ $summary['currency'] }}
                    </dd>
                </div>
            </div>

            <!-- Matched Transactions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                        {{ __('Matched') }}
                    </dt>
                    <dd class="mt-1">
                        <div class="flex items-baseline">
                            <span class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ $summary['matched_count'] }}
                            </span>
                            <span class="ml-1 text-sm text-gray-500 dark:text-gray-400">
                                / {{ $summary['transaction_count'] }}
                            </span>
                        </div>
                        <div class="mt-2">
                            <div class="bg-gray-200 dark:bg-gray-700 rounded-full h-2 w-full">
                                <div class="bg-indigo-600 rounded-full h-2" style="width: {{ $summary['transaction_count'] > 0 ? ($summary['matched_count'] / $summary['transaction_count'] * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </dd>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    {{ __('Transactions') }}
                </h3>
            </div>
            <div class="overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Date') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Description') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Category') }}
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Amount') }}
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Balance') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Invoice') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Notes') }}
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($transactions as $transaction)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $transaction->transaction_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        <div class="max-w-xs truncate" title="{{ $transaction->description }}">
                                            {{ $transaction->description }}
                                        </div>
                                        @if($transaction->reference_number)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                Ref: {{ $transaction->reference_number }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                            {{ $transaction->category ?? __('Uncategorized') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ $transaction->type === 'credit' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $transaction->type === 'credit' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900 dark:text-gray-100">
                                        @if($transaction->balance)
                                            {{ number_format($transaction->balance, 2) }} {{ $transaction->currency }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($transaction->matchedInvoice)
                                            <a href="{{ route('user.invoices.show', $transaction->matchedInvoice) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                {{ $transaction->matchedInvoice->invoice_number }}
                                            </a>
                                            @if($transaction->match_confidence)
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    ({{ $transaction->match_confidence }}%)
                                                </span>
                                            @endif
                                        @else
                                            <button onclick="findMatches({{ $transaction->id }})" 
                                                    class="text-xs text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                {{ __('Find match') }}
                                            </button>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        <div class="max-w-xs">
                                            <span id="notes-text-{{ $transaction->id }}" class="truncate block">
                                                {{ $transaction->notes ?? '-' }}
                                            </span>
                                            <input id="notes-input-{{ $transaction->id }}" 
                                                   type="text" 
                                                   value="{{ $transaction->notes }}"
                                                   class="hidden w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700"
                                                   onblur="saveNotes({{ $transaction->id }})"
                                                   onkeypress="if(event.key === 'Enter') saveNotes({{ $transaction->id }})">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button onclick="editNotes({{ $transaction->id }})" 
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- File Preview Modal (same as banks index) -->
    <div id="filePreviewModal" class="hidden fixed inset-0 z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" id="modal-backdrop"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                    <div class="absolute top-0 right-0 pt-4 pr-4 z-10">
                        <button type="button" onclick="closePreviewModal()" class="rounded-md bg-white dark:bg-gray-800 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
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

    <!-- Match Invoice Modal -->
    <div id="matchModal" class="hidden fixed inset-0 z-50" aria-labelledby="match-modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4" id="match-modal-title">
                            {{ __('Match to Invoice') }}
                        </h3>
                        <div id="match-content" class="mt-2">
                            <!-- Match results will be loaded here -->
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" onclick="closeMatchModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-600 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-500 hover:bg-gray-50 dark:hover:bg-gray-500 sm:mt-0 sm:w-auto">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Copy preview functions from banks index
    window.previewFile = function(file) {
        const modal = document.getElementById('filePreviewModal');
        const previewTitle = document.getElementById('preview-title');
        const previewContent = document.getElementById('preview-content');
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        previewTitle.textContent = file.name;
        previewTitle.style.display = 'block';
        previewContent.innerHTML = '<div class="flex justify-center py-12"><svg class="animate-spin h-8 w-8 text-gray-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>';
        
        const modalContent = previewContent.closest('.bg-white');
        modalContent.classList.remove('p-0');
        modalContent.classList.add('px-4', 'pb-4', 'pt-5', 'sm:p-6', 'sm:pb-4');
        
        if (file.mime_type && file.mime_type.startsWith('image/')) {
            previewTitle.style.display = 'block';
            previewContent.innerHTML = `<img src="${file.url}" alt="${file.name}" class="max-w-full h-auto mx-auto">`;
        } else if (file.mime_type === 'application/pdf') {
            modalContent.classList.remove('px-4', 'pb-4', 'pt-5', 'sm:p-6', 'sm:pb-4');
            modalContent.classList.add('p-0');
            previewTitle.style.display = 'none';
            previewContent.innerHTML = `
                <div class="flex justify-between items-center px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">${file.name}</h3>
                </div>
                <iframe src="${file.url}" class="w-full h-[80vh]" frameborder="0"></iframe>`;
        } else {
            previewContent.innerHTML = `
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-sm text-gray-500 mb-4">Preview not available for this file type</p>
                    <a href="${file.url}" target="_blank" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        Open in New Tab
                    </a>
                </div>`;
        }
    }

    window.closePreviewModal = function() {
        const modal = document.getElementById('filePreviewModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Transaction functions
    let currentTransactionId = null;

    function editNotes(transactionId) {
        const textElement = document.getElementById(`notes-text-${transactionId}`);
        const inputElement = document.getElementById(`notes-input-${transactionId}`);
        
        textElement.classList.add('hidden');
        inputElement.classList.remove('hidden');
        inputElement.focus();
        inputElement.select();
    }

    function saveNotes(transactionId) {
        const textElement = document.getElementById(`notes-text-${transactionId}`);
        const inputElement = document.getElementById(`notes-input-${transactionId}`);
        const newNotes = inputElement.value;
        
        fetch(`/user/banks/transactions/${transactionId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ notes: newNotes })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                textElement.textContent = newNotes || '-';
                textElement.classList.remove('hidden');
                inputElement.classList.add('hidden');
            }
        });
    }

    function findMatches(transactionId) {
        currentTransactionId = transactionId;
        const modal = document.getElementById('matchModal');
        const content = document.getElementById('match-content');
        
        modal.classList.remove('hidden');
        content.innerHTML = '<div class="text-center py-4"><svg class="animate-spin h-8 w-8 text-gray-500 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>';
        
        fetch(`/user/banks/transactions/${transactionId}/find-matches`)
        .then(response => response.json())
        .then(data => {
            if (data.matches && data.matches.length > 0) {
                let html = '<div class="space-y-2 max-h-96 overflow-y-auto">';
                data.matches.forEach(invoice => {
                    html += `
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">${invoice.invoice_number}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        ${invoice.client_name || 'Unknown'} • ${invoice.invoice_date}
                                    </p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                                        ${invoice.total} ${invoice.currency}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        ${invoice.match_confidence}% match
                                    </span>
                                    <button onclick="matchToInvoice(${invoice.id}, ${invoice.match_confidence})" 
                                            class="mt-2 block w-full px-3 py-1 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                                        Match
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                content.innerHTML = html;
            } else {
                content.innerHTML = '<p class="text-center text-gray-500 dark:text-gray-400 py-4">No matching invoices found</p>';
            }
        });
    }

    function matchToInvoice(invoiceId, confidence) {
        fetch(`/user/banks/transactions/${currentTransactionId}/match`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                invoice_id: invoiceId,
                confidence: confidence
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Failed to match invoice');
            }
        });
    }

    function closeMatchModal() {
        document.getElementById('matchModal').classList.add('hidden');
        currentTransactionId = null;
    }
    
    // Re-analyze bank statement
    function reanalyzeStatement(fileId) {
        if (!confirm('Are you sure you want to re-analyze this bank statement? This will delete existing transactions and extract them again.')) {
            return;
        }
        
        // Show loading state
        const button = event.target.closest('button');
        const originalContent = button.innerHTML;
        button.disabled = true;
        button.innerHTML = `
            <svg class="animate-spin h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ __('Re-analyzing...') }}
        `;
        
        // Make request with force flag
        fetch(`/user/banks/statements/${fileId}/analyze`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ force: true })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw data;
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Reload the page to show new data
                window.location.reload();
            } else {
                console.error('Re-analysis failed:', data);
                alert(data.message || 'Re-analysis failed');
                button.disabled = false;
                button.innerHTML = originalContent;
            }
        })
        .catch(error => {
            console.error('Re-analysis error:', error);
            alert('An error occurred during re-analysis. Check console for details.');
            button.disabled = false;
            button.innerHTML = originalContent;
        });
    }

    // Initialize event listeners
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('filePreviewModal');
        const backdrop = document.getElementById('modal-backdrop');
        
        backdrop.addEventListener('click', function(event) {
            if (event.target === backdrop) {
                closePreviewModal();
            }
        });
        
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                closePreviewModal();
            }
        });
    });
    </script>
</x-user.layout>