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

            <!-- Filters -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <form method="GET" action="{{ route('user.ai-analysis.history') }}" class="space-y-4">
                    <input type="hidden" name="tab" value="{{ $currentTab }}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search Files</label>
                            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" 
                                   placeholder="Search by filename..."
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 text-sm">
                        </div>

                        <!-- Date From -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From Date</label>
                            <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 text-sm">
                        </div>

                        <!-- Date To -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To Date</label>
                            <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 text-sm">
                        </div>

                        <!-- Folder Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Folder</label>
                            <select name="folder" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 text-sm">
                                <option value="">All Folders</option>
                                @foreach($userFolders as $folder)
                                    <option value="{{ $folder->id }}" {{ ($filters['folder'] ?? '') == $folder->id ? 'selected' : '' }}>
                                        {{ $folder->full_path }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if($currentTab === 'analyzed')
                            <!-- Confidence Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Min Confidence</label>
                                <select name="confidence" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 text-sm">
                                    <option value="">Any</option>
                                    <option value="90" {{ ($filters['confidence'] ?? '') == '90' ? 'selected' : '' }}>90%+</option>
                                    <option value="80" {{ ($filters['confidence'] ?? '') == '80' ? 'selected' : '' }}>80%+</option>
                                    <option value="70" {{ ($filters['confidence'] ?? '') == '70' ? 'selected' : '' }}>70%+</option>
                                    <option value="50" {{ ($filters['confidence'] ?? '') == '50' ? 'selected' : '' }}>50%+</option>
                                </select>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-between items-center">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Apply Filters
                        </button>

                        @if(count(array_filter($filters)))
                            <a href="{{ route('user.ai-analysis.history', ['tab' => $currentTab]) }}" 
                               class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                Clear Filters
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Bulk Actions -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700" x-show="selectedFiles.length > 0" x-cloak>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700 dark:text-gray-300">
                        <span x-text="selectedFiles.length"></span> file(s) selected
                    </span>
                    <div class="flex space-x-3">
                        @if($currentTab === 'not_analyzed')
                            <button @click="bulkAnalyze()" :disabled="isProcessing"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                                Analyze Selected
                            </button>
                        @endif

                        @if($currentTab === 'analyzed')
                            <button @click="bulkApprove()" :disabled="isProcessing"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Approve Suggestions
                            </button>

                            <button @click="bulkReanalyze()" :disabled="isProcessing"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Re-analyze
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- File List -->
            <x-ui.card.body>
                @if($files->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        <input type="checkbox" 
                                               @change="toggleAll($event.target.checked)" 
                                               :checked="allFileIds.length > 0 && selectedFiles.length === allFileIds.length"
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">File</th>
                                    @if($currentTab === 'analyzed')
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Analyzed</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Suggested Folder</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Confidence</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    @else
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Uploaded</th>
                                    @endif
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Current Folder</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($files as $file)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" :checked="selectedFiles.includes({{ $file->id }})" 
                                                   @change="toggleFile({{ $file->id }})"
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
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
                                        </td>
                                        
                                        @if($currentTab === 'analyzed')
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                {{ $file->ai_analyzed_at->format('M d, Y H:i') }}
                                            </td>
                                            
                                            <td class="px-6 py-4 whitespace-nowrap">
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
                                            </td>
                                            
                                            <td class="px-6 py-4 whitespace-nowrap">
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
                                            </td>
                                            
                                            <td class="px-6 py-4 whitespace-nowrap">
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
                                            </td>
                                        @else
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                {{ $file->created_at->format('M d, Y H:i') }}
                                            </td>
                                        @endif
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($file->folder)
                                                <a href="{{ route('user.folders.show', $file->folder) }}" target="_blank"
                                                   class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                                    {{ $file->folder->full_path }}
                                                </a>
                                            @else
                                                <span class="text-sm text-gray-500">Root</span>
                                            @endif
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                @if($file->ai_analyzed_at)
                                                    <button onclick="showAnalysisDetails({{ json_encode($file->ai_analysis) }}, {{ json_encode([
                                                        'id' => $file->id,
                                                        'original_name' => $file->original_name,
                                                        'name' => $file->name,
                                                        'current_folder' => $file->folder ? $file->folder->full_path : 'Root',
                                                        'ai_suggestion_accepted' => $file->ai_suggestion_accepted,
                                                        'ai_analysis' => $file->ai_analysis
                                                    ]) }})" 
                                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                                            title="View details">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 0 1 6 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </button>
                                                    <button onclick="showAISuggestionModal({{ $file->id }}, true)" 
                                                            class="text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400"
                                                            title="Re-analyze">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                        </svg>
                                                    </button>
                                                @else
                                                    <button onclick="showAISuggestionModal({{ $file->id }})" 
                                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                                            title="Analyze">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
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
        <!-- Bulk Progress Modal -->
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
    
    <!-- Analysis Details Modal -->
    <div id="analysis-details-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Analysis Details</h3>
                    <div id="analysis-details-content" class="space-y-3 text-sm">
                        <!-- Content will be dynamically inserted -->
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6">
                    <!-- Action Buttons Row -->
                    <div class="flex flex-col sm:flex-row sm:justify-between gap-3 mb-4" id="analysis-action-buttons">
                        <!-- Primary Actions -->
                        <div class="flex flex-col sm:flex-row gap-2">
                            <button type="button" id="accept-suggestion-btn" onclick="acceptAnalysisSuggestion()" 
                                    class="hidden w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Accept Suggestion
                            </button>
                            <button type="button" onclick="reanalyzeFromDetails()" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Re-analyze
                            </button>
                        </div>
                        
                        <!-- Secondary Actions -->
                        <div class="flex flex-col sm:flex-row gap-2">
                            <button type="button" onclick="manualMoveFromDetails()" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                </svg>
                                Manual Move
                            </button>
                            <button type="button" id="view-file-btn" onclick="viewFileFromDetails()" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View File
                            </button>
                        </div>
                    </div>
                    
                    <!-- Close Button -->
                    <div class="flex justify-end">
                        <button type="button" onclick="closeAnalysisDetails()" 
                                class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
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
        
        function showAnalysisDetails(analysis, fileData = null) {
            if (!analysis) return;
            
            // Store current file data for actions
            currentModalFile = fileData;
            
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
                                ${analysis.key_information.map(info => `<li>${typeof info === 'object' ? JSON.stringify(info) : info}</li>`).join('')}
                            </ul>
                        </div>
                    ` : ''}
                    
                    ${analysis.alternative_folders && analysis.alternative_folders.length > 0 ? `
                        <div>
                            <p class="font-medium text-gray-700 dark:text-gray-300">Alternative Folders:</p>
                            <div class="space-y-2">
                                ${analysis.alternative_folders.map(alt => `
                                    <div class="bg-gray-100 dark:bg-gray-700 rounded p-2">
                                        <p class="font-medium text-gray-700 dark:text-gray-300">${alt.folder_name || alt.name || 'Unknown'}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">${alt.reason || ''}</p>
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
                    
                    ${fileData ? `
                        <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                            <p class="font-medium text-gray-700 dark:text-gray-300">File Information:</p>
                            <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                <p><span class="font-medium">Name:</span> ${fileData.original_name || fileData.name}</p>
                                <p><span class="font-medium">Current Folder:</span> ${fileData.current_folder || 'Unknown'}</p>
                                ${analysis.suggested_folder_path ? `<p><span class="font-medium">Suggested Folder:</span> ${analysis.suggested_folder_path}</p>` : ''}
                                <p><span class="font-medium">Status:</span> ${fileData.ai_suggestion_accepted ? '<span class="text-green-600">Accepted</span>' : '<span class="text-amber-600">Pending</span>'}</p>
                            </div>
                        </div>
                    ` : ''}
                </div>
            `;
            
            // Show/hide action buttons based on file status
            updateActionButtons(analysis, fileData);
            
            document.getElementById('analysis-details-modal').classList.remove('hidden');
        }
        
        function updateActionButtons(analysis, fileData) {
            const acceptBtn = document.getElementById('accept-suggestion-btn');
            const viewBtn = document.getElementById('view-file-btn');
            
            // Show Accept button only if there's a pending suggestion
            if (fileData && analysis && analysis.suggested_folder_id && !fileData.ai_suggestion_accepted) {
                acceptBtn.classList.remove('hidden');
            } else {
                acceptBtn.classList.add('hidden');
            }
            
            // Update View File button with correct URL if fileData is available
            if (fileData && fileData.id) {
                viewBtn.onclick = () => viewFileFromDetails(fileData.id);
            }
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
        
        function reanalyzeFromDetails() {
            if (!currentModalFile || !currentModalFile.id) {
                alert('No file selected');
                return;
            }
            
            if (confirm(`Re-analyze "${currentModalFile.original_name || currentModalFile.name}"? This will generate a new AI analysis.`)) {
                closeAnalysisDetails();
                showAISuggestionModal(currentModalFile.id, true);
            }
        }
        
        function manualMoveFromDetails() {
            if (!currentModalFile || !currentModalFile.id) {
                alert('No file selected');
                return;
            }
            
            closeAnalysisDetails();
            showAISuggestionModal(currentModalFile.id, false);
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
    </script>
    @endpush
</x-user.layout>