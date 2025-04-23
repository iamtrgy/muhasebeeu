<x-app-layout>
    <x-unified-header>
        <x-slot name="title">Tax Calendar Tasks</x-slot>
        <x-slot name="description">Manage and track your tax calendar tasks</x-slot>
    </x-unified-header>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <form action="{{ route('user.tax-calendar.tasks.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                                    <option value="changes_requested" {{ request('status') === 'changes_requested' ? 'selected' : '' }}>Changes Requested</option>
                                </select>
                            </div>
                            <div>
                                <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Month</label>
                                <select name="month" id="month" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="">All Months</option>
                                    @foreach(range(1, 12) as $month)
                                        <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="w-full bg-blue-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Apply Filters
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tasks List -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    @if($tasks->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">No tasks found.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-800/50">
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Task</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Due Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Progress</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($tasks as $task)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $task->taxCalendar->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $task->taxCalendar->form_code }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-white">{{ $task->due_date->format('M d, Y') }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $task->due_date->diffForHumans() }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="status-badge {{ $task->status }} px-2.5 py-0.5 rounded-full text-xs font-medium">
                                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $task->progress }}%"></div>
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $task->progress }}% Complete
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('user.tax-calendar.tasks.show', $task->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">View Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($tasks->hasPages())
                            <div class="mt-4">
                                {{ $tasks->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
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
