@props([
    'files' => collect(),
    'folders' => collect(),
    'currentPath' => '/',
    'breadcrumbs' => [],
    'viewMode' => 'grid', // grid, list
    'selectable' => true,
    'uploadable' => true,
    'createFolder' => true,
    'onFileClick' => null,
    'onFolderClick' => null,
    'loading' => false,
])

@php
$browserId = 'file-browser-' . uniqid();
@endphp

<div 
    x-data="{
        viewMode: '{{ $viewMode }}',
        selected: [],
        selectAll: false,
        showUploadModal: false,
        showCreateFolderModal: false,
        newFolderName: '',
        toggleSelectAll() {
            this.selectAll = !this.selectAll;
            this.selected = this.selectAll 
                ? [...@json($folders->pluck('id')->toArray()), ...@json($files->pluck('id')->toArray())]
                : [];
        },
        toggleSelect(id) {
            const index = this.selected.indexOf(id);
            if (index === -1) {
                this.selected.push(id);
            } else {
                this.selected.splice(index, 1);
            }
        },
        isSelected(id) {
            return this.selected.includes(id);
        },
        createFolder() {
            if (this.newFolderName.trim()) {
                // Submit form or call API
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('folders.create') }}';
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'name';
                input.value = this.newFolderName;
                
                const pathInput = document.createElement('input');
                pathInput.type = 'hidden';
                pathInput.name = 'path';
                pathInput.value = '{{ $currentPath }}';
                
                const token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = '{{ csrf_token() }}';
                
                form.appendChild(input);
                form.appendChild(pathInput);
                form.appendChild(token);
                document.body.appendChild(form);
                form.submit();
            }
        }
    }"
    class="w-full"
    id="{{ $browserId }}"
