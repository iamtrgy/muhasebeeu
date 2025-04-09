<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb Navigation -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-4 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center text-sm">
                        <a href="{{ route('user.dashboard') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            Dashboard
                        </a>
                        <svg class="flex-shrink-0 mx-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">AI Document Classification</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center gap-4 mb-6">
                        <h1 class="text-xl font-semibold">Pending Document Classification</h1>
                        
                        <div class="relative flex-1 max-w-md ml-auto">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            </div>
                            <input type="text" id="tableSearch" class="block w-full ps-10 pe-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500" placeholder="{{ __('Search files...') }}" onkeyup="searchTable()">
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded">
                            {{ session('info') }}
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded">
                            {{ session('warning') }}
                        </div>
                    @endif

                    @if($pendingFiles->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No pending classifications</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">All uploaded documents have been classified.</p>
                        </div>
                    @else
                        <form action="{{ route('user.classification.bulk') }}" method="POST" id="bulk-classify-form">
                            @csrf
                            <div class="flex items-center mb-4 space-x-2">
                                <button type="submit" name="action" value="accept_all" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 text-sm disabled:opacity-50" id="accept-all-btn" disabled>
                                    Accept Selected
                                </button>
                                <button type="submit" name="action" value="ignore_all" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 text-sm disabled:opacity-50" id="ignore-all-btn" disabled>
                                    Ignore Selected
                                </button>
                            </div>

                            <!-- Mobile View -->
                            <div class="block md:hidden">
                                <div class="space-y-4 content-rows">
                                    @foreach($pendingFiles as $file)
                                        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden content-row">
                                            <div class="p-4">
                                                <div class="flex items-center mb-2">
                                                    <input type="checkbox" name="file_ids[]" value="{{ $file->id }}" class="file-checkbox mr-3 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                                    <svg class="h-5 w-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                        {{ $file->name }}
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-3 text-sm">
                                                    <div class="flex items-center mb-1">
                                                        <span class="text-gray-500 dark:text-gray-400 w-20">Current:</span>
                                                        <div>
                                                            <span class="text-gray-900 dark:text-gray-100">{{ $file->folder->name ?? 'Unknown' }}</span>
                                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $file->folder->path ?? 'Unknown' }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="text-gray-500 dark:text-gray-400 w-20">Suggested:</span>
                                                        <div>
                                                            <span class="text-green-600 dark:text-green-400 font-medium">{{ $file->suggestedFolder->name ?? 'Unknown' }}</span>
                                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $file->suggestedFolder->path ?? 'Unknown' }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex justify-end space-x-2">
                                                    <a href="{{ route('user.classification.show', $file) }}" class="inline-flex items-center px-3 py-1.5 border border-blue-600 text-blue-600 rounded text-sm hover:bg-blue-600 hover:text-white transition duration-150">
                                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Review
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Desktop View -->
                            <div class="hidden md:block overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="contentTable">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-6">
                                                <input type="checkbox" id="select-all" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                File
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Current Location
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Suggested Location
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Uploaded
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($pendingFiles as $file)
                                            <tr class="content-row">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="checkbox" name="file_ids[]" value="{{ $file->id }}" class="file-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-5 w-5">
                                                            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                {{ $file->name }}
                                                            </div>
                                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                                {{ $file->size_formatted }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $file->folder->name }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $file->folder->path }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-green-600 dark:text-green-400 font-medium">{{ $file->suggestedFolder->name }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $file->suggestedFolder->path }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    <div>{{ $file->created_at->format('M d, Y') }}</div>
                                                    <div>{{ $file->uploader->name ?? 'Unknown' }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('user.classification.show', $file) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                        Review
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                <!-- Pagination -->
                                <div class="mt-4">
                                    {{ $pendingFiles->links() }}
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all');
            const fileCheckboxes = document.querySelectorAll('.file-checkbox');
            const acceptAllBtn = document.getElementById('accept-all-btn');
            const ignoreAllBtn = document.getElementById('ignore-all-btn');
            const bulkForm = document.getElementById('bulk-classify-form');

            // Select all checkbox functionality
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    fileCheckboxes.forEach(checkbox => {
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                    updateButtonState();
                });
            }

            // Individual checkbox functionality
            fileCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateButtonState();
                    
                    // Update "select all" checkbox state
                    if (selectAllCheckbox) {
                        const allChecked = Array.from(fileCheckboxes).every(cb => cb.checked);
                        const someChecked = Array.from(fileCheckboxes).some(cb => cb.checked);
                        
                        selectAllCheckbox.checked = allChecked;
                        selectAllCheckbox.indeterminate = someChecked && !allChecked;
                    }
                });
            });

            // Update button state based on checkbox selection
            function updateButtonState() {
                const hasCheckedBoxes = Array.from(fileCheckboxes).some(cb => cb.checked);
                
                if (acceptAllBtn) acceptAllBtn.disabled = !hasCheckedBoxes;
                if (ignoreAllBtn) ignoreAllBtn.disabled = !hasCheckedBoxes;
            }

            // Table search functionality
            window.searchTable = function() {
                const input = document.getElementById('tableSearch');
                const filter = input.value.toUpperCase();
                const rows = document.querySelectorAll('.content-row');

                rows.forEach(row => {
                    const textContent = row.textContent || row.innerText;
                    if (textContent.toUpperCase().indexOf(filter) > -1) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            };

            // Initialize button state
            updateButtonState();
        });
    </script>
</x-app-layout>
