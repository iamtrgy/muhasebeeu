<x-admin.layout 
    title="{{ __('Create Folder') }}"
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('admin.dashboard'), 'first' => true],
        ['title' => __('File Manager'), 'href' => route('admin.folders.index')],
        ['title' => __('Create Folder')]
    ]"
>
    <div class="space-y-6">
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Create New Folder') }}
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Create a new folder to organize your files.') }}
                </p>
            </x-ui.card.header>
            
            <form method="POST" action="{{ route('admin.folders.store') }}">
                @csrf
                
                <x-ui.card.body>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Folder Name -->
                        <div class="sm:col-span-2">
                            <x-ui.form.input 
                                name="name" 
                                label="{{ __('Folder Name') }}" 
                                :value="old('name')" 
                                required 
                                autofocus
                                placeholder="{{ __('Enter folder name...') }}"
                            />
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="sm:col-span-2">
                            <x-ui.form.textarea 
                                name="description" 
                                label="{{ __('Description') }}" 
                                rows="3"
                                placeholder="{{ __('Optional description for this folder...') }}"
                            >{{ old('description') }}</x-ui.form.textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Parent Folder -->
                        <div>
                            <x-ui.form.select 
                                name="parent_id" 
                                label="{{ __('Parent Folder') }}" 
                                :value="old('parent_id', request('parent_id'))"
                            >
                                <option value="">{{ __('None (Root Folder)') }}</option>
                                @foreach($folders as $folder)
                                    <option value="{{ $folder->id }}" {{ (old('parent_id', request('parent_id')) == $folder->id) ? 'selected' : '' }}>
                                        {{ str_repeat('— ', $folder->depth ?? 0) }}{{ $folder->name }}
                                    </option>
                                @endforeach
                            </x-ui.form.select>
                            @error('parent_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Template Folder -->
                        <div>
                            <x-ui.form.select 
                                name="template_folder_id" 
                                label="{{ __('Copy from Template') }}" 
                                :value="old('template_folder_id')"
                            >
                                <option value="">{{ __('None (Empty Folder)') }}</option>
                                @if(isset($templateFolders))
                                    @foreach($templateFolders as $template)
                                        <option value="{{ $template->id }}" {{ old('template_folder_id') == $template->id ? 'selected' : '' }}>
                                            {{ $template->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-ui.form.select>
                            @error('template_folder_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Folder Settings -->
                        <div class="sm:col-span-2 space-y-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Folder Settings') }}</h4>
                            
                            <!-- Public/Private -->
                            <div class="flex items-center">
                                <x-ui.form.checkbox 
                                    name="is_public" 
                                    id="is_public"
                                    :checked="old('is_public', false)"
                                />
                                <label for="is_public" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                    {{ __('Make this folder public') }}
                                    <span class="text-xs text-gray-500 dark:text-gray-400 block">{{ __('Public folders are visible to all users') }}</span>
                                </label>
                            </div>

                            <!-- Allow Uploads -->
                            <div class="flex items-center">
                                <x-ui.form.checkbox 
                                    name="allow_uploads" 
                                    id="allow_uploads"
                                    :checked="old('allow_uploads', true)"
                                />
                                <label for="allow_uploads" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                    {{ __('Allow file uploads') }}
                                    <span class="text-xs text-gray-500 dark:text-gray-400 block">{{ __('Users can upload files to this folder') }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Assigned Users -->
                        @if(isset($users) && $users->count() > 0)
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                {{ __('Assign Users') }}
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 max-h-48 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                                @foreach($users as $user)
                                    <div class="flex items-center">
                                        <input 
                                            type="checkbox" 
                                            name="user_ids[]" 
                                            value="{{ $user->id }}" 
                                            id="user_{{ $user->id }}"
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                            {{ in_array($user->id, old('user_ids', [])) ? 'checked' : '' }}
                                        >
                                        <label for="user_{{ $user->id }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $user->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('user_ids')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        @endif
                    </div>
                </x-ui.card.body>

                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800 text-right sm:px-6 border-t border-gray-200 dark:border-gray-700">
                    <x-ui.button.secondary href="{{ route('admin.folders.index') }}">
                        {{ __('Cancel') }}
                    </x-ui.button.secondary>
                    <x-ui.button.primary type="submit" class="ml-3">
                        {{ __('Create Folder') }}
                    </x-ui.button.primary>
                </div>
            </form>
        </x-ui.card.base>
    </div>
</x-admin.layout>