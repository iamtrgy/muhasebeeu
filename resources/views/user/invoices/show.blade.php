<x-app-layout>
    <x-unified-header />
    
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Invoice #{{ $invoice->invoice_number }}</h2>
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                <p>Created Date: {{ $invoice->created_at->format('d.m.Y H:i') }}</p>
                                <p>Status: 
                                    @if($invoice->status == 'draft')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            Draft
                                        </span>
                                    @elseif($invoice->status == 'sent')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            Sent
                                        </span>
                                    @elseif($invoice->status == 'paid')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Paid
                                        </span>
                                    @elseif($invoice->status == 'cancelled')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            Cancelled
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('user.invoices.edit', $invoice) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>
                            <a href="{{ route('user.invoices.download-pdf', $invoice) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download PDF
                            </a>
                            <form action="{{ route('user.invoices.regenerate-pdf', $invoice) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-500 active:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring focus:ring-yellow-300 disabled:opacity-25 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Regenerate PDF
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="border dark:border-gray-700 rounded-lg p-4">
                            <h3 class="font-semibold text-lg mb-2 border-b dark:border-gray-700 pb-2">Invoice Information</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Invoice Date</p>
                                    <p class="font-medium">{{ $invoice->invoice_date->format('d.m.Y') }}</p>
                                </div>
                                @if($invoice->due_date)
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Due Date</p>
                                    <p class="font-medium">{{ $invoice->due_date->format('d.m.Y') }}</p>
                                </div>
                                @endif
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Currency</p>
                                    <p class="font-medium">{{ $invoice->currency }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Invoice Language</p>
                                    <p class="font-medium">
                                        @if($invoice->language_code == 'tr')
                                            Turkish
                                        @elseif($invoice->language_code == 'en')
                                            English
                                        @elseif($invoice->language_code == 'de')
                                            German
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="border dark:border-gray-700 rounded-lg p-4">
                            <h3 class="font-semibold text-lg mb-2 border-b dark:border-gray-700 pb-2">Company Information</h3>
                            <p class="font-bold">{{ $invoice->company->name }}</p>
                            <p class="text-sm mt-1">Registry Number: {{ $invoice->company->tax_number }}</p>
                            @if($invoice->company->vat_number)
                                <p class="text-sm">VAT Number: {{ $invoice->company->vat_number }}</p>
                            @endif
                            <p class="text-sm mt-1">{{ $invoice->company->address }}</p>
                        </div>

                        <div class="border dark:border-gray-700 rounded-lg p-4">
                            <h3 class="font-semibold text-lg mb-2 border-b dark:border-gray-700 pb-2">Client Information</h3>
                            @if($invoice->client_id)
                                @php
                                    $client = \App\Models\UserClient::find($invoice->client_id);
                                @endphp
                                @if($client)
                                    <p class="font-bold">{{ $client->name }}</p>
                                    @if($client->vat_number)
                                        <p class="text-sm mt-1">VAT Number: {{ $client->vat_number }}</p>
                                    @endif
                                    @if($client->company_reg_number)
                                        <p class="text-sm">Company Reg. Number: {{ $client->company_reg_number }}</p>
                                    @endif
                                    @if($client->email)
                                        <p class="text-sm mt-1">Email: {{ $client->email }}</p>
                                    @endif
                                    @if($client->phone)
                                        <p class="text-sm">Phone: {{ $client->phone }}</p>
                                    @endif
                                    @if($client->country)
                                        <p class="text-sm">Country: {{ $client->country }}</p>
                                    @endif
                                    @if($client->address)
                                        <p class="text-sm mt-1">Address: {{ $client->address }}</p>
                                    @endif
                                @else
                                    <p class="text-sm text-gray-500">Client information not available.</p>
                                @endif
                            @elseif($invoice->client_name)
                                <p class="font-bold">{{ $invoice->client_name }}</p>
                                @if($invoice->client_vat_number)
                                    <p class="text-sm mt-1">VAT Number: {{ $invoice->client_vat_number }}</p>
                                @endif
                                @if($invoice->client_company_reg_number)
                                    <p class="text-sm">Company Reg. Number: {{ $invoice->client_company_reg_number }}</p>
                                @endif
                                @if($invoice->client_email)
                                    <p class="text-sm mt-1">Email: {{ $invoice->client_email }}</p>
                                @endif
                                @if($invoice->client_phone)
                                    <p class="text-sm">Phone: {{ $invoice->client_phone }}</p>
                                @endif
                                @if($invoice->client_country)
                                    <p class="text-sm">Country: {{ $invoice->client_country }}</p>
                                @endif
                                @if($invoice->client_address)
                                    <p class="text-sm mt-1">Address: {{ $invoice->client_address }}</p>
                                @endif
                            @else
                                <p class="text-sm text-gray-500">No client information available.</p>
                            @endif
                        </div>

                        <div class="border dark:border-gray-700 rounded-lg p-4">
                            <h3 class="font-semibold text-lg mb-2 border-b dark:border-gray-700 pb-2">Total</h3>
                            <div class="flex justify-between py-2">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Subtotal:</span>
                                <span class="font-medium">{{ number_format($invoice->subtotal, 2, ',', '.') }} {{ $invoice->currency }}</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-sm text-gray-500 dark:text-gray-400">VAT Total:</span>
                                <span class="font-medium">{{ number_format($invoice->tax_amount, 2, ',', '.') }} {{ $invoice->currency }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-t dark:border-gray-700 font-bold">
                                <span>Grand Total:</span>
                                <span>{{ number_format($invoice->total, 2, ',', '.') }} {{ $invoice->currency }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="border dark:border-gray-700 rounded-lg p-4 mb-8">
                        <h3 class="font-semibold text-lg mb-4 border-b dark:border-gray-700 pb-2">Invoice Items</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Description
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Quantity
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Unit Price
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            VAT %
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            VAT Amount
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($invoice->items()->orderBy('sort_order')->get() as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-normal">
                                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $item->description }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">
                                                {{ number_format($item->quantity, 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">
                                                {{ number_format($item->unit_price, 2, ',', '.') }} {{ $invoice->currency }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">
                                                %{{ number_format($item->tax_rate, 0) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">
                                                {{ number_format($item->tax_amount, 2, ',', '.') }} {{ $invoice->currency }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ number_format($item->total, 2, ',', '.') }} {{ $invoice->currency }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-50 dark:bg-gray-700">
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            Subtotal:
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">
                                            {{ number_format($invoice->tax_amount, 2, ',', '.') }} {{ $invoice->currency }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ number_format($invoice->subtotal, 2, ',', '.') }} {{ $invoice->currency }}
                                        </td>
                                    </tr>
                                    <tr class="bg-gray-50 dark:bg-gray-700">
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            VAT Total:
                                        </td>
                                        <td colspan="2" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ number_format($invoice->tax_amount, 2, ',', '.') }} {{ $invoice->currency }}
                                        </td>
                                    </tr>
                                    <tr class="bg-gray-100 dark:bg-gray-600">
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold">
                                            Grand Total:
                                        </td>
                                        <td colspan="2" class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900 dark:text-gray-100">
                                            {{ number_format($invoice->total, 2, ',', '.') }} {{ $invoice->currency }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    @if($invoice->notes)
                        <div class="border dark:border-gray-700 rounded-lg p-4">
                            <h3 class="font-semibold text-lg mb-2 border-b dark:border-gray-700 pb-2">Notes</h3>
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $invoice->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($invoice->pdf_path)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="font-semibold text-lg mb-4">File Information</h3>
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-10 h-10 text-red-600 dark:text-red-400 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 2v7a2 2 0 002 2h4"></path>
                                </svg>
                                <div>
                                    <p class="font-medium">Invoice PDF</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $invoice->pdf_path }}</p>
                                </div>
                            </div>
                            <a href="{{ route('user.invoices.download-pdf', $invoice) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 
