<x-app-layout>
    <x-unified-header />
    <x-folder.file-preview-modal />

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            

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

                    <form action="{{ route('user.invoices.store') }}" method="POST" id="invoice-form">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <x-input-label for="invoice_number" :value="__('Invoice Number')" />
                                <x-text-input id="invoice_number" name="invoice_number" type="text" class="mt-1 block w-full" :value="old('invoice_number', $nextInvoiceNumber)" required />
                                <x-input-error :messages="$errors->get('invoice_number')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="invoice_date" :value="__('Invoice Date')" />
                                <x-text-input id="invoice_date" name="invoice_date" type="date" class="mt-1 block w-full" :value="old('invoice_date', date('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('invoice_date')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="due_date" :value="__('Due Date')" />
                                <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full" :value="old('due_date', date('Y-m-d', strtotime('+30 days')))" required />
                                <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="reference" :value="__('Reference')" />
                                <x-text-input id="reference" name="reference" type="text" class="mt-1 block w-full" :value="old('reference')" placeholder="Optional reference or PO number" />
                                <x-input-error :messages="$errors->get('reference')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="payment_terms" :value="__('Payment Terms')" />
                                <select id="payment_terms" name="payment_terms" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="due_receipt" {{ old('payment_terms') == 'due_receipt' ? 'selected' : '' }}>{{ __('Due on Receipt') }}</option>
                                    <option value="net_15" {{ old('payment_terms') == 'net_15' ? 'selected' : '' }}>{{ __('Net 15 Days') }}</option>
                                    <option value="net_30" {{ old('payment_terms', 'net_30') == 'net_30' ? 'selected' : '' }}>{{ __('Net 30 Days') }}</option>
                                    <option value="net_60" {{ old('payment_terms') == 'net_60' ? 'selected' : '' }}>{{ __('Net 60 Days') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('payment_terms')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Currency -->
                            <div>
                                <x-input-label for="currency" :value="__('Currency')" />
                                <select id="currency" name="currency" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="EUR" {{ old('currency', 'EUR') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                    <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                    <option value="TRY" {{ old('currency') == 'TRY' ? 'selected' : '' }}>TRY - Turkish Lira</option>
                                    <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                </select>
                                <x-input-error :messages="$errors->get('currency')" class="mt-2" />
                            </div>

                            <!-- Invoice Language -->
                            <div>
                                <x-input-label for="language_code" :value="__('Invoice Language')" />
                                <select id="language_code" name="language_code" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    @foreach($languages as $code => $language)
                                        <option value="{{ $code }}" {{ old('language_code', 'en') == $code ? 'selected' : '' }}>{{ $language }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('language_code')" class="mt-2" />
                            </div>

                            <!-- Payment Method -->
                            <div>
                                <x-input-label for="payment_method" :value="__('Payment Method')" />
                                <select id="payment_method" name="payment_method" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="bank_transfer" {{ old('payment_method', 'bank_transfer') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="paypal" {{ old('payment_method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                    <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                            </div>

                            <!-- Company -->
                            <div>
                                <x-input-label for="company_id" :value="__('Your Company')" />
                                <select id="company_id" name="company_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="">{{ __('Select Company') }}</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('company_id')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('Client Information') }}</h3>
                            
                            <div class="flex items-center mb-4">
                                <input type="radio" id="client_type_existing" name="client_type" value="existing" class="mr-2" {{ old('client_type', 'existing') == 'existing' ? 'checked' : '' }} onchange="toggleClientFields()">
                                <label for="client_type_existing" class="mr-4">{{ __('Existing Client') }}</label>
                                
                                <input type="radio" id="client_type_new" name="client_type" value="new" class="mr-2" {{ old('client_type') == 'new' ? 'checked' : '' }} onchange="toggleClientFields()">
                                <label for="client_type_new">{{ __('New Client') }}</label>
                            </div>
                            
                            <div id="existing_client_fields" class="{{ old('client_type') == 'new' ? 'hidden' : '' }}">
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Select a client from your saved clients list') }}
                                </p>
                                <x-input-label for="client_id" :value="__('Select Client')" />
                                <select id="client_id" name="client_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">{{ __('- Select a client -') }}</option>
                                    @if(isset($userclients) && count($userclients) > 0)
                                        @foreach($userclients as $customer)
                                            <option value="{{ $customer->id }}" {{ old('client_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>{{ __('No clients found - please add a client') }}</option>
                                    @endif
                                </select>
                                <div class="mt-2 text-right">
                                    <a href="{{ route('user.clients.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        {{ __('Manage Clients') }}
                                        <svg class="inline-block w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                </div>
                                <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                            </div>
                            
                            <div id="new_client_fields" class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4 {{ old('client_type') == 'new' ? '' : 'hidden' }}">
                                <div>
                                    <x-input-label for="client_name" :value="__('Client Name')" />
                                    <x-text-input id="client_name" name="client_name" type="text" class="mt-1 block w-full" :value="old('client_name')" />
                                    <x-input-error :messages="$errors->get('client_name')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="client_email" :value="__('Client Email')" />
                                    <x-text-input id="client_email" name="client_email" type="email" class="mt-1 block w-full" :value="old('client_email')" />
                                    <x-input-error :messages="$errors->get('client_email')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="client_phone" :value="__('Phone')" />
                                    <x-text-input id="client_phone" name="client_phone" type="text" class="mt-1 block w-full" :value="old('client_phone')" />
                                    <x-input-error :messages="$errors->get('client_phone')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="vat_number" :value="__('VAT Number')" />
                                    <x-text-input id="vat_number" name="vat_number" type="text" class="mt-1 block w-full" :value="old('vat_number')" placeholder="e.g. DE123456789" />
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('For EU clients: country code + VAT registration number') }}</p>
                                    <x-input-error :messages="$errors->get('vat_number')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="client_company_reg_number" :value="__('Company Registration Number')" />
                                    <x-text-input id="client_company_reg_number" name="client_company_reg_number" type="text" class="mt-1 block w-full" :value="old('client_company_reg_number')" />
                                    <x-input-error :messages="$errors->get('client_company_reg_number')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="client_country" :value="__('Country')" />
                                    <select id="client_country" name="client_country" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">{{ __('- Select country -') }}</option>
                                        <option value="AT" {{ old('client_country') == 'AT' ? 'selected' : '' }}>Austria</option>
                                        <option value="BE" {{ old('client_country') == 'BE' ? 'selected' : '' }}>Belgium</option>
                                        <option value="BG" {{ old('client_country') == 'BG' ? 'selected' : '' }}>Bulgaria</option>
                                        <option value="HR" {{ old('client_country') == 'HR' ? 'selected' : '' }}>Croatia</option>
                                        <option value="CY" {{ old('client_country') == 'CY' ? 'selected' : '' }}>Cyprus</option>
                                        <option value="CZ" {{ old('client_country') == 'CZ' ? 'selected' : '' }}>Czech Republic</option>
                                        <option value="DK" {{ old('client_country') == 'DK' ? 'selected' : '' }}>Denmark</option>
                                        <option value="EE" {{ old('client_country') == 'EE' ? 'selected' : '' }}>Estonia</option>
                                        <option value="FI" {{ old('client_country') == 'FI' ? 'selected' : '' }}>Finland</option>
                                        <option value="FR" {{ old('client_country') == 'FR' ? 'selected' : '' }}>France</option>
                                        <option value="DE" {{ old('client_country') == 'DE' ? 'selected' : '' }}>Germany</option>
                                        <option value="GR" {{ old('client_country') == 'GR' ? 'selected' : '' }}>Greece</option>
                                        <option value="HU" {{ old('client_country') == 'HU' ? 'selected' : '' }}>Hungary</option>
                                        <option value="IE" {{ old('client_country') == 'IE' ? 'selected' : '' }}>Ireland</option>
                                        <option value="IT" {{ old('client_country') == 'IT' ? 'selected' : '' }}>Italy</option>
                                        <option value="LV" {{ old('client_country') == 'LV' ? 'selected' : '' }}>Latvia</option>
                                        <option value="LT" {{ old('client_country') == 'LT' ? 'selected' : '' }}>Lithuania</option>
                                        <option value="LU" {{ old('client_country') == 'LU' ? 'selected' : '' }}>Luxembourg</option>
                                        <option value="MT" {{ old('client_country') == 'MT' ? 'selected' : '' }}>Malta</option>
                                        <option value="NL" {{ old('client_country') == 'NL' ? 'selected' : '' }}>Netherlands</option>
                                        <option value="PL" {{ old('client_country') == 'PL' ? 'selected' : '' }}>Poland</option>
                                        <option value="PT" {{ old('client_country') == 'PT' ? 'selected' : '' }}>Portugal</option>
                                        <option value="RO" {{ old('client_country') == 'RO' ? 'selected' : '' }}>Romania</option>
                                        <option value="SK" {{ old('client_country') == 'SK' ? 'selected' : '' }}>Slovakia</option>
                                        <option value="SI" {{ old('client_country') == 'SI' ? 'selected' : '' }}>Slovenia</option>
                                        <option value="ES" {{ old('client_country') == 'ES' ? 'selected' : '' }}>Spain</option>
                                        <option value="SE" {{ old('client_country') == 'SE' ? 'selected' : '' }}>Sweden</option>
                                        <option value="GB" {{ old('client_country') == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                                        <option value="TR" {{ old('client_country') == 'TR' ? 'selected' : '' }}>Turkey</option>
                                        <option value="OTHER" {{ old('client_country') == 'OTHER' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('client_country')" class="mt-2" />
                                </div>
                                
                                <div class="md:col-span-2">
                                    <x-input-label for="client_address" :value="__('Address')" />
                                    <textarea id="client_address" name="client_address" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3">{{ old('client_address') }}</textarea>
                                    <x-input-error :messages="$errors->get('client_address')" class="mt-2" />
                                </div>

                                <div class="md:col-span-2">
                                    <div class="flex items-center">
                                        <input id="save_client" name="save_client" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600" {{ old('save_client') ? 'checked' : '' }}>
                                        <label for="save_client" class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Save this client for future invoices') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('Invoice Items') }}</h3>
                            
                            <div class="overflow-x-auto mb-4">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="itemsTable">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                {{ __('Description') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-24">
                                                {{ __('Quantity') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-32">
                                                {{ __('Unit Price') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-36">
                                                {{ __('VAT %') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-32">
                                                {{ __('Amount') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-16">
                                                {{ __('Action') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="itemsContainer">
                                        <!-- Filled by JS -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6" class="px-6 py-4">
                                                <button type="button" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-300 disabled:opacity-25 transition" id="addItemButton">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                    {{ __('Add Item') }}
                                                </button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            
                            <div class="flex justify-end mb-4">
                                <div class="w-full md:w-64">
                                    <div class="flex justify-between py-2 border-b dark:border-gray-700">
                                        <span class="font-medium">{{ __('Subtotal') }}:</span>
                                        <span id="subtotal">0.00</span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b dark:border-gray-700">
                                        <span class="font-medium">{{ __('VAT Total') }}:</span>
                                        <span id="tax_total">0.00</span>
                                    </div>
                                    <div class="flex justify-between py-2 font-bold">
                                        <span>{{ __('Grand Total') }}:</span>
                                        <span id="grand_total">0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('notes', 'Payment to be made to the account specified above. VAT registration number is displayed on the invoice. For EU clients: Reverse charge applies where applicable under EU VAT regulations.') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('EU VAT Information') }}</h4>
                                <div class="flex items-center mb-2">
                                    <input id="reverse_charge" name="reverse_charge" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600" {{ old('reverse_charge') ? 'checked' : '' }} onchange="toggleVatExempt()">
                                    <label for="reverse_charge" class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Apply reverse charge mechanism (VAT paid by recipient)') }}</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="vat_exempt" name="vat_exempt" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600" {{ old('vat_exempt') ? 'checked' : '' }} onchange="toggleReverseCharge()">
                                    <label for="vat_exempt" class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('VAT exempt (Article 146 EU VAT Directive)') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <x-primary-button>
                                {{ __('Create Invoice') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Invoice item template
        function createItemRow(index, data = {}) {
            const description = data.description || '';
            const quantity = data.quantity || 1;
            const unit_price = data.unit_price || 0;
            const tax_rate = data.tax_rate || 20;
            
            return `
                <tr class="item-row">
                    <td class="px-6 py-4">
                        <input type="text" name="items[${index}][description]" placeholder="Product/Service Description" class="item-description w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" value="${description}" required>
                    </td>
                    <td class="px-6 py-4">
                        <input type="number" name="items[${index}][quantity]" min="0.01" step="0.01" class="item-quantity w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" value="${quantity}" required>
                    </td>
                    <td class="px-6 py-4">
                        <input type="number" name="items[${index}][unit_price]" min="0" step="0.01" class="item-unit-price w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" value="${unit_price}" required>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <input type="number" name="items[${index}][tax_rate]" min="0" step="0.1" class="item-tax-rate w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" value="${tax_rate}" required>
                            <span class="ml-2 flex-shrink-0">%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="item-total">0.00</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button type="button" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 remove-item" title="Delete">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </td>
                </tr>
            `;
        }

        // Toggle customer fields
        function toggleClientFields() {
            const clientType = document.querySelector('input[name="client_type"]:checked').value;
            
            if (clientType === 'existing') {
                document.getElementById('existing_client_fields').classList.remove('hidden');
                document.getElementById('new_client_fields').classList.add('hidden');
            } else {
                document.getElementById('existing_client_fields').classList.add('hidden');
                document.getElementById('new_client_fields').classList.remove('hidden');
            }
        }
        
        // Toggle VAT exemption options
        function toggleVatExempt() {
            if (document.getElementById('reverse_charge').checked) {
                document.getElementById('vat_exempt').checked = false;
            }
        }
        
        function toggleReverseCharge() {
            if (document.getElementById('vat_exempt').checked) {
                document.getElementById('reverse_charge').checked = false;
            }
        }

        // Calculate totals
        function calculateTotals() {
            let subtotal = 0;
            let taxTotal = 0;
            
            document.querySelectorAll('.item-row').forEach(row => {
                const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
                const unitPrice = parseFloat(row.querySelector('.item-unit-price').value) || 0;
                const taxRate = parseFloat(row.querySelector('.item-tax-rate').value) || 0;
                
                const rowSubtotal = quantity * unitPrice;
                const rowTax = rowSubtotal * (taxRate / 100);
                const rowTotal = rowSubtotal + rowTax;
                
                subtotal += rowSubtotal;
                taxTotal += rowTax;
                
                row.querySelector('.item-total').textContent = rowTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            });
            
            const grandTotal = subtotal + taxTotal;
            
            document.getElementById('subtotal').textContent = subtotal.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            
            document.getElementById('tax_total').textContent = taxTotal.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            
            document.getElementById('grand_total').textContent = grandTotal.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // When page loads
        document.addEventListener('DOMContentLoaded', function() {
            const itemsContainer = document.getElementById('itemsContainer');
            const addItemButton = document.getElementById('addItemButton');
            
            // Add new item
            addItemButton.addEventListener('click', function() {
                const index = document.querySelectorAll('.item-row').length;
                itemsContainer.insertAdjacentHTML('beforeend', createItemRow(index));
                attachEventListeners();
                calculateTotals();
            });
            
            // Add first row
            if (itemsContainer.querySelectorAll('.item-row').length === 0) {
                itemsContainer.insertAdjacentHTML('beforeend', createItemRow(0));
                attachEventListeners();
            }
            
            // Calculate totals
            calculateTotals();
            
            // Attach event listeners
            function attachEventListeners() {
                // Item delete buttons
                document.querySelectorAll('.remove-item').forEach(button => {
                    button.addEventListener('click', function() {
                        if (document.querySelectorAll('.item-row').length > 1) {
                            this.closest('.item-row').remove();
                            // Reindex rows
                            document.querySelectorAll('.item-row').forEach((row, index) => {
                                row.querySelectorAll('input').forEach(input => {
                                    const name = input.getAttribute('name');
                                    input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
                                });
                            });
                            calculateTotals();
                        } else {
                            alert('At least one invoice item is required.');
                        }
                    });
                });
                
                // Value change events
                document.querySelectorAll('.item-quantity, .item-unit-price, .item-tax-rate').forEach(input => {
                    input.addEventListener('input', calculateTotals);
                });
            }
        });
    </script>
</x-app-layout> 
