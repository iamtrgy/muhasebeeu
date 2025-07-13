<x-user.layout 
    title="{{ __('Documents') }}" 
    :breadcrumbs="[
        ['title' => __('Home'), 'href' => route('user.dashboard')],
        ['title' => __('Documents'), 'active' => true]
    ]"
>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ __('Company Documents') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Manage all your company documents for') }} {{ $company->name }}
                        </p>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $totalDocuments }} {{ __('total documents') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Categories Sidebar -->
            <div class="lg:col-span-1">
                <x-ui.card.base>
                    <x-ui.card.header>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            {{ __('Categories') }}
                        </h3>
                    </x-ui.card.header>
                    <x-ui.card.body class="p-0">
                        <nav class="space-y-1">
                            <a href="{{ route('user.documents.index') }}" 
                               class="group flex items-center justify-between px-4 py-3 text-sm font-medium rounded-md transition-colors
                                   {{ !$selectedCategory ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <span class="flex items-center">
                                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    {{ __('All Documents') }}
                                </span>
                                <span class="text-gray-400 dark:text-gray-500">{{ $totalDocuments }}</span>
                            </a>
                            
                            @foreach($categories as $category)
                                <a href="{{ route('user.documents.index', ['category' => $category->name]) }}" 
                                   class="group flex items-center justify-between px-4 py-3 text-sm font-medium rounded-md transition-colors
                                       {{ $selectedCategory === $category->name ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                    <span class="flex items-center">
                                        @if($category->name === 'Contracts')
                                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        @elseif($category->name === 'Tax Documents')
                                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        @elseif($category->name === 'Legal')
                                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                            </svg>
                                        @else
                                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                        {{ $category->name }}
                                    </span>
                                    <span class="text-gray-400 dark:text-gray-500">{{ $category->files_count }}</span>
                                </a>
                            @endforeach
                        </nav>
                        
                        @if($selectedFolder)
                            <div class="mt-4 px-4">
                                <x-ui.button.primary 
                                    href="{{ route('user.folders.show', $selectedFolder) }}"
                                    target="_blank"
                                    class="w-full justify-center"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    {{ __('Upload Document') }}
                                </x-ui.button.primary>
                            </div>
                        @endif
                    </x-ui.card.body>
                </x-ui.card.base>
            </div>

            <!-- Documents List -->
            <div class="lg:col-span-3">
                <x-ui.card.base>
                    <x-ui.card.header>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            {{ $selectedCategory ? $selectedCategory : __('All Documents') }}
                        </h3>
                    </x-ui.card.header>
                    <x-ui.card.body>
                        @if($selectedCategory && $files->count() > 0)
                            <div class="space-y-3">
                                @foreach($files as $file)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $file->original_name }}
                                                </h4>
                                                <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
                                                    <span>{{ __('Uploaded') }} {{ $file->created_at->diffForHumans() }}</span>
                                                    @if($file->size)
                                                        <span>{{ number_format($file->size / 1024 / 1024, 2) }} MB</span>
                                                    @endif
                                                    <span>{{ __('by') }} {{ $file->uploader->name ?? 'Unknown' }}</span>
                                                </div>
                                                @if($file->notes)
                                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                                        {{ Str::limit($file->notes, 150) }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="flex items-center space-x-2 ml-4">
                                                <a href="{{ route('user.files.preview', $file) }}" 
                                                   class="p-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                                   title="{{ __('Preview') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('user.files.download', $file) }}" 
                                                   class="p-2 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300"
                                                   title="{{ __('Download') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                
                                {{ $files->links() }}
                            </div>
                        @elseif($selectedCategory)
                            <x-ui.table.empty-state>
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('No documents in this category.') }}
                                </p>
                                @if($selectedFolder)
                                    <x-ui.button.primary 
                                        href="{{ route('user.folders.show', $selectedFolder) }}"
                                        target="_blank"
                                        size="sm"
                                        class="mt-4"
                                    >
                                        {{ __('Upload First Document') }}
                                    </x-ui.button.primary>
                                @endif
                            </x-ui.table.empty-state>
                        @else
                            <!-- Recent Documents for All Documents view -->
                            @if($recentDocuments->count() > 0)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">
                                        {{ __('Recent Documents') }}
                                    </h4>
                                    <div class="space-y-3">
                                        @foreach($recentDocuments as $file)
                                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $file->original_name }}
                                                        </h4>
                                                        <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                                                {{ $file->folder->name ?? 'Unknown' }}
                                                            </span>
                                                            <span>{{ $file->created_at->diffForHumans() }}</span>
                                                            @if($file->size)
                                                                <span>{{ number_format($file->size / 1024 / 1024, 2) }} MB</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center space-x-2 ml-4">
                                                        <a href="{{ route('user.files.preview', $file) }}" 
                                                           class="p-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                                           title="{{ __('Preview') }}">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                        </a>
                                                        <a href="{{ route('user.files.download', $file) }}" 
                                                           class="p-2 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300"
                                                           title="{{ __('Download') }}">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <x-ui.table.empty-state>
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('No documents uploaded yet.') }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                        {{ __('Select a category to upload your first document.') }}
                                    </p>
                                </x-ui.table.empty-state>
                            @endif
                        @endif
                    </x-ui.card.body>
                </x-ui.card.base>
            </div>
        </div>
    </div>
</x-user.layout>