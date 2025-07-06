<!-- Calculate variables at the top -->
@php
    $totalItems = count($task->user_checklist ?? []);
    $completedItems = collect($task->user_checklist ?? [])->where('completed', true)->count();
    $progress = $totalItems > 0 ? ($completedItems / $totalItems) * 100 : 0;
@endphp

<x-user.layout 
    title="{{ $task->taxCalendar->name }}" 
    :breadcrumbs="[
        ['title' => __('Home'), 'href' => route('user.dashboard'), 'first' => true],
        ['title' => __('Tax Calendar'), 'href' => route('user.user.tax-calendar.index')],
        ['title' => $task->taxCalendar->name]
    ]"
>
    <div class="space-y-6">
        <!-- Task Header -->
        <x-ui.card.base>
            <x-ui.card.header>
                <div class="flex justify-between items-start">
                    <div class="flex items-start">
                        <svg class="h-6 w-6 text-indigo-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $task->taxCalendar->name }}</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $task->taxCalendar->description }}</p>
                            @if($task->taxCalendar->emta_link)
                                <a href="{{ $task->taxCalendar->emta_link }}" target="_blank" rel="noopener noreferrer" 
                                   class="inline-flex items-center mt-2 text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    {{ __('EMTA Guide') }}
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Due Date') }}</div>
                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $task->due_date->format('M d, Y') }}</div>
                        </div>
                            
                            @php
                                $statusClass = match($task->status) {
                                    'completed' => 'border-green-500 bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 dark:border-green-500/30',
                                    'under_review' => 'border-blue-500 bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-500/30',
                                    'changes_requested' => 'border-yellow-500 bg-yellow-50 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 dark:border-yellow-500/30',
                                    'overdue' => 'border-red-500 bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400 dark:border-red-500/30',
                                    default => 'border-gray-500 bg-gray-50 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400 dark:border-gray-500/30'
                                };

                                $statusIcon = match($task->status) {
                                    'completed' => '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
                                    'under_review' => '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>',
                                    'changes_requested' => '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>',
                                    'overdue' => '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                    default => '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                                };

                                $statusText = match($task->status) {
                                    'completed' => 'Completed',
                                    'under_review' => 'Under Review',
                                    'changes_requested' => 'Changes Requested',
                                    'overdue' => 'Overdue',
                                    default => 'Pending'
                                };
                            @endphp

                        <div class="flex items-center space-x-3">
                            @php
                                $badgeVariant = match($task->status) {
                                    'completed' => 'success',
                                    'under_review' => 'primary',
                                    'changes_requested' => 'warning',
                                    'overdue' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <x-ui.badge :variant="$badgeVariant">
                                {!! $statusIcon !!}
                                {{ $statusText }}
                            </x-ui.badge>


                            @if($task->status === 'pending' || $task->status === 'changes_requested')
                                <form action="{{ route('user.tax-calendar.submit-for-review', $task->id) }}" method="POST" id="complete-task-form">
                                    @csrf
                                    @method('PATCH')
                                    <x-ui.button.primary type="submit" id="complete-task-button" size="sm" :disabled="$completedItems !== $totalItems">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $task->status === 'changes_requested' ? __('Send Updated for Review') : __('Send for Review') }}
                                    </x-ui.button.primary>
                                </form>
                            @elseif($task->status === 'under_review')
                                <x-ui.badge variant="primary">
                                    <svg class="w-4 h-4 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    {{ __('Waiting for Review') }}
                                </x-ui.badge>
                            @endif
                        </div>
                    </div>
                </div>
            </x-ui.card.header>
        </x-ui.card.base>

        <!-- Changes Requested Alert -->
        @if($task->status === 'changes_requested')
            <x-ui.alert variant="warning">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">{{ __('Changes Requested') }}</h3>
                        <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                            <p>{{ $task->review_feedback }}</p>
                        </div>
                        @if($task->review_feedback_date)
                            <div class="mt-2 text-xs text-yellow-600 dark:text-yellow-400">
                                {{ __('Requested on') }} {{ $task->review_feedback_date->format('M d, Y H:i') }}
                            </div>
                        @endif
                    </div>
                </div>
            </x-ui.alert>
        @endif

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
                        <span class="completed-count">{{ $completedItems }}</span> of <span class="total-count">{{ $totalItems }}</span> completed
                    </div>
                </div>
            </x-ui.card.header>
            <x-ui.card.body>
                <x-ui.progress :value="$progress" size="lg" />
            </x-ui.card.body>
        </x-ui.card.base>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Instructions Section -->
            <x-ui.card.base>
                <x-ui.card.header>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
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

            <!-- Checklist Section -->
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
                    @if($task->user_checklist)
                                <div id="checklist-container" data-task-id="{{ $task->id }}" class="space-y-2">
                                    @foreach($task->user_checklist as $index => $item)
                                    <div class="flex items-start group hover:bg-gray-50 dark:hover:bg-gray-700/50 p-3 rounded-lg transition-all duration-200"
                                         data-index="{{ $index }}">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" 
                                                class="checklist-item w-5 h-5 text-blue-600 border-gray-300 dark:border-gray-600 rounded cursor-pointer focus:ring-blue-500 focus:ring-offset-0"
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
                                            <span class="text-xs px-2 py-1 rounded-full {{ $item['completed'] ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }} transition-colors duration-200">
                                                {{ $item['completed'] ? 'Done' : 'Todo' }}
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

            <!-- Chat Section -->
            <x-ui.card.base class="lg:col-span-2">
                <x-ui.card.header>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Chat with') }} {{ auth()->user()->is_accountant ? __('Client') : __('Accountant') }}</h2>
                    </div>
                </x-ui.card.header>
                <x-ui.card.body>

                    <!-- Chat Messages Container -->
                    <div id="chat-messages" class="space-y-4 mb-4 h-64 overflow-y-auto p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                                @foreach($task->messages ?? [] as $message)
                                    <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                        <div class="max-w-[70%] {{ $message->user_id === auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100' }} rounded-lg px-4 py-2 shadow-sm">
                                            <div class="text-sm">{{ $message->content }}</div>
                                            <div class="text-xs {{ $message->user_id === auth()->id() ? 'text-blue-100' : 'text-gray-500 dark:text-gray-400' }} mt-1">
                                                {{ $message->created_at->format('M d, H:i') }} - {{ $message->user->name }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                    </div>

                    <!-- Message Input Form -->
                    <form id="chat-form" class="mt-4">
                        @csrf
                        <div class="flex space-x-2">
                            <div class="flex-1">
                                <x-ui.form.textarea 
                                    name="message"
                                    id="message-input"
                                    rows="1"
                                    placeholder="{{ __('Type your message...') }}"
                                    class="resize-none"
                                />
                            </div>
                            <x-ui.button.primary type="submit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                            </x-ui.button.primary>
                        </div>
                    </form>
                </x-ui.card.body>
            </x-ui.card.base>
        </div>
    </div>


    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Checklist functionality
        const container = document.getElementById('checklist-container');
        if (container) {
            const taskId = container.dataset.taskId;
            let saveTimeout;
            const completeTaskButton = document.getElementById('complete-task-button');
            const completeTaskForm = document.getElementById('complete-task-form');

            // Function to update button state
            function updateCompleteButtonState() {
                const totalItems = container.querySelectorAll('.checklist-item').length;
                const completedItems = container.querySelectorAll('.checklist-item:checked').length;
                const allCompleted = totalItems > 0 && completedItems === totalItems;
                
                if (completeTaskButton) {
                    completeTaskButton.disabled = !allCompleted;
                    
                    // Update tooltip/title
                    completeTaskButton.title = allCompleted 
                        ? 'Mark this task as completed' 
                        : 'Complete all checklist items before sending for review';
                }
            }

            // Add form submit handler
            if (completeTaskForm) {
                completeTaskForm.addEventListener('submit', function(e) {
                    const totalItems = container.querySelectorAll('.checklist-item').length;
                    const completedItems = container.querySelectorAll('.checklist-item:checked').length;
                    
                    if (completedItems < totalItems) {
                        e.preventDefault();
                        toastr.warning('Please complete all checklist items before sending for review.');
                        return false;
                    }
                });
            }

            container.addEventListener('change', async function(e) {
                if (!e.target.matches('.checklist-item')) return;

                const itemDiv = e.target.closest('[data-index]');
                const index = itemDiv.dataset.index;
                const isCompleted = e.target.checked;

                // Update UI immediately
                const label = itemDiv.querySelector('label span');
                const notes = itemDiv.querySelector('label p');
                const status = itemDiv.querySelector('.rounded-full');

                // Update status badge
                status.textContent = isCompleted ? 'Done' : 'Todo';
                status.className = `text-xs px-2 py-1 rounded-full transition-colors duration-200 ${
                    isCompleted 
                        ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' 
                        : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400'
                }`;

                // Update text styles
                label.classList.toggle('line-through', isCompleted);
                label.classList.toggle('text-gray-400', isCompleted);
                if (notes) {
                    notes.classList.toggle('line-through', isCompleted);
                    notes.classList.toggle('opacity-75', isCompleted);
                }

                // Update progress counter immediately
                const completedItems = container.querySelectorAll('.checklist-item:checked').length;
                const totalItems = container.querySelectorAll('.checklist-item').length;
                const progress = (completedItems / totalItems) * 100;

                // Update progress bar and counter
                document.querySelector('.bg-blue-600.h-3').style.width = `${progress}%`;
                document.querySelector('.completed-count').textContent = completedItems;
                document.querySelector('.total-count').textContent = totalItems;

                // Update complete button state
                updateCompleteButtonState();

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
                                title: titleSpan.textContent.trim(),
                                completed: checkbox.checked,
                                notes: notesP ? notesP.textContent.trim() : null
                            };
                        });

                        const response = await fetch(`{{ route('user.tax-calendar.update-checklist', $task->id) }}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ user_checklist: items })
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message || 'Failed to save');
                        }

                        const result = await response.json();
                        toastr.success('Progress saved successfully');

                    } catch (error) {
                        console.error('Failed to save checklist:', error);
                        toastr.error(error.message || 'Failed to save progress. Please try again.');
                    }
                }, 500);
            });

            // Initialize button state on page load
            updateCompleteButtonState();
        }

        // Chat functionality
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        const chatMessages = document.getElementById('chat-messages');

        // Auto-resize textarea
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Scroll to bottom of chat
        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        scrollToBottom();

        // Handle form submission
        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message) return;

            try {
                const response = await fetch(`{{ route('user.tax-calendar.send-message', $task->id) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message })
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Failed to send message');
                }

                const result = await response.json();
                
                // Add message to chat
                const messageHtml = `
                    <div class="flex justify-end">
                        <div class="max-w-[70%] bg-blue-500 text-white rounded-lg px-4 py-2 shadow-sm">
                            <div class="text-sm">${message}</div>
                            <div class="text-xs text-blue-100 mt-1">
                                ${new Date().toLocaleString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })} - {{ auth()->user()->name }}
                            </div>
                        </div>
                    </div>
                `;
                chatMessages.insertAdjacentHTML('beforeend', messageHtml);
                
                // Clear input and reset height
                messageInput.value = '';
                messageInput.style.height = 'auto';
                
                // Scroll to new message
                scrollToBottom();
                
                toastr.success('Message sent successfully');

            } catch (error) {
                console.error('Failed to send message:', error);
                toastr.error(error.message || 'Failed to send message. Please try again.');
            }
        });
    });
    </script>
@endpush
</x-user.layout> 