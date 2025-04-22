<x-app-layout>
    <x-unified-header />
    
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-md">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('user.invoices.update', $invoice) }}" method="POST" id="invoiceForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Invoice Number -->
                            <div>
                                <x-input-label for="invoice_number" :value="__('Invoice Number')" />
                                <x-text-input id="invoice_number" name="invoice_number" type="text" class="mt-1 block w-full" :value="old('invoice_number', $invoice->invoice_number)" required />
                                <x-input-error :messages="$errors->get('invoice_number')" class="mt-2" />
                            </div>

                            <!-- Invoice Date -->
                            <div>
                                <x-input-label for="invoice_date" :value="__('Invoice Date')" />
                                <x-text-input id="invoice_date" name="invoice_date" type="date" class="mt-1 block w-full" :value="old('invoice_date', $invoice->invoice_date->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('invoice_date')" class="mt-2" />
                            </div>

                            <!-- Due Date -->
                            <div>
                                <x-input-label for="due_date" :value="__('Due Date')" />
                                <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full" :value="old('due_date', $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '')" />
                                <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                            </div>

                            <!-- Currency -->
                            <div>
                                <x-input-label for="currency" :value="__('Currency')" />
                                <select id="currency" name="currency" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="TRY" {{ old('currency', $invoice->currency) == 'TRY' ? 'selected' : '' }}>TRY - Turkish Lira</option>
                                    <option value="USD" {{ old('currency', $invoice->currency) == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                    <option value="EUR" {{ old('currency', $invoice->currency) == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                    <option value="GBP" {{ old('currency', $invoice->currency) == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                </select>
                                <x-input-error :messages="$errors->get('currency')" class="mt-2" />
                            </div>

                            <!-- Invoice Language -->
                            <div>
                                <x-input-label for="language_code" :value="__('Invoice Language')" />
                                <select id="language_code" name="language_code" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    @foreach($languages as $code => $language)
                                        <option value="{{ $code }}" {{ old('language_code', $invoice->language_code) == $code ? 'selected' : '' }}>{{ $language }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('language_code')" class="mt-2" />
                            </div>

                            <!-- Company -->
                            <div>
                                <x-input-label for="company_id" :value="__('Your Company')" />
                                <select id="company_id" name="company_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="">{{ __('Select Company') }}</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ old('company_id', $invoice->company_id) == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('company_id')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Client Information</h3>
                            
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                                @if($invoice->client_id)
                                    <!-- Kayıtlı müşteri bilgileri -->
                                    <div class="flex items-center mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span class="font-semibold">Client: </span>
                                        <span class="ml-2">
                                            @php
                                                $client = \App\Models\UserClient::find($invoice->client_id);
                                                echo $client ? $client->name : 'Unknown client';
                                            @endphp
                                        </span>
                                    </div>
                                    
                                    @if($client && $client->vat_number)
                                    <div class="flex items-center mb-2 ml-7">
                                        <span class="font-semibold">VAT/Tax Number: </span>
                                        <span class="ml-2">{{ $client->vat_number }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($client && $client->address)
                                    <div class="flex items-start mb-2 ml-7">
                                        <span class="font-semibold">Address: </span>
                                        <span class="ml-2">{{ $client->address }}</span>
                                    </div>
                                    @endif
                                    
                                    <div class="mt-2 ml-7 text-sm text-gray-600 dark:text-gray-400">
                                        <em>Note: Client information cannot be changed after invoice creation. To use different client, please create a new invoice.</em>
                                    </div>
                                @else
                                    <!-- Manuel girilen müşteri bilgileri -->
                                    <div class="flex items-center mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span class="font-semibold">Client Name: </span>
                                        <span class="ml-2">{{ $invoice->client_name }}</span>
                                    </div>
                                    
                                    @if($invoice->client_vat_number)
                                    <div class="flex items-center mb-2 ml-7">
                                        <span class="font-semibold">VAT/Tax Number: </span>
                                        <span class="ml-2">{{ $invoice->client_vat_number }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($invoice->client_address)
                                    <div class="flex items-start mb-2 ml-7">
                                        <span class="font-semibold">Address: </span>
                                        <span class="ml-2">{{ $invoice->client_address }}</span>
                                    </div>
                                    @endif
                                    
                                    <div class="mt-2 ml-7 text-sm text-gray-600 dark:text-gray-400">
                                        <em>Note: Client information cannot be changed after invoice creation. To use different client, please create a new invoice.</em>
                                    </div>
                                @endif
                                
                                <!-- Gizli input alanları - Form gönderiminde orijinal değerleri korumak için -->
                                @if($invoice->client_id)
                                    <input type="hidden" name="client_type" value="registered">
                                    <input type="hidden" name="client_id" value="{{ $invoice->client_id }}">
                                @else
                                    <input type="hidden" name="client_type" value="manual">
                                    <input type="hidden" name="client_name" value="{{ $invoice->client_name }}">
                                    <input type="hidden" name="vat_number" value="{{ $invoice->client_vat_number }}">
                                    <input type="hidden" name="client_address" value="{{ $invoice->client_address }}">
                                    <input type="hidden" name="client_email" value="{{ $invoice->client_email }}">
                                    <input type="hidden" name="client_phone" value="{{ $invoice->client_phone }}">
                                    <input type="hidden" name="client_company_reg_number" value="{{ $invoice->client_company_reg_number }}">
                                    <input type="hidden" name="client_country" value="{{ $invoice->client_country }}">
                                @endif
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Invoice Items</h3>
                            
                            <div class="overflow-x-auto mb-4">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="itemsTable">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Description
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-24">
                                                Quantity
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-32">
                                                Unit Price
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-24">
                                                VAT %
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-32">
                                                Amount
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-16">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="itemsContainer">
                                        <!-- JS ile doldurulacak -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6" class="px-6 py-4">
                                                <button type="button" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-300 disabled:opacity-25 transition" id="addItemButton">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                    Add Item
                                                </button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            
                            <div class="flex justify-end mb-4">
                                <div class="w-full md:w-64">
                                    <div class="flex justify-between py-2 border-b dark:border-gray-700">
                                        <span class="font-medium">Subtotal:</span>
                                        <span id="subtotal">0,00</span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b dark:border-gray-700">
                                        <span class="font-medium">VAT Total:</span>
                                        <span id="tax_total">0,00</span>
                                    </div>
                                    <div class="flex justify-between py-2 font-bold">
                                        <span>Grand Total:</span>
                                        <span id="grand_total">0,00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3">{{ old('notes', $invoice->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex justify-end">
                            <x-primary-button>
                                Update Invoice
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
            const tax_rate = data.tax_rate || 18;
            
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
                        <input type="number" name="items[${index}][tax_rate]" min="0" step="0.1" class="item-tax-rate w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" value="${tax_rate}" required>
                    </td>
                    <td class="px-6 py-4">
                        <span class="item-total">0,00</span>
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

        // Toggle client fields
        function toggleClientFields() {
            const clientType = document.querySelector('input[name="client_type"]:checked').value;
            
            if (clientType === 'registered') {
                document.getElementById('registered_client_fields').classList.remove('hidden');
                document.getElementById('manual_client_fields').classList.add('hidden');
            } else {
                document.getElementById('registered_client_fields').classList.add('hidden');
                document.getElementById('manual_client_fields').classList.remove('hidden');
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
                
                row.querySelector('.item-total').textContent = rowTotal.toLocaleString('tr-TR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            });
            
            const grandTotal = subtotal + taxTotal;
            
            document.getElementById('subtotal').textContent = subtotal.toLocaleString('tr-TR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            
            document.getElementById('tax_total').textContent = taxTotal.toLocaleString('tr-TR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            
            document.getElementById('grand_total').textContent = grandTotal.toLocaleString('tr-TR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // When page loads
        document.addEventListener('DOMContentLoaded', function() {
            const itemsContainer = document.getElementById('itemsContainer');
            const addItemButton = document.getElementById('addItemButton');
            
            // Load existing items
            const items = @json($invoice->items);
            
            if (items.length > 0) {
                items.forEach((item, index) => {
                    itemsContainer.insertAdjacentHTML('beforeend', createItemRow(index, {
                        description: item.description,
                        quantity: item.quantity,
                        unit_price: item.unit_price,
                        tax_rate: item.tax_rate
                    }));
                });
                
                attachEventListeners();
                calculateTotals();
            } else {
                // Add first row (if no items)
                addItemButton.click();
            }
            
            // Add new item
            addItemButton.addEventListener('click', function() {
                const index = document.querySelectorAll('.item-row').length;
                itemsContainer.insertAdjacentHTML('beforeend', createItemRow(index));
                attachEventListeners();
                calculateTotals();
            });
            
            // Add event listeners
            function attachEventListeners() {
                // Remove item buttons
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
                            alert('You must have at least one invoice item.');
                        }
                    });
                });
                
                // Value changes
                document.querySelectorAll('.item-quantity, .item-unit-price, .item-tax-rate').forEach(input => {
                    input.addEventListener('input', calculateTotals);
                });
            }
        });
    </script>
</x-app-layout> 
