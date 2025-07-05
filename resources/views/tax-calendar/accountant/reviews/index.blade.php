<x-accountant.layout 
    title="Reviews"
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('accountant.dashboard'), 'first' => true],
        ['title' => __('Reviews')]
    ]"
>
    <div class="space-y-6">
        {{-- Stats Overview --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Pending') }}</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $statusCounts['pending'] + $statusCounts['under_review'] }}</div>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
            
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('In Progress') }}</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $statusCounts['in_progress'] }}</div>
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
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Completed') }}</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $statusCounts['completed'] }}</div>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
            
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Rejected') }}</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $statusCounts['rejected'] }}</div>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        </div>

        {{-- Status Filter Tabs --}}
        <div class="border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
            <nav class="-mb-px flex" aria-label="Tabs">
                <a href="{{ route('accountant.tax-calendar.reviews') }}"
                   class="inline-flex items-center py-2 px-3 sm:px-4 text-xs sm:text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ !request('status') && !request('archived') ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ __('Pending') }}
                    @if($pendingCount ?? 0 > 0)
                        <x-ui.badge variant="warning" size="sm" class="ml-1.5">{{ $pendingCount }}</x-ui.badge>
                    @endif
                </a>
                
                <a href="{{ route('accountant.tax-calendar.reviews', ['status' => 'changes_requested']) }}"
                   class="inline-flex items-center py-2 px-3 sm:px-4 text-xs sm:text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ request('status') === 'changes_requested' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span class="hidden sm:inline">{{ __('Changes Requested') }}</span>
                    <span class="sm:hidden">{{ __('Changes') }}</span>
                    @if($statusCounts['changes_requested'] > 0)
                        <x-ui.badge variant="warning" size="sm" class="ml-1.5">{{ $statusCounts['changes_requested'] }}</x-ui.badge>
                    @endif
                </a>
                
                <a href="{{ route('accountant.tax-calendar.reviews', ['status' => 'in_progress']) }}"
                   class="inline-flex items-center py-2 px-3 sm:px-4 text-xs sm:text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ request('status') === 'in_progress' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span class="hidden sm:inline">{{ __('In Progress') }}</span>
                    <span class="sm:hidden">{{ __('Progress') }}</span>
                    @if($statusCounts['in_progress'] > 0)
                        <x-ui.badge variant="secondary" size="sm" class="ml-1.5">{{ $statusCounts['in_progress'] }}</x-ui.badge>
                    @endif
                </a>
                
                <a href="{{ route('accountant.tax-calendar.reviews', ['status' => 'completed']) }}"
                   class="inline-flex items-center py-2 px-3 sm:px-4 text-xs sm:text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ request('status') === 'completed' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ __('Completed') }}
                    @if($statusCounts['completed'] > 0)
                        <x-ui.badge variant="success" size="sm" class="ml-1.5">{{ $statusCounts['completed'] }}</x-ui.badge>
                    @endif
                </a>
                
                <a href="{{ route('accountant.tax-calendar.reviews', ['status' => 'rejected']) }}"
                   class="inline-flex items-center py-2 px-3 sm:px-4 text-xs sm:text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ request('status') === 'rejected' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ __('Rejected') }}
                    @if($statusCounts['rejected'] > 0)
                        <x-ui.badge variant="danger" size="sm" class="ml-1.5">{{ $statusCounts['rejected'] }}</x-ui.badge>
                    @endif
                </a>
                
                <a href="{{ route('accountant.tax-calendar.reviews', ['archived' => true]) }}"
                   class="ml-auto inline-flex items-center py-2 px-3 sm:px-4 text-xs sm:text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ request('archived') ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                    {{ __('Archived') }}
                </a>
            </nav>
        </div>

        {{-- Tasks Table --}}
        @if($tasks->isEmpty())
            <x-ui.card.base>
                <x-ui.card.body>
                    <x-ui.table.empty-state>
                        <x-slot name="icon">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </x-slot>
                        <x-slot name="title">{{ __('No tasks found') }}</x-slot>
                        <x-slot name="description">
                            @if(request('archived'))
                                {{ __('There are no archived tasks at the moment. Completed and rejected tasks will appear here.') }}
                            @else
                                {{ __('There are no tasks requiring your attention at the moment. New submissions will appear here.') }}
                            @endif
                        </x-slot>
                    </x-ui.table.empty-state>
                </x-ui.card.body>
            </x-ui.card.base>
        @else
            <x-ui.card.base>
                <x-ui.card.header>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Tax Calendar Tasks') }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Review and manage submitted tax calendar tasks') }}</p>
                </x-ui.card.header>
                <x-ui.card.body>
                    <x-ui.table.base>
                        <x-slot name="head">
                            <x-ui.table.head-cell>{{ __('Task & Company') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Status') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Submitted') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Due Date') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Reviewed') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell align="right">{{ __('Actions') }}</x-ui.table.head-cell>
                        </x-slot>
                        <x-slot name="body">
                            @foreach($tasks as $task)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <x-ui.table.cell>
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-gray-50 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                                @if($task->company->logo_url)
                                                    <img class="h-10 w-10 rounded-lg object-cover" src="{{ $task->company->logo_url }}" alt="{{ $task->company->name }}">
                                                @else
                                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $task->taxCalendar->name }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $task->company->name }}
                                                </div>
                                                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    {{ $task->company->users->first()->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="flex flex-col items-start gap-1">
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
                                                <span class="inline-flex items-center text-xs font-medium text-red-600 dark:text-red-400">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ __('Overdue') }}
                                                </span>
                                            @endif
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $task->submitted_at?->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $task->submitted_at?->format('H:i') }}
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $task->due_date?->format('M d, Y') }}
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $task->reviewed_at?->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $task->reviewed_at?->format('H:i') }}
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.action-cell>
                                        <a href="{{ route('accountant.tax-calendar.reviews.show', $task->id) }}"
                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                           title="{{ __('Review task') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                            </svg>
                                        </a>
                                    </x-ui.table.action-cell>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-ui.table.base>
                </x-ui.card.body>
            </x-ui.card.base>

            {{-- Pagination --}}
            @if($tasks->hasPages())
                <div class="mt-6">
                    {{ $tasks->links() }}
                </div>
            @endif
        @endif
    </div>
</x-accountant.layout>