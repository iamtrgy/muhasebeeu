@props(['daysToShow' => 30])

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
    <div class="p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Tax Calendar
                <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">Next {{ $daysToShow }} days</span>
            </h2>
        </div>

        @if($deadlines->isEmpty() && $hiddenDeadlines->isEmpty())
            <p class="text-gray-500 dark:text-gray-400">No upcoming tax deadlines.</p>
        @else
            <div class="space-y-4">
                @foreach($deadlines as $deadline)
                    @php
                        $urgencyClass = match($deadline['urgency']) {
                            'past' => 'border-l-4 border-gray-300',
                            'urgent' => 'border-l-4 border-red-500 bg-red-50',
                            'warning' => 'border-l-4 border-yellow-500 bg-yellow-50',
                            'normal' => 'border-l-4 border-green-500 bg-green-50',
                            default => 'border-l-4 border-gray-300'
                        };

                        $dueText = match($deadline['urgency']) {
                            'past' => 'Past due',
                            'urgent' => 'Due in ' . $deadline['days_until'] . ' days (Urgent!)',
                            'warning' => 'Due in ' . $deadline['days_until'] . ' days',
                            'normal' => 'Due in ' . $deadline['days_until'] . ' days',
                            default => 'Due date unknown'
                        };
                    @endphp
                    
                    <div class="{{ $urgencyClass }} rounded-lg">
                        <div class="p-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $deadline['name'] }}
                                        </h3>
                                        @if($deadline['form_code'])
                                            <span class="px-2 py-0.5 text-xs bg-gray-100 dark:bg-gray-700 rounded">
                                                {{ $deadline['form_code'] }}
                                            </span>
                                        @endif
                                        <!-- Add Status Badge -->
                                        @php
                                            $statusClass = match($deadline['status'] ?? 'pending') {
                                                'completed' => 'bg-green-100 text-green-800',
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'overdue' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                            $statusText = match($deadline['status'] ?? 'pending') {
                                                'completed' => 'Completed',
                                                'pending' => 'In Progress',
                                                'overdue' => 'Overdue',
                                                default => 'Unknown'
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 text-xs {{ $statusClass }} rounded-full">
                                            {{ $statusText }}
                                        </span>
                                        <span class="text-sm text-gray-600">{{ $dueText }} ({{ $deadline['next_deadline']->format('M j, Y') }})</span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $deadline['description'] }}
                                    </p>
                                    @if($deadline['emta_link'])
                                        <a href="{{ $deadline['emta_link'] }}" target="_blank" rel="noopener noreferrer" 
                                           class="inline-flex items-center mt-2 text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                            EMTA Guide
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $deadline['next_deadline']->format('M j') }}
                                    </div>
                                    @if($deadline['next_payment'])
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Payment: {{ $deadline['next_payment']->format('M j') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            @if(isset($deadline['progress']))
                                <div class="mt-4">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" 
                                             style="width: {{ $deadline['progress'] }}%"
                                             title="{{ $deadline['progress'] }}% complete"></div>
                                    </div>
                                    <div class="mt-1 flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                        <span>{{ $deadline['completed_tasks'] ?? 0 }}/{{ $deadline['total_tasks'] ?? 0 }} tasks completed</span>
                                        <span>{{ number_format($deadline['progress'], 1) }}%</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if($hiddenDeadlines->isNotEmpty())
                <div class="mt-4 text-center">
                    <a href="?show_all_deadlines=1" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        Show all deadlines
                    </a>
                </div>
            @endif
        @endif
    </div>
</div>