<x-user.layout 
    title="" 
    :breadcrumbs="[
        ['title' => __('Home'), 'href' => route('user.dashboard')],
        ['title' => __('Banks'), 'active' => true]
    ]"
>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ __('Bank Statements') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Manage your monthly bank statements for') }} {{ $company->name }}
                        </p>
                    </div>
                    @if($selectedMonthFolder)
                        <x-ui.button.primary 
                            href="{{ route('user.folders.show', $selectedMonthFolder) }}"
                            target="_blank"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            {{ __('Upload Statement') }}
                        </x-ui.button.primary>
                    @endif
                </div>
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
                            onchange="window.location.href='{{ route('user.banks.index') }}?year=' + this.value + '&month={{ $selectedMonth }}'"
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
                                $hasFiles = $monthFolder && $monthFolder->files()->count() > 0;
                            @endphp
                            <a 
                                href="{{ route('user.banks.index', ['year' => $selectedYear, 'month' => $m]) }}"
                                class="px-3 py-2 text-sm font-medium rounded-md transition-colors
                                    {{ $m == $selectedMonth 
                                        ? 'bg-indigo-600 text-white' 
                                        : ($hasFiles 
                                            ? 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' 
                                            : 'text-gray-400 dark:text-gray-500') 
                                    }}"
                            >
                                {{ $monthNames[$m] }}
                                @if($hasFiles)
                                    <span class="ml-1 text-xs">•</span>
                                @endif
                            </a>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Monthly Statements List -->
            <div class="lg:col-span-2">
                <x-ui.card.base>
                    <x-ui.card.header>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            {{ __('Statements') }}
                        </h3>
                    </x-ui.card.header>
                    <x-ui.card.body>
                        @if($files->count() > 0)
                            <div class="space-y-3">
                                @foreach($files as $file)
                                    <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $file->original_name }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ __('Uploaded') }} {{ $file->created_at->diffForHumans() }}
                                                    @if($file->size) • {{ number_format($file->size / 1024 / 1024, 2) }} MB @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button onclick="previewFile({{ json_encode([
                                                'id' => $file->id,
                                                'name' => $file->original_name,
                                                'mime_type' => $file->mime_type,
                                                'url' => route('user.files.preview', $file)
                                            ]) }})" 
                                               class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                               title="{{ __('Preview') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <a href="{{ route('user.files.download', $file) }}" 
                                               class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300"
                                               title="{{ __('Download') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <x-ui.table.empty-state>
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('No bank statements for this month.') }}
                                </p>
                                @if($selectedMonthFolder)
                                    <x-ui.button.primary 
                                        href="{{ route('user.folders.show', $selectedMonthFolder) }}"
                                        target="_blank"
                                        size="sm"
                                        class="mt-4"
                                    >
                                        {{ __('Upload Statement') }}
                                    </x-ui.button.primary>
                                @endif
                            </x-ui.table.empty-state>
                        @endif
                    </x-ui.card.body>
                </x-ui.card.base>
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-6">
                <!-- Monthly Summary -->
                <x-ui.card.base>
                    <x-ui.card.header>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            {{ __('Monthly Summary') }}
                        </h3>
                    </x-ui.card.header>
                    <x-ui.card.body>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('Statements') }}
                                </dt>
                                <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ $files->count() }}
                                </dd>
                            </div>
                            @if($files->count() > 0)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        {{ __('Last Upload') }}
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $files->first()->created_at->format('M d, Y') }}
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </x-ui.card.body>
                </x-ui.card.base>

                <!-- Recent Statements -->
                <x-ui.card.base>
                    <x-ui.card.header>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            {{ __('Recent Statements') }}
                        </h3>
                    </x-ui.card.header>
                    <x-ui.card.body>
                        @if($recentStatements->count() > 0)
                            <ul class="space-y-3">
                                @foreach($recentStatements->take(5) as $statement)
                                    <li>
                                        <button onclick="previewFile({{ json_encode([
                                            'id' => $statement->id,
                                            'name' => $statement->original_name,
                                            'mime_type' => $statement->mime_type,
                                            'url' => route('user.files.preview', $statement)
                                        ]) }})" 
                                           class="block w-full text-left hover:bg-gray-50 dark:hover:bg-gray-700 -mx-2 px-2 py-1 rounded">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $statement->original_name }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                @if($statement->folder && $statement->folder->parent)
                                                    {{ $statement->folder->parent->name }} {{ $statement->folder->name }}
                                                @else
                                                    {{ $statement->created_at->format('M Y') }}
                                                @endif
                                            </p>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('No statements uploaded yet.') }}
                            </p>
                        @endif
                    </x-ui.card.body>
                </x-ui.card.base>
            </div>
        </div>
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

    <script>
    window.previewFile = function(file) {
        const modal = document.getElementById('filePreviewModal');
        const previewTitle = document.getElementById('preview-title');
        const previewContent = document.getElementById('preview-content');
        
        // Show modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Set title and show loading
        previewTitle.textContent = file.name;
        previewTitle.style.display = 'block';
        previewContent.innerHTML = '<div class="flex justify-center py-12"><svg class="animate-spin h-8 w-8 text-gray-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>';
        
        // Reset modal padding first
        const modalContent = previewContent.closest('.bg-white');
        modalContent.classList.remove('p-0');
        modalContent.classList.add('px-4', 'pb-4', 'pt-5', 'sm:p-6', 'sm:pb-4');
        
        // Load content based on file type
        if (file.mime_type && file.mime_type.startsWith('image/')) {
            // Show title for images
            previewTitle.style.display = 'block';
            previewContent.innerHTML = `<img src="${file.url}" alt="${file.name}" class="max-w-full h-auto mx-auto">`;
        } else if (file.mime_type === 'application/pdf') {
            // For PDFs, remove padding and make iframe full size
            modalContent.classList.remove('px-4', 'pb-4', 'pt-5', 'sm:p-6', 'sm:pb-4');
            modalContent.classList.add('p-0');
            
            // Hide the main title since we don't need it for PDFs
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
                    <a href="/user/files/${file.id}/download" class="ml-3 inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
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
    </script>
</x-user.layout>