<x-accountant.layout 
    title="Dashboard"
    :breadcrumbs="[['title' => __('Dashboard'), 'first' => true]]"
>
<div x-data="{ fileData: null }">
    <div class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Users Count Card -->
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 flex-1">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('Assigned Users') }}
                            </div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $usersCount }}
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('accountant.companies.index') }}" class="inline-flex items-center justify-center w-full px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-white transition ease-in-out duration-150 bg-indigo-600 border border-transparent rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            {{ __('View companies') }}
                        </a>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Companies Count Card -->
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-5 flex-1">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('Assigned Companies') }}
                            </div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $companiesCount }}
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('accountant.companies.index') }}" class="inline-flex items-center justify-center w-full px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-white transition ease-in-out duration-150 bg-indigo-600 border border-transparent rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            {{ __('View all companies') }}
                        </a>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Tasks Pending Review Card -->
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <div class="ml-5 flex-1">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('Tasks Pending Review') }}
                            </div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $pendingReviewCount ?? 0 }}
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('accountant.tax-calendar.reviews') }}" class="inline-flex items-center justify-center w-full px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-white transition ease-in-out duration-150 bg-indigo-600 border border-transparent rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            {{ __('View pending reviews') }}
                        </a>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Recent Users -->
            <x-ui.card.base>
                <x-ui.card.header>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Recent Users') }}</h3>
                </x-ui.card.header>
                <x-ui.card.body>
                    @if($recentUsers->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentUsers as $user)
                                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <div class="flex items-center">
                                        <x-ui.avatar size="md" name="{{ $user->name }}" class="flex-shrink-0" />
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                    @php
                                        $company = $user->companies->first();
                                    @endphp
                                    @if($company)
                                        <a href="{{ route('accountant.companies.show', $company) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-gray-700 transition ease-in-out duration-150 bg-white border border-gray-300 rounded shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                                            {{ __('View Company') }}
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-500">{{ __('No company') }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('No users assigned yet.') }}</p>
                        </div>
                    @endif
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Recent Companies -->
            <x-ui.card.base>
                <x-ui.card.header>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Recent Companies') }}</h3>
                </x-ui.card.header>
                <x-ui.card.body>
                    @if($recentCompanies->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentCompanies as $company)
                                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <div class="flex items-center">
                                        <x-ui.avatar size="md" name="{{ $company->name }}" class="flex-shrink-0" />
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $company->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $company->user->name }}</div>
                                        </div>
                                    </div>
                                    <a href="{{ route('accountant.companies.show', $company) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-gray-700 transition ease-in-out duration-150 bg-white border border-gray-300 rounded shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                                        {{ __('View') }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('No companies assigned yet.') }}</p>
                        </div>
                    @endif
                </x-ui.card.body>
            </x-ui.card.base>
        </div>

        <!-- Recent Files Card -->
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Recent Files') }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Latest files from your assigned users') }} ({{ $recentFiles->count() }} files)</p>
            </x-ui.card.header>
            <x-ui.card.body>
                @if($recentFiles->count() > 0)
                    <x-ui.table.base>
                        <x-slot name="head">
                            <x-ui.table.head-cell>{{ __('File Name') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Folder') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Company') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Notes') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Date') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell align="right">{{ __('Actions') }}</x-ui.table.head-cell>
                        </x-slot>
                        <x-slot name="body">
                            @foreach($recentFiles as $file)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <x-ui.table.cell>
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                @if(in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain']))
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 cursor-pointer" 
                                                         x-on:click="
                                                             $dispatch('file-preview-data', {
                                                                 name: '{{ $file->original_name }}',
                                                                 type: '{{ $file->mime_type }}',
                                                                 previewUrl: '{{ route('accountant.files.preview', $file) }}',
                                                                 downloadUrl: '{{ route('accountant.files.download', $file) }}',
                                                                 notes: '{{ addslashes($file->notes ?? '') }}'
                                                             });
                                                             $dispatch('open-modal', 'file-preview')
                                                         ">
                                                        {{ $file->original_name }}
                                                    </div>
                                                @else
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $file->original_name }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        @if($file->folder)
                                            @php
                                                $company = $file->folder->creator?->companies?->first();
                                            @endphp
                                            @if($company)
                                                <a href="{{ route('accountant.companies.folders.show', ['company' => $company, 'folder' => $file->folder]) }}" class="text-sm text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                                                    {{ $file->folder->name }}
                                                </a>
                                            @else
                                                <span class="text-sm text-gray-900 dark:text-gray-100">{{ $file->folder->name }}</span>
                                            @endif
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('(No folder)') }}</span>
                                        @endif
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        @php
                                            $company = $file->folder?->creator?->companies?->first() ?? $file->uploader?->companies?->first();
                                        @endphp
                                        @if($company)
                                            <a href="{{ route('accountant.companies.show', $company) }}" class="text-sm text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                                                {{ $company->name }}
                                            </a>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('N/A') }}</span>
                                        @endif
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <x-ui.table.editable-cell 
                                            :value="$file->notes ?? ''"
                                            placeholder="Add notes..."
                                            :route="route('accountant.files.update-notes', $file->id)"
                                            field="notes"
                                            type="textarea"
                                            :maxLength="1000"
                                            :file="[
                                                'original_name' => $file->original_name,
                                                'mime_type' => $file->mime_type,
                                                'preview_url' => route('accountant.files.preview', $file),
                                                'download_url' => route('accountant.files.download', $file)
                                            ]"
                                        />
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $file->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $file->created_at->diffForHumans() }}
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.action-cell>
                                        @if(in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain']))
                                            <button type="button" 
                                                 class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                                 title="{{ __('Preview') }}"
                                                 x-on:click="
                                                     $dispatch('file-preview-data', {
                                                         name: '{{ $file->original_name }}',
                                                         type: '{{ $file->mime_type }}',
                                                         previewUrl: '{{ route('accountant.files.preview', $file) }}',
                                                         downloadUrl: '{{ route('accountant.files.download', $file) }}'
                                                     });
                                                     $dispatch('open-modal', 'file-preview')
                                                 ">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        @endif
                                        <a href="{{ route('accountant.files.download', $file) }}" 
                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                           title="{{ __('Download') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </a>
                                    </x-ui.table.action-cell>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-ui.table.base>
                @else
                    <x-ui.table.empty-state>
                        <x-slot name="icon">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </x-slot>
                        <x-slot name="title">{{ __('No Files Found') }}</x-slot>
                        <x-slot name="description">
                            @if($usersCount > 0)
                                {{ __('No files found from your assigned users yet. Files will appear here once your users start uploading documents.') }}
                            @else
                                {{ __('You don\'t have any assigned users yet. Contact your administrator to assign users to your account.') }}
                            @endif
                        </x-slot>
                    </x-ui.table.empty-state>
                @endif
            </x-ui.card.body>
        </x-ui.card.base>
    </div>
    
    {{-- Single File Preview Modal --}}
    <x-ui.modal.base name="file-preview" maxWidth="4xl">
        <div class="text-center" x-data="{ currentFile: null }" 
             x-on:file-preview-data.window="currentFile = $event.detail">
            <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100" x-text="currentFile?.name || 'File Preview'"></h3>
            
            {{-- Image Preview --}}
            <div x-show="currentFile && ['image/jpeg', 'image/png', 'image/gif'].includes(currentFile.type)">
                <div class="max-w-full max-h-96 overflow-auto">
                    <img x-bind:src="currentFile?.previewUrl" x-bind:alt="currentFile?.name" class="max-w-full h-auto rounded-lg">
                </div>
            </div>

            {{-- PDF Preview --}}
            <div x-show="currentFile && currentFile.type === 'application/pdf'">
                <div class="w-full h-96">
                    <iframe x-bind:src="currentFile?.previewUrl" class="w-full h-full border-0 rounded-lg"></iframe>
                </div>
            </div>

            {{-- Text Preview --}}
            <div x-show="currentFile && currentFile.type === 'text/plain'">
                <div class="max-w-full max-h-96 overflow-auto bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('Text file preview') }}</p>
                        <iframe x-bind:src="currentFile?.previewUrl" class="w-full h-64 border border-gray-200 rounded-lg"></iframe>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-center gap-3">
                <x-ui.button.primary x-bind:href="currentFile?.downloadUrl">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('Download') }}
                </x-ui.button.primary>
                <x-ui.button.secondary x-on:click="$dispatch('close-modal', 'file-preview')">
                    {{ __('Close') }}
                </x-ui.button.secondary>
            </div>
        </div>
    </x-ui.modal.base>
</div>
</x-accountant.layout>