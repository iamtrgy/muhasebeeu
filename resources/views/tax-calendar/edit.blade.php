<x-admin.layout 
    title="{{ __('Edit Tax Calendar Task') }}"
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('admin.dashboard'), 'first' => true],
        ['title' => __('Tax Calendar'), 'href' => route('admin.user.tax-calendar.index')],
        ['title' => $task->taxCalendar->name, 'href' => route('admin.tax-calendar.show', $task)],
        ['title' => __('Edit')]
    ]"
>
    <div class="space-y-6">
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Edit Task Information') }}
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Update the task details below.') }}
                </p>
            </x-ui.card.header>
            
            <form action="{{ route('admin.tax-calendar.update', $task) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <x-ui.card.body>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Tax Calendar Template -->
                        <div>
                            <x-ui.form.select 
                                name="tax_calendar_id" 
                                label="{{ __('Tax Calendar Template') }}" 
                                :value="$task->tax_calendar_id"
                                required
                            >
                                <option value="">{{ __('Select a template') }}</option>
                                @foreach($taxCalendars as $calendar)
                                    <option value="{{ $calendar->id }}" {{ $task->tax_calendar_id == $calendar->id ? 'selected' : '' }}>
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
                                :value="$task->company_id"
                                required
                            >
                                <option value="">{{ __('Select a company') }}</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ $task->company_id == $company->id ? 'selected' : '' }}>
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
                                label="{{ __('Assigned To') }}" 
                                :value="$task->user_id"
                                required
                            >
                                <option value="">{{ __('Select a user') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $task->user_id == $user->id ? 'selected' : '' }}>
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
                                :value="$task->due_date->format('Y-m-d')"
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
                            >{{ $task->notes }}</x-ui.form.textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-ui.card.body>

                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800 text-right sm:px-6 border-t border-gray-200 dark:border-gray-700">
                    <x-ui.button.secondary href="{{ route('admin.tax-calendar.show', $task) }}">
                        {{ __('Cancel') }}
                    </x-ui.button.secondary>
                    <x-ui.button.primary type="submit" class="ml-3">
                        {{ __('Save Changes') }}
                    </x-ui.button.primary>
                </div>
            </form>
        </x-ui.card.base>
    </div>
</x-admin.layout>