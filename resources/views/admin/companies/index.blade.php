<x-admin.layout 
    title="{{ __('Companies') }}"
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('admin.dashboard'), 'first' => true],
        ['title' => __('Companies')]
    ]"
>
    <div class="space-y-6">

        <!-- Success/Error Messages -->
        @if(session('success'))
            <x-ui.alert variant="success">
                {{ session('success') }}
            </x-ui.alert>
        @endif

        <x-ui.card.base>
            <x-ui.card.body>
                <!-- Search Controls -->
                <div class="mb-6 flex flex-col lg:flex-row gap-4 lg:items-center">
                    <div class="flex-1">
                        <x-ui.form.input
                            type="search"
                            id="companySearch"
                            name="companySearch"
                            placeholder="{{ __('Search companies...') }}"
                            class="w-full"
                        >
                            <x-slot name="leadingIcon">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </x-slot>
                        </x-ui.form.input>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 lg:flex-shrink-0">
                        <x-ui.button.primary href="{{ route('admin.companies.create') }}" class="w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            {{ __('Add Company') }}
                        </x-ui.button.primary>
                        <a href="{{ route('admin.companies.duplicates') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 shadow-sm transition ease-in-out duration-150 w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{ __('View Duplicates') }}
                        </a>
                    </div>
                </div>
                <x-ui.table.base id="companiesTable">
                    <x-slot name="head">
                        <x-ui.table.head-cell>{{ __('Company') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell>{{ __('Owner / Country') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell>{{ __('Tax Info') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell>{{ __('Created') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell class="text-right">{{ __('Actions') }}</x-ui.table.head-cell>
                    </x-slot>
                    <x-slot name="body">

                        @foreach($companies as $company)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <x-ui.table.cell>
                                    <div class="flex items-center">
                                        <x-ui.avatar name="{{ $company->name }}" size="sm" />
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100 company-name">
                                                {{ $company->name }}
                                            </div>
                                        </div>
                                    </div>
                                </x-ui.table.cell>
                                <x-ui.table.cell>
                                    <div class="text-sm text-gray-900 dark:text-gray-100 owner-name">{{ $company->user->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 country-name">
                                        {{ $company->country->name }}
                                    </div>
                                </x-ui.table.cell>
                                <x-ui.table.cell>
                                    <div>
                                        @if($company->tax_number)
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ __('Registry:') }} {{ $company->tax_number }}</div>
                                        @endif
                                        @if($company->vat_number)
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('VAT:') }} {{ $company->vat_number }}</div>
                                        @endif
                                        @if(!$company->tax_number && !$company->vat_number)
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('No tax info') }}</span>
                                        @endif
                                    </div>
                                </x-ui.table.cell>
                                <x-ui.table.cell>
                                    {{ $company->created_at->format('M d, Y') }}
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $company->created_at->diffForHumans() }}</div>
                                </x-ui.table.cell>
                                <x-ui.table.action-cell>
                                    <div class="flex items-center justify-end">
                                        <a href="{{ route('admin.companies.show', $company) }}" 
                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                           title="{{ __('View details') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </div>
                                </x-ui.table.action-cell>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-ui.table.base>

                <!-- Empty State -->
                <div id="noCompaniesFound" class="hidden text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No companies found') }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Try adjusting your search to find what you\'re looking for.') }}</p>
                </div>

                <div class="mt-4">
                    {{ $companies->links() }}
                </div>
            </x-ui.card.body>
        </x-ui.card.base>
    </div>

    @push('scripts')
    <script>
        // Extremely simplified company search that will work with any structure
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Initializing company search...');
            
            // Store references to key elements
            let searchInput = null;
            let companyTable = null;
            let emptyStateMessage = null;
            let companyRows = [];
            
            // 1. Find search input (try multiple approaches)
            searchInput = document.querySelector('input[type="search"]') || 
                         document.querySelector('input[placeholder*="Search"]');
            
            // 2. Find the companies table
            companyTable = document.getElementById('companiesTable') ||
                         document.querySelector('table');
            
            // 3. Find all data rows
            if (companyTable) {
                // Skip header row if it exists
                const allRows = companyTable.querySelectorAll('tr');
                if (allRows.length > 0) {
                    // Check if first row is a header
                    const firstRow = allRows[0];
                    if (firstRow.querySelector('th')) {
                        // Skip header row
                        companyRows = Array.from(allRows).slice(1);
                    } else {
                        companyRows = Array.from(allRows);
                    }
                }
            }
            
            // 4. Find empty state message
            emptyStateMessage = document.querySelector('[id*="NoCompanies"], [id*="noCompanies"], .empty-state') ||
                               Array.from(document.querySelectorAll('div')).find(el => {
                                 const text = el.textContent.trim();
                                 return text === 'No companies found' || text.includes('No companies found');
                               });
            
            // Log what we found for debugging
            console.log('Search input found:', !!searchInput);
            console.log('Company table found:', !!companyTable);
            console.log('Company rows found:', companyRows.length);
            console.log('Empty state found:', !!emptyStateMessage);
            
            // Hide empty state initially
            if (emptyStateMessage) {
                emptyStateMessage.style.display = 'none';
            }
            
            // Set up search functionality if we have both input and rows
            if (searchInput && companyRows.length > 0) {
                // Add event listeners
                ['input', 'search', 'keyup'].forEach(event => {
                    searchInput.addEventListener(event, doSearch);
                });
                
                // Search function
                function doSearch() {
                    const searchTerm = searchInput.value.toLowerCase().trim();
                    let visibleCount = 0;
                    
                    // Don't log every row, just the search term
                    console.log('Searching for:', searchTerm || '(empty)');
                    
                    // Loop through rows and filter
                    companyRows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        const visible = searchTerm === '' || text.includes(searchTerm);
                        
                        // Show/hide row
                        row.style.display = visible ? '' : 'none';
                        
                        // Count visible rows
                        if (visible) visibleCount++;
                    });
                    
                    // Show/hide empty state message
                    if (emptyStateMessage) {
                        emptyStateMessage.style.display = visibleCount === 0 ? 'block' : 'none';
                    }
                    
                    console.log('Search complete. Visible companies:', visibleCount);
                }
                
                // Initial search (in case there's text already in input)
                doSearch();
            }
        });
    </script>
    @endpush
</x-admin.layout>