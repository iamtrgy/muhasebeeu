<x-app-layout>
    <x-unified-header>
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        {{ $task->company->name }}
                    </span>
                    <span class="mx-2">•</span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}
                        @if($task->due_date && $task->due_date->isPast())
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800/20 dark:text-red-400">
                                Overdue
                            </span>
                        @elseif($task->due_date && $task->due_date->diffInDays(now()) <= 3)
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-800/20 dark:text-yellow-400">
                                Due Soon
                            </span>
                        @endif
                    </span>
                </p>
            </div>
            <div class="flex items-center">
                <div class="flex flex-col items-end">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Status:</span>
                        <span class="status-badge {{ $task->status }} px-2.5 py-0.5 rounded-full text-xs font-medium">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </div>
                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Submitted {{ $task->submitted_at ? $task->submitted_at->diffForHumans() : 'Not yet' }}
                    </div>
                </div>
            </div>
        </div>
    </x-unified-header>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden">
                <div class="p-6">
                    <!-- Main Content Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left Column (2/3) -->
                        <div class="lg:col-span-2">
                            <!-- Task Description -->
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-5 mb-6">
                                <h2 class="text-base font-medium text-gray-900 dark:text-white mb-3">Task Description</h2>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    {{ $task->taxCalendar->description }}
                                </p>
                            </div>
                    </div>

                        <!-- Right Column (1/3) -->
                        <div class="lg:col-span-1">
                            <!-- Company Card -->
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-5 mb-6">
                                <div class="flex items-center mb-4">
                                    <div class="flex-shrink-0">
                                        @if($task->company->logo_url)
                                            <img class="h-10 w-10 rounded-lg object-cover" 
                                                 src="{{ $task->company->logo_url }}" 
                                                 alt="{{ $task->company->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                    @endif
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ $task->company->name }}</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Tax Number: {{ $task->company->tax_number }}</p>
                                    </div>
                                </div>

                                <!-- Contact Person -->
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                    <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-2">Contact Person</h4>
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                            </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $task->company->users->first()->name }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $task->company->users->first()->email }}
                                            </p>
                                        </div>
                            </div>
                            </div>

                                <!-- Important Dates -->
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                                    <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-2">Important Dates</h4>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">Submitted:</span>
                                            <span class="text-sm text-gray-900 dark:text-white">
                                                {{ $task->submitted_at ? $task->submitted_at->format('M d, Y') : 'Not yet' }}
                                        </span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">Due Date:</span>
                                            <span class="text-sm text-gray-900 dark:text-white">
                                                {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}
                                        </span>
                                        </div>
                                        @if($task->reviewed_at)
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">Last Review:</span>
                                            <span class="text-sm text-gray-900 dark:text-white">
                                                {{ $task->reviewed_at->format('M d, Y') }}
                                        </span>
                                        </div>
                                    @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Checklist Section -->
                    <div class="mt-8 checklist-section">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Review Checklist</h2>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ collect($task->user_checklist ?? [])->filter(fn($item) => isset($item['completed']) && $item['completed'])->count() }}
                                    of
                                    {{ count($task->user_checklist ?? []) }}
                                    completed
                                </span>
                            </div>
                    </div>

                        <!-- Dynamic hint will be inserted here -->

                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl checklist-items">
                            @if($task->user_checklist)
                                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($task->user_checklist as $index => $item)
                                        <div class="group relative hover:bg-white dark:hover:bg-gray-800 transition-colors p-4 first:rounded-t-xl last:rounded-b-xl"
                                             id="checklist-item-{{ $index }}"
                                             data-item-title="{{ $item['title'] }}"
                                             data-item-index="{{ $index }}">
                                            
                                            <div class="flex items-start">
                                                <!-- Completion Status -->
                                                <div class="flex-shrink-0 mt-1">
                                                    <div class="{{ isset($item['completed']) && $item['completed'] ? 'bg-green-500 text-white' : 'bg-gray-200 dark:bg-gray-700' }} rounded-full p-1 w-5 h-5 flex items-center justify-center">
                                                        @if(isset($item['completed']) && $item['completed'])
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Item Content -->
                                                <div class="ml-4 flex-grow">
                                                    <div class="flex items-center justify-between">
                                                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $item['title'] }}
                                                        </h3>
                                                        
                                                        <!-- Selection for Review -->
                                                        <button type="button" 
                                                                class="select-for-review px-3 py-1.5 text-xs font-medium rounded-lg
                                                                bg-gray-100 text-gray-700 hover:bg-gray-200 
                                                                dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600
                                                                transition-colors"
                                                                style="display: none;"
                                                                data-index="{{ $index }}"
                                                                data-title="{{ $item['title'] }}">
                                                            Select for Review
                                                        </button>
                                                    </div>

                                                    @if(!empty($item['notes']))
                                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $item['notes'] }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Selected Indicator -->
                                            <div class="absolute inset-y-0 right-0 pr-3 items-center pointer-events-none selected-indicator" style="display: none;">
                                                <span class="flex items-center text-blue-600 dark:text-blue-400">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-4 text-sm text-gray-500 dark:text-gray-400">
                                    No checklist items found.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Selected Items Summary (Initially Hidden) -->
                    <div id="selected-items-summary" class="mt-6 bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4" style="display: none;">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-2">Selected Items for Review</h3>
                        <ul class="space-y-1" id="selected-items-list">
                            <!-- Items will be populated by JavaScript -->
                        </ul>
                    </div>

                    <!-- Attachments -->
                    @if($task->attachments && $task->attachments->isNotEmpty())
                        <div class="mb-8">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Attachments</h2>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach($task->attachments as $attachment)
                                    <div class="relative group bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 transition-colors duration-150">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <svg class="h-8 w-8 text-gray-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                    {{ $attachment->original_name }}
                                                </p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ number_format($attachment->size / 1024, 2) }} KB
                                                </p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <a href="{{ route('attachments.download', $attachment) }}" 
                                                   class="inline-flex items-center p-2 border border-transparent rounded-full text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Review Form -->
                    <form action="{{ route('accountant.tax-calendar.reviews.update', $task) }}" method="POST" class="mt-8" id="reviewForm">
                        @csrf
                        @method('PUT')

                        <!-- Review Status Selection -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Update Review Status</h2>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <!-- In Progress Option -->
                                    <div class="relative">
                                        <input type="radio" name="status" value="in_progress" class="peer hidden" id="status-in-progress" 
                                            {{ $task->status === 'in_progress' ? 'checked' : '' }}
                                            {{ !in_array($task->status, ['under_review', 'pending']) ? 'disabled' : '' }}>
                                        <label for="status-in-progress" 
                                               class="block w-full p-4 cursor-pointer bg-white dark:bg-gray-800 border-2 rounded-xl
                                                      peer-checked:border-blue-500 peer-checked:ring-1 peer-checked:ring-blue-500
                                                      hover:border-gray-300 dark:hover:border-gray-600
                                                      transition-all duration-200
                                                      {{ !in_array($task->status, ['under_review', 'pending']) ? 'opacity-50 cursor-not-allowed' : '' }}">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="flex items-center">
                                                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                    </svg>
                                                    <span class="text-sm font-medium text-gray-900 dark:text-white">In Progress</span>
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Started reviewing the submission</p>
                                        </label>
                                    </div>

                                    <!-- Request Changes Option -->
                                    <div class="relative">
                                        <input type="radio" name="status" value="changes_requested" class="peer hidden" id="status-changes" 
                                            {{ $task->status === 'changes_requested' ? 'checked' : '' }}
                                            {{ $task->status === 'in_progress' || !in_array($task->status, ['under_review', 'pending', 'in_progress']) ? 'disabled' : '' }}>
                                        <label for="status-changes" 
                                               class="block w-full p-4 cursor-pointer bg-white dark:bg-gray-800 border-2 rounded-xl
                                                      peer-checked:border-yellow-500 peer-checked:ring-1 peer-checked:ring-yellow-500
                                                      hover:border-gray-300 dark:hover:border-gray-600
                                                      transition-all duration-200
                                                      {{ $task->status === 'in_progress' || !in_array($task->status, ['under_review', 'pending', 'in_progress']) ? 'opacity-50 cursor-not-allowed' : '' }}">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="flex items-center">
                                                    <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Request Changes</span>
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Changes needed before approval</p>
                                        </label>
                                    </div>

                                    <!-- Reject Option -->
                                    <div class="relative">
                                        <input type="radio" name="status" value="rejected" class="peer hidden" id="status-rejected" 
                                            {{ $task->status === 'rejected' ? 'checked' : '' }}
                                            {{ $task->status === 'in_progress' || !in_array($task->status, ['under_review', 'pending', 'in_progress']) ? 'disabled' : '' }}>
                                        <label for="status-rejected" 
                                               class="block w-full p-4 cursor-pointer bg-white dark:bg-gray-800 border-2 rounded-xl
                                                      peer-checked:border-red-500 peer-checked:ring-1 peer-checked:ring-red-500
                                                      hover:border-gray-300 dark:hover:border-gray-600
                                                      transition-all duration-200
                                                      {{ $task->status === 'in_progress' || !in_array($task->status, ['under_review', 'pending', 'in_progress']) ? 'opacity-50 cursor-not-allowed' : '' }}">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="flex items-center">
                                                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Reject</span>
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Task does not meet requirements</p>
                                        </label>
                                    </div>

                                    <!-- Complete Option -->
                                    <div class="relative">
                                        <input type="radio" name="status" value="completed" class="peer hidden" id="status-completed" 
                                            {{ $task->status === 'completed' ? 'checked' : '' }}
                                            {{ !in_array($task->status, ['under_review', 'pending', 'in_progress']) ? 'disabled' : '' }}>
                                        <label for="status-completed" 
                                               class="block w-full p-4 cursor-pointer bg-white dark:bg-gray-800 border-2 rounded-xl
                                                      peer-checked:border-green-500 peer-checked:ring-1 peer-checked:ring-green-500
                                                      hover:border-gray-300 dark:hover:border-gray-600
                                                      transition-all duration-200
                                                      {{ !in_array($task->status, ['under_review', 'pending', 'in_progress']) ? 'opacity-50 cursor-not-allowed' : '' }}">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="flex items-center">
                                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Complete</span>
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Task meets all requirements</p>
                                        </label>
                                    </div>
                                </div>

                                @if($task->status === 'changes_requested')
                                    <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                        <p class="text-sm text-yellow-800 dark:text-yellow-300">
                                            Waiting for user to submit changes. You cannot update the status until they resubmit.
                                        </p>
                                    </div>
                                @elseif($task->status === 'rejected')
                                    <div class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                        <p class="text-sm text-red-800 dark:text-red-300">
                                            Task has been rejected. You cannot update the status until the user submits a new version.
                                        </p>
                                    </div>
                                @elseif($task->status === 'completed')
                                    <div class="mt-4 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                        <p class="text-sm text-green-800 dark:text-green-300">
                                            Task has been completed. No further status updates are allowed.
                                        </p>
                            </div>
                                @elseif($task->status === 'in_progress')
                                    <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                        <p class="text-sm text-blue-800 dark:text-blue-300">
                                            Task is in progress. You can only mark it as completed from this state.
                                        </p>
                        </div>
                                @endif

                                <!-- Review Comments -->
                                <div class="mt-6">
                                    <div class="flex items-center justify-between">
                                        <label for="review_comments" id="comments_label" class="block text-sm font-medium text-gray-900 dark:text-white">Review Notes</label>
                                        <span id="notes_hint" class="text-xs text-gray-500 dark:text-gray-400"></span>
                                    </div>
                                    <div class="mt-2">
                                <textarea id="review_comments" 
                                          name="review_comments" 
                                          rows="4" 
                                                  class="block w-full rounded-lg border-gray-300 shadow-sm
                                                         focus:border-blue-500 focus:ring-blue-500
                                                         dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                                         text-sm"
                                                  placeholder="Add your review notes here...">{{ old('review_comments', $task->review_comments) }}</textarea>
                            </div>
                        </div>

                                <!-- Form Actions -->
                                <div class="mt-6 flex items-center justify-end space-x-3">
                                    <a href="{{ route('accountant.tax-calendar.reviews') }}" 
                                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                        Cancel
                                    </a>
                            <button type="submit" 
                                            id="actionButton"
                                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                        Update Status
                            </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Chat Section -->
                    <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Communication History</h2>
                        
                        <!-- Messages Container -->
                        <div id="chat-messages" class="space-y-4 mb-4 h-96 overflow-y-auto p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                            @foreach($task->messages as $message)
                                <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-[70%] group relative">
                                        @if($message->user_id !== auth()->id())
                                            <div class="absolute -top-5 left-0">
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $message->user->name }}</span>
                                            </div>
                                        @endif
                                        
                                        <div class="{{ $message->user_id === auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100' }} rounded-lg px-4 py-2 shadow-sm">
                                            <div class="text-sm whitespace-pre-wrap">{!! preg_replace('/#item-(\d+)/', '<span class="font-semibold text-yellow-300">#item-$1</span>', e($message->content)) !!}</div>
                                            <div class="text-xs {{ $message->user_id === auth()->id() ? 'text-blue-100' : 'text-gray-500 dark:text-gray-400' }} mt-1">
                                                {{ $message->created_at->format('H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @push('scripts')
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const reviewForm = document.getElementById('reviewForm');
                            const commentsTextarea = document.getElementById('review_comments');
                            const commentsLabel = document.getElementById('comments_label');
                            const notesHint = document.getElementById('notes_hint');
                            const actionButton = document.getElementById('actionButton');
                            const selectedItems = new Set();

                            // Status-specific configurations
                            const statusConfig = {
                                'in_progress': {
                                    buttonText: 'Mark as In Progress',
                                    buttonClass: 'bg-blue-600 hover:bg-blue-700',
                                    labelText: 'Work Notes (Optional)',
                                    placeholder: 'Add notes about what you\'re working on (optional)...',
                                    hint: 'Optional: Add any notes about your review progress',
                                    required: false
                                },
                                'changes_requested': {
                                    buttonText: 'Request Changes',
                                    buttonClass: 'bg-yellow-600 hover:bg-yellow-700',
                                    labelText: 'Change Request Details',
                                    placeholder: 'Specify what changes are needed...',
                                    hint: 'Required: Please explain what changes are needed',
                                    required: true
                                },
                                'rejected': {
                                    buttonText: 'Reject Submission',
                                    buttonClass: 'bg-red-600 hover:bg-red-700',
                                    labelText: 'Rejection Reason',
                                    placeholder: 'Explain why this submission is being rejected...',
                                    hint: 'Required: Please provide a reason for rejection',
                                    required: true
                                },
                                'completed': {
                                    buttonText: 'Mark as Complete',
                                    buttonClass: 'bg-green-600 hover:bg-green-700',
                                    labelText: 'Completion Notes',
                                    placeholder: 'Add any final notes about the completion...',
                                    hint: 'Optional: Add any final notes about the task completion',
                                    required: false
                                }
                            };

                            // Update UI based on selected status
                            function updateStatusUI(status) {
                                const config = statusConfig[status];
                                
                                // Update button
                                actionButton.textContent = config.buttonText;
                                actionButton.className = `inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white ${config.buttonClass} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200`;
                                
                                // Update textarea and labels
                                commentsLabel.textContent = config.labelText;
                                commentsTextarea.placeholder = config.placeholder;
                                notesHint.textContent = config.hint;
                                commentsTextarea.required = config.required;

                                // Set default completion message if needed
                                if (status === 'completed' && !commentsTextarea.value.trim()) {
                                    const taskName = @json($task->taxCalendar->name);
                                    commentsTextarea.value = `${taskName} has been reviewed and completed successfully.`;
                                }

                                // Show/hide checklist selection for changes_requested and rejected
                                const showChecklist = ['changes_requested', 'rejected'].includes(status);
                                document.querySelectorAll('.select-for-review').forEach(button => {
                                    if (showChecklist) {
                                        button.style.display = 'inline-flex';
                                    } else {
                                        button.style.display = 'none';
                                        // Reset button state
                                        button.textContent = 'Select for Review';
                                        button.classList.remove('bg-blue-100', 'text-blue-700');
                                        button.classList.add('bg-gray-100', 'text-gray-700');
                                    }
                                });

                                // Clear selected items if not requesting changes or rejecting
                                if (!showChecklist) {
                                    selectedItems.clear();
                                    updateSelectedItemsSummary();
                                    // Hide all selected indicators
                                    document.querySelectorAll('.selected-indicator').forEach(indicator => {
                                        indicator.style.display = 'none';
                                    });
                                }

                                // Update the checklist section visibility hint
                                const checklistHint = document.createElement('div');
                                checklistHint.className = 'text-sm text-gray-500 dark:text-gray-400 mb-4';
                                if (showChecklist) {
                                    checklistHint.textContent = 'Select the items that need attention by clicking the "Select for Review" button next to each item.';
                                    document.querySelector('.checklist-section').insertBefore(checklistHint, document.querySelector('.checklist-items'));
                                } else {
                                    const existingHint = document.querySelector('.checklist-section > .text-sm');
                                    if (existingHint) {
                                        existingHint.remove();
                                    }
                                }
                            }

                            // Handle checklist item selection
                            function updateSelectedItemsSummary() {
                                const summary = document.getElementById('selected-items-summary');
                                const itemsList = document.getElementById('selected-items-list');
                                
                                if (selectedItems.size > 0) {
                                    summary.style.display = 'block';
                                    itemsList.innerHTML = Array.from(selectedItems).map(index => {
                                        const item = document.querySelector(`#checklist-item-${index}`);
                                        return `<li class="text-sm text-blue-700 dark:text-blue-300">• ${item.dataset.itemTitle}</li>`;
                                    }).join('');
                                } else {
                                    summary.style.display = 'none';
                                }

                                // Update textarea with selected items
                                const status = document.querySelector('input[name="status"]:checked')?.value;
                                if (['changes_requested', 'rejected'].includes(status)) {
                                    let currentComments = commentsTextarea.value;
                                    
                                    // Remove all existing item references
                                    currentComments = currentComments.replace(/^Item \d+: .*$/gm, '').trim();
                                    
                                    // Add selected items at the beginning
                                    if (selectedItems.size > 0) {
                                        const itemsText = Array.from(selectedItems).map(index => {
                                            const item = document.querySelector(`#checklist-item-${index}`);
                                            return `Item ${parseInt(index) + 1}: ${item.dataset.itemTitle}`;
                                        }).join('\n');
                                        
                                        // Add two newlines after items if there's existing content
                                        const separator = currentComments ? '\n\n' : '';
                                        commentsTextarea.value = itemsText + separator + currentComments;
                                    } else {
                                        commentsTextarea.value = currentComments;
                                    }
                                }
                            }

                            // Add click handlers to select buttons
                            document.querySelectorAll('.select-for-review').forEach(button => {
                                button.addEventListener('click', (e) => {
                                    e.preventDefault();
                                    const index = button.dataset.index;
                                    const item = document.querySelector(`#checklist-item-${index}`);
                                    const indicator = item.querySelector('.selected-indicator');
                                    
                                    if (selectedItems.has(index)) {
                                        selectedItems.delete(index);
                                        indicator.style.display = 'none';
                                        button.textContent = 'Select for Review';
                                        button.classList.remove('bg-blue-100', 'text-blue-700');
                                        button.classList.add('bg-gray-100', 'text-gray-700');
                                    } else {
                                        selectedItems.add(index);
                                        indicator.style.display = 'flex';
                                        button.textContent = 'Selected';
                                        button.classList.remove('bg-gray-100', 'text-gray-700');
                                        button.classList.add('bg-blue-100', 'text-blue-700');
                                    }
                                    
                                    updateSelectedItemsSummary();
                                });
                            });

                            // Update placeholder text based on status
                            function updateTextareaPlaceholder(status) {
                                if (status === 'changes_requested') {
                                    commentsTextarea.placeholder = 'Selected items will appear here. Add your specific change requests for each item...';
                                } else if (status === 'rejected') {
                                    commentsTextarea.placeholder = 'Selected items will appear here. Explain why these items don\'t meet requirements...';
                                }
                            }

                            // Handle status radio changes
                            document.querySelectorAll('input[name="status"]').forEach(radio => {
                                radio.addEventListener('change', (e) => {
                                    updateStatusUI(e.target.value);
                                    updateTextareaPlaceholder(e.target.value);
                                });
                            });

                            // Initialize UI with current status
                            const currentStatus = document.querySelector('input[name="status"]:checked');
                            if (currentStatus) {
                                updateStatusUI(currentStatus.value);
                                updateTextareaPlaceholder(currentStatus.value);
                            }

                        // Form validation
                        reviewForm.addEventListener('submit', function(e) {
                            const status = document.querySelector('input[name="status"]:checked');
                                const comments = commentsTextarea.value.trim();
                            
                            if (!status) {
                                e.preventDefault();
                                toastr.error('Please select a review status');
                                return;
                            }
                            
                                const config = statusConfig[status.value];
                                if (config.required && !comments) {
                                e.preventDefault();
                                    toastr.error(`Please provide ${config.labelText.toLowerCase()}`);
                                    commentsTextarea.focus();
                                return;
                                }

                                if (['changes_requested', 'rejected'].includes(status.value) && selectedItems.size === 0) {
                                    e.preventDefault();
                                    toastr.error('Please select at least one item that needs attention');
                                    return;
                                }
                            });
                        });
                    </script>
                    @endpush
                </div>
            </div>
        </div>
    </div>
    <style>
        .status-badge {
            @apply inline-flex items-center transition-all duration-200 shadow-sm;
        }
        .status-badge.pending {
            @apply bg-gradient-to-r from-yellow-500/10 to-yellow-500/20 text-yellow-700 ring-1 ring-yellow-500/20;
            @apply dark:from-yellow-400/10 dark:to-yellow-400/20 dark:text-yellow-400 dark:ring-yellow-400/30;
        }
        .status-badge.changes_requested {
            @apply bg-gradient-to-r from-orange-500/10 to-orange-500/20 text-orange-700 ring-1 ring-orange-500/20;
            @apply dark:from-orange-400/10 dark:to-orange-400/20 dark:text-orange-400 dark:ring-orange-400/30;
        }
        .status-badge.rejected {
            @apply bg-gradient-to-r from-red-500/10 to-red-500/20 text-red-700 ring-1 ring-red-500/20;
            @apply dark:from-red-400/10 dark:to-red-400/20 dark:text-red-400 dark:ring-red-400/30;
        }
        .status-badge.in_progress {
            @apply bg-gradient-to-r from-blue-500/10 to-blue-500/20 text-blue-700 ring-1 ring-blue-500/20;
            @apply dark:from-blue-400/10 dark:to-blue-400/20 dark:text-blue-400 dark:ring-blue-400/30;
        }
        .status-badge.completed {
            @apply bg-gradient-to-r from-emerald-500/10 to-emerald-500/20 text-emerald-700 ring-1 ring-emerald-500/20;
            @apply dark:from-emerald-400/10 dark:to-emerald-400/20 dark:text-emerald-400 dark:ring-emerald-400/30;
        }
    </style>
</x-app-layout>