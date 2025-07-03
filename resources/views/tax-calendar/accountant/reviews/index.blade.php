<x-accountant.layout 
    title="Task Reviews"
    :breadcrumbs="[
        ['title' => __('Home'), 'href' => route('accountant.dashboard'), 'first' => true],
        ['title' => __('Task Reviews')]
    ]"
>
    <div class="space-y-6">
            <!-- Stats Section -->
            <div class="mb-6 flex justify-end">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                        <span class="inline-block w-3 h-3 rounded-full bg-yellow-400"></span>
                        <span>{{ $pendingCount ?? 0 }} Pending</span>
                    </div>
                    <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                        <span class="inline-block w-3 h-3 rounded-full bg-blue-400"></span>
                        <span>In Progress</span>
                    </div>
                    <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                        <span class="inline-block w-3 h-3 rounded-full bg-green-400"></span>
                        <span>Completed</span>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm mb-6">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex flex-wrap px-6" aria-label="Tabs">
                        <a href="{{ route('accountant.tax-calendar.reviews') }}"
                            class="@if(!request('status') && !request('archived')) border-blue-500 text-blue-600 @else border-transparent hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-3 mr-4 border-b-2 font-medium text-sm flex items-center group transition-colors">
                            <svg class="w-5 h-5 mr-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Pending</span>
                            @if($pendingCount ?? 0 > 0)
                                <span class="ml-2 bg-blue-100 text-blue-600 py-0.5 px-2.5 rounded-full text-xs font-medium group-hover:bg-blue-200">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </a>

                        <a href="{{ route('accountant.tax-calendar.reviews', ['status' => 'changes_requested']) }}"
                            class="@if(request('status') === 'changes_requested') border-yellow-500 text-yellow-600 @else border-transparent hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-3 mr-4 border-b-2 font-medium text-sm flex items-center group transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span>Changes Requested</span>
                        </a>

                        <a href="{{ route('accountant.tax-calendar.reviews', ['status' => 'in_progress']) }}"
                            class="@if(request('status') === 'in_progress') border-blue-500 text-blue-600 @else border-transparent hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-3 mr-4 border-b-2 font-medium text-sm flex items-center group transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span>In Progress</span>
                        </a>

                        <a href="{{ route('accountant.tax-calendar.reviews', ['status' => 'completed']) }}"
                            class="@if(request('status') === 'completed') border-green-500 text-green-600 @else border-transparent hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-3 mr-4 border-b-2 font-medium text-sm flex items-center group transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Completed</span>
                        </a>

                        <a href="{{ route('accountant.tax-calendar.reviews', ['status' => 'rejected']) }}"
                            class="@if(request('status') === 'rejected') border-red-500 text-red-600 @else border-transparent hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-3 mr-4 border-b-2 font-medium text-sm flex items-center group transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Rejected</span>
                        </a>

                        <a href="{{ route('accountant.tax-calendar.reviews', ['archived' => true]) }}"
                            class="@if(request('archived')) border-gray-500 text-gray-600 @else border-transparent hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-3 mr-4 border-b-2 font-medium text-sm flex items-center group transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                            <span>Archived</span>
                        </a>
                    </nav>
                </div>
            </div>

            @if($tasks->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                    <div class="text-center">
                        <div class="mx-auto h-24 w-24 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No tasks found</h3>
                        <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">
                            @if(request('archived'))
                                There are no archived tasks at the moment. Completed and rejected tasks will appear here.
                            @else
                                There are no tasks requiring your attention at the moment. New submissions will appear here.
                            @endif
                        </p>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr class="bg-gray-50/50 dark:bg-gray-800/50">
                                    <th scope="col" class="px-8 py-5 text-left">
                                        <span class="text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Task & Company</span>
                                    </th>
                                    <th scope="col" class="px-8 py-5 text-left">
                                        <span class="text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</span>
                                    </th>
                                    <th scope="col" class="px-8 py-5 text-left">
                                        <span class="text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Submitted</span>
                                    </th>
                                    <th scope="col" class="px-8 py-5 text-left">
                                        <span class="text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Due Date</span>
                                    </th>
                                    <th scope="col" class="px-8 py-5 text-left">
                                        <span class="text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Reviewed</span>
                                    </th>
                                    <th scope="col" class="px-8 py-5 text-right">
                                        <span class="text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Action</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                                @foreach($tasks as $task)
                                    <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-all duration-200">
                                        <td class="px-8 py-6">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-14 w-14 bg-gray-50 dark:bg-gray-700 rounded-xl flex items-center justify-center shadow-sm">
                                                    @if($task->company->logo_url)
                                                        <img class="h-14 w-14 rounded-xl object-cover" src="{{ $task->company->logo_url }}" alt="{{ $task->company->name }}">
                                                    @else
                                                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div class="ml-5">
                                                    <div class="text-base font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                                        {{ $task->taxCalendar->name }}
                                                    </div>
                                                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mt-1">
                                                        {{ $task->company->name }}
                                                    </div>
                                                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        <svg class="w-3.5 h-3.5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                        {{ $task->company->users->first()->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="flex flex-col items-start gap-2">
                                                <span class="status-badge {{ $task->status_color }} text-sm font-medium px-3.5 py-1.5 rounded-full">
                                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                </span>
                                                @if($task->due_date && $task->due_date->isPast())
                                                    <span class="inline-flex items-center text-xs font-medium text-red-600 dark:text-red-400">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Overdue
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $task->submitted_at?->format('M d, Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $task->submitted_at?->format('H:i') }}
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $task->due_date?->format('M d, Y') }}
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $task->reviewed_at?->format('M d, Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $task->reviewed_at?->format('H:i') }}
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <a href="{{ route('accountant.tax-calendar.reviews.show', $task->id) }}"
                                               class="inline-flex items-center px-4 py-2.5 border border-transparent rounded-xl text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow group-hover:shadow-md">
                                                Review
                                                <svg class="ml-2 -mr-0.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-8">
                    {{ $tasks->links() }}
                </div>
            @endif
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
</x-accountant.layout>