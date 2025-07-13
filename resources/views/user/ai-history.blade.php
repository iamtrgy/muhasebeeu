<x-user.layout title="AI Analysis History">
    <div class="space-y-6">
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

            <!-- History Table -->
            <x-ui.card.base>
                <x-ui.card.header>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Analysis History') }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Track all AI document analyses and their outcomes') }}</p>
                </x-ui.card.header>
                <x-ui.card.body>
                    @if($analyses->count() > 0)
                        <div class="overflow-x-auto shadow-inner">
                            <x-ui.table.base>
                            <x-slot name="head">
                                <tr>
                                    <x-ui.table.head-cell>{{ __('File') }}</x-ui.table.head-cell>
                                    <x-ui.table.head-cell>{{ __('Analyzed') }}</x-ui.table.head-cell>
                                    <x-ui.table.head-cell>{{ __('Suggested Folder') }}</x-ui.table.head-cell>
                                    <x-ui.table.head-cell>{{ __('Confidence') }}</x-ui.table.head-cell>
                                    <x-ui.table.head-cell>{{ __('Status') }}</x-ui.table.head-cell>
                                    <x-ui.table.head-cell>{{ __('Current Folder') }}</x-ui.table.head-cell>
                                    <x-ui.table.head-cell>{{ __('Actions') }}</x-ui.table.head-cell>
                                </tr>
                            </x-slot>
                            <x-slot name="body">
                                @foreach($analyses as $file)
                                    <tr>
                                        <x-ui.table.cell>
                                            <div class="flex items-center">
                                                @php
                                                    $extension = pathinfo($file->original_name, PATHINFO_EXTENSION);
                                                    $isPdf = in_array(strtolower($extension), ['pdf']);
                                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                @endphp
                                                @if($isPdf)
                                                    <svg class="w-8 h-8 text-red-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                                    </svg>
                                                @elseif($isImage)
                                                    <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-8 h-8 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M14,2H6C4.9,2,4,2.9,4,4v16c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V8L14,2z M16,18H8v-2h8V18z M16,14H8v-2h8V14z M13,9V3.5L18.5,9H13z"/>
                                                    </svg>
                                                @endif
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $file->original_name ?? $file->name }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $file->size_formatted }}
                                                    </p>
                                                </div>
                                            </div>
                                        </x-ui.table.cell>
                                        
                                        <x-ui.table.cell>
                                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $file->ai_analyzed_at->format('M d, Y H:i') }}
                                            </span>
                                        </x-ui.table.cell>
                                        
                                        <x-ui.table.cell>
                                            @if($file->ai_analysis && isset($file->ai_analysis['folder_name']))
                                                <div>
                                                    @php
                                                        $folderPath = $file->ai_analysis['folder_path'] ?? $file->ai_analysis['folder_name'];
                                                        $pathParts = explode('/', trim($folderPath, '/'));
                                                    @endphp
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        @foreach($pathParts as $index => $part)
                                                            @if($index > 0)
                                                                <span class="mx-1 text-gray-400">›</span>
                                                            @endif
                                                            <span class="{{ $index === count($pathParts) - 1 ? 'font-semibold' : '' }}">
                                                                {{ $part }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                    @if(isset($file->ai_analysis['document_type']))
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $file->ai_analysis['document_type'] }}
                                                        </p>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-500">-</span>
                                            @endif
                                        </x-ui.table.cell>
                                        
                                        <x-ui.table.cell>
                                            @if($file->ai_analysis && isset($file->ai_analysis['confidence']))
                                                <div class="flex items-center">
                                                    <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-2">
                                                        <div class="bg-indigo-600 dark:bg-indigo-400 h-2 rounded-full" 
                                                             style="width: {{ $file->ai_analysis['confidence'] }}%">
                                                        </div>
                                                    </div>
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $file->ai_analysis['confidence'] }}%
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-500">-</span>
                                            @endif
                                        </x-ui.table.cell>
                                        
                                        <x-ui.table.cell>
                                            @if($file->ai_suggestion_accepted)
                                                <x-ui.badge variant="success">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Accepted
                                                </x-ui.badge>
                                            @else
                                                <x-ui.badge variant="secondary">Pending</x-ui.badge>
                                            @endif
                                        </x-ui.table.cell>
                                        
                                        <x-ui.table.cell>
                                            @if($file->folder)
                                                <a href="{{ route('user.folders.show', $file->folder) }}" 
                                                   class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                                    {{ $file->folder->name }}
                                                </a>
                                            @else
                                                <span class="text-sm text-gray-500">{{ __('Root') }}</span>
                                            @endif
                                        </x-ui.table.cell>
                                        
                                        <x-ui.table.action-cell>
                                            <button onclick="showAnalysisDetails({{ json_encode($file->ai_analysis) }})" 
                                                    class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                                    title="{{ __('View details') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 0 1 6 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <button onclick="reanalyzeFile({{ $file->id }})" 
                                                    class="p-2 rounded-lg text-gray-600 hover:text-indigo-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors border border-gray-300 dark:border-gray-600"
                                                    title="{{ __('Re-analyze') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                            </button>
                                        </x-ui.table.action-cell>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-ui.table.base>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $analyses->links() }}
                        </div>
                    @else
                        <x-ui.table.empty-state>
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('No AI analyses yet. Start by analyzing files in your folders.') }}</p>
                        </x-ui.table.empty-state>
                    @endif
                </x-ui.card.body>
            </x-ui.card.base>
        </div>

    <!-- Include AI Suggestion Modal -->
    <x-ai-suggestion-modal />
    
    <!-- Analysis Details Modal -->
    <div id="analysis-details-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Analysis Details') }}</h3>
                    <div id="analysis-details-content" class="space-y-3 text-sm">
                        <!-- Content will be dynamically inserted -->
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeAnalysisDetails()" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function showAnalysisDetails(analysis) {
            if (!analysis) return;
            
            const content = document.getElementById('analysis-details-content');
            content.innerHTML = `
                <div class="space-y-3">
                    ${analysis.reasoning ? `
                        <div>
                            <p class="font-medium text-gray-700 dark:text-gray-300">Reasoning:</p>
                            <p class="text-gray-600 dark:text-gray-400">${analysis.reasoning}</p>
                        </div>
                    ` : ''}
                    
                    ${analysis.key_information && analysis.key_information.length > 0 ? `
                        <div>
                            <p class="font-medium text-gray-700 dark:text-gray-300">Key Information:</p>
                            <ul class="list-disc list-inside text-gray-600 dark:text-gray-400">
                                ${analysis.key_information.map(info => `<li>${info}</li>`).join('')}
                            </ul>
                        </div>
                    ` : ''}
                    
                    ${analysis.alternative_folders && analysis.alternative_folders.length > 0 ? `
                        <div>
                            <p class="font-medium text-gray-700 dark:text-gray-300">Alternative Folders:</p>
                            <div class="space-y-2">
                                ${analysis.alternative_folders.map(alt => `
                                    <div class="bg-gray-100 dark:bg-gray-700 rounded p-2">
                                        <p class="font-medium text-gray-700 dark:text-gray-300">${alt.name}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">${alt.reason}</p>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    ` : ''}
                    
                    ${analysis.document_date ? `
                        <div>
                            <p class="font-medium text-gray-700 dark:text-gray-300">Document Date:</p>
                            <p class="text-gray-600 dark:text-gray-400">${analysis.document_date}</p>
                        </div>
                    ` : ''}
                </div>
            `;
            
            document.getElementById('analysis-details-modal').classList.remove('hidden');
        }
        
        function closeAnalysisDetails() {
            document.getElementById('analysis-details-modal').classList.add('hidden');
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
    </script>
    @endpush
</x-user.layout>