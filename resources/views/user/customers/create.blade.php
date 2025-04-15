<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Breadcrumb Navigation -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-4 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center text-sm">
                        <nav class="flex min-w-full" aria-label="Breadcrumb">
                            <ol role="list" class="flex items-center space-x-2">
                                <li>
                                    <a href="{{ route('user.dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:hover:text-gray-300">
                                        Home
                                    </a>
                                </li>
                                <li class="text-gray-400">/</li>
                                <li>
                                    <a href="{{ route('user.customers.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:hover:text-gray-300">
                                        Customers
                                    </a>
                                </li>
                                <li class="text-gray-400">/</li>
                                <li>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Create New Customer</span>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-md">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('user.customers.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="name" :value="__('Customer Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="phone" :value="__('Phone')" />
                                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone')" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="vat_number" :value="__('VAT Number')" />
                                <x-text-input id="vat_number" name="vat_number" type="text" class="mt-1 block w-full" :value="old('vat_number')" placeholder="e.g. DE123456789" />
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('For EU customers: country code + VAT registration number') }}</p>
                                <x-input-error :messages="$errors->get('vat_number')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="company_reg_number" :value="__('Company Registration Number')" />
                                <x-text-input id="company_reg_number" name="company_reg_number" type="text" class="mt-1 block w-full" :value="old('company_reg_number')" />
                                <x-input-error :messages="$errors->get('company_reg_number')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="country" :value="__('Country')" />
                                <select id="country" name="country" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">{{ __('- Select country -') }}</option>
                                    <option value="AT" {{ old('country') == 'AT' ? 'selected' : '' }}>Austria</option>
                                    <option value="BE" {{ old('country') == 'BE' ? 'selected' : '' }}>Belgium</option>
                                    <option value="BG" {{ old('country') == 'BG' ? 'selected' : '' }}>Bulgaria</option>
                                    <option value="HR" {{ old('country') == 'HR' ? 'selected' : '' }}>Croatia</option>
                                    <option value="CY" {{ old('country') == 'CY' ? 'selected' : '' }}>Cyprus</option>
                                    <option value="CZ" {{ old('country') == 'CZ' ? 'selected' : '' }}>Czech Republic</option>
                                    <option value="DK" {{ old('country') == 'DK' ? 'selected' : '' }}>Denmark</option>
                                    <option value="EE" {{ old('country') == 'EE' ? 'selected' : '' }}>Estonia</option>
                                    <option value="FI" {{ old('country') == 'FI' ? 'selected' : '' }}>Finland</option>
                                    <option value="FR" {{ old('country') == 'FR' ? 'selected' : '' }}>France</option>
                                    <option value="DE" {{ old('country') == 'DE' ? 'selected' : '' }}>Germany</option>
                                    <option value="GR" {{ old('country') == 'GR' ? 'selected' : '' }}>Greece</option>
                                    <option value="HU" {{ old('country') == 'HU' ? 'selected' : '' }}>Hungary</option>
                                    <option value="IE" {{ old('country') == 'IE' ? 'selected' : '' }}>Ireland</option>
                                    <option value="IT" {{ old('country') == 'IT' ? 'selected' : '' }}>Italy</option>
                                    <option value="LV" {{ old('country') == 'LV' ? 'selected' : '' }}>Latvia</option>
                                    <option value="LT" {{ old('country') == 'LT' ? 'selected' : '' }}>Lithuania</option>
                                    <option value="LU" {{ old('country') == 'LU' ? 'selected' : '' }}>Luxembourg</option>
                                    <option value="MT" {{ old('country') == 'MT' ? 'selected' : '' }}>Malta</option>
                                    <option value="NL" {{ old('country') == 'NL' ? 'selected' : '' }}>Netherlands</option>
                                    <option value="PL" {{ old('country') == 'PL' ? 'selected' : '' }}>Poland</option>
                                    <option value="PT" {{ old('country') == 'PT' ? 'selected' : '' }}>Portugal</option>
                                    <option value="RO" {{ old('country') == 'RO' ? 'selected' : '' }}>Romania</option>
                                    <option value="SK" {{ old('country') == 'SK' ? 'selected' : '' }}>Slovakia</option>
                                    <option value="SI" {{ old('country') == 'SI' ? 'selected' : '' }}>Slovenia</option>
                                    <option value="ES" {{ old('country') == 'ES' ? 'selected' : '' }}>Spain</option>
                                    <option value="SE" {{ old('country') == 'SE' ? 'selected' : '' }}>Sweden</option>
                                    <option value="GB" {{ old('country') == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                                    <option value="TR" {{ old('country') == 'TR' ? 'selected' : '' }}>Turkey</option>
                                    <option value="OTHER" {{ old('country') == 'OTHER' ? 'selected' : '' }}>Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('country')" class="mt-2" />
                            </div>
                            
                            <div class="md:col-span-2">
                                <x-input-label for="address" :value="__('Address')" />
                                <textarea id="address" name="address" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3">{{ old('address') }}</textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <x-secondary-button class="mr-2" onclick="window.location='{{ route('user.customers.index') }}'">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            
                            <x-primary-button>
                                {{ __('Create Customer') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
