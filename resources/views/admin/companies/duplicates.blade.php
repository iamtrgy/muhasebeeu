<x-admin.layout 
    title="{{ __('Duplicate Companies') }}"
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('admin.dashboard'), 'first' => true],
        ['title' => __('Companies'), 'href' => route('admin.companies.index')],
        ['title' => __('Duplicate Companies')]
    ]"
>
    <div class="space-y-6">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <x-ui.alert variant="success">
                {{ session('success') }}
            </x-ui.alert>
        @endif

        @if(session('error'))
            <x-ui.alert variant="danger">
                {{ session('error') }}
            </x-ui.alert>
        @endif



            <!-- Duplicate Companies by Name -->
            @if(count($companiesWithDuplicateNames) > 0)
                <x-ui.card.base>
                    <x-ui.card.header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Companies with Duplicate Names') }}</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Select a primary company and mark others for merging.') }}</p>
                    </x-ui.card.header>
                    <x-ui.card.body>

                        @foreach($companiesWithDuplicateNames as $name => $companies)
                            <div class="mb-8 border-b border-gray-200 dark:border-gray-700 pb-4">
                                <h3 class="text-md font-medium mb-2">Name: {{ $name }}</h3>
                                
                                <form action="{{ route('admin.companies.merge') }}" method="POST">
                                    @csrf
                                    <x-ui.table.base>
                                        <x-ui.table.header>
                                            <x-ui.table.head-cell>Primary</x-ui.table.head-cell>
                                            <x-ui.table.head-cell>Merge</x-ui.table.head-cell>
                                            <x-ui.table.head-cell>ID</x-ui.table.head-cell>
                                            <x-ui.table.head-cell>Owner</x-ui.table.head-cell>
                                            <x-ui.table.head-cell>Tax Number</x-ui.table.head-cell>
                                            <x-ui.table.head-cell>Country</x-ui.table.head-cell>
                                            <x-ui.table.head-cell>Created At</x-ui.table.head-cell>
                                        </x-ui.table.header>
                                        
                                        <x-ui.table.body>
                                            @foreach($companies as $company)
                                                <x-ui.table.row>
                                                    <x-ui.table.cell>
                                                        <x-ui.form.radio name="primary_company_id" value="{{ $company->id }}" required />
                                                    </x-ui.table.cell>
                                                    <x-ui.table.cell>
                                                        <x-ui.form.checkbox name="duplicate_company_ids[]" value="{{ $company->id }}" />
                                                    </x-ui.table.cell>
                                                    <x-ui.table.cell class="font-medium">
                                                        {{ $company->id }}
                                                    </x-ui.table.cell>
                                                    <x-ui.table.cell>
                                                        {{ $company->user->name }}
                                                    </x-ui.table.cell>
                                                    <x-ui.table.cell>
                                                        {{ $company->tax_number ?? '-' }}
                                                    </x-ui.table.cell>
                                                    <x-ui.table.cell>
                                                        {{ $company->country->name }}
                                                    </x-ui.table.cell>
                                                    <x-ui.table.cell>
                                                        {{ $company->created_at->format('Y-m-d H:i') }}
                                                    </x-ui.table.cell>
                                                </x-ui.table.row>
                                            @endforeach
                                        </x-ui.table.body>
                                    </x-ui.table.base>
                                    <div class="mt-4">
                                        <x-ui.button.danger type="submit">
                                            Merge Selected Companies
                                        </x-ui.button.danger>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </x-ui.card.body>
                </x-ui.card.base>
            @endif

            <!-- Duplicate Companies by Tax Number -->
            @if(count($companiesWithDuplicateTaxNumbers) > 0)
                <x-ui.card.base>
                    <x-ui.card.header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Companies with Duplicate Tax Numbers') }}</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Select a primary company and mark others for merging.') }}</p>
                    </x-ui.card.header>
                    <x-ui.card.body>

                        @foreach($companiesWithDuplicateTaxNumbers as $taxNumber => $companies)
                            <div class="mb-8 border-b border-gray-200 dark:border-gray-700 pb-4">
                                <h3 class="text-md font-medium mb-2">Tax Number: {{ $taxNumber }}</h3>
                                
                                <form action="{{ route('admin.companies.merge') }}" method="POST">
                                    @csrf
                                    <x-ui.table.base>
                                        <x-ui.table.header>
                                            <x-ui.table.head-cell>Primary</x-ui.table.head-cell>
                                            <x-ui.table.head-cell>Merge</x-ui.table.head-cell>
                                            <x-ui.table.head-cell>ID</x-ui.table.head-cell>
                                            <x-ui.table.head-cell>Name</x-ui.table.head-cell>
                                            <x-ui.table.head-cell>Owner</x-ui.table.head-cell>
                                            <x-ui.table.head-cell>Country</x-ui.table.head-cell>
                                            <x-ui.table.head-cell>Created At</x-ui.table.head-cell>
                                        </x-ui.table.header>
                                        
                                        <x-ui.table.body>
                                            @foreach($companies as $company)
                                                <x-ui.table.row>
                                                    <x-ui.table.cell>
                                                        <x-ui.form.radio name="primary_company_id" value="{{ $company->id }}" required />
                                                    </x-ui.table.cell>
                                                    <x-ui.table.cell>
                                                        <x-ui.form.checkbox name="duplicate_company_ids[]" value="{{ $company->id }}" />
                                                    </x-ui.table.cell>
                                                    <x-ui.table.cell class="font-medium">
                                                        {{ $company->id }}
                                                    </x-ui.table.cell>
                                                    <x-ui.table.cell class="font-medium">
                                                        {{ $company->name }}
                                                    </x-ui.table.cell>
                                                    <x-ui.table.cell>
                                                        {{ $company->user->name }}
                                                    </x-ui.table.cell>
                                                    <x-ui.table.cell>
                                                        {{ $company->country->name }}
                                                    </x-ui.table.cell>
                                                    <x-ui.table.cell>
                                                        {{ $company->created_at->format('Y-m-d H:i') }}
                                                    </x-ui.table.cell>
                                                </x-ui.table.row>
                                            @endforeach
                                        </x-ui.table.body>
                                    </x-ui.table.base>
                                    <div class="mt-4">
                                        <x-ui.button.danger type="submit">
                                            Merge Selected Companies
                                        </x-ui.button.danger>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </x-ui.card.body>
                </x-ui.card.base>
            @endif

            <!-- No Duplicates Message -->
            @if(count($companiesWithDuplicateNames) === 0 && count($companiesWithDuplicateTaxNumbers) === 0)
                <x-ui.card.base>
                    <x-ui.card.body>
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No Duplicates Found') }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('All companies have unique names and tax numbers.') }}</p>
                        </div>
                    </x-ui.card.body>
                </x-ui.card.base>
            @endif
    </div>
</x-admin.layout> 