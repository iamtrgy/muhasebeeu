<x-admin-layout>
    <x-slot name="header">
        <x-admin.page-title 
            title="{{ __('Duplicate Companies') }}" 
            description="{{ __('Manage and merge duplicate company records') }}"
        ></x-admin.page-title>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif



            <!-- Duplicate Companies by Name -->
            @if(count($companiesWithDuplicateNames) > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="text-lg font-medium mb-4">Companies with Duplicate Names</h2>

                        @foreach($companiesWithDuplicateNames as $name => $companies)
                            <div class="mb-8 border-b border-gray-200 dark:border-gray-700 pb-4">
                                <h3 class="text-md font-medium mb-2">Name: {{ $name }}</h3>
                                
                                <form action="{{ route('admin.companies.merge') }}" method="POST">
                                    @csrf
                                    <x-admin.table>
                                        <x-slot name="header">
                                            <x-admin.table.th>Primary</x-admin.table.th>
                                            <x-admin.table.th>Merge</x-admin.table.th>
                                            <x-admin.table.th>ID</x-admin.table.th>
                                            <x-admin.table.th>Owner</x-admin.table.th>
                                            <x-admin.table.th>Tax Number</x-admin.table.th>
                                            <x-admin.table.th>Country</x-admin.table.th>
                                            <x-admin.table.th>Created At</x-admin.table.th>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                @foreach($companies as $company)
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <input type="radio" name="primary_company_id" value="{{ $company->id }}" required>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <input type="checkbox" name="duplicate_company_ids[]" value="{{ $company->id }}">
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $company->id }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $company->user->name }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $company->tax_number ?? '-' }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $company->country->name }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $company->created_at->format('Y-m-d H:i') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-4">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                            Merge Selected Companies
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Duplicate Companies by Tax Number -->
            @if(count($companiesWithDuplicateTaxNumbers) > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="text-lg font-medium mb-4">Companies with Duplicate Tax Numbers</h2>

                        @foreach($companiesWithDuplicateTaxNumbers as $taxNumber => $companies)
                            <div class="mb-8 border-b border-gray-200 dark:border-gray-700 pb-4">
                                <h3 class="text-md font-medium mb-2">Tax Number: {{ $taxNumber }}</h3>
                                
                                <form action="{{ route('admin.companies.merge') }}" method="POST">
                                    @csrf
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        Primary
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        Merge
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        ID
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        Name
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        Owner
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        Country
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        Created At
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                @foreach($companies as $company)
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <input type="radio" name="primary_company_id" value="{{ $company->id }}" required>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <input type="checkbox" name="duplicate_company_ids[]" value="{{ $company->id }}">
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $company->id }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                            {{ $company->name }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $company->user->name }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $company->country->name }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $company->created_at->format('Y-m-d H:i') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-4">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                            Merge Selected Companies
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- No Duplicates Message -->
            @if(count($companiesWithDuplicateNames) === 0 && count($companiesWithDuplicateTaxNumbers) === 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <p class="text-center">No duplicate companies found.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout> 