>
    {{-- Toolbar --}}
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 py-3">
        <div class="flex items-center justify-between">
            {{-- Breadcrumbs --}}
            <x-ui.breadcrumb.base>
                @foreach($breadcrumbs as $crumb)
                    <x-ui.breadcrumb.item 
                        :href="$crumb['url'] ?? '#'" 
                        :active="$loop->last"
                    >
                        @if($loop->first)
                            <x-slot name="icon">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </x-slot>
                        @endif
                        {{ $crumb['label'] }}
                    </x-ui.breadcrumb.item>
                @endforeach
            </x-ui.breadcrumb.base>

            {{-- Actions --}}
            <div class="flex items-center gap-2">
                {{-- View Mode Toggle --}}
                <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg">
                    <button
                        @click="viewMode = 'grid'"
                        :class="viewMode === 'grid' ? 'bg-gray-100 dark:bg-gray-700' : ''"
                        class="p-2 rounded-l-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                        title="Grid view"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </button>
                    <button
                        @click="viewMode = 'list'"
                        :class="viewMode === 'list' ? 'bg-gray-100 dark:bg-gray-700' : ''"
                        class="p-2 rounded-r-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                        title="List view"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                {{-- Selected Actions --}}
                <div x-show="selected.length > 0" x-cloak class="flex items-center gap-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        <span x-text="selected.length"></span> selected
                    </span>
                    
                    <x-ui.button.secondary size="sm">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download
                    </x-ui.button.secondary>
                    
                    <x-ui.button.danger size="sm">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </x-ui.button.danger>
                </div>

                {{-- Main Actions --}}
                @if($createFolder)
                    <x-ui.button.secondary size="sm" @click="showCreateFolderModal = true">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        </svg>
                        New Folder
                    </x-ui.button.secondary>
                @endif

                @if($uploadable)
                    <x-ui.button.primary size="sm" @click="showUploadModal = true">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        Upload
                    </x-ui.button.primary>
                @endif
            </div>
        </div>
    </div>

    {{-- File Browser Content --}}
    <div class="bg-gray-50 dark:bg-gray-900 min-h-[500px] relative">
        @if($loading)
            <div class="absolute inset-0 flex items-center justify-center bg-white/50 dark:bg-gray-900/50 z-10">
                <x-ui.spinner size="lg" text="Loading files..." />
            </div>
        @endif

        {{-- Grid View --}}
        <div x-show="viewMode === 'grid'" class="p-4">
            @if($folders->isEmpty() && $files->isEmpty())
                <div class="flex flex-col items-center justify-center py-12">
                    <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">This folder is empty</p>
                    @if($uploadable)
                        <x-ui.button.primary size="sm" class="mt-4" @click="showUploadModal = true">
                            Upload Files
                        </x-ui.button.primary>
                    @endif
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    {{-- Folders --}}
                    @foreach($folders as $folder)
                        <div 
                            @if($selectable)
                                @click="toggleSelect('{{ $folder->id }}')"
                            @else
                                @click="{{ $onFolderClick }}('{{ $folder->id }}')"
                            @endif
                            :class="{ 'ring-2 ring-indigo-500': isSelected('{{ $folder->id }}') }"
                            class="relative group cursor-pointer rounded-lg p-4 hover:bg-white dark:hover:bg-gray-800 transition-colors"
                        >
                            @if($selectable)
                                <div class="absolute top-2 left-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <input
                                        type="checkbox"
                                        :checked="isSelected('{{ $folder->id }}')"
                                        @click.stop="toggleSelect('{{ $folder->id }}')"
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    >
                                </div>
                            @endif
                            
                            <div class="flex flex-col items-center">
                                <svg class="h-12 w-12 text-blue-500 mb-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                </svg>
                                <p class="text-sm text-center text-gray-700 dark:text-gray-300 truncate w-full">
                                    {{ $folder->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $folder->items_count ?? 0 }} items
                                </p>
                            </div>
                        </div>
                    @endforeach

                    {{-- Files --}}
                    @foreach($files as $file)
                        <div 
                            @if($selectable)
                                @click="toggleSelect('{{ $file->id }}')"
                            @else
                                @click="{{ $onFileClick }}('{{ $file->id }}')"
                            @endif
                            :class="{ 'ring-2 ring-indigo-500': isSelected('{{ $file->id }}') }"
                            class="relative group cursor-pointer rounded-lg p-4 hover:bg-white dark:hover:bg-gray-800 transition-colors"
                        >
                            @if($selectable)
                                <div class="absolute top-2 left-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <input
                                        type="checkbox"
                                        :checked="isSelected('{{ $file->id }}')"
                                        @click.stop="toggleSelect('{{ $file->id }}')"
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    >
                                </div>
                            @endif
                            
                            <div class="flex flex-col items-center">
                                @if(in_array($file->extension, ['jpg', 'jpeg', 'png', 'gif', 'svg']))
                                    <img 
                                        src="{{ $file->thumbnail_url ?? $file->url }}" 
                                        alt="{{ $file->name }}"
                                        class="h-12 w-12 object-cover rounded mb-2"
                                    >
                                @else
                                    <svg class="h-12 w-12 text-gray-400 mb-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                                <p class="text-sm text-center text-gray-700 dark:text-gray-300 truncate w-full">
                                    {{ $file->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $file->formatted_size ?? '0 KB' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- List View --}}
        <div x-show="viewMode === 'list'" x-cloak>
            <x-ui.table.base>
                <x-ui.table.header>
                    <x-ui.table.row>
                        @if($selectable)
                            <x-ui.table.head-cell class="w-4">
                                <input
                                    type="checkbox"
                                    x-model="selectAll"
                                    @change="toggleSelectAll()"
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                >
                            </x-ui.table.head-cell>
                        @endif
                        <x-ui.table.head-cell>Name</x-ui.table.head-cell>
                        <x-ui.table.head-cell>Size</x-ui.table.head-cell>
                        <x-ui.table.head-cell>Modified</x-ui.table.head-cell>
                        <x-ui.table.head-cell align="right">Actions</x-ui.table.head-cell>
                    </x-ui.table.row>
                </x-ui.table.header>
                <x-ui.table.body>
                    {{-- Folders --}}
                    @foreach($folders as $folder)
                        <x-ui.table.row>
                            @if($selectable)
                                <x-ui.table.cell>
                                    <input
                                        type="checkbox"
                                        :checked="isSelected('{{ $folder->id }}')"
                                        @change="toggleSelect('{{ $folder->id }}')"
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    >
                                </x-ui.table.cell>
                            @endif
                            <x-ui.table.cell>
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                    </svg>
                                    <a 
                                        href="#" 
                                        @click.prevent="{{ $onFolderClick }}('{{ $folder->id }}')"
                                        class="text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400"
                                    >
                                        {{ $folder->name }}
                                    </a>
                                </div>
                            </x-ui.table.cell>
                            <x-ui.table.cell>—</x-ui.table.cell>
                            <x-ui.table.cell>{{ $folder->updated_at->diffForHumans() }}</x-ui.table.cell>
                            <x-ui.table.action-cell>
                                <x-ui.dropdown.base align="right">
                                    <x-slot name="trigger">
                                        <button x-on:click="toggle()" class="text-gray-400 hover:text-gray-600">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <div class="py-1">
                                            <x-ui.dropdown.item href="#">Rename</x-ui.dropdown.item>
                                            <x-ui.dropdown.item href="#">Move</x-ui.dropdown.item>
                                            <x-ui.dropdown.divider />
                                            <x-ui.dropdown.item href="#" class="text-red-600">Delete</x-ui.dropdown.item>
                                        </div>
                                    </x-slot>
                                </x-ui.dropdown.base>
                            </x-ui.table.action-cell>
                        </x-ui.table.row>
                    @endforeach

                    {{-- Files --}}
                    @foreach($files as $file)
                        <x-ui.table.row>
                            @if($selectable)
                                <x-ui.table.cell>
                                    <input
                                        type="checkbox"
                                        :checked="isSelected('{{ $file->id }}')"
                                        @change="toggleSelect('{{ $file->id }}')"
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    >
                                </x-ui.table.cell>
                            @endif
                            <x-ui.table.cell>
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                    </svg>
                                    <a 
                                        href="#" 
                                        @click.prevent="{{ $onFileClick }}('{{ $file->id }}')"
                                        class="text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400"
                                    >
                                        {{ $file->name }}
                                    </a>
                                </div>
                            </x-ui.table.cell>
                            <x-ui.table.cell>{{ $file->formatted_size ?? '0 KB' }}</x-ui.table.cell>
                            <x-ui.table.cell>{{ $file->updated_at->diffForHumans() }}</x-ui.table.cell>
                            <x-ui.table.action-cell>
                                <x-ui.dropdown.base align="right">
                                    <x-slot name="trigger">
                                        <button x-on:click="toggle()" class="text-gray-400 hover:text-gray-600">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <div class="py-1">
                                            <x-ui.dropdown.item href="#">Download</x-ui.dropdown.item>
                                            <x-ui.dropdown.item href="#">Preview</x-ui.dropdown.item>
                                            <x-ui.dropdown.item href="#">Share</x-ui.dropdown.item>
                                            <x-ui.dropdown.divider />
                                            <x-ui.dropdown.item href="#">Rename</x-ui.dropdown.item>
                                            <x-ui.dropdown.item href="#">Move</x-ui.dropdown.item>
                                            <x-ui.dropdown.divider />
                                            <x-ui.dropdown.item href="#" class="text-red-600">Delete</x-ui.dropdown.item>
                                        </div>
                                    </x-slot>
                                </x-ui.dropdown.base>
                            </x-ui.table.action-cell>
                        </x-ui.table.row>
                    @endforeach

                    @if($folders->isEmpty() && $files->isEmpty())
                        <x-ui.table.empty-state :colspan="$selectable ? 5 : 4" message="This folder is empty">
                            @if($uploadable)
                                <x-ui.button.primary size="sm" @click="showUploadModal = true">
                                    Upload Files
                                </x-ui.button.primary>
                            @endif
                        </x-ui.table.empty-state>
                    @endif
                </x-ui.table.body>
            </x-ui.table.base>
        </div>
    </div>

    {{-- Create Folder Modal --}}
    <x-ui.modal.base name="create-folder-modal" x-show="showCreateFolderModal" @close="showCreateFolderModal = false">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Create New Folder</h3>
            
            <x-ui.form.input
                name="folder_name"
                label="Folder Name"
                placeholder="Enter folder name"
                x-model="newFolderName"
                @keydown.enter="createFolder()"
                required
            />
            
            <div class="mt-6 flex justify-end gap-3">
                <x-ui.button.secondary @click="showCreateFolderModal = false">
                    Cancel
                </x-ui.button.secondary>
                <x-ui.button.primary @click="createFolder()">
                    Create Folder
                </x-ui.button.primary>
            </div>
        </div>
    </x-ui.modal.base>

    {{-- Upload Modal --}}
    <x-ui.modal.base name="upload-modal" x-show="showUploadModal" @close="showUploadModal = false" maxWidth="xl">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Upload Files</h3>
            
            <div 
                class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center"
                @drop.prevent="handleFileDrop($event)"
                @dragover.prevent
                @dragenter.prevent
            >
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Drop files here or
                    <label for="file-upload" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 cursor-pointer">
                        browse
                    </label>
                </p>
                <input id="file-upload" name="files[]" type="file" class="sr-only" multiple>
            </div>
            
            <div class="mt-6 flex justify-end gap-3">
                <x-ui.button.secondary @click="showUploadModal = false">
                    Cancel
                </x-ui.button.secondary>
                <x-ui.button.primary>
                    Upload
                </x-ui.button.primary>
            </div>
        </div>
    </x-ui.modal.base>
</div>