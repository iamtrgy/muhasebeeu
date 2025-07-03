<x-accountant.layout 
    title="Companies" 
    :breadcrumbs="[
        ['title' => __('Home'), 'href' => route('accountant.dashboard'), 'first' => true],
        ['title' => __('Companies')]
    ]"
>
    <div class="space-y-6">
        <!-- Companies Card -->
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Assigned Companies') }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Companies assigned to your account') }}</p>
            </x-ui.card.header>
            
            <x-ui.card.body>
                @if($companies->count() > 0)
                    <x-ui.table.base>
                        <x-slot name="head">
                            <x-ui.table.head-cell>{{ __('Company') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Owner') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Country') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Tax Number') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell class="text-right">{{ __('Actions') }}</x-ui.table.head-cell>
                        </x-slot>
                        
                        <x-slot name="body">
                            @foreach($companies as $company)
                                <tr>
                                    <x-ui.table.cell>
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                                <span class="text-sm font-medium text-green-800 dark:text-green-200">{{ substr($company->name, 0, 2) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <a href="{{ route('accountant.companies.show', $company) }}" class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                                                    {{ $company->name }}
                                                </a>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $company->email }}</div>
                                            </div>
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $company->user->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $company->user->email }}</div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $company->country->name ?? 'N/A' }}</div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $company->tax_number ?? 'N/A' }}</div>
                                    </x-ui.table.cell>
                                    <x-ui.table.action-cell>
                                        <x-ui.button.secondary size="sm" href="{{ route('accountant.companies.show', $company) }}">
                                            {{ __('View') }}
                                        </x-ui.button.secondary>
                                    </x-ui.table.action-cell>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-ui.table.base>

                    @if($companies instanceof \Illuminate\Pagination\LengthAwarePaginator && $companies->hasPages())
                        <div class="mt-4">
                            {{ $companies->links() }}
                        </div>
                    @endif
                @else
                    <x-ui.table.empty-state>
                        <x-slot name="icon">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </x-slot>
                        <x-slot name="title">{{ __('No Companies Assigned') }}</x-slot>
                        <x-slot name="description">{{ __('You do not have any companies assigned to your account yet.') }}</x-slot>
                    </x-ui.table.empty-state>
                @endif
            </x-ui.card.body>
        </x-ui.card.base>
    </div>
</x-accountant.layout> 