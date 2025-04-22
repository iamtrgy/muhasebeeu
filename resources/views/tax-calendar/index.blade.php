<x-app-layout>
    <x-unified-header />

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Tax Calendar</h1>
            <div class="flex space-x-4">
                <a href="{{ route('admin.tax-calendar.create') }}" 
                   class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Create Task
                </a>
                <select id="filterStatus" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                </select>
                <select id="filterMonth" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">All Months</option>
                    @foreach(range(1, 12) as $month)
                        <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($tasks as $task)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $task->taxCalendar->name }}</div>
                            <div class="text-sm text-gray-500">{{ $task->taxCalendar->description }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $task->due_date->format('M d, Y') }}</div>
                            <div class="text-sm text-gray-500">
                                @if($task->days_until_due > 0)
                                    {{ $task->days_until_due }} days left
                                @elseif($task->days_until_due == 0)
                                    Due today
                                @else
                                    {{ abs($task->days_until_due) }} days overdue
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($task->status === 'completed') bg-green-100 text-green-800
                                @elseif($task->is_overdue) bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($task->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($task->checklist)
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    @php
                                        $completed = collect($task->checklist)->filter(fn($item) => $item['completed'])->count();
                                        $total = count($task->checklist);
                                        $percentage = $total > 0 ? ($completed / $total) * 100 : 0;
                                    @endphp
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">{{ $completed }}/{{ $total }} completed</div>
                            @else
                                <div class="text-xs text-gray-500">No checklist</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.tax-calendar.show', $task->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $tasks->links() }}
        </div>
    </div>
</x-app-layout> 
