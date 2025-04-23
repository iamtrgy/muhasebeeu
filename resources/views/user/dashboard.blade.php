<x-app-layout>
    <x-unified-header />
    
    <!-- Include the file preview modal component -->
    <x-folder.file-preview-modal />

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Task Overview -->
            <div class="mb-6">
                <!-- Deadline Summary Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Due Today</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $tasks->where('due_date', now()->startOfDay())->count() }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Upcoming (7 days)</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $tasks->filter(function($task) { 
                            $daysUntil = now()->startOfDay()->diffInDays($task->due_date->startOfDay(), false);
                            return $daysUntil > 0 && $daysUntil <= 7;
                        })->count() }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Overdue</p>
                        <p class="mt-1 text-2xl font-semibold text-red-600 dark:text-red-400">{{ $tasks->filter(function($task) { 
                            return now()->startOfDay()->gt($task->due_date->startOfDay()) && $task->status !== 'completed';
                        })->count() }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed</p>
                        <p class="mt-1 text-2xl font-semibold text-green-600 dark:text-green-400">{{ $completedTasksCount ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Your Tasks -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Your Tasks</h2>
                    <a href="{{ route('user.tax-calendar.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        View All Tasks
                        <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                
                @if($tasks->isEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <p class="text-gray-500 dark:text-gray-400 text-center">No tasks found.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @php
                            // Sort tasks to show overdue items first
                            $sortedTasks = $tasks->sortBy(function($task) {
                                // Overdue and not completed tasks get priority -1 (highest)
                                if (now()->startOfDay()->gt($task->due_date->startOfDay()) && $task->status !== 'completed') {
                                    return -1;
                                }
                                // Then sort by due_date (ascending)
                                return $task->due_date->timestamp;
                            });
                        @endphp
                        @foreach($sortedTasks as $task)
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
                            
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 border-l-4 {{ $statusColors['card'] }} {{ ($daysUntil < 0 && $task->status !== 'completed') ? 'border border-red-500 dark:border-red-700 bg-red-50 dark:bg-red-900/10' : '' }}">
                                <div class="p-5">
                                    <!-- Header with Status Badge -->
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                                {{ $task->taxCalendar->name }}
                                                @if($daysUntil < 0 && $task->status !== 'completed')
                                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 animate-pulse">
                                                        Overdue
                                                    </span>
                                                @endif
                                            </h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $task->taxCalendar->form_code }}</p>
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

                                        <a href="{{ route('user.tax-calendar.show', $task->id) }}" 
                                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            View Details
                                            <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Recent Activity -->
            <x-cards.activity-card title="Recent Activity">
                @foreach($recentFiles as $file)
                    <x-activity.activity-item
                        title="{{ $file->name }}"
                        subtitle="{{ $file->folder->name }}"
                        timestamp="{{ $file->created_at->diffForHumans() }}"
                    >
                        <x-slot name="icon">
                            <svg class="h-6 w-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </x-slot>
                    </x-activity.activity-item>
                @endforeach
            </x-cards.activity-card>
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
