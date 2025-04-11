<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
            Tax Calendar
            <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">Next {{ $daysToShow }} days</span>
        </h2>

        @if($deadlines->isEmpty() && $hiddenDeadlines->isEmpty())
            <p class="text-gray-500 dark:text-gray-400">No upcoming tax deadlines.</p>
        @else
            <div class="space-y-4">
                @foreach($deadlines as $deadline)
                    @php
                        $borderColor = match($deadline['urgency']) {
                            'past' => 'border-gray-300',
                            'urgent' => 'border-red-500',
                            'warning' => 'border-yellow-500',
                            'normal' => 'border-green-500',
                            default => 'border-gray-300'
                        };
                        
                        $bgColor = match($deadline['urgency']) {
                            'past' => 'bg-gray-50 dark:bg-gray-700',
                            'urgent' => 'bg-red-50 dark:bg-red-900/20',
                            'warning' => 'bg-yellow-50 dark:bg-yellow-900/20',
                            'normal' => 'bg-green-50 dark:bg-green-900/20',
                            default => 'bg-gray-50 dark:bg-gray-700'
                        };
                        
                        $statusColor = match($deadline['urgency']) {
                            'past' => 'bg-gray-100 text-gray-600 dark:bg-gray-600 dark:text-gray-300',
                            'urgent' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                            'warning' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300',
                            'normal' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
                            default => 'bg-gray-100 text-gray-600 dark:bg-gray-600 dark:text-gray-300'
                        };
                    @endphp
                    
                    <div class="border-l-4 {{ $borderColor }} {{ $bgColor }} p-4 rounded-r-lg">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $deadline['name'] }}
                                    </h3>
                                    @if($deadline['form_code'])
                                        <span class="px-2 py-0.5 text-xs bg-gray-200 dark:bg-gray-600 rounded">
                                            {{ $deadline['form_code'] }}
                                        </span>
                                    @endif
                                    <span class="px-2 py-0.5 text-xs rounded {{ $statusColor }}">
                                        {{ $deadline['status'] }}
                                        @if ($deadline['next_deadline'])
                                            ({{ $deadline['next_deadline']->format('M j, Y') }})
                                        @endif
                                    </span>
                                </div>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $deadline['description'] }}
                                </p>
                                @if($deadline['emta_link'])
                                    <a href="{{ $deadline['emta_link'] }}" target="_blank" rel="noopener noreferrer" 
                                       class="inline-flex items-center mt-2 text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                        <span>EMTA Guide</span>
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                            <div class="text-right ml-4">
                                <div class="text-sm font-medium">
                                    {{ $deadline['next_deadline']->format('M j') }}
                                </div>
                                @if($deadline['next_payment'])
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Payment: {{ $deadline['next_payment']->format('M j') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                @if($hiddenDeadlines->isNotEmpty())
                    <div class="mt-4 text-center">
                        <a href="{{ route('user.dashboard', ['show_all_deadlines' => 1]) }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                            Show {{ $hiddenDeadlines->count() }} more deadlines
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>