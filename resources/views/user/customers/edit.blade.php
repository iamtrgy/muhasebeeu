                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="name" :value="__('Customer Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $customer->name)" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $customer->email)" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="phone" :value="__('Phone')" />
                                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $customer->phone)" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="vat_number" :value="__('VAT Number')" />
                                <x-text-input id="vat_number" name="vat_number" type="text" class="mt-1 block w-full" :value="old('vat_number', $customer->vat_number)" placeholder="e.g. DE123456789" />
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('For EU customers: country code + VAT registration number') }}</p>
                                <x-input-error :messages="$errors->get('vat_number')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="company_reg_number" :value="__('Company Registration Number')" />
                                <x-text-input id="company_reg_number" name="company_reg_number" type="text" class="mt-1 block w-full" :value="old('company_reg_number', $customer->company_reg_number)" />
                                <x-input-error :messages="$errors->get('company_reg_number')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="country" :value="__('Country')" />
                                <select id="country" name="country" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">{{ __('- Select country -') }}</option>
                                    <option value="AT" {{ old('country', $customer->country) == 'AT' ? 'selected' : '' }}>Austria</option>
                                    <option value="BE" {{ old('country', $customer->country) == 'BE' ? 'selected' : '' }}>Belgium</option>
                                    <option value="BG" {{ old('country', $customer->country) == 'BG' ? 'selected' : '' }}>Bulgaria</option>
                                    <option value="HR" {{ old('country', $customer->country) == 'HR' ? 'selected' : '' }}>Croatia</option>
                                    <option value="CY" {{ old('country', $customer->country) == 'CY' ? 'selected' : '' }}>Cyprus</option>
                                    <option value="CZ" {{ old('country', $customer->country) == 'CZ' ? 'selected' : '' }}>Czech Republic</option>
                                    <option value="DK" {{ old('country', $customer->country) == 'DK' ? 'selected' : '' }}>Denmark</option>
                                    <option value="EE" {{ old('country', $customer->country) == 'EE' ? 'selected' : '' }}>Estonia</option>
                                    <option value="FI" {{ old('country', $customer->country) == 'FI' ? 'selected' : '' }}>Finland</option>
                                    <option value="FR" {{ old('country', $customer->country) == 'FR' ? 'selected' : '' }}>France</option>
                                    <option value="DE" {{ old('country', $customer->country) == 'DE' ? 'selected' : '' }}>Germany</option>
                                    <option value="GR" {{ old('country', $customer->country) == 'GR' ? 'selected' : '' }}>Greece</option>
                                    <option value="HU" {{ old('country', $customer->country) == 'HU' ? 'selected' : '' }}>Hungary</option>
                                    <option value="IE" {{ old('country', $customer->country) == 'IE' ? 'selected' : '' }}>Ireland</option>
                                    <option value="IT" {{ old('country', $customer->country) == 'IT' ? 'selected' : '' }}>Italy</option>
                                    <option value="LV" {{ old('country', $customer->country) == 'LV' ? 'selected' : '' }}>Latvia</option>
                                    <option value="LT" {{ old('country', $customer->country) == 'LT' ? 'selected' : '' }}>Lithuania</option>
                                    <option value="LU" {{ old('country', $customer->country) == 'LU' ? 'selected' : '' }}>Luxembourg</option>
                                    <option value="MT" {{ old('country', $customer->country) == 'MT' ? 'selected' : '' }}>Malta</option>
                                    <option value="NL" {{ old('country', $customer->country) == 'NL' ? 'selected' : '' }}>Netherlands</option>
                                    <option value="PL" {{ old('country', $customer->country) == 'PL' ? 'selected' : '' }}>Poland</option>
                                    <option value="PT" {{ old('country', $customer->country) == 'PT' ? 'selected' : '' }}>Portugal</option>
                                    <option value="RO" {{ old('country', $customer->country) == 'RO' ? 'selected' : '' }}>Romania</option>
                                    <option value="SK" {{ old('country', $customer->country) == 'SK' ? 'selected' : '' }}>Slovakia</option>
                                    <option value="SI" {{ old('country', $customer->country) == 'SI' ? 'selected' : '' }}>Slovenia</option>
                                    <option value="ES" {{ old('country', $customer->country) == 'ES' ? 'selected' : '' }}>Spain</option>
                                    <option value="SE" {{ old('country', $customer->country) == 'SE' ? 'selected' : '' }}>Sweden</option>
                                    <option value="GB" {{ old('country', $customer->country) == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                                    <option value="TR" {{ old('country', $customer->country) == 'TR' ? 'selected' : '' }}>Turkey</option>
                                    <option value="OTHER" {{ old('country', $customer->country) == 'OTHER' ? 'selected' : '' }}>Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('country')" class="mt-2" />
                            </div>
                            
                            <div class="md:col-span-2">
                                <x-input-label for="address" :value="__('Address')" />
                                <textarea id="address" name="address" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3">{{ old('address', $customer->address) }}</textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                        </div> 