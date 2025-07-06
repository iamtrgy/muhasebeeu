<x-user.layout 
    title="{{ $task->taxCalendar->name }}" 
    :breadcrumbs="[
        ['title' => __('Home'), 'href' => route('user.dashboard'), 'first' => true],
        ['title' => __('Tax Calendar'), 'href' => route('user.tax-calendar.index')],
        ['title' => $task->taxCalendar->name]
    ]"
>
    <div class="space-y-6">
        <!-- Task Header -->
        <x-ui.card.base>
            <x-ui.card.body class="p-6">
                <div class="flex justify-between items-start">
                    <div class="flex items-start space-x-4 flex-1">
                        <div class="flex-shrink-0 mt-1">
                            @php
                                $urgencyIcon = match($task->urgency_level) {
                                    'overdue' => '<svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z" /></svg>',
                                    'urgent' => '<svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                                    'warning' => '<svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                                    'completed' => '<svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>',
                                    default => '<svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>'
                                };
                            @endphp
                            {!! $urgencyIcon !!}
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 {{ $task->is_completed ? 'line-through text-gray-500 dark:text-gray-400' : '' }}">
                                    {{ $task->taxCalendar->name }}
                                </h1>
                                @if($task->taxCalendar->form_code)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        {{ $task->taxCalendar->form_code }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="space-y-1 text-sm text-gray-600 dark:text-gray-400">
                                <p class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    {{ $task->company->name }}
                                </p>
                                <p class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Due {{ $task->due_date->format('F j, Y') }}
                                    @if($task->is_overdue)
                                        <span class="ml-2 text-red-600 dark:text-red-400 font-medium">(Overdue by {{ abs($task->days_until_due) }} days)</span>
                                    @elseif($task->days_until_due >= 0 && $task->days_until_due <= 7 && !$task->is_completed)
                                        <span class="ml-2 text-orange-600 dark:text-orange-400 font-medium">({{ $task->days_until_due }} days left)</span>
                                    @endif
                                </p>
                            </div>
                            
                            @if($task->taxCalendar->description)
                                <p class="mt-3 text-gray-700 dark:text-gray-300">{{ $task->taxCalendar->description }}</p>
                            @endif
                            
                            @if($task->taxCalendar->emta_link)
                                <div class="mt-3">
                                    <a href="{{ $task->taxCalendar->emta_link }}" target="_blank" rel="noopener noreferrer" 
                                       class="inline-flex items-center text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        {{ __('View EMTA Guide') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Status and Actions -->
                    <div class="flex items-center space-x-4">
                        @if($task->is_completed)
                            <x-ui.badge variant="success" size="lg">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ __('Completed') }}
                            </x-ui.badge>
                        @else
                            @php
                                $statusVariant = match($task->urgency_level) {
                                    'overdue' => 'danger',
                                    'urgent' => 'warning',
                                    'warning' => 'warning',
                                    default => 'secondary'
                                };
                                $statusText = match($task->urgency_level) {
                                    'overdue' => 'Overdue',
                                    'urgent' => 'Urgent',
                                    'warning' => 'Due Soon',
                                    default => 'Pending'
                                };
                            @endphp
                            <x-ui.badge :variant="$statusVariant" size="lg">
                                {{ __($statusText) }}
                            </x-ui.badge>
                        @endif
                        
                        <!-- Toggle Complete Button -->
                        <form action="{{ route('user.tax-calendar.toggle-complete', $task) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            @if($task->is_completed)
                                <x-ui.button.secondary type="submit">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    {{ __('Reopen Task') }}
                                </x-ui.button.secondary>
                            @else
                                <x-ui.button.primary type="submit" id="complete-task-button" :disabled="$task->completed_items !== $task->total_items">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ __('Mark Complete') }}
                                </x-ui.button.primary>
                            @endif
                        </form>
                    </div>
                </div>
            </x-ui.card.body>
        </x-ui.card.base>

        <!-- Progress Card -->
        <x-ui.card.base>
            <x-ui.card.header>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Progress') }}</h3>
                    </div>
                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        <span class="completed-count">{{ $task->completed_items }}</span> of <span class="total-count">{{ $task->total_items }}</span> completed
                    </div>
                </div>
            </x-ui.card.header>
            <x-ui.card.body>
                <x-ui.progress :value="$task->progress" size="lg" />
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 text-center">
                    {{ round($task->progress) }}% complete
                </p>
            </x-ui.card.body>
        </x-ui.card.base>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Checklist -->
            <div class="lg:col-span-2">
                <x-ui.card.base>
                    <x-ui.card.header>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12l2 2 4-4" />
                            </svg>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Checklist') }}</h2>
                        </div>
                    </x-ui.card.header>
                    <x-ui.card.body>
                        @if($task->checklist && count($task->checklist) > 0)
                            <div id="checklist-container" data-task-id="{{ $task->id }}" class="space-y-3">
                                @foreach($task->checklist as $index => $item)
                                <div class="flex items-start group hover:bg-gray-50 dark:hover:bg-gray-700/50 p-3 rounded-lg transition-all duration-200"
                                     data-index="{{ $index }}">
                                    <div class="flex items-center h-5 mt-1">
                                        <input type="checkbox" 
                                            class="checklist-item w-5 h-5 text-emerald-600 border-gray-300 dark:border-gray-600 rounded cursor-pointer focus:ring-emerald-500 focus:ring-offset-0"
                                            {{ $item['completed'] ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <label class="text-sm cursor-pointer select-none">
                                            <span class="font-medium text-gray-900 dark:text-gray-100 transition-all duration-200 {{ $item['completed'] ? 'line-through text-gray-400 dark:text-gray-500' : '' }}">
                                                {{ $item['title'] }}
                                            </span>
                                            @if(!empty($item['notes']))
                                                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1 transition-all duration-200 {{ $item['completed'] ? 'line-through opacity-75' : '' }}">
                                                    {{ $item['notes'] }}
                                                </p>
                                            @endif
                                        </label>
                                    </div>
                                    <div class="ml-2">
                                        <span class="text-xs px-2 py-1 rounded-full {{ $item['completed'] ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }} transition-colors duration-200">
                                            {{ $item['completed'] ? __('Done') : __('Todo') }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="mt-4 text-gray-500 dark:text-gray-400">{{ __('No checklist items available.') }}</p>
                            </div>
                        @endif
                    </x-ui.card.body>
                </x-ui.card.base>
            </div>

            <!-- Instructions and Notes -->
            <div class="space-y-6">
                <!-- Instructions -->
                @if($task->taxCalendar->user_instructions)
                <x-ui.card.base>
                    <x-ui.card.header>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Instructions') }}</h2>
                        </div>
                    </x-ui.card.header>
                    <x-ui.card.body>
                        <div class="prose prose-sm max-w-none dark:prose-invert bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4">
                            {!! nl2br(e($task->taxCalendar->user_instructions)) !!}
                        </div>
                    </x-ui.card.body>
                </x-ui.card.base>
                @endif

                <!-- Notes -->
                <x-ui.card.base>
                    <x-ui.card.header>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-amber-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('My Notes') }}</h2>
                        </div>
                    </x-ui.card.header>
                    <x-ui.card.body>
                        <form action="{{ route('user.tax-calendar.update-notes', $task) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <x-ui.form.textarea 
                                name="user_notes"
                                rows="4"
                                placeholder="{{ __('Add your notes here...') }}"
                                value="{{ old('user_notes', $task->user_notes) }}"
                                :error="$errors->first('user_notes')"
                            />
                            <div class="mt-3 flex justify-end">
                                <x-ui.button.primary type="submit" size="sm">
                                    {{ __('Save Notes') }}
                                </x-ui.button.primary>
                            </div>
                        </form>
                    </x-ui.card.body>
                </x-ui.card.base>

                <!-- Task Details -->
                @if($task->notes)
                <x-ui.card.base>
                    <x-ui.card.header>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Task Details') }}</h2>
                        </div>
                    </x-ui.card.header>
                    <x-ui.card.body>
                        <div class="prose prose-sm max-w-none dark:prose-invert bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4">
                            {!! nl2br(e($task->notes)) !!}
                        </div>
                    </x-ui.card.body>
                </x-ui.card.base>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('checklist-container');
        if (!container) return;
        
        const taskId = container.dataset.taskId;
        let saveTimeout;
        const completeTaskButton = document.getElementById('complete-task-button');

        // Function to update button state and progress
        function updateTaskState() {
            const totalItems = container.querySelectorAll('.checklist-item').length;
            const completedItems = container.querySelectorAll('.checklist-item:checked').length;
            const allCompleted = totalItems > 0 && completedItems === totalItems;
            const progress = totalItems > 0 ? (completedItems / totalItems) * 100 : 0;
            
            // Update button state
            if (completeTaskButton) {
                completeTaskButton.disabled = !allCompleted;
                completeTaskButton.title = allCompleted 
                    ? 'All items completed - you can now mark this task as complete' 
                    : 'Complete all checklist items before marking task as complete';
            }
            
            // Update progress counter
            const completedCounter = document.querySelector('.completed-count');
            const totalCounter = document.querySelector('.total-count');
            if (completedCounter) completedCounter.textContent = completedItems;
            if (totalCounter) totalCounter.textContent = totalItems;
            
            // Update progress bar
            const progressBar = document.querySelector('[role="progressbar"] .bg-indigo-600');
            if (progressBar) {
                progressBar.style.width = `${progress}%`;
            }
        }

        // Handle checkbox changes
        container.addEventListener('change', async function(e) {
            if (!e.target.matches('.checklist-item')) return;

            const itemDiv = e.target.closest('[data-index]');
            const index = parseInt(itemDiv.dataset.index);
            const isCompleted = e.target.checked;

            // Update UI immediately
            const label = itemDiv.querySelector('label span');
            const notes = itemDiv.querySelector('label p');
            const status = itemDiv.querySelector('.rounded-full');

            // Update status badge
            status.textContent = isCompleted ? '{{ __("Done") }}' : '{{ __("Todo") }}';
            status.className = `text-xs px-2 py-1 rounded-full transition-colors duration-200 ${
                isCompleted 
                    ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' 
                    : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400'
            }`;

            // Update text styles
            label.classList.toggle('line-through', isCompleted);
            label.classList.toggle('text-gray-400', isCompleted);
            label.classList.toggle('dark:text-gray-500', isCompleted);
            if (notes) {
                notes.classList.toggle('line-through', isCompleted);
                notes.classList.toggle('opacity-75', isCompleted);
            }

            // Update task state
            updateTaskState();

            // Debounced save
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(async () => {
                try {
                    // Collect all checklist items
                    const items = Array.from(container.querySelectorAll('[data-index]')).map(div => {
                        const checkbox = div.querySelector('.checklist-item');
                        const titleSpan = div.querySelector('label span');
                        const notesP = div.querySelector('label p');
                        
                        return {
                            title: titleSpan.textContent.replace(/^\s+|\s+$/g, ''), // Trim whitespace
                            completed: checkbox.checked,
                            notes: notesP ? notesP.textContent.replace(/^\s+|\s+$/g, '') : null
                        };
                    });

                    const response = await fetch(`{{ route('user.tax-calendar.update-checklist', $task->id) }}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ checklist: items })
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Failed to save');
                    }

                    const result = await response.json();
                    
                    // Show success message
                    if (typeof toastr !== 'undefined') {
                        toastr.success('Progress saved successfully');
                    }

                } catch (error) {
                    console.error('Failed to save checklist:', error);
                    if (typeof toastr !== 'undefined') {
                        toastr.error(error.message || 'Failed to save progress. Please try again.');
                    }
                }
            }, 500);
        });

        // Initialize button state on page load
        updateTaskState();
    });
    </script>
    @endpush
</x-user.layout>