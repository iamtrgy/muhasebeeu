<x-admin-layout>
    <x-slot name="header">
        <x-admin.page-title title="{{ __('Edit Folder:') }} {{ $folder->name }}"></x-admin.page-title>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Folder Quick Info -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-xl font-semibold text-gray-900">{{ $folder->name }}</div>
                                <div class="mt-1 text-sm text-gray-600">
                                    Created by <span class="font-medium">{{ $folder->creator->name }}</span> on {{ $folder->created_at->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="px-3 py-1 text-sm rounded-full {{ $folder->is_public ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $folder->is_public ? 'Public' : 'Private' }}
                            </span>
                            <span class="px-3 py-1 text-sm rounded-full {{ $folder->allow_uploads ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $folder->allow_uploads ? 'Uploads Allowed' : 'Uploads Disabled' }}
                            </span>
                            @if($folder->templateFolder)
                                <span class="px-3 py-1 text-sm rounded-full bg-purple-100 text-purple-800">Template Copy</span>
                            @endif
                            @if($folder->derivedFolders()->exists())
                                <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">Template</span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-600 mr-2">Currently assigned to:</span>
                            @if($folder->users->isNotEmpty())
                                <div class="flex flex-wrap gap-2">
                                    @foreach($folder->users->take(3) as $user)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $user->name }}
                                        </span>
                                    @endforeach
                                    @if($folder->users->count() > 3)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            +{{ $folder->users->count() - 3 }} more
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-sm text-gray-500">No users assigned</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('admin.folders.update', $folder) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        @if($folder->templateFolder)
                            <div class="rounded-md bg-blue-50 p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Template Folder Copy</h3>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <p>This is a personal copy created from the template folder "{{ $folder->templateFolder->name }}".</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($folder->derivedFolders()->exists())
                            <div class="rounded-md bg-yellow-50 p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">Template Folder</h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <p>This is a template folder. Changes to name, description, and permissions will be applied to all {{ $folder->derivedFolders()->count() }} user copies.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div>
                            <x-input-label for="name" :value="__('Folder Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $folder->name)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('description', $folder->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="parent_id" :value="__('Parent Folder')" />
                            <select id="parent_id" name="parent_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm" style="text-overflow: ellipsis;">
                                <option value="" title="{{ __('None (Root Level)') }}">{{ __('None (Root Level)') }}</option>
                                @foreach($folders as $f)
                                    @if($f->id !== $folder->id)
                                        <option value="{{ $f->id }}" 
                                            {{ old('parent_id', $folder->parent_id) == $f->id ? 'selected' : '' }} 
                                            title="{{ $f->name }} ({{ $f->parent ? __('in :parent', ['parent' => $f->parent->name]) : __('Root Level') }})">
                                            {{ $f->name }} ({{ $f->parent ? __('in :parent', ['parent' => $f->parent->name]) : __('Root Level') }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @if($folder->derivedFolders()->exists())
                                <p class="mt-1 text-sm text-blue-600">{{ __('Parent folder changes will be applied to all derived folders.') }}</p>
                            @endif
                            <x-input-error class="mt-2" :messages="$errors->get('parent_id')" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="is_public" :value="__('Visibility')" />
                            <label class="inline-flex items-center mt-2">
                                <input type="checkbox" name="is_public" id="is_public" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" value="1" {{ $folder->is_public ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Make this folder public') }}</span>
                            </label>
                            @if($folder->derivedFolders()->exists())
                                <p class="mt-1 text-sm text-blue-600">{{ __('This setting will be applied to all derived folders.') }}</p>
                            @endif
                        </div>

                        <div class="mt-4">
                            <x-input-label for="allow_uploads" :value="__('Upload Permission')" />
                            <label class="inline-flex items-center mt-2">
                                <input type="checkbox" name="allow_uploads" id="allow_uploads" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" value="1" {{ $folder->allow_uploads ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Allow users to upload files to this folder') }}</span>
                            </label>
                        </div>

                        <div>
                            <x-input-label :value="__('Assign to Users')" />
                            @if($folder->derivedFolders()->exists())
                                <div class="mt-2 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">{{ __('User assignments cannot be modified for template folders.') }}</p>
                                            <p class="mt-1 text-sm text-yellow-600">{{ __('Each derived folder maintains its own user assignments.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="mt-4 space-y-2">
                                    <div class="flex items-center mb-3">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" id="selectAll" 
                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                                            <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ __('Assign to all users') }}
                                            </span>
                                        </label>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                        @foreach($users as $user)
                                            <label class="inline-flex items-center p-2 rounded-md hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150 ease-in-out">
                                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" 
                                                    class="user-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out" 
                                                    {{ in_array($user->id, old('user_ids', $folder->users->pluck('id')->toArray())) ? 'checked' : '' }}
                                                    title="{{ $user->name }} ({{ $user->email }})">
                                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">
                                                    {{ $user->name }}
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">({{ $user->email }})</span>
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                {{ __('Update Folder') }}
                            </button>
                            <a href="{{ route('admin.folders.show', $folder) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                {{ __('Cancel') }}
                            </a>
                        </div>
                        
                        @if($folder->derivedFolders()->exists())
                            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                                <p class="text-sm text-yellow-700">
                                    <span class="font-medium">{{ __('Note:') }}</span> 
                                    {{ __('Changes to this template folder will affect all :count derived folders.', ['count' => $folder->derivedFolders()->count()]) }}
                                </p>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const nameInput = document.getElementById('name');
            const selectAllCheckbox = document.getElementById('selectAll');
            const userCheckboxes = document.querySelectorAll('.user-checkbox');
            const parentSelect = document.getElementById('parent_id');
            const submitButton = form.querySelector('button[type="submit"]');
            let debounceTimeout;

            // Clear existing error messages
            function clearErrors(input) {
                const existingError = input.parentNode.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }
                input.classList.remove('border-red-500');
            }

            // Show error message
            function showError(input, message) {
                clearErrors(input);
                input.classList.add('border-red-500');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message mt-2 text-sm text-red-600 dark:text-red-400';
                errorDiv.innerHTML = message;
                input.parentNode.appendChild(errorDiv);
            }

            // Validate folder name with debounce
            nameInput.addEventListener('input', function() {
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(() => {
                    clearErrors(this);
                    if (!this.value.trim()) {
                        showError(this, 'Folder name is required.');
                    } else if (this.value.length > 255) {
                        showError(this, 'Folder name cannot exceed 255 characters.');
                    }
                }, 300);
            });

            // Form validation
            form.addEventListener('submit', function(e) {
                let isValid = true;

                // Validate folder name
                clearErrors(nameInput);
                if (!nameInput.value.trim()) {
                    isValid = false;
                    showError(nameInput, 'Folder name is required.');
                } else if (nameInput.value.length > 255) {
                    isValid = false;
                    showError(nameInput, 'Folder name cannot exceed 255 characters.');
                }

                // Prevent form submission if validation fails
                if (!isValid) {
                    e.preventDefault();
                    nameInput.focus();
                    return;
                }

                // Show loading state
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Updating...</span>
                `;
            });

            // Handle select all checkbox
            if (selectAllCheckbox) {
                function updateSelectAllState() {
                    const allChecked = Array.from(userCheckboxes).every(cb => cb.checked);
                    const someChecked = Array.from(userCheckboxes).some(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = someChecked && !allChecked;
                }

                // Set initial state
                updateSelectAllState();

                // Handle select all changes
                selectAllCheckbox.addEventListener('change', function() {
                    userCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateSelectAllState();
                });

                // Handle individual checkbox changes
                userCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateSelectAllState);
                });
            }

            // Handle parent folder selection
            if (parentSelect) {
                parentSelect.dataset.currentId = '{{ $folder->id }}';
                parentSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption.value === this.dataset.currentId) {
                        showError(this, 'A folder cannot be its own parent.');
                        this.value = '';
                    }
                });
            }
        });
    </script>
    @endpush
</x-admin-layout>
