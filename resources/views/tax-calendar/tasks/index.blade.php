<x-admin.layout 
    title="{{ __('Tax Calendar') }}"
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('admin.dashboard'), 'first' => true],
        ['title' => __('Tax Calendar')]
    ]"
>
    <div class="space-y-6">
        <!-- Page Header with Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Manage and track tax calendar tasks for all companies') }}
                </p>
            </div>
            <div class="flex gap-3">
                <x-ui.button.secondary href="{{ route('admin.tax-calendar-templates.index') }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('Manage Templates') }}
                </x-ui.button.secondary>
                <x-ui.button.primary href="{{ route('admin.tax-calendar.create') }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    {{ __('Add Task') }}
                </x-ui.button.primary>
            </div>
        </div>

        <!-- Filters Card -->
        <x-ui.card.base>
            <x-ui.card.body>
                <form action="{{ route('admin.user.tax-calendar.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <x-ui.form.select name="status" id="status" label="{{ __('Status') }}" value="{{ request('status') }}">
                            <option value="">{{ __('All Status') }}</option>
                            <option value="pending">{{ __('Pending') }}</option>
                            <option value="in_progress">{{ __('In Progress') }}</option>
                            <option value="completed">{{ __('Completed') }}</option>
                            <option value="under_review">{{ __('Under Review') }}</option>
                            <option value="changes_requested">{{ __('Changes Requested') }}</option>
                        </x-ui.form.select>
                    </div>
                    <div>
                        <x-ui.form.select name="month" id="month" label="{{ __('Month') }}" value="{{ request('month') }}">
                            <option value="">{{ __('All Months') }}</option>
                            @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}">
                                    {{ \Carbon\Carbon::createFromDate(null, $month, 1)->format('F') }}
                                </option>
                            @endforeach
                        </x-ui.form.select>
                    </div>
                    <div class="flex items-end">
                        <x-ui.button.primary type="submit" class="w-full">
                            {{ __('Apply Filters') }}
                        </x-ui.button.primary>
                    </div>
                </form>
            </x-ui.card.body>
        </x-ui.card.base>

        <!-- Tasks Table -->
        <x-ui.card.base>
            <x-ui.card.body>
                <x-ui.table.base>
                    <x-slot name="head">
                        <x-ui.table.head-cell>{{ __('Task') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell>{{ __('Company') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell>{{ __('Due Date') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell>{{ __('Status') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell>{{ __('Progress') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell align="right">{{ __('Actions') }}</x-ui.table.head-cell>
                    </x-slot>
                    <x-slot name="body">
                        @forelse($tasks as $task)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <x-ui.table.cell>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $task->taxCalendar->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $task->taxCalendar->form_code }}
                                    </div>
                                </x-ui.table.cell>
                                <x-ui.table.cell>
                                    @if($task->company)
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $task->company->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $task->user->name }}</div>
                                    @else
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('N/A') }}</span>
                                    @endif
                                </x-ui.table.cell>
                                <x-ui.table.cell>
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $task->due_date->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $task->due_date->diffForHumans() }}
                                    </div>
                                </x-ui.table.cell>
                                <x-ui.table.cell>
                                    @php
                                        $statusVariant = 'secondary';
                                        switch($task->status) {
                                            case 'pending':
                                                $statusVariant = 'warning';
                                                break;
                                            case 'in_progress':
                                                $statusVariant = 'primary';
                                                break;
                                            case 'completed':
                                                $statusVariant = 'success';
                                                break;
                                            case 'under_review':
                                                $statusVariant = 'secondary';
                                                break;
                                            case 'changes_requested':
                                                $statusVariant = 'warning';
                                                break;
                                            case 'rejected':
                                                $statusVariant = 'danger';
                                                break;
                                        }
                                    @endphp
                                    <x-ui.badge :variant="$statusVariant" size="sm">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </x-ui.badge>
                                </x-ui.table.cell>
                                <x-ui.table.cell>
                                    @if(auth()->user()->is_admin)
                                        @php
                                            // Calculate progress for each checklist separately
                                            $accountantChecklist = $task->checklist ?? [];
                                            $userChecklist = $task->user_checklist ?? [];
                                            
                                            $accountantProgress = !empty($accountantChecklist) 
                                                ? round(collect($accountantChecklist)->where('completed', true)->count() * 100 / count($accountantChecklist))
                                                : 0;
                                                
                                            $userProgress = !empty($userChecklist) 
                                                ? round(collect($userChecklist)->where('completed', true)->count() * 100 / count($userChecklist))
                                                : 0;
                                        @endphp
                                        <div class="space-y-2">
                                            <!-- Accountant Progress -->
                                            <div>
                                                <div class="text-xs text-gray-600 dark:text-gray-400 mb-1">{{ __('Accountant') }}</div>
                                                <x-ui.progress :value="$accountantProgress" :max="100" size="sm" />
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $accountantProgress }}%
                                                </div>
                                            </div>
                                            <!-- User Progress -->
                                            <div>
                                                <div class="text-xs text-gray-600 dark:text-gray-400 mb-1">{{ __('User') }}</div>
                                                <x-ui.progress :value="$userProgress" :max="100" size="sm" color="success" />
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $userProgress }}%
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        @php
                                            $progress = round($task->progress ?? 0);
                                        @endphp
                                        <x-ui.progress :value="$progress" :max="100" size="sm" />
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $progress }}% {{ __('Complete') }}
                                        </div>
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
                                        <a href="{{ route('admin.tax-calendar.edit', $task->id) }}" 
                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                           title="{{ __('Edit') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    </div>
                                </x-ui.table.action-cell>
                            </tr>
                        @empty
                            <x-ui.table.empty-state 
                                colspan="6"
                                message="{{ __('No tasks found. Try adjusting your filters or create a new task.') }}"
                            />
                        @endforelse
                    </x-slot>
                </x-ui.table.base>
                
                @if($tasks->hasPages())
                    <div class="mt-4">
                        {{ $tasks->links() }}
                    </div>
                @endif
            </x-ui.card.body>
        </x-ui.card.base>
    </div>
</x-admin.layout>
