<x-user.layout 
    title="{{ __('Tax Calendar') }}" 
    :breadcrumbs="[
        ['title' => __('Home'), 'href' => route('user.dashboard'), 'first' => true],
        ['title' => __('Tax Calendar')]
    ]"
>
    <div class="space-y-6">
        <!-- Stats Cards -->
        @php
            $pendingTasks = $tasks->where('is_completed', false);
            $completedTasks = $tasks->where('is_completed', true);
            $overdueTasks = $pendingTasks->where('due_date', '<', now());
            $upcomingTasks = $pendingTasks->where('due_date', '>=', now())->where('due_date', '<=', now()->addDays(7));
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-ui.card.base>
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Tasks') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $tasks->count() }}</p>
                        </div>
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-full">
                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <x-ui.card.base>
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Pending') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $pendingTasks->count() }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-full">
                            <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <x-ui.card.base>
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Overdue') }}</p>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $overdueTasks->count() }}</p>
                        </div>
                        <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-full">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <x-ui.card.base>
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Completed') }}</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $completedTasks->count() }}</p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        </div>

        <!-- Filters -->
        <div class="flex justify-end">
            <form method="GET" class="flex gap-3">
                <x-ui.form.select name="status" onchange="this.form.submit()">
                    <option value="" {{ request('status') === '' ? 'selected' : '' }}>{{ __('All Tasks') }}</option>
                    <option value="pending" {{ request('status', 'pending') === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                </x-ui.form.select>
                
                <x-ui.form.select name="year" onchange="this.form.submit()">
                    @for($year = now()->year + 1; $year >= now()->year - 2; $year--)
                        <option value="{{ $year }}" {{ request('year', now()->year) == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endfor
                    <option value="" {{ request('year') === '' ? 'selected' : '' }}>{{ __('All Years') }}</option>
                </x-ui.form.select>
                
                <x-ui.form.select name="month" onchange="this.form.submit()">
                    <option value="" {{ request('month') === '' ? 'selected' : '' }}>{{ __('All Months') }}</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('month', now()->month) == $i ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                        </option>
                    @endfor
                </x-ui.form.select>
            </form>
        </div>

        <!-- Tasks List -->
        <x-ui.card.base>
            <x-ui.card.header>
                <h2 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Tasks') }}</h2>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Track and manage your tax filing requirements') }}</p>
            </x-ui.card.header>
            <x-ui.card.body>
                @if($tasks->count() > 0)
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
                @else
                    <x-ui.table.empty-state>
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No tasks found') }}</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('There are no tax calendar tasks matching your filters.') }}</p>
                    </x-ui.table.empty-state>
                @endif
            </x-ui.card.body>
        </x-ui.card.base>
    </div>
</x-user.layout>