<x-admin.layout title="Tax Calendar Templates" :breadcrumbs="[
    ['title' => __('Dashboard'), 'href' => route('admin.dashboard'), 'first' => true],
    ['title' => __('Tax Calendar'), 'href' => route('admin.tax-calendar.index')],
    ['title' => __('Templates')]
]">
    <div class="space-y-6">
        <!-- Page Header with Actions -->
        <x-ui.card.base>
            <x-ui.card.body>
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Tax Calendar Templates') }}</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage tax obligation templates that can be assigned to companies') }}</p>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <x-ui.button.secondary size="sm" href="{{ route('admin.tax-calendar.index') }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ __('Back to Tasks') }}
                        </x-ui.button.secondary>
                        
                        <x-ui.button.primary size="sm" href="{{ route('admin.tax-calendar-templates.create') }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('Create Template') }}
                        </x-ui.button.primary>
                    </div>
                </div>
            </x-ui.card.body>
        </x-ui.card.base>

        <!-- Templates Table -->
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Available Templates') }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Define tax obligations that companies need to fulfill') }}</p>
            </x-ui.card.header>
            <x-ui.card.body>
                <x-ui.table.base>
                    <x-slot name="head">
                        <x-ui.table.head-cell>{{ __('Name') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell>{{ __('Country') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell>{{ __('Form Code') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell>{{ __('Frequency') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell>{{ __('Due Day') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell>{{ __('Status') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell class="text-right">{{ __('Actions') }}</x-ui.table.head-cell>
                    </x-slot>
                    
                    <x-slot name="body">
                        @forelse($templates as $template)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <x-ui.table.cell>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $template->name }}
                                        </div>
                                        @if($template->description)
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ Str::limit($template->description, 50) }}
                                            </div>
                                        @endif
                                    </div>
                                </x-ui.table.cell>
                                
                                <x-ui.table.cell>
                                    <div class="flex items-center">
                                        <img src="https://flagcdn.com/w40/{{ strtolower($template->country_code) }}.png" 
                                             alt="{{ $template->country_code }}" 
                                             class="w-6 h-4 mr-2">
                                        <span class="text-sm text-gray-900 dark:text-gray-100">{{ $template->country_code }}</span>
                                    </div>
                                </x-ui.table.cell>
                                
                                <x-ui.table.cell>
                                    <span class="font-mono text-sm text-gray-900 dark:text-gray-100">{{ $template->form_code }}</span>
                                </x-ui.table.cell>
                                
                                <x-ui.table.cell>
                                    <x-ui.badge variant="secondary">{{ ucfirst($template->frequency) }}</x-ui.badge>
                                </x-ui.table.cell>
                                
                                <x-ui.table.cell>
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $template->due_day }}{{ $template->due_day == 1 ? 'st' : ($template->due_day == 2 ? 'nd' : ($template->due_day == 3 ? 'rd' : 'th')) }}
                                        @if($template->frequency == 'annual' && $template->due_month)
                                            {{ date('F', mktime(0, 0, 0, $template->due_month, 1)) }}
                                        @endif
                                    </div>
                                    @if($template->requires_payment)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Payment: {{ $template->payment_due_day }}{{ $template->payment_due_day == 1 ? 'st' : ($template->payment_due_day == 2 ? 'nd' : ($template->payment_due_day == 3 ? 'rd' : 'th')) }}
                                        </div>
                                    @endif
                                </x-ui.table.cell>
                                
                                <x-ui.table.cell>
                                    <div class="flex flex-col gap-1">
                                        @if($template->is_active)
                                            <x-ui.badge variant="success" size="sm">{{ __('Active') }}</x-ui.badge>
                                        @else
                                            <x-ui.badge variant="secondary" size="sm">{{ __('Inactive') }}</x-ui.badge>
                                        @endif
                                        
                                        @if($template->auto_create_tasks)
                                            <x-ui.badge variant="warning" size="sm">{{ __('Auto-create') }}</x-ui.badge>
                                        @endif
                                    </div>
                                </x-ui.table.cell>
                                
                                <x-ui.table.action-cell>
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.tax-calendar-templates.edit', $template->id) }}" 
                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                           title="{{ __('Edit') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        
                                        <form action="{{ route('admin.tax-calendar-templates.destroy', $template->id) }}" method="POST" 
                                              onsubmit="return confirm('{{ __('Are you sure you want to delete this template?') }}')"
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
                                <td colspan="7">
                                    <x-ui.table.empty-state>
                                        <x-slot name="icon">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </x-slot>
                                        <x-slot name="title">{{ __('No tax calendar templates found') }}</x-slot>
                                        <x-slot name="description">{{ __('Create your first template to define tax obligations.') }}</x-slot>
                                    </x-ui.table.empty-state>
                                </td>
                            </tr>
                        @endforelse
                    </x-slot>
                </x-ui.table.base>
            </x-ui.card.body>
        </x-ui.card.base>

        <!-- Pagination -->
        @if($templates->hasPages())
            <div>
                {{ $templates->links() }}
            </div>
        @endif
    </div>
</x-admin.layout>