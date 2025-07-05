<x-admin.layout 
    title="{{ $task->taxCalendar->name }}"
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('admin.dashboard'), 'first' => true],
        ['title' => __('Tax Calendar'), 'href' => route('admin.tax-calendar.index')],
        ['title' => $task->taxCalendar->name]
    ]"
>
    <div class="space-y-6">
        <!-- Task Header Card -->
        <x-ui.card.base>
            <x-ui.card.body>
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ $task->taxCalendar->name }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ $task->taxCalendar->description }}
                        </p>
                        
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Company') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $task->company->name ?? __('N/A') }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Form Code') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $task->taxCalendar->form_code }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Due Date') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $task->due_date->format('M d, Y') }}
                                    <span class="text-xs text-gray-500 dark:text-gray-400">({{ $task->due_date->diffForHumans() }})</span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</dt>
                                <dd class="mt-1">
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
                                    <x-ui.badge :variant="$statusVariant">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </x-ui.badge>
                                </dd>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 ml-4">
                        <x-ui.button.secondary href="{{ route('admin.tax-calendar.edit', $task) }}" size="sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ __('Edit') }}
                        </x-ui.button.secondary>
                        
                        @if($task->status !== 'completed')
                            <form action="{{ route('admin.tax-calendar.complete', $task) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <x-ui.button.primary type="submit" size="sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ __('Mark Complete') }}
                                </x-ui.button.primary>
                            </form>
                        @else
                            <form action="{{ route('admin.tax-calendar.reopen', $task) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <x-ui.button.secondary type="submit" size="sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    {{ __('Reopen') }}
                                </x-ui.button.secondary>
                            </form>
                        @endif
                    </div>
                </div>
            </x-ui.card.body>
        </x-ui.card.base>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Progress Card -->
            <x-ui.card.base>
                <x-ui.card.header>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Progress') }}
                    </h3>
                </x-ui.card.header>
                <x-ui.card.body>
                    @if($isAdmin)
                        <!-- Overall Progress for Admin -->
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Overall Progress') }}</h4>
                            <x-ui.progress :value="$progress" :max="100" size="lg" />
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ $progress }}% {{ __('Complete') }}
                            </p>
                        </div>
                        
                        <!-- Accountant Progress -->
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Accountant Checklist') }}</h4>
                            <x-ui.progress :value="$accountantProgress" :max="100" size="md" />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ $accountantProgress }}% {{ __('Complete') }}
                                @if(!empty($accountantChecklist))
                                    ({{ collect($accountantChecklist)->where('completed', true)->count() }}/{{ count($accountantChecklist) }} {{ __('items') }})
                                @endif
                            </p>
                        </div>
                        
                        <!-- User Progress -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('User Checklist') }}</h4>
                            <x-ui.progress :value="$userProgress" :max="100" size="md" />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ $userProgress }}% {{ __('Complete') }}
                                @if(!empty($userChecklist))
                                    ({{ collect($userChecklist)->where('completed', true)->count() }}/{{ count($userChecklist) }} {{ __('items') }})
                                @endif
                            </p>
                        </div>
                    @else
                        <!-- Single Progress for Non-Admin -->
                        <div class="mb-4">
                            <x-ui.progress :value="$progress" :max="100" size="lg" />
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ $progress }}% {{ __('Complete') }}
                            </p>
                        </div>
                        
                        @if(!empty($checklist))
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                <p>{{ collect($checklist)->where('completed', true)->count() }} {{ __('of') }} {{ count($checklist) }} {{ __('items completed') }}</p>
                            </div>
                        @endif
                    @endif
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Notes Card -->
            <x-ui.card.base>
                <x-ui.card.header>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Notes') }}
                    </h3>
                </x-ui.card.header>
                <x-ui.card.body>
                    <form action="{{ route('admin.tax-calendar.update-notes', $task) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <x-ui.form.textarea 
                            name="notes" 
                            rows="4"
                            placeholder="{{ __('Add notes about this task...') }}"
                        >{{ $task->notes }}</x-ui.form.textarea>
                        <div class="mt-3">
                            <x-ui.button.primary type="submit" size="sm">
                                {{ __('Save Notes') }}
                            </x-ui.button.primary>
                        </div>
                    </form>
                </x-ui.card.body>
            </x-ui.card.base>
        </div>

        <!-- Checklist Section -->
        @if($isAdmin)
            <!-- Admin sees both checklists -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Accountant Checklist -->
                <x-ui.card.base>
                    <x-ui.card.header>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Accountant Checklist') }}
                        </h3>
                    </x-ui.card.header>
                    <x-ui.card.body>
                        @if(!empty($accountantChecklist))
                            <div class="space-y-3">
                                @foreach($accountantChecklist as $index => $item)
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input 
                                                type="checkbox" 
                                                id="accountant-item-{{ $index }}"
                                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded cursor-not-allowed"
                                                {{ $item['completed'] ? 'checked' : '' }}
                                                disabled
                                            >
                                        </div>
                                        <div class="ml-3 text-sm flex-1">
                                            <label for="accountant-item-{{ $index }}" class="font-medium text-gray-700 dark:text-gray-300 {{ $item['completed'] ? 'line-through text-gray-500' : '' }}">
                                                {{ $item['title'] }}
                                            </label>
                                            @if(isset($item['notes']) && $item['notes'])
                                                <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">{{ $item['notes'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('No accountant checklist items') }}</p>
                        @endif
                    </x-ui.card.body>
                </x-ui.card.base>

                <!-- User Checklist -->
                <x-ui.card.base>
                    <x-ui.card.header>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                            {{ __('User Checklist') }}
                        </h3>
                    </x-ui.card.header>
                    <x-ui.card.body>
                        @if(!empty($userChecklist))
                            <div class="space-y-3">
                                @foreach($userChecklist as $index => $item)
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input 
                                                type="checkbox" 
                                                id="user-item-{{ $index }}"
                                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded cursor-not-allowed"
                                                {{ $item['completed'] ? 'checked' : '' }}
                                                disabled
                                            >
                                        </div>
                                        <div class="ml-3 text-sm flex-1">
                                            <label for="user-item-{{ $index }}" class="font-medium text-gray-700 dark:text-gray-300 {{ $item['completed'] ? 'line-through text-gray-500' : '' }}">
                                                {{ $item['title'] }}
                                            </label>
                                            @if(isset($item['notes']) && $item['notes'])
                                                <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">{{ $item['notes'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('No user checklist items') }}</p>
                        @endif
                    </x-ui.card.body>
                </x-ui.card.base>
            </div>
        @else
            <!-- Non-admin sees only their checklist -->
            <x-ui.card.base>
                <x-ui.card.header>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Checklist') }}
                    </h3>
                </x-ui.card.header>
                <x-ui.card.body>
                    @if(!empty($checklist))
                        <div class="space-y-3" x-data="{ checklist: {{ json_encode($checklist) }} }">
                            @foreach($checklist as $index => $item)
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input 
                                            type="checkbox" 
                                            id="item-{{ $index }}"
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                            {{ $item['completed'] ? 'checked' : '' }}
                                            x-model="checklist[{{ $index }}].completed"
                                            @change="updateChecklist()"
                                        >
                                    </div>
                                    <div class="ml-3 text-sm flex-1">
                                        <label for="item-{{ $index }}" class="font-medium text-gray-700 dark:text-gray-300 {{ $item['completed'] ? 'line-through text-gray-500' : '' }}">
                                            {{ $item['title'] }}
                                        </label>
                                        @if(isset($item['notes']) && $item['notes'])
                                            <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">{{ $item['notes'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <script>
                            function updateChecklist() {
                                const checklist = this.checklist;
                                
                                fetch('{{ route('admin.tax-calendar.update-checklist', $task) }}', {
                                    method: 'PATCH',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        user_checklist: checklist
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Update progress bar if needed
                                        console.log('Checklist updated successfully');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error updating checklist:', error);
                                });
                            }
                        </script>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('No checklist items available') }}</p>
                    @endif
                </x-ui.card.body>
            </x-ui.card.base>
        @endif
    </div>
</x-admin.layout>