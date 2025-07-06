<x-user.layout 
    title="{{ __('Edit Company') }}" 
    :breadcrumbs="[
        ['title' => __('Home'), 'href' => route('user.dashboard'), 'first' => true],
        ['title' => __('Companies'), 'href' => route('user.companies.index')],
        ['title' => __('Edit Company')]
    ]"
>
    <div class="space-y-6">
        @if(session('success'))
            <x-ui.alert variant="success">{{ session('success') }}</x-ui.alert>
        @endif

        @if(session('error'))
            <x-ui.alert variant="danger">{{ session('error') }}</x-ui.alert>
        @endif

        <form method="POST" action="{{ route('user.companies.update', $company->id) }}">
            @csrf
            @method('PUT')

            <!-- Company Information -->
            <x-ui.card.base>
                <x-ui.card.header>
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Company Information') }}</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Basic company details and registration information') }}</p>
                        </div>
                    </div>
                </x-ui.card.header>
                <x-ui.card.body>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="name" 
                                label="{{ __('Company Name') }}" 
                                value="{{ old('name', $company->name) }}" 
                                required
                                :error="$errors->first('name')"
                            />
                        </x-ui.form.group>

                        <x-ui.form.group>
                            <x-ui.form.select 
                                name="country_id" 
                                label="{{ __('Country') }}" 
                                required
                                :error="$errors->first('country_id')"
                            >
                                <option value="">{{ __('Select a country') }}</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" 
                                            {{ old('country_id', $company->country_id) == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }} ({{ $country->code }})
                                    </option>
                                @endforeach
                            </x-ui.form.select>
                        </x-ui.form.group>

                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="tax_number" 
                                label="{{ __('Registry Number') }}" 
                                value="{{ old('tax_number', $company->tax_number) }}" 
                                :error="$errors->first('tax_number')"
                            />
                        </x-ui.form.group>

                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="vat_number" 
                                label="{{ __('VAT Number') }}" 
                                value="{{ old('vat_number', $company->vat_number) }}" 
                                :error="$errors->first('vat_number')"
                            />
                        </x-ui.form.group>

                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="phone" 
                                label="{{ __('Phone') }}" 
                                type="tel"
                                value="{{ old('phone', $company->phone) }}" 
                                :error="$errors->first('phone')"
                            />
                        </x-ui.form.group>

                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="email" 
                                label="{{ __('Email') }}" 
                                type="email"
                                value="{{ old('email', $company->email) }}" 
                                :error="$errors->first('email')"
                            />
                        </x-ui.form.group>

                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="foundation_date" 
                                label="{{ __('Foundation Date') }}" 
                                type="date"
                                value="{{ old('foundation_date', $company->foundation_date ? $company->foundation_date->format('Y-m-d') : '') }}" 
                                :error="$errors->first('foundation_date')"
                            />
                        </x-ui.form.group>
                    </div>

                    <div class="mt-6">
                        <x-ui.form.group>
                            <x-ui.form.textarea 
                                name="address" 
                                label="{{ __('Address') }}" 
                                value="{{ old('address', $company->address) }}" 
                                rows="3"
                                :error="$errors->first('address')"
                            />
                        </x-ui.form.group>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Bank Account Information -->
            <x-ui.card.base>
                <x-ui.card.header>
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Bank Account Information') }}</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Bank details for receiving payments (shown on payment page)') }}</p>
                        </div>
                    </div>
                </x-ui.card.header>
                <x-ui.card.body>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="bank_name" 
                                label="{{ __('Bank Name') }}" 
                                value="{{ old('bank_name', $company->bank_name) }}" 
                                placeholder="Deutsche Bank, ING Bank, etc."
                                :error="$errors->first('bank_name')"
                            />
                        </x-ui.form.group>

                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="bank_account" 
                                label="{{ __('Account Number') }}" 
                                value="{{ old('bank_account', $company->bank_account) }}" 
                                placeholder="123456789"
                                :error="$errors->first('bank_account')"
                            />
                        </x-ui.form.group>

                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="bank_iban" 
                                label="{{ __('IBAN') }}" 
                                value="{{ old('bank_iban', $company->bank_iban) }}" 
                                placeholder="DE89 3704 0044 0532 0130 00"
                                :error="$errors->first('bank_iban')"
                                :helperText="__('International Bank Account Number for international transfers')"
                            />
                        </x-ui.form.group>

                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="bank_swift" 
                                label="{{ __('SWIFT/BIC Code') }}" 
                                value="{{ old('bank_swift', $company->bank_swift) }}" 
                                placeholder="DEUTDEFF"
                                :error="$errors->first('bank_swift')"
                                :helperText="__('Bank Identifier Code for international transfers')"
                            />
                        </x-ui.form.group>
                    </div>

                    <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">{{ __('Payment Page Integration') }}</h4>
                                <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                    {{ __('This information will be displayed on the public payment page for your invoices, allowing customers to pay via bank transfer.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 mt-6">
                <x-ui.button.secondary href="{{ route('user.companies.show', $company) }}">
                    {{ __('Cancel') }}
                </x-ui.button.secondary>
                <x-ui.button.primary type="submit">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('Save Changes') }}
                </x-ui.button.primary>
            </div>
        </form>
    </div>
</x-user.layout>