<x-user.layout 
    title="{{ __('Create Invoice') }}" 
    :breadcrumbs="[
        ['title' => __('Home'), 'href' => route('user.dashboard'), 'first' => true],
        ['title' => __('Invoices'), 'href' => route('user.invoices.index')],
        ['title' => __('Create Invoice')]
    ]"
>
    <div class="space-y-6" x-data="invoiceForm()">
        @if($errors->any())
            <x-ui.alert variant="danger">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-ui.alert>
        @endif

        <form action="{{ route('user.invoices.store') }}" method="POST" id="invoice-form">
            @csrf
            
            <!-- Invoice Basic Information -->
            <x-ui.card.base>
                <x-ui.card.header>
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Invoice Information') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Basic invoice details and settings') }}</p>
                        </div>
                    </div>
                </x-ui.card.header>
                <x-ui.card.body>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="invoice_number" 
                                label="{{ __('Invoice Number') }}" 
                                value="{{ old('invoice_number', $nextInvoiceNumber) }}" 
                                required
                                :error="$errors->first('invoice_number')"
                            />
                        </x-ui.form.group>
                        
                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="invoice_date" 
                                label="{{ __('Invoice Date') }}" 
                                type="date" 
                                value="{{ old('invoice_date', date('Y-m-d')) }}" 
                                required
                                :error="$errors->first('invoice_date')"
                            />
                        </x-ui.form.group>
                        
                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="due_date" 
                                label="{{ __('Due Date') }}" 
                                type="date" 
                                value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" 
                                required
                                :error="$errors->first('due_date')"
                            />
                        </x-ui.form.group>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="reference" 
                                label="{{ __('Reference') }}" 
                                value="{{ old('reference') }}" 
                                placeholder="Optional reference or PO number"
                                :error="$errors->first('reference')"
                            />
                        </x-ui.form.group>
                        
                        <x-ui.form.group>
                            <x-ui.form.select 
                                name="payment_terms" 
                                label="{{ __('Payment Terms') }}" 
                                :error="$errors->first('payment_terms')"
                            >
                                <option value="due_receipt" {{ old('payment_terms') == 'due_receipt' ? 'selected' : '' }}>{{ __('Due on Receipt') }}</option>
                                <option value="net_15" {{ old('payment_terms') == 'net_15' ? 'selected' : '' }}>{{ __('Net 15 Days') }}</option>
                                <option value="net_30" {{ old('payment_terms', 'net_30') == 'net_30' ? 'selected' : '' }}>{{ __('Net 30 Days') }}</option>
                                <option value="net_60" {{ old('payment_terms') == 'net_60' ? 'selected' : '' }}>{{ __('Net 60 Days') }}</option>
                            </x-ui.form.select>
                        </x-ui.form.group>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Currency and Language Settings -->
            <x-ui.card.base>
                <x-ui.card.header>
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Invoice Settings') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Currency, language and payment settings') }}</p>
                        </div>
                    </div>
                </x-ui.card.header>
                <x-ui.card.body>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-ui.form.group>
                            <x-ui.form.select 
                                name="currency" 
                                label="{{ __('Currency') }}" 
                                :error="$errors->first('currency')"
                            >
                                <option value="EUR" {{ old('currency', 'EUR') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                <option value="TRY" {{ old('currency') == 'TRY' ? 'selected' : '' }}>TRY - Turkish Lira</option>
                                <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                            </x-ui.form.select>
                        </x-ui.form.group>

                        <x-ui.form.group>
                            <x-ui.form.select 
                                name="language_code" 
                                label="{{ __('Invoice Language') }}" 
                                :error="$errors->first('language_code')"
                            >
                                <option value="en" {{ old('language_code', 'en') == 'en' ? 'selected' : '' }}>English</option>
                                <option value="de" {{ old('language_code') == 'de' ? 'selected' : '' }}>Deutsch</option>
                                <option value="tr" {{ old('language_code') == 'tr' ? 'selected' : '' }}>Türkçe</option>
                            </x-ui.form.select>
                        </x-ui.form.group>

                        <x-ui.form.group>
                            <x-ui.form.select 
                                name="payment_method" 
                                label="{{ __('Payment Method') }}" 
                                :error="$errors->first('payment_method')"
                            >
                                <option value="bank_transfer" {{ old('payment_method', 'bank_transfer') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="paypal" {{ old('payment_method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                            </x-ui.form.select>
                        </x-ui.form.group>

                        <x-ui.form.group>
                            <x-ui.form.select 
                                name="company_id" 
                                label="{{ __('Your Company') }}" 
                                required
                                :error="$errors->first('company_id')"
                            >
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id', $companies->first()->id ?? '') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                @endforeach
                            </x-ui.form.select>
                        </x-ui.form.group>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Client Information -->
            <x-ui.card.base>
                <x-ui.card.header>
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Client Information') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Select existing client or add new client details') }}</p>
                        </div>
                    </div>
                </x-ui.card.header>
                <x-ui.card.body>
                    <div class="flex items-center gap-6 mb-6">
                        <label class="flex items-center">
                            <input type="radio" name="client_type" value="existing" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" 
                                   {{ old('client_type', 'existing') == 'existing' ? 'checked' : '' }} 
                                   x-on:change="clientType = 'existing'">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Existing Client') }}</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="radio" name="client_type" value="new" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" 
                                   {{ old('client_type') == 'new' ? 'checked' : '' }} 
                                   x-on:change="clientType = 'new'">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('New Client') }}</span>
                        </label>
                    </div>
                    
                    <div x-show="clientType === 'existing'" x-transition>
                        <div class="mb-4">
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Select a client from your saved clients list') }}
                            </p>
                            <x-ui.form.select 
                                name="client_id" 
                                label="{{ __('Select Client') }}" 
                                :error="$errors->first('client_id')"
                            >
                                <option value="">{{ __('- Select a client -') }}</option>
                                @if(isset($userclients) && count($userclients) > 0)
                                    @foreach($userclients as $customer)
                                        <option value="{{ $customer->id }}" {{ old('client_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                    @endforeach
                                @else
                                    <option value="" disabled>{{ __('No clients found - please add a client') }}</option>
                                @endif
                            </x-ui.form.select>
                            <div class="mt-2 text-right">
                                <a href="{{ route('user.clients.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    {{ __('Manage Clients') }}
                                    <svg class="inline-block w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div x-show="clientType === 'new'" x-transition class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-ui.form.group>
                                <x-ui.form.input 
                                    name="client_name" 
                                    label="{{ __('Client Name') }}" 
                                    value="{{ old('client_name') }}" 
                                    :error="$errors->first('client_name')"
                                />
                            </x-ui.form.group>
                            
                            <x-ui.form.group>
                                <x-ui.form.input 
                                    name="client_email" 
                                    label="{{ __('Client Email') }}" 
                                    type="email" 
                                    value="{{ old('client_email') }}" 
                                    :error="$errors->first('client_email')"
                                />
                            </x-ui.form.group>

                            <x-ui.form.group>
                                <x-ui.form.input 
                                    name="client_phone" 
                                    label="{{ __('Phone') }}" 
                                    value="{{ old('client_phone') }}" 
                                    :error="$errors->first('client_phone')"
                                />
                            </x-ui.form.group>
                            
                            <x-ui.form.group>
                                <x-ui.form.input 
                                    name="vat_number" 
                                    label="{{ __('VAT Number') }}" 
                                    value="{{ old('vat_number') }}" 
                                    placeholder="e.g. DE123456789"
                                    help="{{ __('For EU clients: country code + VAT registration number') }}"
                                    :error="$errors->first('vat_number')"
                                />
                            </x-ui.form.group>
                            
                            <x-ui.form.group>
                                <x-ui.form.input 
                                    name="client_company_reg_number" 
                                    label="{{ __('Company Registration Number') }}" 
                                    value="{{ old('client_company_reg_number') }}" 
                                    :error="$errors->first('client_company_reg_number')"
                                />
                            </x-ui.form.group>
                            
                            <x-ui.form.group>
                                <x-ui.form.select 
                                    name="client_country" 
                                    label="{{ __('Country') }}" 
                                    :error="$errors->first('client_country')"
                                >
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
                                </x-ui.form.select>
                            </x-ui.form.group>
                        </div>
                        
                        <x-ui.form.group>
                            <x-ui.form.textarea 
                                name="client_address" 
                                label="{{ __('Address') }}" 
                                value="{{ old('client_address') }}" 
                                rows="3"
                                :error="$errors->first('client_address')"
                            />
                        </x-ui.form.group>

                        <x-ui.form.group>
                            <x-ui.form.checkbox 
                                name="save_client" 
                                label="{{ __('Save this client for future invoices') }}" 
                                value="1" 
                                :checked="old('save_client')"
                            />
                        </x-ui.form.group>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Invoice Items -->
            <x-ui.card.base>
                <x-ui.card.header>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V9a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Invoice Items') }}</h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Products and services for this invoice') }}</p>
                            </div>
                        </div>
                        <x-ui.button.primary type="button" size="sm" x-on:click="addItem()">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            {{ __('Add Item') }}
                        </x-ui.button.primary>
                    </div>
                </x-ui.card.header>
                <x-ui.card.body>
                    <div class="overflow-x-auto mb-4">
                        <x-ui.table.base>
                            <x-slot name="head">
                                <x-ui.table.head-cell>{{ __('Description') }}</x-ui.table.head-cell>
                                <x-ui.table.head-cell>{{ __('Quantity') }}</x-ui.table.head-cell>
                                <x-ui.table.head-cell>{{ __('Unit Price') }}</x-ui.table.head-cell>
                                <x-ui.table.head-cell>{{ __('VAT %') }}</x-ui.table.head-cell>
                                <x-ui.table.head-cell>{{ __('Amount') }}</x-ui.table.head-cell>
                                <x-ui.table.head-cell align="center">{{ __('Action') }}</x-ui.table.head-cell>
                            </x-slot>
                            <x-slot name="body">
                                <tbody x-ref="itemsContainer">
                                    <!-- Items will be added dynamically -->
                                </tbody>
                            </x-slot>
                        </x-ui.table.base>
                    </div>
                    
                    <!-- Totals -->
                    <div class="flex justify-end">
                        <div class="w-full md:w-64 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-600">
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ __('Subtotal') }}:</span>
                                <span class="text-gray-900 dark:text-gray-100" x-text="formatCurrency(subtotal)">0.00</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-600">
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ __('VAT Total') }}:</span>
                                <span class="text-gray-900 dark:text-gray-100" x-text="formatCurrency(taxTotal)">0.00</span>
                            </div>
                            <div class="flex justify-between py-2 font-bold text-lg">
                                <span class="text-gray-900 dark:text-gray-100">{{ __('Grand Total') }}:</span>
                                <span class="text-gray-900 dark:text-gray-100" x-text="formatCurrency(grandTotal)">0.00</span>
                            </div>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Notes and VAT Settings -->
            <x-ui.card.base>
                <x-ui.card.header>
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Additional Information') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Notes and VAT settings') }}</p>
                        </div>
                    </div>
                </x-ui.card.header>
                <x-ui.card.body>
                    <div class="space-y-6">
                        <x-ui.form.group>
                            <x-ui.form.textarea 
                                name="notes" 
                                label="{{ __('Notes') }}" 
                                value="{{ old('notes', 'Payment to be made to the account specified above. VAT registration number is displayed on the invoice. For EU clients: Reverse charge applies where applicable under EU VAT regulations.') }}" 
                                rows="3"
                                :error="$errors->first('notes')"
                            />
                        </x-ui.form.group>
                        
                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="payment_url" 
                                label="{{ __('Payment URL (Optional)') }}" 
                                type="url"
                                value="{{ old('payment_url') }}" 
                                placeholder="https://buy.stripe.com/payment-link..."
                                :error="$errors->first('payment_url')"
                                :helperText="__('Add a Stripe payment link or other payment URL. This will be included in invoice emails.')"
                            />
                        </x-ui.form.group>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mt-6">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('EU VAT Information') }}</h4>
                        <div class="space-y-3">
                            <x-ui.form.checkbox 
                                name="reverse_charge" 
                                label="{{ __('Apply reverse charge mechanism (VAT paid by recipient)') }}" 
                                value="1" 
                                :checked="old('reverse_charge')"
                                x-on:change="if ($event.target.checked) vatExempt = false"
                            />
                            <x-ui.form.checkbox 
                                name="vat_exempt" 
                                label="{{ __('VAT exempt (Article 146 EU VAT Directive)') }}" 
                                value="1" 
                                :checked="old('vat_exempt')"
                                x-on:change="if ($event.target.checked) reverseCharge = false"
                            />
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Actions -->
            <div class="mt-6 flex items-center justify-end gap-3">
                <x-ui.button.secondary href="{{ route('user.invoices.index') }}">
                    {{ __('Cancel') }}
                </x-ui.button.secondary>
                <x-ui.button.primary type="submit">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Create Invoice') }}
                </x-ui.button.primary>
            </div>
        </form>
    </div>

    <script>
        function invoiceForm() {
            return {
                clientType: '{{ old('client_type', 'existing') }}',
                reverseCharge: {{ old('reverse_charge') ? 'true' : 'false' }},
                vatExempt: {{ old('vat_exempt') ? 'true' : 'false' }},
                items: [],
                subtotal: 0,
                taxTotal: 0,
                grandTotal: 0,

                init() {
                    this.addItem();
                    this.calculateTotals();
                },

                addItem() {
                    const index = this.items.length;
                    this.items.push({
                        description: '',
                        quantity: 1,
                        unit_price: 0,
                        tax_rate: 20
                    });
                    
                    this.$nextTick(() => {
                        this.renderItems();
                        this.calculateTotals();
                    });
                },

                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                        this.renderItems();
                        this.calculateTotals();
                    } else {
                        alert('At least one invoice item is required.');
                    }
                },

                renderItems() {
                    const container = this.$refs.itemsContainer;
                    container.innerHTML = '';
                    
                    this.items.forEach((item, index) => {
                        const row = document.createElement('tr');
                        row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors';
                        row.innerHTML = `
                            <td class="px-6 py-4">
                                <input type="text" name="items[${index}][description]" 
                                       placeholder="Product/Service Description" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" 
                                       value="${item.description}" 
                                       @input="items[${index}].description = $event.target.value" 
                                       required>
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" name="items[${index}][quantity]" min="0.01" step="0.01" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" 
                                       value="${item.quantity}" 
                                       @input="items[${index}].quantity = parseFloat($event.target.value) || 0; calculateTotals()" 
                                       required>
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" name="items[${index}][unit_price]" min="0" step="0.01" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" 
                                       value="${item.unit_price}" 
                                       @input="items[${index}].unit_price = parseFloat($event.target.value) || 0; calculateTotals()" 
                                       required>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <input type="number" name="items[${index}][tax_rate]" min="0" step="0.1" 
                                           class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" 
                                           value="${item.tax_rate}" 
                                           @input="items[${index}].tax_rate = parseFloat($event.target.value) || 0; calculateTotals()" 
                                           required>
                                    <span class="ml-2 flex-shrink-0 text-gray-500 dark:text-gray-400">%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900 dark:text-gray-100" x-text="formatCurrency((items[${index}].quantity * items[${index}].unit_price) * (1 + items[${index}].tax_rate / 100))">0.00</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button type="button" @click="removeItem(${index})" 
                                        class="p-1 rounded-lg text-gray-400 hover:text-red-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" 
                                        title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </td>
                        `;
                        container.appendChild(row);
                    });
                },

                calculateTotals() {
                    this.subtotal = 0;
                    this.taxTotal = 0;
                    
                    this.items.forEach(item => {
                        const quantity = parseFloat(item.quantity) || 0;
                        const unitPrice = parseFloat(item.unit_price) || 0;
                        const taxRate = parseFloat(item.tax_rate) || 0;
                        
                        const rowSubtotal = quantity * unitPrice;
                        const rowTax = rowSubtotal * (taxRate / 100);
                        
                        this.subtotal += rowSubtotal;
                        this.taxTotal += rowTax;
                    });
                    
                    this.grandTotal = this.subtotal + this.taxTotal;
                },

                formatCurrency(amount) {
                    return parseFloat(amount || 0).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            }
        }
    </script>
</x-user.layout>