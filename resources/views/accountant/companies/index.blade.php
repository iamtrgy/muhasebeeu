<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb Navigation -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-4 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center text-sm">
                        <a href="{{ route('accountant.dashboard') }}" class="flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            <svg class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                            {{ __('Dashboard') }}
                        </a>
                        
                        <span class="mx-2 text-gray-400">/</span>
                        
                        <span class="text-gray-900 dark:text-gray-100">{{ __('Companies') }}</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Assigned Companies') }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Companies assigned to your account') }}</p>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 dark:border-gray-700">
                    @if($companies->count() > 0)
                        <x-admin.table>
                            <x-slot name="header">
                                <x-admin.table.tr>
                                    <x-admin.table.th>{{ __('Company') }}</x-admin.table.th>
                                    <x-admin.table.th>{{ __('Owner') }}</x-admin.table.th>
                                    <x-admin.table.th>{{ __('Country') }}</x-admin.table.th>
                                    <x-admin.table.th>{{ __('Tax Number') }}</x-admin.table.th>
                                    {{-- <x-admin.table.th class="text-right">{{ __('Actions') }}</x-admin.table.th> --}}
                                </x-admin.table.tr>
                            </x-slot>

                            @foreach($companies as $company)
                                <x-admin.table.tr>
                                    <x-admin.table.td>
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
                                    </x-admin.table.td>
                                    <x-admin.table.td>
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $company->user->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $company->user->email }}</div>
                                    </x-admin.table.td>
                                    <x-admin.table.td>
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $company->country->name ?? 'N/A' }}</div>
                                    </x-admin.table.td>
                                    <x-admin.table.td>
                                        <div class="text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $company->tax_number ?? 'N/A' }}</div>
                                    </x-admin.table.td>
                                    {{-- <x-admin.table.td class="text-right">
                                        <a href="{{ route('accountant.companies.show', $company) }}" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 dark:text-blue-200 dark:bg-blue-900 dark:hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            {{ __('View') }}
                                        </a>
                                    </x-admin.table.td> --}}
                                </x-admin.table.tr>
                            @endforeach
                        </x-admin.table>

                        @if($companies instanceof \Illuminate\Pagination\LengthAwarePaginator && $companies->hasPages())
                            <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-3">
                                {{ $companies->links() }}
                            </div>
                        @endif
                    @else
                        <div class="py-8 text-center">
                            <x-icons name="office-building" class="mx-auto h-12 w-12 text-gray-400" />
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No Companies Assigned') }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('You do not have any companies assigned to your account yet.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 