<x-app-layout>

    <div class="py-6">
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
                        
                        <a href="{{ route('accountant.companies.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            {{ __('Companies') }}
                        </a>
                        
                        <span class="mx-2 text-gray-400">/</span>
                        
                        <span class="text-gray-900 dark:text-gray-100">{{ $company->name }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Company Header Card -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-2xl leading-6 font-bold text-gray-900 dark:text-gray-100">{{ $company->name }}</h3>
                    <div class="mt-2 max-w-4xl text-sm text-gray-500 dark:text-gray-400">
                        <span class="mr-4"><strong>{{ __('Owner:') }}</strong> 
                            <a href="{{ route('accountant.users.show', $company->user->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">{{ $company->user->name }}</a>
                            ({{ $company->user->email }})
                        </span>
                        <span><strong>{{ __('Country:') }}</strong> {{ $company->country->name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Company Owner Folders Card -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Owner\'s Folders') }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Root folders owned by :name', ['name' => $company->user->name]) }}</p>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 dark:border-gray-700">
                    @if($folders->count() > 0)
                        <x-admin.table>
                            <x-slot name="header">
                                <x-admin.table.tr>
                                    <x-admin.table.th>{{ __('Folder Name') }}</x-admin.table.th>
                                    <x-admin.table.th>{{ __('Files') }}</x-admin.table.th>
                                    <x-admin.table.th>{{ __('Size') }}</x-admin.table.th>
                                    <x-admin.table.th>{{ __('Last Modified') }}</x-admin.table.th>
                                    <x-admin.table.th>{{ __('Status') }}</x-admin.table.th>
                                </x-admin.table.tr>
                            </x-slot>

                            @foreach($folders as $folder)
                                <x-admin.table.tr>
                                    <x-admin.table.td>
                                        <a href="{{ route('accountant.users.viewFolder', ['userId' => $company->user->id, 'folderId' => $folder->id]) }}" class="flex items-center hover:text-blue-600 dark:hover:text-blue-400">
                                            <div class="flex-shrink-0">
                                                <x-icons name="folder" class="h-6 w-6 text-yellow-500" />
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $folder->name }}</div>
                                                @if($folder->description)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $folder->description }}</div>
                                                @endif
                                            </div>
                                        </a>
                                    </x-admin.table.td>
                                    <x-admin.table.td>{{ $folder->files->count() }}</x-admin.table.td>
                                    <x-admin.table.td>{{ $folder->totalSize() }}</x-admin.table.td>
                                    <x-admin.table.td>
                                        @if($folder->lastModified())
                                            <span title="{{ $folder->lastModified()->format('F j, Y H:i') }}">{{ $folder->lastModified()->diffForHumans() }}</span>
                                        @else
                                            -
                                        @endif
                                    </x-admin.table.td>
                                    <x-admin.table.td>
                                        @if($folder->is_public)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                {{ __('Public') }}
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                {{ __('Private') }}
                                            </span>
                                        @endif
                                    </x-admin.table.td>
                                </x-admin.table.tr>
                            @endforeach
                        </x-admin.table>
                    @else
                        <div class="py-8 text-center">
                            <x-icons name="folder" class="mx-auto h-12 w-12 text-gray-400" />
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No Folders') }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('This user has not created any folders yet.') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Detailed Company Info Card -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Additional Details') }}</h3>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <dl>
                         <div class="bg-white dark:bg-gray-900 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Tax number') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2 font-mono">{{ $company->tax_number ?? 'N/A' }}</dd>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('VAT number') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2 font-mono">{{ $company->vat_number ?? 'N/A' }}</dd>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-900 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Address') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">{{ $company->address ?? 'N/A' }}</dd>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Phone') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">{{ $company->phone ?? 'N/A' }}</dd>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-900 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">{{ $company->email ?? 'N/A' }}</dd>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Foundation date') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                {{ $company->foundation_date ? $company->foundation_date->format('F j, Y') : 'N/A' }}
                            </dd>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-900 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created on') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                {{ $company->created_at->format('F j, Y') }}
                                <span class="text-gray-500 dark:text-gray-400">({{ $company->created_at->diffForHumans() }})</span>
                            </dd>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Last updated') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                {{ $company->updated_at->format('F j, Y') }}
                                <span class="text-gray-500 dark:text-gray-400">({{ $company->updated_at->diffForHumans() }})</span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

        </div>
    </div>
    <x-folder.file-preview-modal />
</x-app-layout> 