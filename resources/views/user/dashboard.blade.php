<x-user.layout 
    title="Dashboard"
    :breadcrumbs="[['title' => __('Dashboard'), 'first' => true]]"
>
    <div class="space-y-6">
        <!-- Task Overview Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Due Today Card -->
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-orange-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Due Today') }}</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $tasks->where('due_date', now()->startOfDay())->count() }}</div>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Upcoming Tasks Card -->
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Upcoming (7 days)') }}</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $tasks->filter(function($task) { 
                                $daysUntil = now()->startOfDay()->diffInDays($task->due_date->startOfDay(), false);
                                return $daysUntil > 0 && $daysUntil <= 7;
                            })->count() }}</div>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Overdue Tasks Card -->
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Overdue') }}</div>
                            <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $tasks->filter(function($task) { 
                                return now()->startOfDay()->gt($task->due_date->startOfDay()) && $task->status !== 'completed';
                            })->count() }}</div>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Completed Tasks Card -->
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Completed') }}</div>
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $completedTasksCount ?? 0 }}</div>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        </div>

            <!-- Your Tasks -->
            <div class="mb-6">
                <!-- Tabs for Active and Completed Tasks -->
                <div x-data="{ activeTab: 'active' }">
                    <x-ui.tabs.list>
                        <x-ui.tabs.tab name="active" x-on:click="activeTab = 'active'" :active="true">
                            Active Tasks
                        </x-ui.tabs.tab>
                        <x-ui.tabs.tab name="completed" x-on:click="activeTab = 'completed'">
                            Completed Tasks
                        </x-ui.tabs.tab>
                        <div class="ml-auto">
                            <a href="{{ route('user.tax-calendar.index') }}" class="inline-block p-4 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors duration-200">
                                View All Tasks
                                <span aria-hidden="true">&rarr;</span>
                            </a>
                        </div>
                    </x-ui.tabs.list>

                    @if($tasks->isEmpty())
                        <x-ui.card.base class="mt-4">
                            <x-ui.card.body class="p-6">
                                <p class="text-gray-500 dark:text-gray-400 text-center">No tasks found.</p>
                            </x-ui.card.body>
                        </x-ui.card.base>
                    @else
                        <!-- Tab Content -->
                        <x-ui.tabs.panels>
                            <!-- Active Tasks Tab Panel -->
                            <x-ui.tabs.panel name="active">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @php $hasActiveTasks = false; @endphp
                                @foreach($tasks as $checkTask)
                                    @if($checkTask->status !== 'completed')
                                        @php $hasActiveTasks = true; break; @endphp
                                    @endif
                                @endforeach

                                @if(!$hasActiveTasks)
                                    <div class="col-span-1 md:col-span-2">
                                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                                            <p class="text-gray-500 dark:text-gray-400 text-center">No active tasks found.</p>
                                        </div>
                                    </div>
                                @endif
                                
                                @foreach($tasks as $task)
                                    @if($task->status !== 'completed')
                                @php
                                    // Define status colors with more distinct visual cues
                                    $statusColors = match($task->status ?? 'pending') {
                                        'completed' => [
                                            'card' => 'border-green-500 bg-green-50 dark:bg-green-900/10',
                                            'badge' => 'bg-green-100 text-green-800 ring-1 ring-green-600/20 dark:bg-green-900/10 dark:text-green-400 dark:ring-green-500/20',
                                            'icon' => 'text-green-500',
                                            'progress' => 'bg-green-500 dark:bg-green-500'
                                        ],
                                        'in_progress' => [
                                            'card' => 'border-blue-500 bg-blue-50 dark:bg-blue-900/10',
                                            'badge' => 'bg-blue-100 text-blue-800 ring-1 ring-blue-600/20 dark:bg-blue-900/10 dark:text-blue-400 dark:ring-blue-500/20',
                                            'icon' => 'text-blue-500',
                                            'progress' => 'bg-blue-500 dark:bg-blue-500'
                                        ],
                                        'under_review' => [
                                            'card' => 'border-blue-500 bg-blue-50 dark:bg-blue-900/10',
                                            'badge' => 'bg-blue-100 text-blue-800 ring-1 ring-blue-600/20 dark:bg-blue-900/10 dark:text-blue-400 dark:ring-blue-500/20',
                                            'icon' => 'text-blue-500',
                                            'progress' => 'bg-blue-500 dark:bg-blue-500'
                                        ],
                                        'changes_requested' => [
                                            'card' => 'border-orange-500 bg-orange-50 dark:bg-orange-900/10',
                                            'badge' => 'bg-orange-100 text-orange-800 ring-1 ring-orange-600/20 dark:bg-orange-900/10 dark:text-orange-400 dark:ring-orange-500/20',
                                            'icon' => 'text-orange-500',
                                            'progress' => 'bg-orange-500 dark:bg-orange-500'
                                        ],
                                        'rejected' => [
                                            'card' => 'border-red-500 bg-red-50 dark:bg-red-900/10',
                                            'badge' => 'bg-red-100 text-red-800 ring-1 ring-red-600/20 dark:bg-red-900/10 dark:text-red-400 dark:ring-red-500/20',
                                            'icon' => 'text-red-500',
                                            'progress' => 'bg-red-500 dark:bg-red-500'
                                        ],
                                        default => [
                                            'card' => 'border-yellow-500 bg-yellow-50 dark:bg-yellow-900/10',
                                            'badge' => 'bg-yellow-100 text-yellow-800 ring-1 ring-yellow-600/20 dark:bg-yellow-900/10 dark:text-yellow-400 dark:ring-yellow-500/20',
                                            'icon' => 'text-yellow-500',
                                            'progress' => 'bg-yellow-500 dark:bg-yellow-500'
                                        ]
                                    };
                                    
                                    $statusText = match($task->status ?? 'pending') {
                                        'completed' => 'Completed',
                                        'pending' => 'Pending',
                                        'in_progress' => 'In Progress',
                                        'under_review' => 'Waiting for Review',
                                        'changes_requested' => 'Changes Requested',
                                        'rejected' => 'Rejected',
                                        default => 'Unknown'
                                    };
                                    
                                    // Calculate days until due
                                    $daysUntil = now()->startOfDay()->diffInDays($task->due_date->startOfDay(), false);
                                    
                                    // Improved due date text with better formatting
                                    if ($task->status === 'completed') {
                                        $dueText = 'Completed';
                                        $dueClass = 'text-green-600 dark:text-green-400';
                                    } elseif ($daysUntil < 0) {
                                        $dueText = abs($daysUntil) . ' ' . (abs($daysUntil) === 1 ? 'day' : 'days') . ' overdue';
                                        $dueClass = 'text-red-600 dark:text-red-400 font-medium';
                                    } elseif ($daysUntil === 0) {
                                        $dueText = 'Due today';
                                        $dueClass = 'text-orange-600 dark:text-orange-400 font-medium';
                                    } elseif ($daysUntil <= 3) {
                                        $dueText = 'Due in ' . $daysUntil . ' ' . ($daysUntil === 1 ? 'day' : 'days') . ' (Urgent!)';
                                        $dueClass = 'text-orange-600 dark:text-orange-400';
                                    } else {
                                        $dueText = 'Due in ' . $daysUntil . ' days';
                                        $dueClass = 'text-gray-600 dark:text-gray-400';
                                    }
                                    
                                    // Calculate progress percentage safely
                                    // Override progress to 100% if status is completed
                                    $completedTasks = isset($task->user_checklist) ? collect($task->user_checklist)->where('completed', true)->count() : 0;
                                    $totalTasks = isset($task->user_checklist) ? count($task->user_checklist) : 0;
                                    $progress = $task->status === 'completed' ? 100 : ($totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0);
                                    $progressWidth = max(0, min(100, $progress)); // Ensure progress is between 0-100%
                                @endphp
                                
                                <x-ui.card.base class="hover:shadow-lg transition-all duration-300 border-l-4 {{ $statusColors['card'] }} {{ ($daysUntil < 0 && $task->status !== 'completed') ? 'border border-red-500 dark:border-red-700 bg-red-50 dark:bg-red-900/10' : '' }}">
                                    <x-ui.card.body class="p-6">
                                        <!-- Header with Status Badge -->
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                                    {{ $task->taxCalendar->name }}
                                                    @if($daysUntil < 0 && $task->status !== 'completed')
                                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 animate-pulse">
                                                            OVERDUE
                                                        </span>
                                                    @endif
                                                </h3>
                                                <div class="flex flex-wrap items-center gap-2">
                                                    @if($task->taxCalendar->form_code)
                                                        <span class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-700 rounded-md dark:bg-gray-700 dark:text-gray-300">
                                                            {{ $task->taxCalendar->form_code }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors['badge'] }}">
                                                {{ $statusText }}
                                            </span>
                                        </div>
                                        
                                        <!-- Due Date and Progress -->
                                        <div class="mb-4">
                                            <div class="flex items-center mb-2">
                                                <svg class="w-5 h-5 {{ $statusColors['icon'] }} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span class="text-sm {{ $dueClass }}">{{ $dueText }}</span>
                                            </div>
                                            
                                            <!-- Payment Date (if applicable) -->
                                            @if($task->taxCalendar->requires_payment)
                                                @php
                                                    $paymentDate = $task->due_date->copy()->addDays($task->taxCalendar->payment_due_day - $task->taxCalendar->due_day);
                                                    $paymentDaysUntil = now()->startOfDay()->diffInDays($paymentDate->startOfDay(), false);
                                                    
                                                    if ($task->status === 'completed') {
                                                        $paymentClass = 'text-green-600 dark:text-green-400';
                                                        $paymentText = 'Payment completed';
                                                    } elseif ($paymentDaysUntil < 0) {
                                                        $paymentClass = 'text-red-600 dark:text-red-400 font-medium';
                                                        $paymentText = 'Payment ' . abs($paymentDaysUntil) . ' days overdue';
                                                    } elseif ($paymentDaysUntil === 0) {
                                                        $paymentClass = 'text-orange-600 dark:text-orange-400 font-medium';
                                                        $paymentText = 'Payment due today';
                                                    } elseif ($paymentDaysUntil <= 3) {
                                                        $paymentClass = 'text-orange-600 dark:text-orange-400';
                                                        $paymentText = 'Payment due in ' . $paymentDaysUntil . ' days';
                                                    } else {
                                                        $paymentClass = 'text-gray-600 dark:text-gray-400';
                                                        $paymentText = 'Payment due on ' . $paymentDate->format('M d, Y');
                                                    }
                                                @endphp
                                                <div class="flex items-center mt-1">
                                                    <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="text-sm {{ $paymentClass }}">{{ $paymentText }}</span>
                                                </div>
                                            @endif
                                            
                                            <!-- Progress Bar -->
                                            <div class="mt-3">
                                                <div class="flex justify-between items-center mb-1 text-xs">
                                                    <span class="text-gray-500 dark:text-gray-400">Progress</span>
                                                    <span class="text-gray-700 dark:text-gray-300">{{ $progress }}%</span>
                                                </div>
                                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                    <div class="{{ $statusColors['progress'] }} h-2 rounded-full" style="width: {{ $progressWidth }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Task Description (if available) -->
                                        @if($task->taxCalendar->description)
                                            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                                                <p class="line-clamp-2">{{ $task->taxCalendar->description }}</p>
                                            </div>
                                        @endif
                                        
                                        <!-- Action Buttons -->
                                        <div class="mt-4 flex justify-between items-center">
                                            @if($task->taxCalendar->emta_link)
                                                <a href="{{ $task->taxCalendar->emta_link }}" target="_blank" rel="noopener noreferrer" 
                                                   class="inline-flex items-center text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors duration-200">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    EMTA Guide
                                                </a>
                                            @else
                                                <div></div> <!-- Empty div to maintain flex layout -->
                                            @endif

                                            <x-ui.button.primary href="{{ route('user.tax-calendar.show', $task->id) }}">
                                                View Details
                                                <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </x-ui.button.primary>
                                        </div>
                                    </x-ui.card.body>
                                </x-ui.card.base>
                                    @endif
                                @endforeach
                            </div>
                        </x-ui.tabs.panel>
                        
                        <!-- Completed Tasks Tab Panel -->
                        <x-ui.tabs.panel name="completed">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @php $hasCompletedTasks = false; @endphp
                                @foreach($tasks as $checkTask)
                                    @if($checkTask->status === 'completed')
                                        @php $hasCompletedTasks = true; break; @endphp
                                    @endif
                                @endforeach

                                @if(!$hasCompletedTasks)
                                    <div class="col-span-1 md:col-span-2">
                                        <x-ui.card.base>
                                            <x-ui.card.body class="p-6">
                                                <p class="text-gray-500 dark:text-gray-400 text-center">No completed tasks found.</p>
                                            </x-ui.card.body>
                                        </x-ui.card.base>
                                    </div>
                                @endif
                                
                                @foreach($tasks as $task)
                                    @if($task->status === 'completed')
                                @php
                                    // Define status colors with more distinct visual cues
                                    $statusColors = match($task->status ?? 'pending') {
                                        'completed' => [
                                            'card' => 'border-green-500 bg-green-50 dark:bg-green-900/10',
                                            'badge' => 'bg-green-100 text-green-800 ring-1 ring-green-600/20 dark:bg-green-900/10 dark:text-green-400 dark:ring-green-500/20',
                                            'icon' => 'text-green-500',
                                            'progress' => 'bg-green-500 dark:bg-green-500'
                                        ],
                                        default => [
                                            'card' => 'border-gray-300 dark:border-gray-700',
                                            'badge' => 'bg-gray-100 text-gray-800 ring-1 ring-gray-600/20 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-500/20',
                                            'icon' => 'text-gray-500',
                                            'progress' => 'bg-gray-500 dark:bg-gray-600'
                                        ]
                                    };
                                    
                                    // Calculate days until due date
                                    $daysUntil = now()->startOfDay()->diffInDays($task->due_date->startOfDay(), false);
                                    
                                    // Set due date text and class
                                    if ($task->status === 'completed') {
                                        $dueClass = 'text-green-600 dark:text-green-400';
                                        $dueText = 'Completed on ' . $task->updated_at->format('M d, Y');
                                    } else {
                                        $dueClass = 'text-gray-600 dark:text-gray-400';
                                        $dueText = 'Completed';
                                    }
                                    
                                    // Calculate progress percentage
                                    $progress = 100;
                                    $progressWidth = 100;
                                    
                                    // Set status text
                                    $statusText = 'Completed';
                                @endphp
                                
                                <div class="col-span-1">
                                    <x-ui.card.base class="hover:shadow-lg transition-all duration-300 border-l-4 {{ $statusColors['card'] }}">
                                        <x-ui.card.body class="p-6">
                                            <!-- Header with Status Badge -->
                                            <div class="flex justify-between items-start mb-4">
                                                <div>
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                                        {{ $task->taxCalendar->name }}
                                                    </h3>
                                                    <div class="flex flex-wrap items-center gap-2">
                                                        @if($task->taxCalendar->form_code)
                                                            <span class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-700 rounded-md dark:bg-gray-700 dark:text-gray-300">
                                                                {{ $task->taxCalendar->form_code }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors['badge'] }}">
                                                    {{ $statusText }}
                                                </span>
                                            </div>
                                            
                                            <!-- Due Date and Progress -->
                                            <div class="mb-4">
                                                <div class="flex items-center mb-2">
                                                    <svg class="w-5 h-5 {{ $statusColors['icon'] }} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span class="text-sm {{ $dueClass }}">{{ $dueText }}</span>
                                                </div>
                                                
                                                <!-- Payment Date (if applicable) -->
                                                @if($task->taxCalendar->requires_payment)
                                                    @php
                                                        // For completed tasks, we just need to know if payment was required,
                                                        // We might not need complex date calculation here, just show completed or relevant info.
                                                        // Let's keep the calculation for context but simplify text
                                                        $paymentDate = $task->due_date->copy()->addDays($task->taxCalendar->payment_due_day - $task->taxCalendar->due_day);
                                                        $paymentClass = 'text-green-600 dark:text-green-400'; // Assuming payment is done if task is completed
                                                        $paymentText = 'Payment Completed'; 
                                                    @endphp
                                                    <div class="flex items-center mt-1">
                                                        <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span class="text-sm {{ $paymentClass }}">{{ $paymentText }}</span>
                                                    </div>
                                                @endif
                                                
                                                <!-- Progress Bar -->
                                                <div class="mt-3">
                                                    <div class="flex justify-between items-center mb-1 text-xs">
                                                        <span class="text-gray-500 dark:text-gray-400">Progress</span>
                                                        <span class="text-gray-700 dark:text-gray-300">{{ $progress }}%</span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                        <div class="{{ $statusColors['progress'] }} h-2 rounded-full" style="width: {{ $progressWidth }}%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Task Description (if available) -->
                                            @if($task->taxCalendar->description)
                                                <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                                                    <p class="line-clamp-2">{{ $task->taxCalendar->description }}</p>
                                                </div>
                                            @endif
                                            
                                            <!-- Action Buttons -->
                                            <div class="mt-4 flex justify-between items-center">
                                                @if($task->taxCalendar->emta_link)
                                                    <a href="{{ $task->taxCalendar->emta_link }}" target="_blank" rel="noopener noreferrer" 
                                                       class="inline-flex items-center text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors duration-200">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        EMTA Guide
                                                    </a>
                                                @else
                                                    <div></div> <!-- Empty div to maintain flex layout -->
                                                @endif

                                                <x-ui.button.primary href="{{ route('user.tax-calendar.show', $task->id) }}">
                                                    View Details
                                                    <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </x-ui.button.primary>
                                            </div>
                                        </x-ui.card.body>
                                    </x-ui.card.base>
                                </div>
                                    @endif
                                @endforeach
                            </div>
                        </x-ui.tabs.panel>
                    </x-ui.tabs.panels>
                </div>
                @endif
                
            </div>

            <!-- Recent Activity -->
            <x-ui.card.base>
                <x-ui.card.header>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Recent Activity</h3>
                </x-ui.card.header>
                <x-ui.card.body>
                    @if($recentFiles->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($recentFiles as $file)
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $file->name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $file->folder->name }}</p>
                                    </div>
                                    <div class="flex-shrink-0 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $file->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">No recent activity</p>
                    @endif
                </x-ui.card.body>
            </x-ui.card.base>
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
</x-user.layout>
