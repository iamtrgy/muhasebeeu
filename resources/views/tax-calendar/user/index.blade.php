<x-user.layout 
    title="{{ __('Tax Calendar') }}" 
    :breadcrumbs="[
        ['title' => __('Home'), 'href' => route('user.dashboard'), 'first' => true],
        ['title' => __('Tax Calendar')]
    ]"
>
    <div class="space-y-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-5 flex-1">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('Due Today') }}
                            </div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                                {{ $deadlines->where('days_until', 0)->count() }}
                            </div>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-amber-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 flex-1">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('Upcoming (7 days)') }}
                            </div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                                {{ $deadlines->where('days_until', '>', 0)->where('days_until', '<=', 7)->count() }}
                            </div>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-5 flex-1">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('Overdue') }}
                            </div>
                            <div class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">
                                {{ $deadlines->where('days_until', '<', 0)->where('status', '!=', 'completed')->count() }}
                            </div>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-emerald-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 flex-1">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('Completed') }}
                            </div>
                            <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">
                                {{ $deadlines->where('status', 'completed')->count() }}
                            </div>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        </div>
        
        <!-- Debug Info -->
        @if($deadlines->where('days_until', '<', 0)->where('status', '!=', 'completed')->count() > 0)
            <x-ui.alert variant="warning">
                <strong>{{ __('Overdue Task Details:') }}</strong>
                <ul class="mt-2 list-disc list-inside">
                @foreach($deadlines->where('days_until', '<', 0)->where('status', '!=', 'completed') as $overdue)
                    <li>
                        <strong>{{ $overdue['name'] }}</strong> - 
                        Days until: {{ $overdue['days_until'] }}, 
                        Status: {{ $overdue['status'] }}
                    </li>
                @endforeach
                </ul>
            </x-ui.alert>
        @endif

        <!-- Tax Calendar Tasks -->
        <x-ui.card.base>
            <x-ui.card.header>
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Tax Deadlines') }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Track your tax obligations and deadlines') }}</p>
                    </div>
                </div>
            </x-ui.card.header>
            <x-ui.card.body>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @php
                            // Sort deadlines to show overdue items first
                            $sortedDeadlines = $deadlines->sortBy(function($deadline) {
                                // Overdue and not completed tasks get priority -1 (highest)
                                if ($deadline['days_until'] < 0 && $deadline['status'] !== 'completed') {
                                    return -1;
                                }
                                // Then sort by days_until (ascending)
                                return $deadline['days_until'];
                            });
                        @endphp
                        @foreach($sortedDeadlines as $deadline)
                            @php
                                // Define status colors with more distinct visual cues
                                $statusColors = match($deadline['status'] ?? 'pending') {
                                    'completed' => [
                                        'card' => 'border-green-500 bg-green-50 dark:bg-green-900/10',
                                        'badge' => 'bg-green-100 text-green-800 ring-1 ring-green-600/20 dark:bg-green-900/10 dark:text-green-400 dark:ring-green-500/20',
                                        'icon' => 'text-green-500',
                                        'progress' => 'bg-green-500 dark:bg-green-500'
                                    ],
                                    'overdue' => [
                                        'card' => 'border-red-500 bg-red-50 dark:bg-red-900/10',
                                        'badge' => 'bg-red-100 text-red-800 ring-1 ring-red-600/20 dark:bg-red-900/10 dark:text-red-400 dark:ring-red-500/20',
                                        'icon' => 'text-red-500',
                                        'progress' => 'bg-red-500 dark:bg-red-500'
                                    ],
                                    'pending' => [
                                        'card' => 'border-yellow-500 bg-yellow-50 dark:bg-yellow-900/10',
                                        'badge' => 'bg-yellow-100 text-yellow-800 ring-1 ring-yellow-600/20 dark:bg-yellow-900/10 dark:text-yellow-400 dark:ring-yellow-500/20',
                                        'icon' => 'text-yellow-500',
                                        'progress' => 'bg-yellow-500 dark:bg-yellow-500'
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
                                        'card' => 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/10',
                                        'badge' => 'bg-indigo-100 text-indigo-800 ring-1 ring-indigo-600/20 dark:bg-indigo-900/10 dark:text-indigo-400 dark:ring-indigo-500/20',
                                        'icon' => 'text-indigo-500',
                                        'progress' => 'bg-indigo-500 dark:bg-indigo-500'
                                    ]
                                };

                                $statusText = match($deadline['status'] ?? 'pending') {
                                    'approved' => 'Approved',
                                    'completed' => 'Completed',
                                    'pending' => 'In Progress',
                                    'overdue' => 'Overdue',
                                    'under_review' => 'Waiting for Review',
                                    'changes_requested' => 'Changes Requested',
                                    'rejected' => 'Rejected',
                                    default => 'Unknown'
                                };

                                // Improved due date text with better formatting
                                if ($deadline['status'] === 'completed') {
                                    $dueText = 'Completed';
                                    $dueClass = 'text-green-600 dark:text-green-400';
                                } elseif ($deadline['days_until'] < 0) {
                                    $dueText = abs($deadline['days_until']) . ' ' . (abs($deadline['days_until']) === 1 ? 'day' : 'days') . ' overdue';
                                    $dueClass = 'text-red-600 dark:text-red-400 font-medium';
                                } elseif ($deadline['days_until'] === 0) {
                                    $dueText = 'Due today';
                                    $dueClass = 'text-orange-600 dark:text-orange-400 font-medium';
                                } elseif ($deadline['days_until'] <= 3) {
                                    $dueText = 'Due in ' . $deadline['days_until'] . ' ' . ($deadline['days_until'] === 1 ? 'day' : 'days') . ' (Urgent!)';
                                    $dueClass = 'text-orange-600 dark:text-orange-400';
                                } else {
                                    $dueText = 'Due in ' . $deadline['days_until'] . ' days';
                                    $dueClass = 'text-gray-600 dark:text-gray-400';
                                }

                                // Calculate progress percentage safely
                                // Override progress to 100% if status is completed
                                $progress = $deadline['status'] === 'completed' ? 100 : (isset($deadline['progress']) ? $deadline['progress'] : 0);
                                $progressWidth = max(0, min(100, $progress)); // Ensure progress is between 0-100%
                            @endphp
                            
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 border-l-4 {{ $statusColors['card'] }} {{ ($deadline['days_until'] < 0 && $deadline['status'] !== 'completed') ? 'border border-red-500 dark:border-red-700 bg-red-50 dark:bg-red-900/10' : '' }}">
                                <div class="p-5">
                                    <!-- Header with Status Badge -->
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                                {{ $deadline['name'] }}
                                                @if($deadline['days_until'] < 0 && $deadline['status'] !== 'completed')
                                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 animate-pulse">
                                                        OVERDUE
                                                    </span>
                                                @endif
                                            </h3>
                                            <div class="flex flex-wrap items-center gap-2">
                                                @if($deadline['form_code'])
                                                    <span class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-700 rounded-md dark:bg-gray-700 dark:text-gray-300">
                                                        {{ $deadline['form_code'] }}
                                                    </span>
                                                @endif
                                                <span class="px-2.5 py-1 text-xs font-medium {{ $statusColors['badge'] }} rounded-full">
                                                    {{ $statusText }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Due Date Information -->
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2 {{ $statusColors['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-sm font-medium {{ $dueClass }}">
                                                {{ $dueText }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $deadline['next_deadline']->format('F j, Y') }}
                                        </div>
                                        @if($deadline['next_payment'])
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Payment: {{ $deadline['next_payment']->format('M j, Y') }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Description -->
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        {{ Str::limit($deadline['description'], 120) }}
                                    </p>

                                    <!-- Improved Progress Bar -->
                                    @if(isset($deadline['progress']))
                                        <div class="mt-4 mb-4">
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                                    Progress
                                                </span>
                                                <span class="text-xs font-medium {{ $progress >= 100 ? 'text-green-600 dark:text-green-400' : 'text-indigo-600 dark:text-indigo-400' }}">
                                                    {{ number_format($progress, 0) }}%
                                                </span>
                                            </div>
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                                                <div class="h-2.5 rounded-full transition-all duration-500 ease-out {{ $statusColors['progress'] }}"
                                                     style="width: {{ $progressWidth }}%"
                                                     aria-valuenow="{{ $progressWidth }}"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                {{ $deadline['completed_tasks'] ?? 0 }}/{{ $deadline['total_tasks'] ?? 0 }} tasks completed
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Action Buttons -->
                                    <div class="mt-4 flex justify-between items-center">
                                        @if($deadline['emta_link'])
                                            <a href="{{ $deadline['emta_link'] }}" target="_blank" rel="noopener noreferrer" 
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

                                        <a href="{{ route('user.tax-calendar.show', $deadline['id']) }}" 
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
                
                @if(isset($deadlines) && method_exists($deadlines, 'links'))
                    <div class="mt-6">
                        {{ $deadlines->links() }}
                    </div>
                @endif
            </x-ui.card.body>
        </x-ui.card.base>
    </div>

    @push('scripts')
    <script>
        // Add null checks before adding event listeners
        const filterStatus = document.getElementById('filterStatus');
        if (filterStatus) {
            filterStatus.addEventListener('change', function() {
                window.location.href = updateQueryStringParameter(window.location.href, 'status', this.value);
            });
        }

        const filterMonth = document.getElementById('filterMonth');
        if (filterMonth) {
            filterMonth.addEventListener('change', function() {
                window.location.href = updateQueryStringParameter(window.location.href, 'month', this.value);
            });
        }

        function updateQueryStringParameter(uri, key, value) {
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            var separator = uri.indexOf('?') !== -1 ? "&" : "?";
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            }
            else {
                return uri + separator + key + "=" + value;
            }
        }
    </script>
    @endpush
</x-user.layout> 