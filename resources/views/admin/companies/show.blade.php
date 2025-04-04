<x-admin-layout>
    <x-slot name="header">
        <x-admin.page-title 
            title="{{ $company->name }}" 
            description="{{ __('Company Details') }}"
        ></x-admin.page-title>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Company Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Company Information</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-2">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $company->name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Country</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $company->country->name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tax Number</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $company->tax_number ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">VAT Number</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $company->vat_number ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $company->address ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $company->phone ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $company->email ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Foundation Date</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $company->foundation_date ? $company->foundation_date->format('Y-m-d') : '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $company->created_at->format('Y-m-d H:i:s') }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Updated At</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $company->updated_at->format('Y-m-d H:i:s') }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Owner Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Owner Information</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-2">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $company->user->name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $company->user->email }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Registered At</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $company->user->created_at->format('Y-m-d H:i:s') }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 flex justify-end space-x-4">
                        <a href="{{ route('admin.companies.edit', $company) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            {{ __('Edit Company') }}
                        </a>
                        <a href="{{ route('admin.companies.assign.accountants', $company) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                            </svg>
                            {{ __('Assign Accountants') }}
                        </a>
                    </div>

                    <!-- Assigned Accountants -->
                    @if($company->accountants->count() > 0)
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Assigned Accountants') }}</h3>
                        <div class="mt-4 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                                @foreach($company->accountants as $accountant)
                                <li class="py-3 flex justify-between items-center">
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $accountant->name }}</span>
                                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">{{ $accountant->email }}</span>
                                    </div>
                                    <a href="{{ route('admin.users.show', $accountant) }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        {{ __('View Profile') }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 