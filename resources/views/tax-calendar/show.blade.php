<x-app-layout>
    <x-unified-header />

    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('admin.tax-calendar.index') }}" class="text-indigo-600 hover:text-indigo-900">
                ← Back to Tax Calendar
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $task->taxCalendar->name }}</h1>
                        <p class="mt-1 text-sm text-gray-600">{{ $task->taxCalendar->description }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-gray-900">Due Date</div>
                        <div class="text-sm text-gray-500">{{ $task->due_date->format('M d, Y') }}</div>
                        <span class="mt-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($task->status === 'completed') bg-green-100 text-green-800
                            @elseif($task->is_overdue) bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst($task->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Instructions Section -->
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Instructions</h2>
                        <div class="prose prose-sm max-w-none dark:prose-invert">
                            {!! nl2br(e($task->taxCalendar->task_instructions)) !!}
                        </div>
                    </div>

                    <!-- Checklist Section -->
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Checklist</h2>
                        @if($task->checklist)
                            <form action="{{ route('admin.tax-calendar.update-checklist', $task->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="space-y-3">
                                    @foreach($task->checklist as $index => $item)
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" name="checklist[{{ $index }}][completed]"
                                                {{ $item['completed'] ? 'checked' : '' }}
                                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label class="font-medium text-gray-700">{{ $item['title'] }}</label>
                                            @if(!empty($item['notes']))
                                                <p class="text-gray-500">{{ $item['notes'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                        Save Progress
                                    </button>
                                </div>
                            </form>
                        @else
                            <p class="text-gray-500">No checklist items available.</p>
                        @endif
                    </div>

                    <!-- Notes Section -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Notes</h2>
                        <form action="{{ route('admin.tax-calendar.update-notes', $task->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <textarea name="notes" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Add your notes here...">{{ $task->notes }}</textarea>
                            <div class="mt-4">
                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                    Save Notes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Task Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <div class="flex space-x-4">
                        @if($task->status !== 'completed')
                            <form action="{{ route('admin.tax-calendar.complete', $task->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                    Mark as Completed
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.tax-calendar.reopen', $task->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700">
                                    Reopen Task
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
