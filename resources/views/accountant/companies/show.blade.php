<x-accountant.layout 
    title="{{ $company->name }}"
    :breadcrumbs="[
        ['title' => __('Home'), 'href' => route('accountant.dashboard'), 'first' => true],
        ['title' => __('Companies'), 'href' => route('accountant.companies.index')],
        ['title' => $company->name]
    ]"
>
    <div class="space-y-6">
        {{-- Company Overview Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v10" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7v10" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7v10" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Folders') }}</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $folders->count() }}</div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <x-ui.button.primary size="sm" href="#document-folders" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7z" />
                            </svg>
                            {{ __('Browse Files') }}
                        </x-ui.button.primary>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-emerald-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Files') }}</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $folders->sum(function($folder) { return $folder->files->count(); }) }}</div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <x-ui.button.secondary size="sm" href="#document-folders" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 616 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ __('View All') }}
                        </x-ui.button.secondary>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-amber-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Company Owner') }}</div>
                            <div class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $company->user->name }}</div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <x-ui.button.secondary size="sm" href="mailto:{{ $company->user->email }}" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ __('Contact Owner') }}
                        </x-ui.button.secondary>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        </div>

        {{-- Company Information --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Basic Information --}}
            <x-ui.card.base>
                <x-ui.card.header>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Company Information') }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Basic company details and contact information') }}</p>
                </x-ui.card.header>
                <x-ui.card.body>
                    <dl class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-32 flex-shrink-0">{{ __('Company Name') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 font-medium">{{ $company->name }}</dd>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-32 flex-shrink-0">{{ __('Tax Number') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 font-mono">{{ $company->tax_number ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-32 flex-shrink-0">{{ __('VAT Number') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 font-mono">{{ $company->vat_number ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-32 flex-shrink-0">{{ __('Country') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0">{{ $company->country->name ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-32 flex-shrink-0">{{ __('Foundation Date') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0">
                                {{ $company->foundation_date ? $company->foundation_date->format('M d, Y') : 'N/A' }}
                            </dd>
                        </div>
                    </dl>
                </x-ui.card.body>
            </x-ui.card.base>

            {{-- Contact Information --}}
            <x-ui.card.base>
                <x-ui.card.header>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Contact Details') }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Company contact information and address') }}</p>
                </x-ui.card.header>
                <x-ui.card.body>
                    <dl class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-24 flex-shrink-0">{{ __('Email') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0">
                                @if($company->email)
                                    <a href="mailto:{{ $company->email }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        {{ $company->email }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </dd>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-24 flex-shrink-0">{{ __('Phone') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0">
                                @if($company->phone)
                                    <a href="tel:{{ $company->phone }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        {{ $company->phone }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </dd>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-24 flex-shrink-0">{{ __('Address') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0">{{ $company->address ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-24 flex-shrink-0">{{ __('Owner') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0">
                                <div class="flex items-center">
                                    <x-ui.avatar :name="$company->user->name" size="sm" class="mr-2" />
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ $company->user->name }}
                                        </div>
                                        <a href="mailto:{{ $company->user->email }}" class="text-xs text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">{{ $company->user->email }}</a>
                                    </div>
                                </div>
                            </dd>
                        </div>
                    </dl>
                </x-ui.card.body>
            </x-ui.card.base>
        </div>

        {{-- Company Folders --}}
        @if($folders->count() > 0)
            <x-ui.card.base id="document-folders">
                <x-ui.card.header>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Document Folders') }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Document folders organized by category and purpose') }}</p>
                </x-ui.card.header>
                <x-ui.card.body>
                    <x-ui.table.base>
                        <x-slot name="head">
                            <x-ui.table.head-cell>{{ __('Folder') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Files') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Size') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Last Modified') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Status') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell align="right">{{ __('Actions') }}</x-ui.table.head-cell>
                        </x-slot>
                        <x-slot name="body">
                            @foreach($folders as $folder)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <x-ui.table.cell>
                                        <a href="{{ route('accountant.companies.folders.show', ['company' => $company->id, 'folder' => $folder->id]) }}" class="flex items-center hover:text-blue-600 dark:hover:text-blue-400 group">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-yellow-500 group-hover:text-yellow-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $folder->name }}</div>
                                                @if($folder->description)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $folder->description }}</div>
                                                @endif
                                            </div>
                                        </a>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $folder->files->count() }}</span>
                                            <svg class="h-4 w-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <span class="text-sm text-gray-900 dark:text-gray-100">{{ $folder->totalSize() }}</span>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        @if($folder->lastModified())
                                            <span class="text-sm text-gray-500 dark:text-gray-400" title="{{ $folder->lastModified()->format('F j, Y H:i') }}">{{ $folder->lastModified()->diffForHumans() }}</span>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        @if($folder->is_public)
                                            <x-ui.badge variant="success">{{ __('Public') }}</x-ui.badge>
                                        @else
                                            <x-ui.badge variant="secondary">{{ __('Private') }}</x-ui.badge>
                                        @endif
                                    </x-ui.table.cell>
                                    <x-ui.table.action-cell>
                                        <a href="{{ route('accountant.companies.folders.show', ['company' => $company->id, 'folder' => $folder->id]) }}"
                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                           title="{{ __('Browse folder') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7z" />
                                            </svg>
                                        </a>
                                    </x-ui.table.action-cell>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-ui.table.base>
                </x-ui.card.body>
            </x-ui.card.base>
        @else
            <x-ui.card.base>
                <x-ui.card.body>
                    <x-ui.table.empty-state>
                        <x-slot name="icon">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7z" />
                            </svg>
                        </x-slot>
                        <x-slot name="title">{{ __('No Folders Found') }}</x-slot>
                        <x-slot name="description">{{ __('This company has not created any folders yet. Once they start uploading documents, they will appear here.') }}</x-slot>
                    </x-ui.table.empty-state>
                </x-ui.card.body>
            </x-ui.card.base>
        @endif

        {{-- Company Metadata --}}
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('System Information') }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Account creation and update history') }}</p>
            </x-ui.card.header>
            <x-ui.card.body>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created On') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            <div class="font-medium">{{ $company->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $company->created_at->diffForHumans() }}</div>
                        </dd>
                    </div>
                    <div class="flex flex-col">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Last Updated') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            <div class="font-medium">{{ $company->updated_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $company->updated_at->diffForHumans() }}</div>
                        </dd>
                    </div>
                </dl>
            </x-ui.card.body>
        </x-ui.card.base>
    </div>
</x-accountant.layout>