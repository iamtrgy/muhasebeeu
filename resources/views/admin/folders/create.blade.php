<x-admin-layout>
    <x-slot name="header">
        <x-admin.page-title title="{{ __('Create New Folder') }}"></x-admin.page-title>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.folders.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Folder Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="parent_id" :value="__('Parent Folder')" />
                            <select id="parent_id" name="parent_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ __('None (Root Folder)') }}</option>
                                @foreach($folders as $folder)
                                    <option value="{{ $folder->id }}" {{ (old('parent_id', isset($parent) ? $parent->id : null) == $folder->id) ? 'selected' : '' }}>
                                        {{ $folder->name }}
                                        @if($folder->parent)
                                            (in {{ $folder->parent->name }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('parent_id')" />
                        </div>

                        <div>
                            <x-input-label for="created_by" :value="__('Folder Owner (User)')" />
                            <select id="created_by" name="created_by" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">{{ __('Select User') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('created_by') == $user->id ? 'selected' : '' }} data-companies='{{ json_encode($user->companies->pluck('name', 'id')) }}'>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('created_by')" />
                        </div>

                        <div>
                            <x-input-label for="company_id" :value="__('Associate with Company (Optional)')" />
                            <select id="company_id" name="company_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" disabled> {{-- Disabled initially --}}
                                <option value="">{{ __('Select User First') }}</option>
                                {{-- Options will be populated by JavaScript --}}
                            </select>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Linking a folder to a company makes it visible on the company detail page for assigned accountants.') }}</p>
                            <x-input-error class="mt-2" :messages="$errors->get('company_id')" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="is_public" :value="__('Visibility')" />
                            <label class="inline-flex items-center mt-2">
                                <input type="checkbox" name="is_public" id="is_public" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" value="1">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Make this folder public (visible to everyone)') }}</span>
                            </label>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="allow_uploads" :value="__('Upload Permission')" />
                            <label class="inline-flex items-center mt-2">
                                <input type="checkbox" name="allow_uploads" id="allow_uploads" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" value="1" checked>
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Allow users to upload files to this folder') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>{{ __('Create Folder') }}</x-primary-button>
                            <a href="{{ route('admin.folders.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userSelect = document.getElementById('created_by');
            const companySelect = document.getElementById('company_id');
            
            // Function to update company dropdown
            function updateCompanyOptions() {
                const selectedUserOption = userSelect.options[userSelect.selectedIndex];
                // Ensure dataset.companies exists and is valid JSON before parsing
                let companiesData = {};
                try {
                    if (selectedUserOption.dataset.companies) {
                        companiesData = JSON.parse(selectedUserOption.dataset.companies);
                    }
                } catch (e) {
                    console.error("Error parsing companies data:", e);
                    companiesData = {}; // Reset on error
                }
                const companyIds = Object.keys(companiesData);

                // Clear existing options
                companySelect.innerHTML = '<option value="">{{ __("None") }}</option>'; 

                if (selectedUserOption.value && companyIds.length > 0) {
                    companySelect.disabled = false;
                    companyIds.forEach(id => {
                        const option = document.createElement('option');
                        option.value = id;
                        option.textContent = companiesData[id]; // Company name
                        // Re-select old value if applicable
                        if (id === '{{ old("company_id") }}') { 
                            option.selected = true;
                        }
                        companySelect.appendChild(option);
                    });
                } else {
                    companySelect.disabled = true;
                    companySelect.innerHTML = '<option value="">{{ __("Selected user has no companies or no user selected") }}</option>';
                }
            }

            // Add event listener to user select
            userSelect.addEventListener('change', updateCompanyOptions);
            
            // Initial population if a user is pre-selected (e.g., from old input)
            updateCompanyOptions();
        });
    </script>
    @endpush
</x-admin-layout> 