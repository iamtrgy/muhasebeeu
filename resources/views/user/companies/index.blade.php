<x-user.layout>
    <x-unified-header />
    
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Success/Error Messages -->
            @if(session('success'))
                <x-ui.alert type="success" class="mb-4" dismissible>
                    {{ session('success') }}
                </x-ui.alert>
            @endif

            @if(session('error'))
                <x-ui.alert type="error" class="mb-4" dismissible>
                    {{ session('error') }}
                </x-ui.alert>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Search Controls -->
                    <div class="mb-6">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="search" id="companySearch" placeholder="Search companies..." class="bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-md focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 pr-3 py-2 placeholder-gray-400 dark:placeholder-gray-500">
                        </div>
                    </div>

                    @if($companies->isEmpty())
                        <div class="py-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No companies found') }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Get started by creating your first company.') }}</p>
                        </div>
                    @else
                        <x-ui.table.base id="companiesTable">
                            <x-ui.table.header>
                                <x-ui.table.row>
                                    <x-ui.table.head-cell>{{ __('Company') }}</x-ui.table.head-cell>
                                    <x-ui.table.head-cell>{{ __('Tax Info') }}</x-ui.table.head-cell>
                                    <x-ui.table.head-cell>{{ __('Country') }}</x-ui.table.head-cell>
                                    <x-ui.table.head-cell align="right">{{ __('Actions') }}</x-ui.table.head-cell>
                                </x-ui.table.row>
                            </x-ui.table.header>
                            <x-ui.table.body>
                                @foreach($companies as $company)
                                    <x-ui.table.row>
                                        <x-ui.table.cell>
                                            <div class="flex items-center">
                                                <x-ui.avatar 
                                                    size="md" 
                                                    name="{{ $company->name }}"
                                                    class="flex-shrink-0"
                                                />
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $company->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $company->email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Registry Number</div>
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $company->tax_number }}</div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell>
                                            {{ $company->country->name ?? '-' }}
                                        </x-ui.table.cell>
                                        <x-ui.table.action-cell>
                                            <x-ui.tooltip text="View Details" position="left">
                                                <a href="{{ route('user.companies.show', $company->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            </x-ui.tooltip>
                                            <x-ui.tooltip text="Edit" position="left">
                                                <a href="{{ route('user.companies.edit', $company->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                            </x-ui.tooltip>
                                        </x-ui.table.action-cell>
                                    </x-ui.table.row>
                                @endforeach
                            </x-ui.table.body>
                        </x-ui.table.base>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Company search functionality
        document.getElementById('companySearch').addEventListener('input', function(e) {
            const searchText = e.target.value.toLowerCase();
            const table = document.getElementById('companiesTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let row of rows) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchText) ? '' : 'none';
            }
        });
    </script>
    @endpush
</x-user.layout>
