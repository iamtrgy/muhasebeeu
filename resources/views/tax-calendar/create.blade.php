<x-admin.layout 
    title="{{ __('Create Tax Calendar Task') }}"
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('admin.dashboard'), 'first' => true],
        ['title' => __('Tax Calendar'), 'href' => route('admin.user.tax-calendar.index')],
        ['title' => __('Create')]
    ]"
>
    <div class="space-y-6">
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Create New Task') }}
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Fill out the form below to create a new tax calendar task.') }}
                </p>
            </x-ui.card.header>
            
            <form action="{{ route('admin.tax-calendar.store') }}" method="POST">
                @csrf
                
                <x-ui.card.body>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Tax Calendar Template -->
                        <div>
                            <x-ui.form.select 
                                name="tax_calendar_id" 
                                label="{{ __('Tax Calendar Template') }}" 
                                :value="old('tax_calendar_id')"
                                required
                            >
                                <option value="">{{ __('Select a template') }}</option>
                                @foreach($taxCalendars as $calendar)
                                    <option value="{{ $calendar->id }}" {{ old('tax_calendar_id') == $calendar->id ? 'selected' : '' }}>
                                        {{ $calendar->name }} ({{ $calendar->form_code }})
                                    </option>
                                @endforeach
                            </x-ui.form.select>
                            @error('tax_calendar_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Company -->
                        <div>
                            <x-ui.form.select 
                                name="company_id" 
                                label="{{ __('Company') }}" 
                                :value="old('company_id')"
                                required
                            >
                                <option value="">{{ __('Select a company') }}</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </x-ui.form.select>
                            @error('company_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Assigned User -->
                        <div>
                            <x-ui.form.select 
                                name="user_id" 
                                label="{{ __('Assign To') }}" 
                                :value="old('user_id')"
                                required
                            >
                                <option value="">{{ __('Select a user') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </x-ui.form.select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Due Date -->
                        <div>
                            <x-ui.form.input 
                                type="date" 
                                name="due_date" 
                                label="{{ __('Due Date') }}" 
                                :value="old('due_date')"
                                required
                            />
                            @error('due_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="sm:col-span-2">
                            <x-ui.form.textarea 
                                name="notes" 
                                label="{{ __('Notes') }}" 
                                rows="4"
                                placeholder="{{ __('Any additional notes...') }}"
                            >{{ old('notes') }}</x-ui.form.textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Checklist Preview -->
                    <div class="mt-6 sm:col-span-2" x-data="{ selectedTemplate: '{{ old('tax_calendar_id') }}' }">
                        <div x-show="selectedTemplate" class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">
                                {{ __('Checklist Preview') }}
                            </h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                {{ __('The following checklist items will be created for this task:') }}
                            </p>
                            
                            @foreach($taxCalendars as $calendar)
                                <div x-show="selectedTemplate == '{{ $calendar->id }}'" class="space-y-2">
                                    @if($calendar->checklist && is_array($calendar->checklist))
                                        @foreach($calendar->checklist as $item)
                                            <div class="flex items-start">
                                                <div class="flex items-center h-5">
                                                    <input type="checkbox" disabled class="h-4 w-4 text-gray-300 border-gray-300 rounded cursor-not-allowed">
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <label class="font-medium text-gray-700 dark:text-gray-300">
                                                        {{ $item['title'] }}
                                                    </label>
                                                    @if(isset($item['notes']) && $item['notes'])
                                                        <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">{{ $item['notes'] }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('No checklist items defined for this template.') }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </x-ui.card.body>

                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800 text-right sm:px-6 border-t border-gray-200 dark:border-gray-700">
                    <x-ui.button.secondary href="{{ route('admin.user.tax-calendar.index') }}">
                        {{ __('Cancel') }}
                    </x-ui.button.secondary>
                    <x-ui.button.primary type="submit" class="ml-3">
                        {{ __('Create Task') }}
                    </x-ui.button.primary>
                </div>
            </form>
        </x-ui.card.base>
    </div>

    @push('scripts')
    <script>
        // Update the x-data when template selection changes
        document.addEventListener('DOMContentLoaded', function() {
            const templateSelect = document.querySelector('select[name="tax_calendar_id"]');
            if (templateSelect) {
                templateSelect.addEventListener('change', function() {
                    const event = new CustomEvent('template-changed', { detail: { value: this.value } });
                    window.dispatchEvent(event);
                });
            }
        });
        
        window.addEventListener('template-changed', function(e) {
            document.querySelectorAll('[x-data]').forEach(el => {
                if (el.__x) {
                    el.__x.$data.selectedTemplate = e.detail.value;
                }
            });
        });
    </script>
    @endpush
</x-admin.layout>