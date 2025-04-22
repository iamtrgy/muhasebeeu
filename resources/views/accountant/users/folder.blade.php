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
                        
                        <a href="{{ route('accountant.users.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            {{ __('Users') }}
                        </a>
                        
                        <span class="mx-2 text-gray-400">/</span>
                        
                        <a href="{{ route('accountant.users.show', $user->id) }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            {{ $user->name }}
                        </a>
                        
                        @php
                            $breadcrumbs = collect([]);
                            $current = $folder;
                            
                            // Build the breadcrumb path
                            while ($current) {
                                $breadcrumbs->push($current);
                                $current = $current->parent;
                            }
                            
                            // Reverse to get the correct order
                            $breadcrumbs = $breadcrumbs->reverse();
                        @endphp
                        
                        @foreach ($breadcrumbs as $breadcrumb)
                            <span class="mx-2 text-gray-400">/</span>
                            
                            @if($breadcrumb->id === $folder->id)
                                <span class="text-gray-900 dark:text-gray-100">{{ $breadcrumb->name }}</span>
                            @else
                                <a href="{{ route('accountant.users.viewFolder', ['userId' => $user->id, 'folderId' => $breadcrumb->id]) }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                    {{ $breadcrumb->name }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- User Profile Card -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="sm:flex sm:items-center sm:justify-between">
                        <div class="sm:flex sm:items-center">
                            <div class="flex-shrink-0 h-20 w-20 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                <span class="text-2xl font-medium text-blue-800 dark:text-blue-200">{{ substr($user->name, 0, 2) }}</span>
                            </div>
                            <div class="mt-4 sm:mt-0 sm:ml-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Country') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->country->name ?? 'N/A' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created at') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->created_at->format('M d, Y') }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Documents') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $user->folders->count() }} {{ __('folders') }}, 
                                    {{ $user->folders->sum(function($folder) { return $folder->files->count(); }) }} {{ __('files') }}
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Companies') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $user->companies->count() }} {{ Str::plural('company', $user->companies->count()) }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Child Folders Section -->
            @if($childFolders->count() > 0)
                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Sub-folders') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Folders inside this directory') }}</p>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 dark:border-gray-700">
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

                            @foreach($childFolders as $childFolder)
                                <x-admin.table.tr>
                                    <x-admin.table.td>
                                        <a href="{{ route('accountant.users.viewFolder', ['userId' => $user->id, 'folderId' => $childFolder->id]) }}" class="flex items-center hover:text-blue-600 dark:hover:text-blue-400">
                                            <div class="flex-shrink-0">
                                                <x-icons name="folder" class="h-6 w-6 text-yellow-500" />
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $childFolder->name }}</div>
                                                @if($childFolder->description)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $childFolder->description }}</div>
                                                @endif
                                            </div>
                                        </a>
                                    </x-admin.table.td>
                                    <x-admin.table.td>{{ $childFolder->files->count() }}</x-admin.table.td>
                                    <x-admin.table.td>{{ $childFolder->totalSize() }}</x-admin.table.td>
                                    <x-admin.table.td>
                                        @if($childFolder->lastModified())
                                            <span title="{{ $childFolder->lastModified()->format('F j, Y H:i') }}">{{ $childFolder->lastModified()->diffForHumans() }}</span>
                                        @else
                                            -
                                        @endif
                                    </x-admin.table.td>
                                    <x-admin.table.td>
                                        @if($childFolder->is_public)
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
                    </div>
                </div>
            @endif

            <!-- Files Section -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Files') }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Documents stored in this folder') }}</p>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 dark:border-gray-700">
                    @if($files->count() > 0)
                        <x-admin.table>
                            <x-slot name="header">
                                <x-admin.table.tr>
                                    <x-admin.table.th>{{ __('File Name') }}</x-admin.table.th>
                                    <x-admin.table.th>{{ __('Size') }}</x-admin.table.th>
                                    <x-admin.table.th>{{ __('Type') }}</x-admin.table.th>
                                    <x-admin.table.th>{{ __('Uploaded') }}</x-admin.table.th>
                                    <x-admin.table.th class="relative text-right">
                                        <span class="sr-only">{{ __('Actions') }}</span>
                                    </x-admin.table.th>
                                </x-admin.table.tr>
                            </x-slot>

                            @foreach($files as $file)
                                <x-admin.table.tr>
                                    <x-admin.table.td>
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <x-icons name="file" class="h-6 w-6 text-blue-500" />
                                            </div>
                                            <div class="ml-4">
                                                @if(in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain']))
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 cursor-pointer" onclick="previewFile('{{ $file->original_name }}', '{{ $file->mime_type }}', '{{ route('accountant.files.preview', $file) }}')">
                                                        {{ $file->original_name }}
                                                    </div>
                                                @else
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $file->original_name }}
                                                    </div>
                                                @endif
                                                <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $file->id }}</div>
                                            </div>
                                        </div>
                                    </x-admin.table.td>
                                    <x-admin.table.td>{{ $file->human_readable_size }}</x-admin.table.td>
                                    <x-admin.table.td>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            {{ $file->mime_type }}
                                        </span>
                                    </x-admin.table.td>
                                    <x-admin.table.td>
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $file->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $file->created_at->diffForHumans() }}</div>
                                    </x-admin.table.td>
                                    <x-admin.table.td class="text-right">
                                        <div class="flex space-x-3 justify-end">
                                            <a href="{{ route('accountant.files.download', $file) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="{{ __('Download') }}">
                                                <x-icons name="download" />
                                            </a>
                                        </div>
                                    </x-admin.table.td>
                                </x-admin.table.tr>
                            @endforeach
                        </x-admin.table>
                    @else
                        <div class="py-8 text-center">
                            <x-icons name="file" class="mx-auto h-12 w-12 text-gray-400" />
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No Files') }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('This folder does not contain any files yet.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <x-folder.file-preview-modal />
</x-app-layout> 