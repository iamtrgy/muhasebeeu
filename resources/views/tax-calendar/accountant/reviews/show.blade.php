<x-accountant.layout 
    title="{{ $task->taxCalendar->name }}"
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('accountant.dashboard'), 'first' => true],
        ['title' => __('Reviews'), 'href' => route('accountant.tax-calendar.reviews')],
        ['title' => $task->taxCalendar->name]
    ]"
>
    <div class="space-y-6">
        {{-- Task Header --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Task Info Card --}}
            <x-ui.card.base class="lg:col-span-2">
                <x-ui.card.body class="p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $task->taxCalendar->name }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $task->taxCalendar->description }}</p>
                            <div class="mt-3 flex items-center gap-3">
                                @php
                                    $statusVariant = match($task->status) {
                                        'pending' => 'warning',
                                        'approved', 'completed' => 'success',
                                        'rejected' => 'danger',
                                        'changes_requested' => 'warning',
                                        'in_progress' => 'secondary',
                                        default => 'secondary'
                                    };
                                @endphp
                                <x-ui.badge :variant="$statusVariant">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </x-ui.badge>
                                @if($task->due_date && $task->due_date->isPast())
                                    <x-ui.badge variant="danger">{{ __('Overdue') }}</x-ui.badge>
                                @elseif($task->due_date && $task->due_date->diffInDays(now()) <= 3)
                                    <x-ui.badge variant="warning">{{ __('Due Soon') }}</x-ui.badge>
                                @endif
                            </div>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            {{-- Due Date Card --}}
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-amber-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Due Date') }}</div>
                            <div class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                {{ $task->due_date ? $task->due_date->format('M d, Y') : __('No due date') }}
                            </div>
                            @if($task->due_date)
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $task->due_date->diffForHumans() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            {{-- Submitted Date Card --}}
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Submitted') }}</div>
                            <div class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                {{ $task->submitted_at ? $task->submitted_at->format('M d, Y') : __('Not submitted') }}
                            </div>
                            @if($task->submitted_at)
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $task->submitted_at->diffForHumans() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Checklist Section --}}
                <x-ui.card.base>
                    <x-ui.card.header>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Task Checklist') }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Items completed by the user') }}</p>
                    </x-ui.card.header>
                    <x-ui.card.body>
                        @if($task->checklist && count($task->checklist) > 0)
                            <div class="space-y-3">
                                @foreach($task->checklist as $index => $item)
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" 
                                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-700"
                                                   {{ $item['completed'] ? 'checked' : '' }}
                                                   disabled>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label class="text-gray-700 dark:text-gray-300 {{ $item['completed'] ? 'line-through' : '' }}">
                                                {{ $item['title'] }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No checklist items available') }}</p>
                        @endif
                    </x-ui.card.body>
                </x-ui.card.base>

                {{-- Notes Section --}}
                <x-ui.card.base>
                    <x-ui.card.header>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('User Notes') }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Additional notes provided by the user') }}</p>
                    </x-ui.card.header>
                    <x-ui.card.body>
                        @if($task->notes)
                            <div class="prose prose-sm max-w-none dark:prose-invert">
                                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $task->notes }}</p>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No notes provided') }}</p>
                        @endif
                    </x-ui.card.body>
                </x-ui.card.base>

                {{-- Review Actions --}}
                @if($task->status === 'under_review')
                    <x-ui.card.base>
                        <x-ui.card.header>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Review Actions') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Take action on this submission') }}</p>
                        </x-ui.card.header>
                        <x-ui.card.body>
                            <form action="{{ route('accountant.tax-calendar.reviews.update', $task) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PUT')
                                
                                <x-ui.form.group>
                                    <x-ui.form.textarea 
                                        name="review_notes" 
                                        label="{{ __('Review Notes') }}"
                                        placeholder="{{ __('Enter your review comments...') }}"
                                        rows="4"
                                    />
                                </x-ui.form.group>

                                <div class="flex items-center gap-3">
                                    <x-ui.button.primary type="submit" name="action" value="approve">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ __('Approve') }}
                                    </x-ui.button.primary>
                                    
                                    <x-ui.button.secondary type="submit" name="action" value="request_changes">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        {{ __('Request Changes') }}
                                    </x-ui.button.secondary>
                                    
                                    <x-ui.button.danger type="submit" name="action" value="reject">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ __('Reject') }}
                                    </x-ui.button.danger>
                                </div>
                            </form>
                        </x-ui.card.body>
                    </x-ui.card.base>
                @endif

                {{-- Messages Section --}}
                <x-ui.card.base>
                    <x-ui.card.header>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Messages') }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Communication between user and reviewer') }}</p>
                    </x-ui.card.header>
                    <x-ui.card.body>
                        <div class="space-y-4 max-h-96 overflow-y-auto" id="messages-container">
                            @forelse($task->messages->sortBy('created_at') as $message)
                                <div class="flex items-start">
                                    <x-ui.avatar name="{{ $message->user->name }}" size="sm" class="flex-shrink-0" />
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center gap-2">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $message->user->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $message->created_at->format('M d, H:i') }}</p>
                                        </div>
                                        <div class="mt-1 bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                            <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $message->content }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <x-ui.table.empty-state>
                                    <x-slot name="icon">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                    </x-slot>
                                    <x-slot name="title">{{ __('No Messages') }}</x-slot>
                                    <x-slot name="description">{{ __('No messages have been exchanged yet. Start a conversation with the user.') }}</x-slot>
                                </x-ui.table.empty-state>
                            @endforelse
                        </div>
                        
                        {{-- Message Input --}}
                        <form action="{{ route('accountant.tax-calendar.send-message', $task) }}" method="POST" class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4" id="message-form">
                            @csrf
                            <div class="flex items-end gap-3">
                                <div class="flex-1">
                                    <x-ui.form.textarea 
                                        name="content" 
                                        placeholder="{{ __('Type your message...') }}"
                                        rows="2"
                                        required
                                        id="message-input"
                                    />
                                </div>
                                <x-ui.button.primary type="submit">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                    {{ __('Send Message') }}
                                </x-ui.button.primary>
                            </div>
                        </form>
                    </x-ui.card.body>
                </x-ui.card.base>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Company Info --}}
                <x-ui.card.base>
                    <x-ui.card.header>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Company Information') }}</h3>
                    </x-ui.card.header>
                    <x-ui.card.body>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                @if($task->company->logo_url)
                                    <img class="h-12 w-12 rounded-lg object-cover" src="{{ $task->company->logo_url }}" alt="{{ $task->company->name }}">
                                @else
                                    <div class="h-12 w-12 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $task->company->name }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Tax Number') }}: {{ $task->company->tax_number }}</p>
                                </div>
                            </div>
                            
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-2">{{ __('Contact Person') }}</h5>
                                <div class="flex items-center">
                                    <x-ui.avatar name="{{ $task->company->users->first()->name }}" size="sm" />
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $task->company->users->first()->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $task->company->users->first()->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-ui.card.body>
                </x-ui.card.base>

                {{-- Review History --}}
                @if($task->reviewed_at)
                    <x-ui.card.base>
                        <x-ui.card.header>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Review History') }}</h3>
                        </x-ui.card.header>
                        <x-ui.card.body>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">{{ __('Reviewed By') }}</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $task->reviewer->name ?? __('System') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">{{ __('Review Date') }}</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $task->reviewed_at->format('M d, Y H:i') }}</p>
                                </div>
                                @if($task->review_notes)
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">{{ __('Review Notes') }}</p>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $task->review_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </x-ui.card.body>
                    </x-ui.card.base>
                @endif

                {{-- Quick Actions --}}
                <x-ui.card.base>
                    <x-ui.card.header>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Quick Actions') }}</h3>
                    </x-ui.card.header>
                    <x-ui.card.body class="space-y-3">
                        <x-ui.button.secondary href="{{ route('accountant.tax-calendar.reviews') }}" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ __('Back to Reviews') }}
                        </x-ui.button.secondary>
                        
                        @if($task->status === 'pending')
                            <x-ui.button.primary href="#review-actions" class="w-full">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __('Review Now') }}
                            </x-ui.button.primary>
                        @endif
                    </x-ui.card.body>
                </x-ui.card.base>
            </div>
        </div>
    </div>
    
    <style>
        #messages-container {
            scroll-behavior: smooth;
        }
    </style>
    
    <script>
        let lastMessageCount = {{ $task->messages->count() }};
        let isTyping = false;
        
        // Track if user is typing in message input
        document.addEventListener('DOMContentLoaded', () => {
            const messageInput = document.querySelector('#message-input');
            const messageForm = document.querySelector('#message-form');
            
            if (messageInput) {
                messageInput.addEventListener('focus', () => { isTyping = true; });
                messageInput.addEventListener('blur', () => { 
                    setTimeout(() => { isTyping = messageInput.value.length > 0; }, 100);
                });
                messageInput.addEventListener('input', () => { isTyping = messageInput.value.length > 0; });
            }
            
            // Save scroll position before form submission
            if (messageForm) {
                messageForm.addEventListener('submit', (e) => {
                    // Save current scroll position
                    sessionStorage.setItem('scrollPosition', window.pageYOffset);
                });
            }
            
            // Restore scroll position after page load (for form submissions)
            const savedPosition = sessionStorage.getItem('scrollPosition');
            if (savedPosition) {
                window.scrollTo(0, parseInt(savedPosition));
                sessionStorage.removeItem('scrollPosition');
            }
            
            // Scroll to bottom of messages
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
        
        // Auto-refresh messages (only when not typing)
        setInterval(() => {
            if (!isTyping) {
                fetch('{{ route('accountant.tax-calendar.messages', $task) }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.messages && data.messages.length > lastMessageCount) {
                            lastMessageCount = data.messages.length;
                            // Only reload if user is not actively typing
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.log('Message refresh failed:', error);
                    });
            }
        }, 10000); // Reduced frequency to 10 seconds
    </script>
</x-accountant.layout>