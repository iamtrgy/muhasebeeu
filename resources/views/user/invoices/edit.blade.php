<x-user.layout 
    title="{{ __('Edit Invoice') }} #{{ $invoice->invoice_number }}" 
    :breadcrumbs="[
        ['title' => __('Home'), 'href' => route('user.dashboard'), 'first' => true],
        ['title' => __('Invoices'), 'href' => route('user.invoices.index')],
        ['title' => __('Edit Invoice')]
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

        <form action="{{ route('user.invoices.update', $invoice) }}" method="POST" id="invoice-form">
            @csrf
            @method('PUT')
            
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
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Update invoice details and settings') }}</p>
                        </div>
                    </div>
                </x-ui.card.header>
                <x-ui.card.body>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="invoice_number" 
                                label="{{ __('Invoice Number') }}" 
                                value="{{ old('invoice_number', $invoice->invoice_number) }}" 
                                required
                                :error="$errors->first('invoice_number')"
                            />
                        </x-ui.form.group>
                        
                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="invoice_date" 
                                label="{{ __('Invoice Date') }}" 
                                type="date" 
                                value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" 
                                required
                                :error="$errors->first('invoice_date')"
                            />
                        </x-ui.form.group>
                        
                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="due_date" 
                                label="{{ __('Due Date') }}" 
                                type="date" 
                                value="{{ old('due_date', $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '') }}" 
                                :error="$errors->first('due_date')"
                            />
                        </x-ui.form.group>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="reference" 
                                label="{{ __('Reference') }}" 
                                value="{{ old('reference', $invoice->reference) }}" 
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
                                <option value="due_receipt" {{ old('payment_terms', $invoice->payment_terms) == 'due_receipt' ? 'selected' : '' }}>{{ __('Due on Receipt') }}</option>
                                <option value="net_15" {{ old('payment_terms', $invoice->payment_terms) == 'net_15' ? 'selected' : '' }}>{{ __('Net 15 Days') }}</option>
                                <option value="net_30" {{ old('payment_terms', $invoice->payment_terms) == 'net_30' ? 'selected' : '' }}>{{ __('Net 30 Days') }}</option>
                                <option value="net_60" {{ old('payment_terms', $invoice->payment_terms) == 'net_60' ? 'selected' : '' }}>{{ __('Net 60 Days') }}</option>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Regional Settings') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Currency and language preferences') }}</p>
                        </div>
                    </div>
                </x-ui.card.header>
                <x-ui.card.body>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <x-ui.form.group>
                            <x-ui.form.select 
                                name="currency" 
                                label="{{ __('Currency') }}" 
                                required
                                :error="$errors->first('currency')"
                            >
                                <option value="TRY" {{ old('currency', $invoice->currency) == 'TRY' ? 'selected' : '' }}>TRY - Turkish Lira</option>
                                <option value="USD" {{ old('currency', $invoice->currency) == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                <option value="EUR" {{ old('currency', $invoice->currency) == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                <option value="GBP" {{ old('currency', $invoice->currency) == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                            </x-ui.form.select>
                        </x-ui.form.group>
                        
                        <x-ui.form.group>
                            <x-ui.form.select 
                                name="language_code" 
                                label="{{ __('Invoice Language') }}" 
                                required
                                :error="$errors->first('language_code')"
                            >
                                <option value="en" {{ old('language_code', $invoice->language_code) == 'en' ? 'selected' : '' }}>English</option>
                                <option value="de" {{ old('language_code', $invoice->language_code) == 'de' ? 'selected' : '' }}>Deutsch</option>
                                <option value="tr" {{ old('language_code', $invoice->language_code) == 'tr' ? 'selected' : '' }}>Türkçe</option>
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
                                    <option value="{{ $company->id }}" {{ old('company_id', $invoice->company_id) == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                @endforeach
                            </x-ui.form.select>
                        </x-ui.form.group>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Client Information (Read Only) -->
            <x-ui.card.base>
                <x-ui.card.header>
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Client Information') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Client details (read-only)') }}</p>
                        </div>
                    </div>
                </x-ui.card.header>
                <x-ui.card.body>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        @if($invoice->client_id)
                            @php
                                $client = \App\Models\UserClient::find($invoice->client_id);
                            @endphp
                            @if($client)
                                <p class="font-bold text-gray-900 dark:text-gray-100">{{ $client->name }}</p>
                                @if($client->vat_number)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('VAT Number') }}: {{ $client->vat_number }}</p>
                                @endif
                                @if($client->company_reg_number)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Company Reg. Number') }}: {{ $client->company_reg_number }}</p>
                                @endif
                                @if($client->email)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Email') }}: {{ $client->email }}</p>
                                @endif
                                @if($client->phone)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Phone') }}: {{ $client->phone }}</p>
                                @endif
                                @if($client->country)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Country') }}: {{ $client->country }}</p>
                                @endif
                                @if($client->address)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Address') }}: {{ $client->address }}</p>
                                @endif
                            @else
                                <p class="text-sm text-gray-500">{{ __('Client information not available.') }}</p>
                            @endif
                        @elseif($invoice->client_name)
                            <p class="font-bold text-gray-900 dark:text-gray-100">{{ $invoice->client_name }}</p>
                            @if($invoice->client_vat_number)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('VAT Number') }}: {{ $invoice->client_vat_number }}</p>
                            @endif
                            @if($invoice->client_company_reg_number)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Company Reg. Number') }}: {{ $invoice->client_company_reg_number }}</p>
                            @endif
                            @if($invoice->client_email)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Email') }}: {{ $invoice->client_email }}</p>
                            @endif
                            @if($invoice->client_phone)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Phone') }}: {{ $invoice->client_phone }}</p>
                            @endif
                            @if($invoice->client_country)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Country') }}: {{ $invoice->client_country }}</p>
                            @endif
                            @if($invoice->client_address)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Address') }}: {{ $invoice->client_address }}</p>
                            @endif
                        @else
                            <p class="text-sm text-gray-500">{{ __('No client information available.') }}</p>
                        @endif
                        
                        <div class="mt-4 p-3 bg-amber-50 dark:bg-amber-900/20 rounded-md">
                            <p class="text-sm text-amber-800 dark:text-amber-200">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('Note: Client information cannot be changed after invoice creation. To use a different client, please create a new invoice.') }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Hidden fields to preserve client data -->
                    @if($invoice->client_id)
                        <input type="hidden" name="client_type" value="existing">
                        <input type="hidden" name="client_id" value="{{ $invoice->client_id }}">
                    @else
                        <input type="hidden" name="client_type" value="new">
                        <input type="hidden" name="client_name" value="{{ $invoice->client_name }}">
                        <input type="hidden" name="client_vat_number" value="{{ $invoice->client_vat_number }}">
                        <input type="hidden" name="client_address" value="{{ $invoice->client_address }}">
                        <input type="hidden" name="client_email" value="{{ $invoice->client_email }}">
                        <input type="hidden" name="client_phone" value="{{ $invoice->client_phone }}">
                        <input type="hidden" name="client_company_reg_number" value="{{ $invoice->client_company_reg_number }}">
                        <input type="hidden" name="client_country" value="{{ $invoice->client_country }}">
                    @endif
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
                                value="{{ old('notes', $invoice->notes) }}" 
                                rows="3"
                                :error="$errors->first('notes')"
                            />
                        </x-ui.form.group>
                        
                        <x-ui.form.group>
                            <x-ui.form.input 
                                name="payment_url" 
                                label="{{ __('Payment URL (Optional)') }}" 
                                type="url"
                                value="{{ old('payment_url', $invoice->payment_url) }}" 
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
                                :checked="old('reverse_charge', $invoice->reverse_charge)"
                                x-on:change="if ($event.target.checked) vatExempt = false"
                            />
                            <x-ui.form.checkbox 
                                name="vat_exempt" 
                                label="{{ __('VAT exempt (Article 146 EU VAT Directive)') }}" 
                                value="1" 
                                :checked="old('vat_exempt', $invoice->vat_exempt)"
                                x-on:change="if ($event.target.checked) reverseCharge = false"
                            />
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Actions -->
            <div class="mt-6 flex items-center justify-end gap-3">
                <x-ui.button.secondary href="{{ route('user.invoices.show', $invoice) }}">
                    {{ __('Cancel') }}
                </x-ui.button.secondary>
                <x-ui.button.primary type="submit">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('Update Invoice') }}
                </x-ui.button.primary>
            </div>
        </form>
    </div>

    @php
        $invoiceItems = $invoice->items()->orderBy('sort_order')->get()->map(function($item) {
            return [
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'tax_rate' => $item->tax_rate
            ];
        })->toArray();
    @endphp
    
    <script>
        function invoiceForm() {
            return {
                reverseCharge: {{ old('reverse_charge', $invoice->reverse_charge) ? 'true' : 'false' }},
                vatExempt: {{ old('vat_exempt', $invoice->vat_exempt) ? 'true' : 'false' }},
                items: @json($invoiceItems),
                subtotal: 0,
                taxTotal: 0,
                grandTotal: 0,

                init() {
                    this.renderItems();
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
                    this.items.splice(index, 1);
                    this.renderItems();
                    this.calculateTotals();
                },

                renderItems() {
                    const container = this.$refs.itemsContainer;
                    container.innerHTML = '';
                    
                    this.items.forEach((item, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-6 py-4">
                                <input type="text" 
                                       name="items[${index}][description]" 
                                       class="w-full min-w-[200px] border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                       placeholder="Product/Service Description"
                                       value="${item.description}"
                                       required
                                       x-on:input="items[${index}].description = $event.target.value">
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" 
                                       name="items[${index}][quantity]" 
                                       class="w-full min-w-[80px] border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                       min="0.01" 
                                       step="0.01" 
                                       value="${item.quantity}"
                                       required
                                       x-on:input="items[${index}].quantity = $event.target.value; calculateTotals()">
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" 
                                       name="items[${index}][unit_price]" 
                                       class="w-full min-w-[100px] border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                       min="0" 
                                       step="0.01" 
                                       value="${item.unit_price}"
                                       required
                                       x-on:input="items[${index}].unit_price = $event.target.value; calculateTotals()">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <input type="number" 
                                           name="items[${index}][tax_rate]" 
                                           class="w-full min-w-[60px] border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                           min="0" 
                                           step="0.1" 
                                           value="${item.tax_rate}"
                                           required
                                           x-on:input="items[${index}].tax_rate = $event.target.value; calculateTotals()">
                                    <span class="ml-2 flex-shrink-0 text-gray-500 dark:text-gray-400">%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900 dark:text-gray-100" :data-index="index" x-text="formatCurrency((items[index].quantity * items[index].unit_price) * (1 + items[index].tax_rate / 100))">0.00</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button type="button" @click="removeItem(index)" 
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