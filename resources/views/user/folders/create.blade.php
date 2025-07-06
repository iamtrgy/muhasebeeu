<x-user.layout 
    title="{{ __('Create New Folder') }}" 
    :breadcrumbs="[
        ['title' => __('Home'), 'href' => route('user.dashboard'), 'first' => true],
        ['title' => __('My Folders'), 'href' => route('user.folders.index')],
        ['title' => __('Create Folder')]
    ]"
>
    <div class="space-y-6">
        @if(session('success'))
            <x-ui.alert variant="success">{{ session('success') }}</x-ui.alert>
        @endif

        @if(session('error'))
            <x-ui.alert variant="danger">{{ session('error') }}</x-ui.alert>
        @endif

        <x-ui.card.base>
            <x-ui.card.header>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Create New Folder') }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Create a new folder to organize your files') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('New Folder') }}</span>
                    </div>
                </div>
            </x-ui.card.header>
            <x-ui.card.body>
                <form method="POST" action="{{ route('user.folders.store') }}" class="space-y-6">
                    @csrf
                    
                    <x-ui.form.group>
                        <x-ui.form.input 
                            name="name" 
                            label="{{ __('Folder Name') }}" 
                            value="{{ old('name') }}" 
                            required
                            :error="$errors->first('name')"
                            placeholder="{{ __('Enter folder name') }}"
                        />
                    </x-ui.form.group>

                    <x-ui.form.group>
                        <x-ui.form.textarea 
                            name="description" 
                            label="{{ __('Description') }}" 
                            value="{{ old('description') }}" 
                            :error="$errors->first('description')"
                            placeholder="{{ __('Optional description for this folder') }}"
                            rows="3"
                        />
                    </x-ui.form.group>

                    <x-ui.form.group>
                        <x-ui.form.select 
                            name="company_id" 
                            label="{{ __('Associate with Company (Optional)') }}" 
                            :error="$errors->first('company_id')"
                            help="{{ __('Linking a folder to a company allows your accountant to see it on the company detail page.') }}"
                        >
                            <option value="">{{ __('None') }}</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </x-ui.form.select>
                    </x-ui.form.group>

                    <x-ui.form.group>
                        <x-ui.form.select 
                            name="parent_id" 
                            label="{{ __('Parent Folder') }}" 
                            :error="$errors->first('parent_id')"
                        >
                            <option value="">{{ __('None (Root Folder)') }}</option>
                            @foreach($folders as $folder)
                                <option value="{{ $folder->id }}" {{ old('parent_id') == $folder->id ? 'selected' : '' }}>
                                    {{ $folder->name }}
                                </option>
                            @endforeach
                        </x-ui.form.select>
                    </x-ui.form.group>

                    <x-ui.form.group>
                        <x-ui.form.checkbox 
                            name="is_public" 
                            label="{{ __('Make this folder public') }}" 
                            value="1" 
                            :checked="old('is_public')"
                        />
                    </x-ui.form.group>

                    <div class="flex justify-end gap-3">
                        <x-ui.button.secondary :href="route('user.folders.index')">
                            {{ __('Cancel') }}
                        </x-ui.button.secondary>
                        <x-ui.button.primary type="submit">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('Create Folder') }}
                        </x-ui.button.primary>
                    </div>
                </form>
            </x-ui.card.body>
        </x-ui.card.base>
    </div>
</x-user.layout> 
