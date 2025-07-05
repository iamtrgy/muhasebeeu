<x-admin.layout title="Tax Calendar" :breadcrumbs="[
    ['title' => __('Dashboard'), 'href' => route('admin.dashboard'), 'first' => true],
    ['title' => __('Tax Calendar')]
]">
    <div class="space-y-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Total Tasks Card -->
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-5 flex-1">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('Total Tasks') }}
                            </div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                                {{ $tasks->total() }}
                            </div>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Pending Tasks Card -->
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
                                {{ __('Pending') }}
                            </div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                                {{ $tasks->where('status', 'pending')->count() }}
                            </div>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Completed Tasks Card -->
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
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                                {{ $tasks->where('status', 'completed')->count() }}
                            </div>
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
                        <div class="ml-5 flex-1">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('Overdue') }}
                            </div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                                {{ $tasks->filter(fn($task) => $task->is_overdue)->count() }}
                            </div>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        </div>

        <!-- Page Header with Actions -->
        <x-ui.card.base>
            <x-ui.card.body>
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Tax Calendar Tasks</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage and track tax-related deadlines and tasks</p>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Manage Templates Button -->
                        <x-ui.button.secondary size="sm" href="{{ route('admin.tax-calendar-templates.index') }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            {{ __('Manage Templates') }}
                        </x-ui.button.secondary>
                        
                        <!-- Create Task Button -->
                        <x-ui.button.primary size="sm" href="{{ route('admin.tax-calendar.create') }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            {{ __('Create Task') }}
                        </x-ui.button.primary>
                        
                        <!-- Status Filter -->
                        <div>
                            <x-ui.form.select id="filterStatus" class="w-40">
                                <option value="">{{ __('All Status') }}</option>
                                <option value="pending">{{ __('Pending') }}</option>
                                <option value="completed">{{ __('Completed') }}</option>
                            </x-ui.form.select>
                        </div>
                        
                        <!-- Month Filter -->
                        <div>
                            <x-ui.form.select id="filterMonth" class="w-40">
                                <option value="">{{ __('All Months') }}</option>
                                @foreach(range(1, 12) as $month)
                                    <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</option>
                                @endforeach
                            </x-ui.form.select>
                        </div>
                    </div>
                </div>
            </x-ui.card.body>
        </x-ui.card.base>

        <!-- Tasks Table -->
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Tax Calendar Tasks') }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('View and manage all tax-related tasks and deadlines') }}</p>
            </x-ui.card.header>
            <x-ui.card.body>
                <x-ui.table.base>
                    <x-slot name="head">
                        <x-ui.table.head-cell>{{ __('Task') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell>{{ __('Due Date') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell>{{ __('Status') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell>{{ __('Progress') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell class="text-right">{{ __('Actions') }}</x-ui.table.head-cell>
                    </x-slot>
                    
                    <x-slot name="body">
                        @forelse($tasks as $task)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <x-ui.table.cell>
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $task->taxCalendar->name }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $task->taxCalendar->description }}
                                </div>
                            </div>
                        </x-ui.table.cell>
                        
                        <x-ui.table.cell>
                            <div>
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $task->due_date->format('M d, Y') }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    @if($task->days_until_due > 0)
                                        {{ $task->days_until_due }} days left
                                    @elseif($task->days_until_due == 0)
                                        Due today
                                    @else
                                        {{ abs($task->days_until_due) }} days overdue
                                    @endif
                                </div>
                            </div>
                        </x-ui.table.cell>
                        
                        <x-ui.table.cell>
                            @if($task->status === 'completed')
                                <x-ui.badge variant="success">{{ ucfirst($task->status) }}</x-ui.badge>
                            @elseif($task->is_overdue)
                                <x-ui.badge variant="danger">{{ ucfirst($task->status) }}</x-ui.badge>
                            @else
                                <x-ui.badge variant="warning">{{ ucfirst($task->status) }}</x-ui.badge>
                            @endif
                        </x-ui.table.cell>
                        
                        <x-ui.table.cell>
                            @if($task->checklist && count($task->checklist) > 0)
                                @php
                                    $completed = collect($task->checklist)->filter(fn($item) => $item['completed'])->count();
                                    $total = count($task->checklist);
                                    $percentage = $total > 0 ? ($completed / $total) * 100 : 0;
                                @endphp
                                <div class="space-y-1">
                                    <x-ui.progress :value="$percentage" size="sm" />
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $completed }}/{{ $total }} completed
                                    </div>
                                </div>
                            @else
                                <div class="text-xs text-gray-500 dark:text-gray-400">No checklist</div>
                            @endif
                        </x-ui.table.cell>
                        
                        <x-ui.table.action-cell>
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.tax-calendar.show', $task->id) }}" 
                                   class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                   title="{{ __('View details') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                
                                @if($task->status !== 'completed')
                                <a href="{{ route('admin.tax-calendar.edit', $task->id) }}" 
                                   class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                   title="{{ __('Edit') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                @endif
                                
                                <form action="{{ route('admin.tax-calendar.destroy', $task->id) }}" method="POST" 
                                      onsubmit="return confirm('{{ __('Are you sure you want to delete this task?') }}')"
                                      class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-1 rounded-lg text-red-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                            title="{{ __('Delete') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </x-ui.table.action-cell>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <x-ui.table.empty-state>
                                <x-slot name="icon">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </x-slot>
                                <x-slot name="title">{{ __('No tax calendar tasks found') }}</x-slot>
                                <x-slot name="description">{{ __('Create your first task to get started tracking tax deadlines.') }}</x-slot>
                            </x-ui.table.empty-state>
                        </td>
                    </tr>
                @endforelse
                    </x-slot>
                </x-ui.table.base>
            </x-ui.card.body>
        </x-ui.card.base>

        <!-- Pagination -->
        @if($tasks->hasPages())
            <div>
                {{ $tasks->links() }}
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const statusFilter = document.getElementById('filterStatus');
            const monthFilter = document.getElementById('filterMonth');
            
            function applyFilters() {
                const params = new URLSearchParams(window.location.search);
                
                if (statusFilter.value) {
                    params.set('status', statusFilter.value);
                } else {
                    params.delete('status');
                }
                
                if (monthFilter.value) {
                    params.set('month', monthFilter.value);
                } else {
                    params.delete('month');
                }
                
                window.location.href = window.location.pathname + '?' + params.toString();
            }
            
            statusFilter.addEventListener('change', applyFilters);
            monthFilter.addEventListener('change', applyFilters);
            
            // Set current filter values from URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('status')) {
                statusFilter.value = urlParams.get('status');
            }
            if (urlParams.has('month')) {
                monthFilter.value = urlParams.get('month');
            }
        });
    </script>
    @endpush
</x-admin.layout>