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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Overdue') }}</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $tasks->filter(function($task) {
                                return !$task->is_completed && now()->startOfDay()->diffInDays($task->due_date->startOfDay(), false) < 0;
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Completed') }}</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $tasks->where('is_completed', true)->count() }}</div>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        </div>

        <!-- Tasks Section -->
        <x-ui.card.base>
            <x-ui.card.header>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Tax Calendar Tasks') }}
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Track and manage your tax filing requirements') }}
                        </p>
                    </div>
                    <a href="{{ route('user.tax-calendar.index') }}" class="inline-flex items-center text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors">
                        View All Tasks
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </x-ui.card.header>

            @if($tasks->isEmpty())
                <x-ui.card.body class="p-6">
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No tasks found.</p>
                    </div>
                </x-ui.card.body>
            @else
                <x-ui.card.body>
                    <x-ui.table.base>
                        <x-slot name="head">
                            <x-ui.table.head-cell>Task</x-ui.table.head-cell>
                            <x-ui.table.head-cell>Company</x-ui.table.head-cell>
                            <x-ui.table.head-cell>Due Date</x-ui.table.head-cell>
                            <x-ui.table.head-cell>Progress</x-ui.table.head-cell>
                            <x-ui.table.head-cell>Status</x-ui.table.head-cell>
                            <x-ui.table.head-cell class="text-right">Actions</x-ui.table.head-cell>
                        </x-slot>
                        <x-slot name="body">
                            @foreach($tasks as $task)
                                @php
                                    // Calculate progress and urgency for table display
                                    $completedTasks = $task->completed_items ?? 0;
                                    $totalTasks = $task->total_items ?? 0;
                                    $progress = $task->is_completed ? 100 : round($task->progress ?? 0);
                                    
                                    // Determine urgency styling
                                    $daysUntil = now()->startOfDay()->diffInDays($task->due_date->startOfDay(), false);
                                    
                                    if ($task->is_completed) {
                                        $urgencyClass = 'bg-green-50 dark:bg-green-900/10';
                                        $statusBadge = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
                                        $statusText = 'Completed';
                                        $progressBarColor = 'bg-green-500';
                                    } else {
                                        $urgencyClass = match($task->urgency_level ?? 'normal') {
                                            'overdue' => 'bg-red-50 dark:bg-red-900/10',
                                            'urgent' => 'bg-orange-50 dark:bg-orange-900/10', 
                                            'warning' => 'bg-yellow-50 dark:bg-yellow-900/10',
                                            default => ''
                                        };
                                        
                                        $statusBadge = match($task->urgency_level ?? 'normal') {
                                            'overdue' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                            'urgent' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400',
                                            'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                                        };
                                        
                                        $statusText = match($task->urgency_level ?? 'normal') {
                                            'overdue' => 'Overdue',
                                            'urgent' => 'Urgent',
                                            'warning' => 'Due Soon',
                                            default => 'Pending'
                                        };
                                        
                                        $progressBarColor = match($task->urgency_level ?? 'normal') {
                                            'overdue' => 'bg-red-500',
                                            'urgent' => 'bg-orange-500',
                                            'warning' => 'bg-yellow-500',
                                            default => 'bg-indigo-500'
                                        };
                                    }
                                @endphp
                                
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 {{ $urgencyClass }} transition-colors">
                                    <!-- Task Name -->
                                    <x-ui.table.cell>
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $task->taxCalendar->name }}
                                                </div>
                                                @if($task->taxCalendar->form_code)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $task->taxCalendar->form_code }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </x-ui.table.cell>
                                    
                                    <!-- Company -->
                                    <x-ui.table.cell>
                                        {{ $task->company->name }}
                                    </x-ui.table.cell>
                                    
                                    <!-- Due Date -->
                                    <x-ui.table.cell>
                                        <div class="text-gray-900 dark:text-gray-100">
                                            {{ $task->due_date->format('M j, Y') }}
                                        </div>
                                        @if(!$task->is_completed)
                                            @if($daysUntil < 0)
                                                <div class="text-xs text-red-600 dark:text-red-400">
                                                    {{ abs($daysUntil) }} {{ abs($daysUntil) === 1 ? 'day' : 'days' }} overdue
                                                </div>
                                            @elseif($daysUntil === 0)
                                                <div class="text-xs text-orange-600 dark:text-orange-400">
                                                    Due today
                                                </div>
                                            @elseif($daysUntil <= 3)
                                                <div class="text-xs text-orange-600 dark:text-orange-400">
                                                    {{ $daysUntil }} {{ $daysUntil === 1 ? 'day' : 'days' }} left
                                                </div>
                                            @endif
                                        @endif
                                    </x-ui.table.cell>
                                    
                                    <!-- Progress -->
                                    <x-ui.table.cell>
                                        <div class="flex items-center">
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                                                    <span>{{ $completedTasks }}/{{ $totalTasks }}</span>
                                                    <span>{{ $progress }}%</span>
                                                </div>
                                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                    <div class="h-2 rounded-full {{ $progressBarColor }}" style="width: {{ $progress }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </x-ui.table.cell>
                                    
                                    <!-- Status -->
                                    <x-ui.table.cell>
                                        <x-ui.badge class="{{ $statusBadge }}">
                                            {{ $statusText }}
                                        </x-ui.badge>
                                    </x-ui.table.cell>
                                    
                                    <!-- Actions -->
                                    <x-ui.table.action-cell>
                                        <div class="flex items-center justify-end gap-2">
                                            @if($task->taxCalendar->emta_link)
                                                <a href="{{ $task->taxCalendar->emta_link }}" target="_blank" rel="noopener noreferrer" 
                                                   class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                                   title="EMTA Guide">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                </a>
                                            @endif
                                            <a href="{{ route('user.tax-calendar.show', $task->id) }}" 
                                               class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                               title="View details">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </x-ui.table.action-cell>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-ui.table.base>
                </x-ui.card.body>
            @endif
        </x-ui.card.base>

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
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $file->folder->name ?? 'No folder' }} • {{ $file->created_at->diffForHumans() }}</p>
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
</x-user.layout>