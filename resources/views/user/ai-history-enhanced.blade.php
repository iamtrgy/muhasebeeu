<x-user.layout title="AI Document Analysis">
    <div class="space-y-4" x-data="aiHistoryManager({{ $files->pluck('id')->toJson() }}, {{ $files->map(function($file) { return ['id' => $file->id, 'ai_suggestion_accepted' => $file->ai_suggestion_accepted]; })->toJson() }})">
        <x-ai-history.summary-cards 
            :total-analyses="$totalAnalyses"
            :accepted-count="$acceptedCount" 
            :avg-confidence="$avgConfidence"
            :last-analysis="$lastAnalysis" />

        <!-- Main Content -->
        <x-ui.card.base>
            <x-ai-history.tabs :current-tab="$currentTab" :tab-counts="$tabCounts" />

            <!-- Bulk Actions Bar (Only shown when files are selected) -->
            <div class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-600" x-show="selectedFiles.length > 0" x-transition>
                <div class="px-4 py-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                <span x-text="selectedFiles.length"></span> files selected
                            </span>
                        </div>
                        
                        <!-- Bulk Actions -->
                        <div class="flex items-center space-x-2">
                            @if($currentTab === 'not_analyzed')
                                <button @click="bulkAnalyze()" :disabled="isProcessing"
                                        class="inline-flex items-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded border border-transparent transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                    <span x-text="isProcessing ? 'Analyzing...' : 'Analyze Selected'"></span>
                                </button>
                            @endif
                            
                            @if($currentTab === 'analyzed')
                                <button @click="bulkApprove()" :disabled="isProcessing" x-show="hasSelectedFilesPendingApproval"
                                        class="inline-flex items-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded border border-transparent transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span x-text="isProcessing ? 'Approving...' : 'Approve Selected'"></span>
                                </button>
                                
                                <button @click="bulkReanalyze()" :disabled="isProcessing"
                                        class="inline-flex items-center px-3 py-2 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium rounded border border-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 disabled:opacity-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    <span x-text="isProcessing ? 'Re-analyzing...' : 'Re-analyze'"></span>
                                </button>
                            @endif
                            
                            @if($currentTab === 'all')
                                <!-- Show appropriate actions based on selected files -->
                                <button @click="bulkAnalyze()" :disabled="isProcessing" x-show="hasSelectedFilesNotAnalyzed"
                                        class="inline-flex items-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded border border-transparent transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                    <span x-text="isProcessing ? 'Analyzing...' : 'Analyze Selected'"></span>
                                </button>
                                
                                <button @click="bulkApprove()" :disabled="isProcessing" x-show="hasSelectedFilesPendingApproval"
                                        class="inline-flex items-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded border border-transparent transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span x-text="isProcessing ? 'Approving...' : 'Approve Selected'"></span>
                                </button>
                                
                                <button @click="bulkReanalyze()" :disabled="isProcessing" x-show="hasSelectedFilesAnalyzed"
                                        class="inline-flex items-center px-3 py-2 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium rounded border border-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 disabled:opacity-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    <span x-text="isProcessing ? 'Re-analyzing...' : 'Re-analyze'"></span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- File List -->
            <x-ui.card.body class="p-0">
                @if($files->count() > 0)
                    <!-- File List Header -->
                    <div class="bg-gray-50 dark:bg-gray-800 px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" 
                                   @change="toggleAll($event.target.checked)" 
                                   :checked="allFileIds.length > 0 && selectedFiles.length === allFileIds.length"
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Select All
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400" x-show="selectedFiles.length > 0">
                                (<span x-text="selectedFiles.length"></span> selected)
                            </span>
                        </div>
                    </div>
                    
                    <!-- File List -->
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($files as $file)
                            <div class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors" 
                                 data-file-id="{{ $file->id }}"
                                 data-file='@json($file)'>
                                <!-- Left: Checkbox + File Info -->
                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                    <!-- Enhanced Checkbox -->
                                    <input type="checkbox" :checked="selectedFiles.includes({{ $file->id }})" 
                                           @change="toggleFile({{ $file->id }})"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-4 h-4">
                                    
                                    <!-- File Icon and Info -->
                                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                                        <x-ai-history.file-icon :file="$file" />
                                        
                                        <!-- File Details -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2">
                                                <button onclick="showFilePreview('{{ $file->original_name ?? $file->name }}', '{{ $file->mime_type ?? 'application/octet-stream' }}', '{{ $file->url }}')"
                                                        class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 truncate text-left">
                                                    {{ $file->original_name ?? $file->name }}
                                                </button>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                                    {{ $file->size_formatted }}
                                                </span>
                                            </div>
                                            <div class="flex items-center mt-1 space-x-2">
                                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                </svg>
                                                @if($file->folder)
                                                    <a href="{{ route('user.folders.show', $file->folder) }}" target="_blank" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                                                        {{ $file->folder->name }}
                                                    </a>
                                                @else
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">Root</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Center: AI Status (show for all tabs if analyzed) -->
                                @if($file->ai_analyzed_at)
                                    <div class="hidden lg:flex items-center space-x-4 text-sm">
                                        <!-- AI Suggestion -->
                                        @if($file->ai_analysis)
                                            <div>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">Suggested:</span>
                                                @if(isset($file->ai_analysis['suggest_deletion']) && $file->ai_analysis['suggest_deletion'])
                                                    <span class="text-xs font-medium text-red-600 dark:text-red-400 ml-1">
                                                        Delete File
                                                    </span>
                                                @elseif(isset($file->ai_analysis['folder_name']))
                                                    @php
                                                        // Get the actual suggested folder from database to ensure correct path
                                                        $suggestedFolderId = $file->ai_analysis['suggested_folder_id'] ?? null;
                                                        $actualFolder = null;
                                                        if ($suggestedFolderId) {
                                                            $actualFolder = auth()->user()->folders()->find($suggestedFolderId);
                                                        }
                                                        
                                                        // Use actual database path if available, otherwise fallback to stored analysis
                                                        $suggestedPath = $actualFolder ? $actualFolder->full_path : ($file->ai_analysis['folder_path'] ?? $file->ai_analysis['folder_name']);
                                                        $suggestedName = $actualFolder ? $actualFolder->name : $file->ai_analysis['folder_name'];
                                                        
                                                        if (!$actualFolder && $suggestedPath && str_contains($suggestedPath, '/')) {
                                                            $suggestedName = last(explode('/', $suggestedPath));
                                                        }
                                                    @endphp
                                                    <span class="inline-flex items-center relative group">
                                                        <span class="text-xs font-medium text-indigo-600 dark:text-indigo-400 ml-1 cursor-help border-b border-dotted border-indigo-300 dark:border-indigo-500">
                                                            {{ $suggestedName }}
                                                        </span>
                                                        <svg class="w-3 h-3 ml-1 text-gray-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <!-- Custom Tooltip -->
                                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                            Suggested Folder: {{ $suggestedPath }}
                                                            <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
                                                        </div>
                                                    </span>
                                                @else
                                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400 ml-1">
                                                        No suggestion
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                        
                                        <!-- Confidence -->
                                        @if($file->ai_analysis && isset($file->ai_analysis['confidence']))
                                            <div>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">Confidence:</span>
                                                <span class="text-xs font-medium text-gray-900 dark:text-gray-100 ml-1">
                                                    {{ $file->ai_analysis['confidence'] }}%
                                                </span>
                                            </div>
                                        @endif
                                        
                                        <!-- Status -->
                                        @php
                                            $currentFolderId = $file->folder_id;
                                            $suggestedFolderId = $file->ai_analysis['suggested_folder_id'] ?? null;
                                            $isInCorrectFolder = $currentFolderId == $suggestedFolderId;
                                            $wasRecentlyAnalyzed = $file->ai_analyzed_at && $file->ai_analyzed_at->diffInMinutes(now()) < 5;
                                            $suggestsDeletion = isset($file->ai_analysis['suggest_deletion']) && $file->ai_analysis['suggest_deletion'];
                                        @endphp
                                        
                                        @if($suggestsDeletion)
                                            {{-- File is marked for deletion and needs review --}}
                                            <button onclick="showAISuggestionModal({{ $file->id }})" 
                                                    class="inline-flex items-center text-xs text-red-600 dark:text-red-400 font-medium hover:text-red-700 dark:hover:text-red-300 transition-colors cursor-pointer"
                                                    @if(isset($file->ai_analysis['deletion_reason']))
                                                        title="{{ $file->ai_analysis['deletion_reason'] }}"
                                                    @else
                                                        title="AI suggests deletion"
                                                    @endif>
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete Suggested
                                            </button>
                                        @elseif($isInCorrectFolder)
                                            {{-- File is already in the correct folder --}}
                                            <span class="inline-flex items-center text-xs text-green-600 dark:text-green-400 font-medium">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                Correct Place
                                            </span>
                                        @elseif($file->ai_suggestion_accepted)
                                            {{-- File was moved and accepted --}}
                                            <span class="inline-flex items-center text-xs text-green-600 dark:text-green-400 font-medium">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                Accepted
                                            </span>
                                        @elseif($wasRecentlyAnalyzed)
                                            {{-- File was just analyzed and needs review --}}
                                            <button onclick="showAISuggestionModal({{ $file->id }})" 
                                                    class="inline-flex items-center text-xs text-blue-600 dark:text-blue-400 font-medium hover:text-blue-700 dark:hover:text-blue-300 transition-colors cursor-pointer">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                Just Analyzed
                                            </button>
                                        @else
                                            {{-- File needs review --}}
                                            <button onclick="showAISuggestionModal({{ $file->id }})" 
                                                    class="inline-flex items-center text-xs text-amber-600 dark:text-amber-400 font-medium hover:text-amber-700 dark:hover:text-amber-300 transition-colors cursor-pointer">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Needs Review
                                            </button>
                                        @endif
                                    </div>
                                @endif

                                <!-- Right: Quick Actions -->
                                <div class="flex items-center space-x-2 ml-4">
                                    @if($file->ai_analyzed_at)
                                        <!-- Action Button (Accept move or Delete file) -->
                                        @if(!$file->ai_suggestion_accepted && $file->ai_analysis)
                                            @if(isset($file->ai_analysis['suggest_deletion']) && $file->ai_analysis['suggest_deletion'])
                                                {{-- Show Delete button for files marked for deletion --}}
                                                <button onclick="showDeleteConfirm({{ $file->id }}, '{{ addslashes($file->original_name ?? $file->name) }}')"
                                                        class="inline-flex items-center text-xs text-red-600 dark:text-red-400 font-medium hover:text-red-700 dark:hover:text-red-300 transition-colors cursor-pointer"
                                                        title="Delete recommended">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            @elseif(isset($file->ai_analysis['suggested_folder_id']))
                                                {{-- Show Accept button for files that need to be moved --}}
                                                @php
                                                    $currentFolderId = $file->folder_id;
                                                    $suggestedFolderId = $file->ai_analysis['suggested_folder_id'] ?? null;
                                                    $needsMove = $currentFolderId != $suggestedFolderId;
                                                @endphp
                                                @if($needsMove)
                                                    <button onclick="acceptSuggestionQuick({{ $file->id }}, {{ $file->ai_analysis['suggested_folder_id'] ?? 'null' }}, '{{ addslashes($file->original_name ?? $file->name) }}')"
                                                            class="inline-flex items-center text-xs text-indigo-600 dark:text-indigo-400 font-medium hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors cursor-pointer"
                                                            title="Accept AI suggestion">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Accept
                                                    </button>
                                                @endif
                                            @endif
                                        @endif
                                        
                                        <button onclick="showAISuggestionModal({{ $file->id }})"
                                                class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 focus:outline-none"
                                                title="View details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                        
                                        <button onclick="reanalyzeFile({{ $file->id }})"
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
                                    
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                        {{ $files->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-8">
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
                        <div class="text-center">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" x-text="progressTitle">Processing Files</h3>
                            <div class="mt-3">
                                <p class="text-sm text-gray-500 dark:text-gray-400" x-text="progressMessage">Starting...</p>
                                
                                <!-- Progress Bar -->
                                <div class="mt-4">
                                    <div class="bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-indigo-600 dark:bg-indigo-400 h-2 rounded-full transition-all duration-300" 
                                             :style="'width: ' + (progressPercentage || 0) + '%'"></div>
                                    </div>
                                    <div class="text-center text-sm text-gray-500 dark:text-gray-400 mt-2">
                                        <span x-text="(progressCurrent || 0) + ' of ' + (progressTotal || 0)"></span>
                                    </div>
                                </div>
                                
                                <!-- Close Button (show when completed) -->
                                <div class="mt-6" x-show="progressPercentage >= 100">
                                    <button @click="checkAnalyzedFiles()" 
                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Check Analyzed Files
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    <!-- Include AI Suggestion Modal -->
    <x-ai-suggestion-modal />
    
    <!-- Review Guidance Modal -->
    <div id="review-guidance-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeReviewGuidance()"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">File Needs Review</h3>
                    </div>
                    <button onclick="closeReviewGuidance()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Content -->
                <div class="p-4">
                    <div class="space-y-4">
                        <!-- File Info -->
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100" id="review-file-name">File Name</p>
                        </div>
                        
                        <!-- Why Review -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Why does this need review?</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                AI has analyzed this file and suggested where it should be placed. You need to review the suggestion and decide if it's correct.
                            </p>
                        </div>
                        
                        <!-- How to Review -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">How to review:</h4>
                            <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex items-start space-x-2">
                                    <span class="font-medium text-indigo-600 dark:text-indigo-400">1.</span>
                                    <span>Click the details button (info icon) to see AI's analysis</span>
                                </div>
                                <div class="flex items-start space-x-2">
                                    <span class="font-medium text-indigo-600 dark:text-indigo-400">2.</span>
                                    <span>Check if the suggested folder makes sense</span>
                                </div>
                                <div class="flex items-start space-x-2">
                                    <span class="font-medium text-indigo-600 dark:text-indigo-400">3.</span>
                                    <span>Click "Accept" to move the file, or use "Re-analyze" if the suggestion seems wrong</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="p-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" onclick="closeReviewGuidance(); if(currentReviewFileId) { showAISuggestionModal(currentReviewFileId); }" 
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Review Now
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    </div> <!-- End of Alpine component -->

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
            
            if (!modalContent || !modalActions) {
                console.error('Modal elements not found');
                return;
            }
            
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
        
        function aiHistoryManager(allFileIds = [], filesData = []) {
            return {
                selectedFiles: [],
                isProcessing: false,
                allFileIds: allFileIds,
                filesData: filesData,
                
                // Progress tracking
                progressTitle: '',
                progressMessage: '',
                progressCurrent: 0,
                progressTotal: 0,
                progressPercentage: 0,
                progressMessages: [],
                
                // Computed property to check if any selected files need approval
                get hasSelectedFilesPendingApproval() {
                    if (!this.selectedFiles.length) return false;
                    return this.selectedFiles.some(fileId => {
                        const fileData = this.filesData.find(f => f.id === fileId);
                        return fileData && !fileData.ai_suggestion_accepted;
                    });
                },
                
                // Computed property to check if any selected files are not analyzed
                get hasSelectedFilesNotAnalyzed() {
                    if (!this.selectedFiles.length) return false;
                    return this.selectedFiles.some(fileId => {
                        const fileData = this.filesData.find(f => f.id === fileId);
                        return fileData && !fileData.ai_analysis;
                    });
                },
                
                // Computed property to check if any selected files are analyzed
                get hasSelectedFilesAnalyzed() {
                    if (!this.selectedFiles.length) return false;
                    return this.selectedFiles.some(fileId => {
                        const fileData = this.filesData.find(f => f.id === fileId);
                        return fileData && fileData.ai_analysis;
                    });
                },
                
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
                    
                    // Show completion message with next steps
                    if (successCount > 0) {
                        this.progressTitle = '✓ Re-analysis Complete';
                        this.progressMessage = `${successCount} files analyzed successfully!` + (errorCount > 0 ? ` (${errorCount} failed)` : '');
                        
                        // Show completion message with guidance
                        setTimeout(() => {
                            this.progressTitle = 'What\'s Next?';
                            this.progressMessage = 'Review the new AI suggestions and accept or reject them.';
                        }, 1500);
                        
                        // Note: User will manually close modal via Close button
                        // Update filesData to reflect new analysis status before clearing selection
                        const reanalyzedFiles = [...this.selectedFiles];
                        this.filesData.forEach(fileData => {
                            if (reanalyzedFiles.includes(fileData.id)) {
                                fileData.ai_suggestion_accepted = false; // Mark as pending review
                            }
                        });
                        this.selectedFiles = []; // Clear selection
                        window.location.reload();
                    } else {
                        this.progressTitle = '⚠️ Re-analysis Failed';
                        this.progressMessage = 'No files were successfully analyzed. Please try again.';
                        
                        // Note: User will manually close modal via Close button
                    }
                },
                
                checkAnalyzedFiles() {
                    // Close the modal
                    this.isProcessing = false;
                    
                    // Navigate to analyzed tab to show results
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('tab', 'analyzed');
                    window.location.href = currentUrl.toString();
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
                    
                    // Use custom confirmation modal
                    showConfirmationModal({
                        title: 'Analyze Files',
                        message: `Analyze ${this.selectedFiles.length} selected files?`,
                        confirmText: 'Analyze',
                        type: 'info',
                        onConfirm: () => this.executeBulkAnalyze()
                    });
                },
                
                async executeBulkAnalyze() {
                    
                    this.startProgress('Analyzing Files', this.selectedFiles.length);
                    
                    try {
                        this.addProgressMessage('Starting analysis...', 'info');
                        
                        let successCount = 0;
                        let errorCount = 0;
                        
                        // Analyze files one by one to show progress
                        for (const fileId of this.selectedFiles) {
                            try {
                                this.addProgressMessage(`Analyzing file ${successCount + errorCount + 1} of ${this.selectedFiles.length}...`, 'info');
                                
                                const response = await fetch(`/user/files/${fileId}/analyze?force_new=1`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'Accept': 'application/json'
                                    }
                                });
                                
                                const data = await response.json();
                                
                                if (data.success) {
                                    successCount++;
                                    this.updateProgress(successCount + errorCount);
                                } else {
                                    errorCount++;
                                    this.addProgressMessage(`Failed to analyze file: ${data.error}`, 'error');
                                }
                            } catch (error) {
                                errorCount++;
                                this.addProgressMessage(`Error analyzing file: ${error.message}`, 'error');
                            }
                        }
                        
                        this.addProgressMessage(`Completed! Analyzed ${successCount} files${errorCount > 0 ? `, ${errorCount} failed` : ''}.`, 'success');
                        this.finishProgress(successCount, errorCount);
                        
                    } catch (error) {
                        this.addProgressMessage(`Error: ${error.message}`, 'error');
                        this.finishProgress(0, this.selectedFiles.length);
                    }
                },
                
                async bulkApprove() {
                    if (!this.selectedFiles.length) return;
                    
                    // Check which files actually need to be moved
                    const filesToMove = [];
                    const filesAlreadyCorrect = [];
                    
                    this.selectedFiles.forEach(fileId => {
                        const fileRow = document.querySelector(`div[data-file-id="${fileId}"]`);
                        if (fileRow) {
                            const fileData = JSON.parse(fileRow.getAttribute('data-file') || '{}');
                            if (fileData.ai_analysis) {
                                const currentFolderId = fileData.folder_id;
                                const suggestedFolderId = fileData.ai_analysis.suggested_folder_id;
                                const isAccepted = fileData.ai_suggestion_accepted;
                                
                                // File needs to be moved if:
                                // 1. It has a suggested folder that's different from current
                                // 2. AND it hasn't been accepted yet
                                if (currentFolderId !== suggestedFolderId && suggestedFolderId && !isAccepted) {
                                    filesToMove.push({
                                        id: fileId,
                                        name: fileData.original_name || fileData.name,
                                        from: fileData.folder?.name || 'Unknown',
                                        to: fileData.ai_analysis.folder_name || 'Unknown'
                                    });
                                } else {
                                    filesAlreadyCorrect.push({
                                        id: fileId,
                                        name: fileData.original_name || fileData.name
                                    });
                                }
                            }
                        }
                    });
                    
                    // Show detailed confirmation
                    let message = '';
                    if (filesToMove.length === 0) {
                        message = `All ${this.selectedFiles.length} selected files are already in their correct folders. No changes needed.`;
                        alert(message);
                        return;
                    } else if (filesAlreadyCorrect.length > 0) {
                        message = `Out of ${this.selectedFiles.length} selected files:\n\n`;
                        message += `✓ ${filesAlreadyCorrect.length} files are already in correct folders\n`;
                        message += `→ ${filesToMove.length} files will be moved to suggested folders\n\n`;
                        message += `Continue with moving ${filesToMove.length} files?`;
                    } else {
                        message = `Move ${filesToMove.length} files to their suggested folders?`;
                    }
                    
                    // Use custom confirmation modal
                    showConfirmationModal({
                        title: 'Approve Suggestions',
                        message: message,
                        confirmText: 'Approve',
                        type: 'info',
                        onConfirm: () => this.executeBulkApprove()
                    });
                },
                
                async executeBulkApprove() {
                    
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
                    
                    // Use custom confirmation modal
                    showConfirmationModal({
                        title: 'Re-analyze Files',
                        message: `Re-analyze ${this.selectedFiles.length} selected files? This will generate new AI analysis for each file.`,
                        confirmText: 'Re-analyze',
                        type: 'warning',
                        onConfirm: () => this.executeBulkReanalyze()
                    });
                },
                
                async executeBulkReanalyze() {
                    
                    this.startProgress('Re-analyzing Files', this.selectedFiles.length);
                    
                    try {
                        // Process files one by one for re-analysis with progress updates
                        let completed = 0;
                        let errors = [];
                        
                        for (let i = 0; i < this.selectedFiles.length; i++) {
                            const fileId = this.selectedFiles[i];
                            
                            this.updateProgress(i + 1, `Analyzing file ${i + 1} of ${this.selectedFiles.length}...`);
                            
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
                                } else {
                                    errors.push(`File ${fileId}: Failed to re-analyze`);
                                }
                            } catch (error) {
                                errors.push(`File ${fileId}: ${error.message}`);
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
        // Removed showAnalysisDetails - using showAISuggestionModal
        /*window.showAnalysisDetails = function showAnalysisDetails(analysis, fileData = null) {
            if (!analysis) return;
            
            // Store current file data for actions
            currentModalFile = fileData;
            
            // Reset modal state to default
            modalState = 'default';
            modalStateData = {};
            currentConfirmAction = null;
            currentCancelAction = null;
            
            const content = document.getElementById('analysis-details-content');
            if (!content) {
                console.error('Analysis details content not found');
                return;
            }
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
                            ${analysis.confidence && analysis.confidence > 0 ? `
                                <div class="flex items-center justify-between bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Confidence Level</span>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-24 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-indigo-500 to-blue-500 h-2 rounded-full transition-all duration-500" style="width: ${analysis.confidence || 0}%"></div>
                                        </div>
                                        <span class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">${analysis.confidence || 0}%</span>
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                    ` : ''}
                    
                    <!-- Alternative Folders with Use This buttons -->
                    ${analysis.alternative_folders && analysis.alternative_folders.length > 0 ? (() => {
                        // Filter out duplicates and current folder
                        const currentFolderName = fileData.folder ? fileData.folder.name : 'Root';
                        // Standardize folder name extraction
                        const getSuggestedFolderName = (analysis) => {
                            const path = analysis.folder_path || analysis.folder_name;
                            return path && path.includes('/') ? path.split('/').pop() : (analysis.folder_name || '');
                        };
                        
                        const suggestedFolderName = getSuggestedFolderName(analysis);
                        const seen = new Set([currentFolderName, suggestedFolderName]);
                        
                        const uniqueAlternatives = [];
                        analysis.alternative_folders.forEach(alt => {
                            const folderName = getSuggestedFolderName(alt) || alt.name || 'Unknown';
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
                                        const altFolderName = getSuggestedFolderName(alt) || alt.name || 'Unknown';
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
                    <button type="button" onclick="acceptSuggestionFromModal(${fileData.id}, ${analysis.suggested_folder_id})" 
                            class="inline-flex items-center px-4 py-2 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm font-medium transition-colors focus:outline-none">
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
            
            // Use custom confirmation modal
            showConfirmationModal({
                title: 'Accept Suggestion',
                message: `Accept AI suggestion and move "${currentModalFile.original_name || currentModalFile.name}" to the suggested folder?`,
                confirmText: 'Accept & Move',
                type: 'info',
                onConfirm: () => {
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
            });
        }
        */
        
        // Make all functions globally accessible for onclick handlers
        window.manualMoveFromDetails = manualMoveFromDetails;
        window.viewFileFromDetails = viewFileFromDetails;
        // window.closeAnalysisDetails = closeAnalysisDetails; // Removed - using new modal
        // window.acceptAnalysisSuggestion = acceptAnalysisSuggestion; // Removed - using new modal
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
            const textSpan = reanalyzeBtn ? (reanalyzeBtn.querySelector('span') || reanalyzeBtn) : null;
            const spinner = reanalyzeBtn ? reanalyzeBtn.querySelector('.reanalyze-spinner') : null;
            
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
            
            if (!modalContent || !modalActions) {
                console.error('Modal elements not found');
                return;
            }
            
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
            
            if (!modalContent || !modalActions) {
                console.error('Modal elements not found');
                return;
            }
            
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
            // Get file data to show summary
            const fileData = document.querySelector(`[data-file-id="${fileId}"]`)?.dataset.file;
            let summary = `Accept AI suggestion and move "${fileName}" to the suggested folder?`;
            
            if (fileData) {
                try {
                    const file = JSON.parse(fileData);
                    const analysis = file.ai_analysis;
                    
                    if (analysis) {
                        summary = `File: ${fileName}\n\n`;
                        
                        if (analysis.folder_path) {
                            summary += `Move to: ${analysis.folder_path}\n\n`;
                        }
                        
                        summary += `Accept AI suggestion and move this file?`;
                    }
                } catch (e) {
                    // Fallback to simple message if parsing fails
                    console.warn('Failed to parse file data:', e);
                }
            }
            
            // Use custom confirmation modal
            showConfirmationModal({
                title: 'Accept AI Suggestion',
                message: summary,
                confirmText: 'Accept & Move',
                type: 'info',
                onConfirm: () => executeAcceptSuggestion(fileId, folderId)
            });
        }
        
        function executeAcceptSuggestion(fileId, folderId) {
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
        
        
        function reanalyzeFile(fileId) {
            // Use custom confirmation modal
            window.showConfirmationModal({
                title: 'Re-analyze File',
                message: 'Are you sure you want to re-analyze this file? This will generate a new AI analysis.',
                confirmText: 'Re-analyze',
                cancelText: 'Cancel',
                onConfirm: function() {
                    // Force re-analysis by passing true for forceNew parameter
                    showAISuggestionModal(fileId, true);
                }
            });
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
        
        // Review Guidance Modal Functions
        let currentReviewFileId = null;
        let currentReviewFileData = null;
        
        function showReviewGuidance(fileId, fileName) {
            currentReviewFileId = fileId;
            // Store file data for later use
            const fileData = @json($files->keyBy('id'));
            currentReviewFileData = fileData[fileId];
            
            document.getElementById('review-file-name').textContent = fileName;
            document.getElementById('review-guidance-modal').classList.remove('hidden');
        }
        
        function closeReviewGuidance() {
            document.getElementById('review-guidance-modal').classList.add('hidden');
            currentReviewFileId = null;
            currentReviewFileData = null;
        }
        
        function showAnalysisDetailsFromReview() {
            if (currentReviewFileId && currentReviewFileData) {
                const analysis = currentReviewFileData.ai_analysis;
                const file = currentReviewFileData;
                
                // Show minimal analysis modal
                document.getElementById('analysis-details-modal').classList.remove('hidden');
                
                // Simple content
                const content = document.getElementById('analysis-details-content');
            if (!content) {
                console.error('Analysis details content not found');
                return;
            }
                content.innerHTML = `
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">${file.original_name || file.name}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Current: ${file.folder ? file.folder.full_path : 'Root'}</p>
                    </div>
                    
                    ${analysis && analysis.folder_name ? `
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            <span class="font-medium">AI Suggests:</span> ${analysis.folder_name}
                        </p>
                        ${analysis.confidence ? `<p class="text-xs text-gray-500 dark:text-gray-400">Confidence: ${analysis.confidence}%</p>` : ''}
                    </div>
                    ` : ''}
                    
                    ${analysis && analysis.reasoning ? `
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            <span class="font-medium">Why:</span> ${analysis.reasoning}
                        </p>
                    </div>
                    ` : ''}
                `;
                
                // Simple action buttons
                const actions = document.getElementById('analysis-action-buttons');
                const currentFolderId = file.folder_id;
                const suggestedFolderId = analysis && analysis.suggested_folder_id;
                const needsMove = currentFolderId != suggestedFolderId;
                
                if (needsMove && suggestedFolderId) {
                    actions.innerHTML = `
                        <button onclick="acceptSuggestionFromModal(${file.id}, ${suggestedFolderId})" 
                                class="px-4 py-2 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm font-medium">
                            Accept & Move
                        </button>
                        <button onclick="closeAnalysisDetails()" 
                                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                            Cancel
                        </button>
                    `;
                } else {
                    actions.innerHTML = `
                        <div class="text-sm text-green-600 dark:text-green-400 font-medium">✓ File is in correct place</div>
                    `;
                }
            }
        }
        
        function acceptSuggestionFromModal(fileId, folderId) {
            // Use the existing quick accept function
            const fileName = currentReviewFileData.original_name || currentReviewFileData.name;
            acceptSuggestionQuick(fileId, folderId, fileName);
            closeAnalysisDetails();
        }
        
        function closeAnalysisDetails() {
            document.getElementById('analysis-details-modal').classList.add('hidden');
        }
        
        // Removed showMinimalAnalysisDetails - now using showAISuggestionModal from component
        
        function acceptSuggestionFromModalTable(fileId, folderId, fileName) {
            acceptSuggestionQuick(fileId, folderId, fileName);
        }
        
        // File Preview Modal Functions
        function showFilePreview(fileName, mimeType, url) {
            const modal = document.getElementById('file-preview-modal');
            const title = document.getElementById('file-preview-title');
            const content = document.getElementById('file-preview-content');
            
            // Set title
            title.textContent = fileName;
            
            // Show loading first
            content.innerHTML = `
                <div class="flex items-center justify-center py-12">
                    <svg class="animate-spin h-8 w-8 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="ml-3 text-gray-600 dark:text-gray-400">Loading preview...</span>
                </div>
            `;
            
            // Show modal
            modal.classList.remove('hidden');
            
            // Load content based on file type
            if (mimeType.startsWith('image/')) {
                content.innerHTML = `
                    <div class="p-4">
                        <img src="${url}" alt="${fileName}" class="max-w-full h-auto mx-auto rounded-lg shadow-sm">
                    </div>
                `;
            } else if (mimeType === 'application/pdf') {
                content.innerHTML = `
                    <iframe src="${url}" class="w-full h-[80vh]" frameborder="0"></iframe>
                `;
            } else {
                // For non-previewable files, show download option
                content.innerHTML = `
                    <div class="text-center p-12">
                        <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Preview not available</h4>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">This file type cannot be previewed directly.</p>
                        <a href="${url}?download=${encodeURIComponent(fileName)}" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download File
                        </a>
                    </div>
                `;
            }
        }
        
        function closeFilePreview() {
            const modal = document.getElementById('file-preview-modal');
            modal.classList.add('hidden');
        }

        // Simple Delete Confirmation Modal Functions
        let deleteFileId = null;
        
        function showDeleteConfirm(fileId, fileName) {
            deleteFileId = fileId;
            
            // Get file data to show summary
            const fileData = document.querySelector(`[data-file-id="${fileId}"]`)?.dataset.file;
            let summary = `File: ${fileName}\n\nAre you sure you want to delete this file? This action cannot be undone.`;
            
            if (fileData) {
                try {
                    const file = JSON.parse(fileData);
                    const analysis = file.ai_analysis;
                    
                    if (analysis) {
                        summary = `File: ${fileName}\n\n`;
                        
                        if (analysis.suggest_deletion && analysis.deletion_reason) {
                            summary += `AI recommends deletion:\n${analysis.deletion_reason}\n\n`;
                        }
                        
                        summary += `Are you sure you want to delete this file?\nThis action cannot be undone.`;
                    }
                } catch (e) {
                    // Fallback to simple message if parsing fails
                    console.warn('Failed to parse file data:', e);
                }
            }
            
            // Use custom confirmation modal instead of the component modal
            showConfirmationModal({
                title: 'Delete File',
                message: summary,
                confirmText: 'Delete',
                cancelText: 'Cancel',
                type: 'danger',
                onConfirm: () => confirmDelete()
            });
        }

        function closeDeleteConfirm() {
            const modal = document.getElementById('delete-confirm-modal');
            modal.classList.add('hidden');
            deleteFileId = null;
        }

        function confirmDelete() {
            if (!deleteFileId) return;
            
            const routeTemplate = `{{ route('user.files.destroy', ['file' => ':fileId']) }}`;
            const deleteUrl = routeTemplate.replace(':fileId', deleteFileId);
            
            fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the file row from the page
                    const fileRow = document.querySelector(`[data-file-id="${deleteFileId}"]`);
                    if (fileRow) {
                        fileRow.remove();
                    }
                    
                    // Close modal and refresh page
                    closeDeleteConfirm();
                    window.location.reload();
                } else {
                    alert('Error deleting file: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                alert('Error deleting file: ' + error.message);
            });
        }

        // Make functions globally accessible
        window.showReviewGuidance = showReviewGuidance;
        window.closeReviewGuidance = closeReviewGuidance;
        window.showAnalysisDetailsFromReview = showAnalysisDetailsFromReview;
        window.acceptSuggestionFromModal = acceptSuggestionFromModal;
        // window.closeAnalysisDetails = closeAnalysisDetails; // Removed - using new modal
        // window.showMinimalAnalysisDetails = showMinimalAnalysisDetails; // Removed - using showAISuggestionModal
        window.acceptSuggestionFromModalTable = acceptSuggestionFromModalTable;
        window.showFilePreview = showFilePreview;
        window.closeFilePreview = closeFilePreview;
        window.showDeleteConfirm = showDeleteConfirm;
        window.closeDeleteConfirm = closeDeleteConfirm;
        window.confirmDelete = confirmDelete;
    </script>
    @endpush

    <x-ai-history.modals.file-preview />
    <x-ai-history.modals.delete-confirm />
</x-user.layout>
