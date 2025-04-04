<x-app-layout>
    <x-page-header
        title="{{ __('Company Details') }}"
        description="{{ $company->name }}"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('user.dashboard')],
            ['title' => 'Companies', 'url' => route('user.companies.index')],
            ['title' => 'Company Details', 'current' => true]
        ]"
    />

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Company Information Card -->
                <div class="md:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center mb-6">
                                <div class="flex-shrink-0 h-16 w-16 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center">
                                    <span class="text-2xl font-medium text-blue-700 dark:text-blue-300">{{ substr($company->name, 0, 1) }}</span>
                                </div>
                                <div class="ml-4">
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $company->name }}</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $company->email }}</p>
                                </div>
                            </div>

                            <div class="border-t dark:border-gray-700 -mx-6 px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Company Information') }}</h3>
                                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Tax Number') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $company->tax_number ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Tax Office') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $company->tax_office ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Country') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $company->country->name ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Phone') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $company->phone ?? '-' }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <div class="border-t dark:border-gray-700 -mx-6 px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Address') }}</h3>
                                <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $company->address ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="md:col-span-1">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Quick Actions') }}</h3>
                            <nav class="space-y-3">
                                <a href="{{ route('user.companies.edit', $company->id) }}" class="flex items-center px-4 py-2 text-sm text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-colors duration-150">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    {{ __('Edit Company') }}
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-colors duration-150">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ __('View Documents') }}
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-colors duration-150">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ __('View Activity') }}
                                </a>
                            </nav>
                        </div>
                    </div>

                    <!-- Stats Card -->
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Statistics') }}</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ __('Total Documents') }}</dt>
                                    <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">0</dd>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ __('Last Activity') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ __('No recent activity') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>