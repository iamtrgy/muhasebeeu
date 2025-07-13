<x-user.layout title="AI Document Analysis">
    <div class="space-y-6" x-data="aiHistoryManager({{ $files->pluck('id')->toJson() }})">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-ui.card.base>
                <x-ui.card.body>
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-10 w-10 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Analyses</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalAnalyses }}</p>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <x-ui.card.base>
                <x-ui.card.body>
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-10 w-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Accepted</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $acceptedCount }}</p>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <x-ui.card.base>
                <x-ui.card.body>
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-10 w-10 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Avg Confidence</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $avgConfidence }}%</p>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <x-ui.card.base>
                <x-ui.card.body>
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-10 w-10 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Analysis</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                {{ $lastAnalysis ? $lastAnalysis->diffForHumans() : 'Never' }}
                            </p>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        </div>

        <!-- Main Content -->
        <x-ui.card.base>
            <!-- Tabs -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8 px-6 pt-6" aria-label="Tabs">
                    <a href="{{ route('user.ai-analysis.history', ['tab' => 'analyzed'] + request()->only(['search', 'date_from', 'date_to', 'folder', 'confidence', 'status'])) }}" 
                       class="{{ $currentTab === 'analyzed' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Analyzed 
                        <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $currentTab === 'analyzed' ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-300' : 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-gray-300' }}">
                            {{ $tabCounts['analyzed'] }}
                        </span>
                    </a>
                    <a href="{{ route('user.ai-analysis.history', ['tab' => 'not_analyzed'] + request()->only(['search', 'date_from', 'date_to', 'folder', 'confidence', 'status'])) }}"
                       class="{{ $currentTab === 'not_analyzed' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Not Analyzed
                        <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $currentTab === 'not_analyzed' ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-300' : 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-gray-300' }}">
                            {{ $tabCounts['not_analyzed'] }}
                        </span>
                    </a>
                    <a href="{{ route('user.ai-analysis.history', ['tab' => 'all'] + request()->only(['search', 'date_from', 'date_to', 'folder', 'confidence', 'status'])) }}"
                       class="{{ $currentTab === 'all' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        All Files
                        <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $currentTab === 'all' ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-300' : 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-gray-300' }}">
                            {{ $tabCounts['all'] }}
                        </span>
                    </a>
                </nav>
            </div>

            <!-- Bulk Actions Bar -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- Select All -->
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   @change="toggleAll($event.target.checked)" 
                                   :checked="allFileIds.length > 0 && selectedFiles.length === allFileIds.length"
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                Select All (<span x-text="selectedFiles.length"></span>)
                            </label>
                        </div>
                    </div>
                    
                    <!-- Bulk Actions -->
                    <div class="flex items-center space-x-3" x-show="selectedFiles.length > 0">
                        @if($currentTab === 'not_analyzed')
                            <button @click="bulkAnalyze()" :disabled="isProcessing"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                                Analyze Selected
                            </button>
                        @endif
                        
                        @if($currentTab === 'analyzed')
                            <button @click="bulkApprove()" :disabled="isProcessing"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Approve Selected
                            </button>
                        @endif
                        
                        @if($currentTab === 'analyzed')
                            <button @click="bulkReanalyze()" :disabled="isProcessing"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Re-analyze
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- File List -->
            <x-ui.card.body class="p-0">
                @if($files->count() > 0)
                    <!-- Minimal File List -->
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($files as $file)
                            <div class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <!-- Left: Checkbox + File Info -->
                                <div class="flex items-center space-x-4 flex-1 min-w-0">
                                    <input type="checkbox" :checked="selectedFiles.includes({{ $file->id }})" 
                                           @change="toggleFile({{ $file->id }})"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    
                                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                                        @php
                                            $extension = pathinfo($file->original_name, PATHINFO_EXTENSION);
                                            $isPdf = in_array(strtolower($extension), ['pdf']);
                                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                        @endphp
                                        @if($isPdf)
                                            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                            </svg>
                                        @elseif($isImage)
                                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M14,2H6C4.9,2,4,2.9,4,4v16c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V8L14,2z M16,18H8v-2h8V18z M16,14H8v-2h8V14z M13,9V3.5L18.5,9H13z"/>
                                            </svg>
                                        @endif
                                        
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                {{ $file->original_name ?? $file->name }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $file->size_formatted }} • 
                                                @if($file->folder)
                                                    <a href="{{ route('user.folders.show', $file->folder) }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                                        {{ $file->folder->name }}
                                                    </a>
                                                @else
                                                    Root
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Center: AI Status (analyzed only) -->
                                @if($currentTab === 'analyzed' && $file->ai_analyzed_at)
                                    <div class="hidden md:flex items-center space-x-6 px-4 text-center">
                                        <!-- AI Suggestion -->
                                        @if($file->ai_analysis && isset($file->ai_analysis['folder_name']))
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Suggested</p>
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 max-w-24 truncate" 
                                                   title="{{ $file->ai_analysis['folder_path'] ?? $file->ai_analysis['folder_name'] }}"
                                                   data-tooltip="true">
                                                    {{ $file->ai_analysis['folder_name'] }}
                                                </p>
                                            </div>
                                        @endif
                                        
                                        <!-- Confidence -->
                                        @if($file->ai_analysis && isset($file->ai_analysis['confidence']))
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Confidence</p>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $file->ai_analysis['confidence'] }}%</p>
                                            </div>
                                        @endif
                                        
                                        <!-- Status -->
                                        <div>
                                            @if($file->ai_suggestion_accepted)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    ✓ Accepted
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                    Pending
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Right: Quick Actions -->
                                <div class="flex items-center space-x-1">
                                    @if($file->ai_analyzed_at)
                                        <!-- Accept Button (pending only) -->
                                        @if(!$file->ai_suggestion_accepted && $file->ai_analysis && isset($file->ai_analysis['suggested_folder_id']))
                                            <button onclick="acceptSuggestionQuick({{ $file->id }}, {{ $file->ai_analysis['suggested_folder_id'] ?? 'null' }}, '{{ addslashes($file->original_name ?? $file->name) }}')"
                                                    class="inline-flex items-center px-3 py-1 text-xs font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500"
                                                    title="Accept AI suggestion">
                                                ✓ Accept
                                            </button>
                                        @endif
                                        
                                        <button onclick="showAnalysisDetails({{ json_encode($file->ai_analysis) }}, {{ json_encode([
                                            'id' => $file->id,
                                            'original_name' => $file->original_name,
                                            'name' => $file->name,
                                            'current_folder' => $file->folder ? $file->folder->full_path : 'Root',
                                            'ai_suggestion_accepted' => $file->ai_suggestion_accepted,
                                            'ai_analysis' => $file->ai_analysis
                                        ]) }})"
                                                class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 focus:outline-none"
                                                title="View details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                        
                                        <button onclick="showAISuggestionModal({{ $file->id }}, true)"
                                                class="p-1.5 text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 focus:outline-none"
                                                title="Re-analyze">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        </button>
                                    @else
                                        <button onclick="showAISuggestionModal({{ $file->id }})"
                                                class="inline-flex items-center px-3 py-1 text-xs font-medium rounded text-indigo-600 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900 dark:text-indigo-300 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500"
                                                title="Analyze with AI">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                            </svg>
                                            Analyze
                                        </button>
                                    @endif
                                    
                                    <button onclick="window.open('/user/files/{{ $file->id }}/preview', '_blank')"
                                            class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none"
                                            title="View file">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $files->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                            @if($currentTab === 'not_analyzed')
                                No files waiting for analysis
                            @elseif($currentTab === 'analyzed')
                                No analyzed files found
                            @else
                                No files found
                            @endif
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            @if($currentTab === 'not_analyzed')
                                All your files have been analyzed!
                            @else
                                Try adjusting your filters or upload some documents.
                            @endif
                        </p>
                    </div>
                @endif
            </x-ui.card.body>
        </x-ui.card.base>

        <!-- Progress Modal -->
        <div x-show="isProcessing" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6">
                        <div class="flex items-center">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900">
                                <svg class="animate-spin h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            <div class="ml-4 text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" x-text="progressTitle">Processing Files</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400" x-text="progressMessage">Starting...</p>
                                    
                                    <!-- Progress Bar -->
                                    <div class="mt-4">
                                        <div class="bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="bg-indigo-600 dark:bg-indigo-400 h-2 rounded-full transition-all duration-300" 
                                                 :style="`width: ${progressPercentage}%`"></div>
                                        </div>
                                        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            <span x-text="`${progressCurrent} of ${progressTotal}`"></span>
                                            <span x-text="`${progressPercentage}%`"></span>
                                        </div>
                                    </div>
                                    
                                    <!-- Status Messages -->
                                    <div class="mt-4 max-h-32 overflow-y-auto">
                                        <template x-for="message in progressMessages" :key="message.id">
                                            <div class="text-xs py-1" :class="message.type === 'success' ? 'text-green-600 dark:text-green-400' : message.type === 'error' ? 'text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-gray-400'">
                                                <span x-text="message.text"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include AI Suggestion Modal -->
    <x-ai-suggestion-modal />
    
    <!-- Analysis Details Modal - Modern Design -->
    <div id="analysis-details-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeAnalysisDetails()"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Analysis Details</h3>
                    </div>
                    <button onclick="closeAnalysisDetails()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Content -->
                <div class="p-6 overflow-y-auto max-h-[60vh]">
                    <div id="analysis-details-content">
                        <!-- Content will be dynamically inserted -->
                    </div>
                </div>
                
                <!-- Actions Footer -->
                <div class="p-6 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                    <div id="analysis-action-buttons" class="flex flex-wrap gap-3">
                        <!-- Action buttons will be inserted here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Modal state management
        let modalState = 'default'; // default, confirmation, loading, success, folder_selection
        let modalStateData = {};
        let currentConfirmAction = null;
        let currentCancelAction = null;
        
        function setModalState(state, data = {}) {
            modalState = state;
            modalStateData = data;
            
            // Store actions globally for onclick access
            currentConfirmAction = data.confirmAction || null;
            currentCancelAction = data.cancelAction || null;
            
            updateModalContent();
        }
        
        function updateModalContent() {
            const modalTitle = document.querySelector('#analysis-details-modal h3');
            const modalContent = document.getElementById('analysis-details-content');
            const modalActions = document.getElementById('analysis-action-buttons');
            
            switch(modalState) {
                case 'confirmation':
                    // Update title
                    modalTitle.textContent = modalStateData.title || 'Confirm Action';
                    
                    // Update content
                    modalContent.innerHTML = `
                        <div class="text-center py-8">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-amber-100 dark:bg-amber-900/20 mb-4">
                                <svg class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                ${modalStateData.message || 'Are you sure you want to proceed?'}
                            </p>
                        </div>
                    `;
                    
                    // Update action buttons
                    modalActions.innerHTML = `
                        <button type="button" onclick="handleConfirmAction()" 
                                class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            ${modalStateData.confirmText || 'Confirm'}
                        </button>
                        <button type="button" onclick="handleCancelAction()" 
                                class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                            ${modalStateData.cancelText || 'Cancel'}
                        </button>
                    `;
                    break;
                    
                case 'loading':
                    // Update title
                    modalTitle.textContent = modalStateData.title || 'Processing...';
                    
                    // Update content
                    modalContent.innerHTML = `
                        <div class="flex items-center justify-center py-8">
                            <svg class="animate-spin h-8 w-8 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="ml-3 text-gray-600 dark:text-gray-400">${modalStateData.message || 'Please wait...'}</span>
                        </div>
                    `;
                    
                    // Hide action buttons during loading
                    modalActions.innerHTML = '';
                    break;
                    
                case 'success':
                    // Update title
                    modalTitle.textContent = modalStateData.title || 'Success!';
                    
                    // Update content
                    modalContent.innerHTML = `
                        <div class="text-center py-8">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/20 mb-4">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                ${modalStateData.message || 'Operation completed successfully!'}
                            </p>
                        </div>
                    `;
                    
                    // Show close button
                    modalActions.innerHTML = `
                        <button type="button" onclick="closeAnalysisDetails()" 
                                class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Close
                        </button>
                    `;
                    break;
                    
                case 'folder_selection':
                    // Update title
                    modalTitle.textContent = modalStateData.title || 'Select Destination Folder';
                    
                    // Show loading first
                    modalContent.innerHTML = `
                        <div class="flex items-center justify-center py-8">
                            <svg class="animate-spin h-6 w-6 text-indigo-600 dark:text-indigo-400 mr-3" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 718-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Loading folders...</span>
                        </div>
                    `;
                    
                    // Hide action buttons while loading
                    modalActions.innerHTML = '';
                    
                    // Load folders via analyze endpoint
                    loadFoldersForSelection();
                    break;
                    
                case 'default':
                default:
                    // Reset to default state - this will be handled by showAnalysisDetails function
                    break;
            }
        }
        
        // Global handler functions for modal buttons
        window.handleConfirmAction = function() {
            if (currentConfirmAction && typeof currentConfirmAction === 'function') {
                currentConfirmAction();
            }
        }
        
        window.handleCancelAction = function() {
            if (currentCancelAction && typeof currentCancelAction === 'function') {
                currentCancelAction();
            }
        }
        
        function aiHistoryManager(allFileIds = []) {
            return {
                selectedFiles: [],
                isProcessing: false,
                allFileIds: allFileIds,
                
                // Progress tracking
                progressTitle: '',
                progressMessage: '',
                progressCurrent: 0,
                progressTotal: 0,
                progressPercentage: 0,
                progressMessages: [],
                
                // Progress helper methods
                startProgress(title, total) {
                    this.isProcessing = true;
                    this.progressTitle = title;
                    this.progressCurrent = 0;
                    this.progressTotal = total;
                    this.progressPercentage = 0;
                    this.progressMessages = [];
                    this.progressMessage = `Starting to process ${total} files...`;
                },
                
                updateProgress(current, message = '') {
                    this.progressCurrent = current;
                    this.progressPercentage = Math.round((current / this.progressTotal) * 100);
                    if (message) {
                        this.progressMessage = message;
                    }
                },
                
                addProgressMessage(text, type = 'info') {
                    this.progressMessages.push({
                        id: Date.now() + Math.random(),
                        text,
                        type
                    });
                    
                    // Auto-scroll to bottom
                    this.$nextTick(() => {
                        const container = document.querySelector('.max-h-32.overflow-y-auto');
                        if (container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    });
                },
                
                finishProgress(successCount, errorCount = 0) {
                    this.progressPercentage = 100;
                    this.progressMessage = `Completed! ${successCount} successful` + (errorCount > 0 ? `, ${errorCount} errors` : '');
                    
                    setTimeout(() => {
                        this.isProcessing = false;
                        if (successCount > 0) {
                            window.location.reload();
                        }
                    }, 2000);
                },
                
                toggleFile(fileId) {
                    if (this.selectedFiles.includes(fileId)) {
                        this.selectedFiles = this.selectedFiles.filter(id => id !== fileId);
                    } else {
                        this.selectedFiles.push(fileId);
                    }
                },
                
                toggleAll(checked) {
                    if (checked) {
                        this.selectedFiles = [...this.allFileIds];
                    } else {
                        this.selectedFiles = [];
                    }
                },
                
                async bulkAnalyze() {
                    if (!this.selectedFiles.length) return;
                    
                    if (!confirm(`Analyze ${this.selectedFiles.length} selected files?`)) return;
                    
                    this.startProgress('Analyzing Files', this.selectedFiles.length);
                    
                    try {
                        this.addProgressMessage('Starting batch analysis...', 'info');
                        
                        const response = await fetch('/user/files/batch-analyze', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ file_ids: this.selectedFiles })
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            this.updateProgress(this.progressTotal, 'Analysis completed!');
                            this.addProgressMessage(`Successfully analyzed ${this.selectedFiles.length} files!`, 'success');
                            this.finishProgress(this.selectedFiles.length);
                        } else {
                            throw new Error(data.error || 'Failed to analyze files');
                        }
                    } catch (error) {
                        this.addProgressMessage(`Error: ${error.message}`, 'error');
                        this.finishProgress(0, this.selectedFiles.length);
                    }
                },
                
                async bulkApprove() {
                    if (!this.selectedFiles.length) return;
                    
                    if (!confirm(`Approve AI suggestions for ${this.selectedFiles.length} selected files? This will move files to their suggested folders.`)) return;
                    
                    this.startProgress('Approving Suggestions', this.selectedFiles.length);
                    
                    try {
                        this.addProgressMessage('Processing bulk approval...', 'info');
                        
                        const response = await fetch('/user/ai-analysis/bulk-approve', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ file_ids: this.selectedFiles })
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            this.updateProgress(this.progressTotal, data.message);
                            this.addProgressMessage(`Moved: ${data.moved} files`, 'success');
                            
                            if (data.skipped > 0) {
                                this.addProgressMessage(`Skipped: ${data.skipped} files (already in correct folder)`, 'info');
                            }
                            
                            // Show detailed errors
                            if (data.errors && data.errors.length > 0) {
                                data.errors.forEach(error => {
                                    this.addProgressMessage(error, 'error');
                                });
                            }
                            
                            this.finishProgress(data.moved, data.errors ? data.errors.length : 0);
                        } else {
                            throw new Error(data.error || 'Failed to approve suggestions');
                        }
                    } catch (error) {
                        this.addProgressMessage(`Error: ${error.message}`, 'error');
                        this.finishProgress(0, this.selectedFiles.length);
                    }
                },
                
                async bulkReanalyze() {
                    if (!this.selectedFiles.length) return;
                    
                    if (!confirm(`Re-analyze ${this.selectedFiles.length} selected files? This will generate new AI analysis for each file.`)) return;
                    
                    this.startProgress('Re-analyzing Files', this.selectedFiles.length);
                    
                    try {
                        // Process files one by one for re-analysis with progress updates
                        let completed = 0;
                        let errors = [];
                        
                        for (let i = 0; i < this.selectedFiles.length; i++) {
                            const fileId = this.selectedFiles[i];
                            
                            this.updateProgress(i, `Re-analyzing file ${i + 1} of ${this.selectedFiles.length}...`);
                            this.addProgressMessage(`Processing file ID: ${fileId}`, 'info');
                            
                            try {
                                const response = await fetch(`/user/files/${fileId}/analyze?force_new=1`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                    }
                                });
                                
                                if (response.ok) {
                                    completed++;
                                    this.addProgressMessage(`✓ File ${fileId} analyzed successfully`, 'success');
                                } else {
                                    const errorText = `File ${fileId}: Failed to re-analyze`;
                                    errors.push(errorText);
                                    this.addProgressMessage(`✗ ${errorText}`, 'error');
                                }
                            } catch (error) {
                                const errorText = `File ${fileId}: ${error.message}`;
                                errors.push(errorText);
                                this.addProgressMessage(`✗ ${errorText}`, 'error');
                            }
                        }
                        
                        this.finishProgress(completed, errors.length);
                        
                    } catch (error) {
                        this.addProgressMessage(`Critical error: ${error.message}`, 'error');
                        this.finishProgress(0, this.selectedFiles.length);
                    }
                }
            }
        }
        
        // Global variable to store current file data for modal actions
        let currentModalFile = null;
        
        // Make functions globally accessible for onclick handlers
        window.showAnalysisDetails = function showAnalysisDetails(analysis, fileData = null) {
            if (!analysis) return;
            
            // Store current file data for actions
            currentModalFile = fileData;
            
            // Reset modal state to default
            modalState = 'default';
            modalStateData = {};
            currentConfirmAction = null;
            currentCancelAction = null;
            
            const content = document.getElementById('analysis-details-content');
            content.innerHTML = `
                <div class="space-y-6">
                    <!-- File Info Card -->
                    ${fileData ? `
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-base font-semibold text-gray-900 dark:text-white">File Information</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">${fileData.size_formatted || 'Unknown size'}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-3">
                                <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-600 last:border-b-0">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">File Name</span>
                                    <span class="text-sm text-gray-900 dark:text-white font-mono">${fileData.original_name || fileData.name}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-600 last:border-b-0">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Current Location</span>
                                    <span class="text-sm text-gray-900 dark:text-white font-mono">${fileData.current_folder || 'Root'}</span>
                                </div>
                            </div>
                        </div>
                    ` : ''}
                    
                    <!-- AI Analysis Section -->
                    ${analysis.reasoning ? `
                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-xl p-4 border border-purple-200 dark:border-purple-700">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                </div>
                                <h4 class="text-base font-semibold text-gray-900 dark:text-white">AI Analysis</h4>
                            </div>
                            <p class="text-sm leading-relaxed ${analysis.reasoning.toLowerCase().includes('already in the correct folder') || analysis.reasoning.toLowerCase().includes('already in correct folder') ? 'text-green-700 dark:text-green-300 font-medium bg-green-50 dark:bg-green-900/20 p-3 rounded-lg border border-green-200 dark:border-green-700' : 'text-gray-700 dark:text-gray-300'}">${analysis.reasoning}</p>
                        </div>
                    ` : ''}
                    
                    <!-- Suggested Folder Section -->
                    ${analysis.folder_path || analysis.folder_name ? `
                        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 rounded-xl p-4 border border-indigo-200 dark:border-indigo-700">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-base font-semibold text-gray-900 dark:text-white">Suggested Location</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 font-mono">${analysis.folder_path || analysis.folder_name}</p>
                                </div>
                            </div>
                            ${analysis.confidence ? `
                                <div class="flex items-center justify-between bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Confidence Level</span>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-24 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-indigo-500 to-blue-500 h-2 rounded-full transition-all duration-500" style="width: ${analysis.confidence}%"></div>
                                        </div>
                                        <span class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">${analysis.confidence}%</span>
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                    ` : ''}
                    
                    <!-- Alternative Folders with Use This buttons -->
                    ${analysis.alternative_folders && analysis.alternative_folders.length > 0 ? (() => {
                        // Filter out duplicates and current folder
                        const currentFolderName = fileData.folder ? fileData.folder.name : 'Root';
                        const suggestedFolderName = analysis.folder_path ? analysis.folder_path.split('/').pop() : analysis.folder_name || '';
                        const seen = new Set([currentFolderName, suggestedFolderName]);
                        
                        const uniqueAlternatives = [];
                        analysis.alternative_folders.forEach(alt => {
                            const folderName = alt.folder_path ? alt.folder_path.split('/').pop() : alt.folder_name || alt.name || 'Unknown';
                            if (!seen.has(folderName) && alt.folder_id) {
                                seen.add(folderName);
                                uniqueAlternatives.push(alt);
                            }
                        });
                        
                        return uniqueAlternatives.length > 0 ? `
                            <div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-xl p-4 border border-amber-200 dark:border-amber-700">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    </div>
                                    <h4 class="text-base font-semibold text-gray-900 dark:text-white">Alternative Suggestions</h4>
                                </div>
                                <div class="grid gap-3">
                                    ${uniqueAlternatives.map((alt, index) => {
                                        const altFolderName = alt.folder_path ? alt.folder_path.split('/').pop() : alt.folder_name || alt.name || 'Unknown';
                                        return `
                                            <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-600 hover:shadow-sm transition-shadow">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-2 mb-1">
                                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                            </svg>
                                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                                ${altFolderName}
                                                            </p>
                                                        </div>
                                                        ${alt.reason ? `<p class="text-xs text-gray-600 dark:text-gray-400 ml-6">${alt.reason}</p>` : ''}
                                                    </div>
                                                    <button onclick="acceptAlternativeSuggestion(${alt.folder_id}, '${altFolderName.replace(/'/g, "\\'")}')" 
                                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg bg-amber-600 hover:bg-amber-700 text-white transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Use This
                                                    </button>
                                                </div>
                                            </div>
                                        `;
                                    }).join('')}
                                </div>
                            </div>
                        ` : '';
                    })() : ''}
                    
                    ${analysis.document_date ? `
                        <div>
                            <h5 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-1">Document Date</h5>
                            <p class="text-sm text-gray-600 dark:text-gray-400">${analysis.document_date}</p>
                        </div>
                    ` : ''}
                    
                </div>
            `;
            
            // Create action buttons with proper styling and SVG icons
            createActionButtons(analysis, fileData);
            
            document.getElementById('analysis-details-modal').classList.remove('hidden');
        }
        
        function createActionButtons(analysis, fileData) {
            const actionContainer = document.getElementById('analysis-action-buttons');
            
            // Check if file is already in the correct folder
            let isInCorrectFolder = false;
            if (fileData && analysis) {
                // Get the current folder path
                const currentFolderPath = fileData.current_folder || 'Root';
                
                // Get the suggested folder path
                const suggestedFolderPath = analysis.folder_path || analysis.folder_name || '';
                
                // Compare full paths (normalize by removing trailing slashes)
                const normalizedCurrent = currentFolderPath.replace(/\/$/, '').toLowerCase();
                const normalizedSuggested = suggestedFolderPath.replace(/\/$/, '').toLowerCase();
                
                isInCorrectFolder = normalizedCurrent === normalizedSuggested;
                
                // Debug log to see what we're comparing
                console.log('Folder comparison:', {
                    current: normalizedCurrent,
                    suggested: normalizedSuggested,
                    isInCorrectFolder: isInCorrectFolder,
                    reasoning: analysis.reasoning
                });
            }
            
            let buttonsHTML = '';
            
            // Primary Action - Accept Suggestion (only if pending and not in correct folder)
            if (fileData && analysis && analysis.suggested_folder_id && !fileData.ai_suggestion_accepted && !isInCorrectFolder) {
                buttonsHTML += `
                    <button type="button" onclick="acceptAnalysisSuggestion()" 
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Accept Suggestion
                    </button>
                `;
            }
            
            // Secondary Actions
            buttonsHTML += `
                <button type="button" onclick="reanalyzeFromDetails()" 
                        class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg border border-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Re-analyze
                </button>
                
                <button type="button" onclick="manualMoveFromDetails()" 
                        class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg border border-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                    Move File
                </button>
            `;
            
            // File actions (only if fileData is available)
            if (fileData && fileData.id) {
                buttonsHTML += `
                    <button type="button" onclick="previewFileFromDetails()" 
                            class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg border border-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Preview File
                    </button>
                    
                    <button type="button" onclick="goToFolder()" 
                            class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg border border-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        Go to Folder
                    </button>
                `;
            }
            
            actionContainer.innerHTML = buttonsHTML;
        }
        
        // Inline error display function
        function showInlineError(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transition-opacity duration-300';
            errorDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    ${message}
                </div>
            `;
            document.body.appendChild(errorDiv);
            setTimeout(() => errorDiv.remove(), 5000);
        }
        
        window.acceptAlternativeSuggestion = function acceptAlternativeSuggestion(folderId, folderName) {
            if (!currentModalFile || !currentModalFile.id) {
                showInlineError('No file selected');
                return;
            }
            
            // Switch to loading state
            setModalState('loading', {
                title: 'Moving File',
                message: 'Moving file to alternative folder...'
            });
            
            fetch(`/user/files/${currentModalFile.id}/accept-suggestion`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    folder_id: folderId
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Switch to success state
                        setModalState('success', {
                            title: 'File Moved Successfully',
                            message: `The file has been moved to "${folderName}".`
                        });
                        
                        setTimeout(() => {
                            closeAnalysisDetails();
                            window.location.reload();
                        }, 2000);
                    } else {
                        // Switch back to default and show error
                        setModalState('default');
                        showInlineError('Failed to move file: ' + (data.error || 'Unknown error'));
                        
                        // Restore the original view
                        if (currentModalFile && currentModalFile.id && currentModalFile.ai_analysis) {
                            showAnalysisDetails(currentModalFile.ai_analysis, currentModalFile);
                        }
                    }
                })
                .catch(error => {
                    setModalState('default');
                    showInlineError('Network error: ' + error.message);
                    
                    // Restore the original view
                    if (currentModalFile && currentModalFile.id && currentModalFile.ai_analysis) {
                        showAnalysisDetails(currentModalFile.ai_analysis, currentModalFile);
                    }
                });
        }
        
        function acceptAnalysisSuggestion() {
            if (!currentModalFile || !currentModalFile.id) {
                alert('No file selected');
                return;
            }
            
            const analysis = currentModalFile.ai_analysis || {};
            if (!analysis.suggested_folder_id) {
                alert('No folder suggestion available');
                return;
            }
            
            if (confirm(`Accept AI suggestion and move "${currentModalFile.original_name || currentModalFile.name}" to the suggested folder?`)) {
                fetch(`/user/files/${currentModalFile.id}/accept-suggestion`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        folder_id: analysis.suggested_folder_id
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('File moved successfully!');
                        closeAnalysisDetails();
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.error || 'Failed to move file'));
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
            }
        }
        
        // Make all functions globally accessible for onclick handlers
        window.manualMoveFromDetails = manualMoveFromDetails;
        window.viewFileFromDetails = viewFileFromDetails;
        window.closeAnalysisDetails = closeAnalysisDetails;
        window.acceptAnalysisSuggestion = acceptAnalysisSuggestion;
        window.restoreAnalysisDetailsView = restoreAnalysisDetailsView;
        window.selectFolderAndConfirm = selectFolderAndConfirm;
        
        window.reanalyzeFromDetails = function reanalyzeFromDetails() {
            if (!currentModalFile || !currentModalFile.id) {
                showInlineError('No file selected');
                return;
            }
            
            // Disable all buttons during reanalysis
            const buttons = document.querySelectorAll('#analysis-action-buttons button');
            buttons.forEach(btn => btn.disabled = true);
            
            // Add loading state to re-analyze button
            const reanalyzeBtn = document.querySelector('#analysis-action-buttons button[onclick*="reanalyzeFromDetails"]');
            const textSpan = reanalyzeBtn.querySelector('span') || reanalyzeBtn;
            const spinner = reanalyzeBtn.querySelector('.reanalyze-spinner');
            
            if (textSpan) textSpan.textContent = 'Analyzing...';
            if (spinner) spinner.classList.remove('hidden');
            
            const startTime = Date.now();
            
            fetch(`/user/files/${currentModalFile.id}/analyze`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    force_new: true
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Reanalysis response:', data);
                
                // Check if analysis actually changed
                if (currentModalFile.ai_analysis && data.analysis) {
                    const oldAnalysis = JSON.stringify(currentModalFile.ai_analysis);
                    const newAnalysis = JSON.stringify(data.analysis);
                    if (oldAnalysis === newAnalysis) {
                        console.warn('WARNING: Analysis returned is identical to previous analysis!');
                    } else {
                        console.log('Analysis has changed - new results received');
                    }
                }
                
                // Always restore button state
                if (textSpan) textSpan.textContent = 'Re-analyze';
                if (spinner) spinner.classList.add('hidden');
                buttons.forEach(btn => btn.disabled = false);
                
                if (data.success && data.analysis) {
                    // Update currentModalFile with new analysis data
                    currentModalFile = {
                        ...currentModalFile,
                        ai_analysis: data.analysis,
                        ai_analyzed_at: new Date().toISOString()
                    };
                    
                    console.log('Updated modal file:', currentModalFile);
                    
                    // Update the modal with new analysis
                    const fileDataForModal = {
                        id: currentModalFile.id,
                        original_name: currentModalFile.original_name || currentModalFile.name,
                        name: currentModalFile.name,
                        size_formatted: currentModalFile.size_formatted || 'Unknown size',
                        folder: currentModalFile.folder || null,
                        current_folder: currentModalFile.current_folder || 'Root',
                        ai_suggestion_accepted: false, // Reset since it's a new analysis
                        ai_analysis: data.analysis,
                        ai_analyzed_at: new Date().toISOString()
                    };
                    
                    console.log('Calling showAnalysisDetails with:', data.analysis, fileDataForModal);
                    showAnalysisDetails(data.analysis, fileDataForModal);
                    
                    // Show success message
                    const successMsg = document.createElement('div');
                    successMsg.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transition-opacity duration-300';
                    successMsg.innerHTML = `
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            File re-analyzed successfully!
                        </div>
                    `;
                    document.body.appendChild(successMsg);
                    setTimeout(() => successMsg.remove(), 3000);
                } else {
                    showInlineError('Failed to re-analyze: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Reanalysis error:', error);
                
                // Restore button state
                if (textSpan) textSpan.textContent = 'Re-analyze';
                if (spinner) spinner.classList.add('hidden');
                buttons.forEach(btn => btn.disabled = false);
                
                const errorMsg = document.createElement('div');
                errorMsg.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                errorMsg.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Failed to re-analyze: ${error.message}
                    </div>
                `;
                document.body.appendChild(errorMsg);
                setTimeout(() => errorMsg.remove(), 5000);
            });
        }
        
        function manualMoveFromDetails() {
            if (!currentModalFile || !currentModalFile.id) {
                alert('No file selected');
                return;
            }
            
            // Switch to folder selection state
            setModalState('folder_selection', {
                title: 'Select Destination Folder',
                message: 'Choose where to move this file:'
            });
        }
        
        function loadFoldersForSelection() {
            if (!currentModalFile || !currentModalFile.id) {
                showFolderSelectionError('No file selected');
                return;
            }
            
            // Fetch available folders by triggering analyze to get folder suggestions
            fetch(`/user/files/${currentModalFile.id}/analyze`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    throw new Error(data.error || 'Analysis failed');
                }
                
                // Get folders from analyze response
                let folders = [];
                if (data.folders && Array.isArray(data.folders)) {
                    folders = data.folders;
                } else if (data.suggested_folder && data.alternative_folders) {
                    // Combine suggested and alternative folders
                    folders = [data.suggested_folder, ...data.alternative_folders];
                } else {
                    console.error('No folders in response:', data);
                    throw new Error('No folders available');
                }
                
                if (folders.length === 0) {
                    throw new Error('No folders available');
                }
                
                // Display folders for selection
                displayFolderSelection(folders);
            })
            .catch(error => {
                console.error('Error loading folders:', error);
                showFolderSelectionError('Failed to load folders: ' + error.message);
            });
        }
        
        function displayFolderSelection(folders) {
            const modalContent = document.getElementById('analysis-details-content');
            const modalActions = document.getElementById('analysis-action-buttons');
            
            // Update content with folder selection
            modalContent.innerHTML = `
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        ${modalStateData.message || 'Choose where to move this file:'}
                    </p>
                    <div class="max-h-96 overflow-y-auto space-y-1">
                        ${folders.map(folder => `
                            <button onclick="selectFolderAndConfirm(${folder.id}, '${(folder.path || folder.name || 'Unknown').replace(/'/g, "\\'")}')"
                                    class="w-full text-left px-3 py-2 text-sm rounded hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center justify-between group transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">${folder.path || folder.name || 'Unknown'}</span>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 opacity-0 group-hover:opacity-100">Select</span>
                            </button>
                        `).join('')}
                    </div>
                </div>
            `;
            
            // Update action buttons - just a back button
            modalActions.innerHTML = `
                <button type="button" onclick="restoreAnalysisDetailsView()" 
                        class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                    ← Back
                </button>
            `;
        }
        
        function showFolderSelectionError(message) {
            const modalContent = document.getElementById('analysis-details-content');
            const modalActions = document.getElementById('analysis-action-buttons');
            
            modalContent.innerHTML = `
                <div class="text-center py-8">
                    <p class="text-red-600 dark:text-red-400">${message}</p>
                </div>
            `;
            
            modalActions.innerHTML = `
                <button type="button" onclick="restoreAnalysisDetailsView()" 
                        class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                    ← Back
                </button>
            `;
        }
        
        function restoreAnalysisDetailsView() {
            // Reset modal state variables without calling updateModalContent
            modalState = 'default';
            modalStateData = {};
            currentConfirmAction = null;
            currentCancelAction = null;
            
            // Restore the original analysis details view directly
            if (currentModalFile && currentModalFile.id && currentModalFile.ai_analysis) {
                showAnalysisDetails(currentModalFile.ai_analysis, currentModalFile);
            }
        }
        
        function selectFolderAndConfirm(folderId, folderName) {
            // Switch to confirmation state
            setModalState('confirmation', {
                title: 'Confirm Move',
                message: `Move file to "${folderName}"?`,
                confirmText: 'Yes, Move',
                cancelText: 'Cancel',
                confirmAction: () => executeFileMove(folderId, folderName),
                cancelAction: () => restoreAnalysisDetailsView()
            });
        }
        
        function executeFileMove(folderId, folderName) {
            // Switch to loading state
            setModalState('loading', {
                title: 'Moving File',
                message: 'Moving file to selected folder...'
            });
            
            fetch(`/user/files/${currentModalFile.id}/accept-suggestion`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    folder_id: folderId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Switch to success state
                    setModalState('success', {
                        title: 'File Moved Successfully',
                        message: `The file has been moved to "${folderName}".`
                    });
                    
                    setTimeout(() => {
                        closeAnalysisDetails();
                        window.location.reload();
                    }, 2000);
                } else {
                    // Switch back to default and show error
                    setModalState('default');
                    alert('Failed to move file: ' + (data.error || 'Unknown error'));
                    
                    // Restore the original view
                    if (currentModalFile && currentModalFile.id && currentModalFile.ai_analysis) {
                        showAnalysisDetails(currentModalFile.ai_analysis, currentModalFile);
                    }
                }
            })
            .catch(error => {
                setModalState('default');
                alert('Network error: ' + error.message);
                
                // Restore the original view
                if (currentModalFile && currentModalFile.id && currentModalFile.ai_analysis) {
                    showAnalysisDetails(currentModalFile.ai_analysis, currentModalFile);
                }
            });
        }
        
        function viewFileFromDetails(fileId = null) {
            const id = fileId || (currentModalFile && currentModalFile.id);
            if (!id) {
                alert('No file selected');
                return;
            }
            
            // Open file preview in new tab
            window.open(`/user/files/${id}/preview`, '_blank');
        }
        
        function closeAnalysisDetails() {
            document.getElementById('analysis-details-modal').classList.add('hidden');
        }
        
        // Quick accept function for inline buttons
        function acceptSuggestionQuick(fileId, folderId, fileName) {
            if (confirm(`Accept AI suggestion and move "${fileName}" to the suggested folder?`)) {
                fetch(`/user/files/${fileId}/accept-suggestion`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        folder_id: folderId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('File moved successfully!');
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.error || 'Failed to move file'));
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
            }
        }
        
        function reanalyzeFile(fileId) {
            if (confirm('Are you sure you want to re-analyze this file? This will generate a new AI analysis.')) {
                // Force re-analysis by passing true for forceNew parameter
                showAISuggestionModal(fileId, true);
            }
        }
        
        // Preview file function
        function previewFileFromDetails() {
            if (!currentModalFile || !currentModalFile.id) {
                showInlineError('No file selected');
                return;
            }
            window.open(`/user/files/${currentModalFile.id}/preview`, '_blank');
        }
        
        // Go to folder function
        function goToFolder() {
            if (!currentModalFile || !currentModalFile.folder) {
                showInlineError('No folder information available');
                return;
            }
            window.open(`/user/folders/${currentModalFile.folder.id}`, '_blank');
        }
        
        // Make new functions globally accessible
        window.previewFileFromDetails = previewFileFromDetails;
        window.goToFolder = goToFolder;
    </script>
    @endpush
</x-user.layout>
