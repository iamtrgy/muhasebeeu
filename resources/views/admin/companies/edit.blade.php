<x-admin-layout>
    <x-slot name="header">
        <x-admin.page-title title="{{ __('Edit Company') }} - {{ $company->name }}"></x-admin.page-title>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.companies.update', $company) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Company Name -->
                        <div>
                            <x-input-label for="name" :value="__('Company Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $company->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Owner -->
                        <div>
                            <x-input-label for="user_id" :value="__('Owner')" />
                            <select id="user_id" name="user_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm" style="text-overflow: ellipsis;" required>
                                <option value="">{{ __('Select Owner') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ (old('user_id', $company->user_id) == $user->id) ? 'selected' : '' }} title="{{ $user->name }} ({{ $user->email }})">
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                        </div>

                        <!-- Country -->
                        <div>
                            <x-input-label for="country_id" :value="__('Country')" />
                            <select id="country_id" name="country_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm" style="text-overflow: ellipsis;" required>
                                <option value="">{{ __('Select Country') }}</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ (old('country_id', $company->country_id) == $country->id) ? 'selected' : '' }} title="{{ $country->name }} ({{ $country->code }})">
                                        {{ $country->name }} ({{ $country->code }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('country_id')" class="mt-2" />
                        </div>

                        <!-- Tax Number -->
                        <div>
                            <x-input-label for="tax_number" :value="__('Registry Number')" />
                            <x-text-input id="tax_number" name="tax_number" type="text" class="mt-1 block w-full" :value="old('tax_number', $company->tax_number)" />
                            <x-input-error :messages="$errors->get('tax_number')" class="mt-2" />
                        </div>

                        <!-- VAT Number -->
                        <div>
                            <x-input-label for="vat_number" :value="__('VAT Number')" />
                            <x-text-input id="vat_number" name="vat_number" type="text" class="mt-1 block w-full" :value="old('vat_number', $company->vat_number)" />
                            <x-input-error :messages="$errors->get('vat_number')" class="mt-2" />
                        </div>

                        <!-- Address -->
                        <div>
                            <x-input-label for="address" :value="__('Address')" />
                            <textarea id="address" name="address" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3">{{ old('address', $company->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <!-- Phone -->
                        <div>
                            <x-input-label for="phone" :value="__('Phone')" />
                            <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone', $company->phone)" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $company->email)" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Foundation Date -->
                        <div>
                            <x-input-label for="foundation_date" :value="__('Foundation Date')" />
                            <x-text-input id="foundation_date" name="foundation_date" type="date" class="mt-1 block w-full" :value="old('foundation_date', $company->foundation_date ? $company->foundation_date->format('Y-m-d') : '')" />
                            <x-input-error :messages="$errors->get('foundation_date')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ __('Update Company') }}
                            </button>
                            <a href="{{ route('admin.companies.show', $company) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 