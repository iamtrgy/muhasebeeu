<x-user.layout 
    title="{{ __('Edit Client') }}" 
    :breadcrumbs="[
        ['title' => __('Home'), 'href' => route('user.dashboard'), 'first' => true],
        ['title' => __('Clients'), 'href' => route('user.clients.index')],
        ['title' => __('Edit Client')]
    ]"
>
    <div class="space-y-6">
        @if($errors->any())
            <x-ui.alert variant="danger">
                <strong>{{ __('Please fix the following errors:') }}</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-ui.alert>
        @endif

        <form action="{{ route('user.clients.update', $client) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Client Information -->
            <x-ui.card.base>
                <x-ui.card.header>
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Edit Client Information') }}</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Update client details and contact information') }}</p>
                        </div>
                    </div>
                </x-ui.card.header>
                <x-ui.card.body>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="name" 
                                label="{{ __('Client Name') }}" 
                                value="{{ old('name', $client->name) }}" 
                                required
                                :error="$errors->first('name')"
                            />
                        </x-ui.form.group>
                        
                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="email" 
                                label="{{ __('Email') }}" 
                                type="email"
                                value="{{ old('email', $client->email) }}" 
                                :error="$errors->first('email')"
                            />
                        </x-ui.form.group>

                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="phone" 
                                label="{{ __('Phone') }}" 
                                type="tel"
                                value="{{ old('phone', $client->phone) }}" 
                                :error="$errors->first('phone')"
                            />
                        </x-ui.form.group>
                        
                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="vat_number" 
                                label="{{ __('VAT Number') }}" 
                                value="{{ old('vat_number', $client->vat_number) }}" 
                                placeholder="e.g. DE123456789"
                                :helperText="__('For EU clients: country code + VAT registration number')"
                                :error="$errors->first('vat_number')"
                            />
                        </x-ui.form.group>

                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="company_reg_number" 
                                label="{{ __('Company Registration Number') }}" 
                                value="{{ old('company_reg_number', $client->company_reg_number) }}" 
                                :error="$errors->first('company_reg_number')"
                            />
                        </x-ui.form.group>
                        
                        <x-ui.form.group>
                            <x-ui.form.select 
                                name="country" 
                                label="{{ __('Country') }}" 
                                :error="$errors->first('country')"
                            >
                                <option value="">{{ __('- Select country -') }}</option>
                                <option value="AT" {{ old('country', $client->country) == 'AT' ? 'selected' : '' }}>Austria</option>
                                <option value="BE" {{ old('country', $client->country) == 'BE' ? 'selected' : '' }}>Belgium</option>
                                <option value="BG" {{ old('country', $client->country) == 'BG' ? 'selected' : '' }}>Bulgaria</option>
                                <option value="HR" {{ old('country', $client->country) == 'HR' ? 'selected' : '' }}>Croatia</option>
                                <option value="CY" {{ old('country', $client->country) == 'CY' ? 'selected' : '' }}>Cyprus</option>
                                <option value="CZ" {{ old('country', $client->country) == 'CZ' ? 'selected' : '' }}>Czech Republic</option>
                                <option value="DK" {{ old('country', $client->country) == 'DK' ? 'selected' : '' }}>Denmark</option>
                                <option value="EE" {{ old('country', $client->country) == 'EE' ? 'selected' : '' }}>Estonia</option>
                                <option value="FI" {{ old('country', $client->country) == 'FI' ? 'selected' : '' }}>Finland</option>
                                <option value="FR" {{ old('country', $client->country) == 'FR' ? 'selected' : '' }}>France</option>
                                <option value="DE" {{ old('country', $client->country) == 'DE' ? 'selected' : '' }}>Germany</option>
                                <option value="GR" {{ old('country', $client->country) == 'GR' ? 'selected' : '' }}>Greece</option>
                                <option value="HU" {{ old('country', $client->country) == 'HU' ? 'selected' : '' }}>Hungary</option>
                                <option value="IE" {{ old('country', $client->country) == 'IE' ? 'selected' : '' }}>Ireland</option>
                                <option value="IT" {{ old('country', $client->country) == 'IT' ? 'selected' : '' }}>Italy</option>
                                <option value="LV" {{ old('country', $client->country) == 'LV' ? 'selected' : '' }}>Latvia</option>
                                <option value="LT" {{ old('country', $client->country) == 'LT' ? 'selected' : '' }}>Lithuania</option>
                                <option value="LU" {{ old('country', $client->country) == 'LU' ? 'selected' : '' }}>Luxembourg</option>
                                <option value="MT" {{ old('country', $client->country) == 'MT' ? 'selected' : '' }}>Malta</option>
                                <option value="NL" {{ old('country', $client->country) == 'NL' ? 'selected' : '' }}>Netherlands</option>
                                <option value="PL" {{ old('country', $client->country) == 'PL' ? 'selected' : '' }}>Poland</option>
                                <option value="PT" {{ old('country', $client->country) == 'PT' ? 'selected' : '' }}>Portugal</option>
                                <option value="RO" {{ old('country', $client->country) == 'RO' ? 'selected' : '' }}>Romania</option>
                                <option value="SK" {{ old('country', $client->country) == 'SK' ? 'selected' : '' }}>Slovakia</option>
                                <option value="SI" {{ old('country', $client->country) == 'SI' ? 'selected' : '' }}>Slovenia</option>
                                <option value="ES" {{ old('country', $client->country) == 'ES' ? 'selected' : '' }}>Spain</option>
                                <option value="SE" {{ old('country', $client->country) == 'SE' ? 'selected' : '' }}>Sweden</option>
                                <option value="GB" {{ old('country', $client->country) == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                                <option value="TR" {{ old('country', $client->country) == 'TR' ? 'selected' : '' }}>Turkey</option>
                                <option value="OTHER" {{ old('country', $client->country) == 'OTHER' ? 'selected' : '' }}>Other</option>
                            </x-ui.form.select>
                        </x-ui.form.group>
                        
                        <div class="md:col-span-2">
                            <x-ui.form.group>
                                <x-ui.form.textarea 
                                    name="address" 
                                    label="{{ __('Address') }}" 
                                    value="{{ old('address', $client->address) }}" 
                                    rows="3"
                                    :error="$errors->first('address')"
                                />
                            </x-ui.form.group>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 mt-6">
                <x-ui.button.secondary href="{{ route('user.clients.index') }}">
                    {{ __('Cancel') }}
                </x-ui.button.secondary>
                <x-ui.button.primary type="submit">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('Update Client') }}
                </x-ui.button.primary>
            </div>
        </form>
    </div>
</x-user.layout> 
