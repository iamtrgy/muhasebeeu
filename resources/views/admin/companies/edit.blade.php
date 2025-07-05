<x-admin.layout 
    title="{{ __('Edit Company') }} - {{ $company->name }}"
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('admin.dashboard'), 'first' => true],
        ['title' => __('Companies'), 'href' => route('admin.companies.index')],
        ['title' => $company->name, 'href' => route('admin.companies.show', $company)],
        ['title' => __('Edit')]
    ]"
>
    <div class="space-y-6">
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Edit Company Information') }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Update the company details and settings.') }}</p>
            </x-ui.card.header>
            <x-ui.card.body>
                    <form method="POST" action="{{ route('admin.companies.update', $company) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Company Name -->
                        <div>
                            <x-ui.form.input
                                id="name"
                                name="name"
                                type="text"
                                label="{{ __('Company Name') }}"
                                :value="old('name', $company->name)"
                                :error="$errors->first('name')"
                                required
                                autofocus
                            />
                        </div>

                        <!-- Owner -->
                        <div>
                            <x-ui.form.select
                                id="user_id"
                                name="user_id"
                                label="{{ __('Owner') }}"
                                :value="old('user_id', $company->user_id)"
                                :error="$errors->first('user_id')"
                                required
                            >
                                <option value="">{{ __('Select Owner') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ (old('user_id', $company->user_id) == $user->id) ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </x-ui.form.select>
                        </div>

                        <!-- Country -->
                        <div>
                            <x-ui.form.select
                                id="country_id"
                                name="country_id"
                                label="{{ __('Country') }}"
                                :value="old('country_id', $company->country_id)"
                                :error="$errors->first('country_id')"
                                required
                            >
                                <option value="">{{ __('Select Country') }}</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ (old('country_id', $company->country_id) == $country->id) ? 'selected' : '' }}>
                                        {{ $country->name }} ({{ $country->code }})
                                    </option>
                                @endforeach
                            </x-ui.form.select>
                        </div>

                        <!-- Tax Number -->
                        <div>
                            <x-ui.form.input
                                id="tax_number"
                                name="tax_number"
                                type="text"
                                label="{{ __('Registry Number') }}"
                                :value="old('tax_number', $company->tax_number)"
                                :error="$errors->first('tax_number')"
                            />
                        </div>

                        <!-- VAT Number -->
                        <div>
                            <x-ui.form.input
                                id="vat_number"
                                name="vat_number"
                                type="text"
                                label="{{ __('VAT Number') }}"
                                :value="old('vat_number', $company->vat_number)"
                                :error="$errors->first('vat_number')"
                            />
                        </div>

                        <!-- Address -->
                        <div>
                            <x-ui.form.textarea
                                id="address"
                                name="address"
                                label="{{ __('Address') }}"
                                :value="old('address', $company->address)"
                                :error="$errors->first('address')"
                                rows="3"
                            />
                        </div>

                        <!-- Phone -->
                        <div>
                            <x-ui.form.input
                                id="phone"
                                name="phone"
                                type="tel"
                                label="{{ __('Phone') }}"
                                :value="old('phone', $company->phone)"
                                :error="$errors->first('phone')"
                            />
                        </div>

                        <!-- Email -->
                        <div>
                            <x-ui.form.input
                                id="email"
                                name="email"
                                type="email"
                                label="{{ __('Email') }}"
                                :value="old('email', $company->email)"
                                :error="$errors->first('email')"
                            />
                        </div>

                        <!-- Foundation Date -->
                        <div>
                            <x-ui.form.input
                                id="foundation_date"
                                name="foundation_date"
                                type="date"
                                label="{{ __('Foundation Date') }}"
                                :value="old('foundation_date', $company->foundation_date ? $company->foundation_date->format('Y-m-d') : '')"
                                :error="$errors->first('foundation_date')"
                            />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-ui.button.primary type="submit">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ __('Update Company') }}
                            </x-ui.button.primary>
                            <x-ui.button.secondary href="{{ route('admin.companies.show', $company) }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                {{ __('Cancel') }}
                            </x-ui.button.secondary>
                        </div>
                    </form>
            </x-ui.card.body>
        </x-ui.card.base>
    </div>
</x-admin.layout> 