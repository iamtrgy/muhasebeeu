<x-admin-layout>
    <x-slot name="header">
        <x-admin.page-title title="{{ __('Companies') }}">
        </x-admin.page-title>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Search Controls -->
                    <div class="mb-6 flex flex-col sm:flex-row gap-4 items-center">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <x-icons name="search" class="w-4 h-4 text-gray-500 dark:text-gray-400" />
                            </div>
                            <input type="search" id="companySearch" placeholder="Search companies.." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('admin.companies.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <x-icons name="add" class="w-4 h-4 mr-2" />
                                ADD NEW COMPANY
                            </a>
                            <a href="{{ route('admin.companies.duplicates') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <x-icons name="company" class="w-4 h-4 mr-2" />
                                VIEW DUPLICATES
                            </a>
                        </div>
                    </div>
                    <x-admin.table id="companiesTable">
                        <x-slot name="header">
                            <x-admin.table.tr>
                                <x-admin.table.th>{{ __('Company') }}</x-admin.table.th>
                                <x-admin.table.th>{{ __('Owner / Country') }}</x-admin.table.th>
                                <x-admin.table.th>{{ __('Tax Info') }}</x-admin.table.th>
                                <x-admin.table.th>{{ __('Created') }}</x-admin.table.th>
                                <x-admin.table.th class="relative">
                                    <span class="sr-only">{{ __('Actions') }}</span>
                                </x-admin.table.th>
                            </x-admin.table.tr>
                        </x-slot>

                        @foreach($companies as $company)
                            <x-admin.table.tr>
                                <x-admin.table.td>
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center">
                                            <span class="text-lg font-medium text-blue-700 dark:text-blue-300">{{ substr($company->name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100 company-name">
                                                {{ $company->name }}
                                            </div>
                                        </div>
                                    </div>
                                </x-admin.table.td>
                                <x-admin.table.td>
                                    <div class="text-sm text-gray-900 dark:text-gray-100 owner-name">{{ $company->user->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 country-name">
                                        {{ $company->country->name }}
                                    </div>
                                </x-admin.table.td>
                                <x-admin.table.td>
                                    <div>
                                        @if($company->tax_number)
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ __('Tax Number:') }} {{ $company->tax_number }}</div>
                                        @endif
                                        @if($company->vat_number)
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('VAT:') }} {{ $company->vat_number }}</div>
                                        @endif
                                        @if(!$company->tax_number && !$company->vat_number)
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('No tax info') }}</span>
                                        @endif
                                    </div>
                                </x-admin.table.td>
                                <x-admin.table.td>
                                    {{ $company->created_at->format('M d, Y') }}
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $company->created_at->diffForHumans() }}</div>
                                </x-admin.table.td>
                                <x-admin.table.td class="text-right">
                                    <div class="flex justify-end items-center space-x-3">
                                        <a href="{{ route('admin.companies.show', $company) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            <x-icons name="view" class="h-5 w-5" />
                                        </a>
                                    </div>
                                </x-admin.table.td>
                            </x-admin.table.tr>
                        @endforeach
                    </x-admin.table>

                    <!-- Empty State -->
                    <div id="noCompaniesFound" class="hidden py-8 text-center">
                        <x-icons name="company" class="mx-auto h-12 w-12 text-gray-400" />
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No companies found') }}</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Try adjusting your search to find what you\'re looking for.') }}</p>
                    </div>

                    <div class="mt-4">
                        {{ $companies->links() }}
                    </div>
                </div>
            </div>
        </div>
        </div>
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
</x-admin-layout>