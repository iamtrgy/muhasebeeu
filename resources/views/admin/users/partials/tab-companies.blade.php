<!-- Companies Tab -->
<div class="space-y-6">
    @if($user->companies->count() > 0)
        <x-ui.card.base>
            <x-ui.card.body>
                <x-ui.table.base>
                    <x-slot name="head">
                        <x-ui.table.head-cell>{{ __('Name') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell>{{ __('Country') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell>{{ __('Tax ID') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell>{{ __('Created') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell class="text-right">{{ __('Actions') }}</x-ui.table.head-cell>
                    </x-slot>
                    <x-slot name="body">
                        @foreach($user->companies as $company)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <x-ui.table.cell>
                                    <div class="flex items-center">
                                        <x-ui.avatar name="{{ $company->name }}" size="sm" />
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $company->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $company->email }}</div>
                                        </div>
                                    </div>
                                </x-ui.table.cell>
                                <x-ui.table.cell>{{ $company->country->name ?? 'N/A' }}</x-ui.table.cell>
                                <x-ui.table.cell>
                                    <span class="font-mono text-sm">{{ $company->tax_id ?? 'N/A' }}</span>
                                </x-ui.table.cell>
                                <x-ui.table.cell>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $company->created_at->format('M d, Y') }}
                                    </span>
                                </x-ui.table.cell>
                                <x-ui.table.action-cell>
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.companies.show', $company) }}" 
                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                           title="{{ __('View details') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.companies.edit', $company) }}" 
                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                           title="{{ __('Edit') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    </div>
                                </x-ui.table.action-cell>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-ui.table.base>
            </x-ui.card.body>
        </x-ui.card.base>
    @else
        <x-ui.card.base>
            <x-ui.card.body>
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No Companies') }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('This user has not created any companies yet.') }}</p>
                    <div class="mt-6">
                        <x-ui.button.primary size="sm" href="{{ route('admin.companies.create') }}?user_id={{ $user->id }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            {{ __('Create Company') }}
                        </x-ui.button.primary>
                    </div>
                </div>
            </x-ui.card.body>
        </x-ui.card.base>
    @endif
</div>
