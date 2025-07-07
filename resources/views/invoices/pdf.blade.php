<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin: 0;
        }
        .invoice-info {
            margin-top: 10px;
        }
        .row {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .section-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            margin-top: 30px;
        }
        .totals table {
            width: 300px;
            float: right;
        }
        .totals td {
            border: none;
            padding: 5px;
        }
        .total-row {
            font-weight: bold;
            font-size: 16px;
            border-top: 2px solid #333;
        }
        .notes {
            clear: both;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="invoice-title">INVOICE</h1>
            <div class="invoice-info">
                <strong>Invoice Number:</strong> {{ $invoice->invoice_number }}<br>
                <strong>Date:</strong> {{ $invoice->invoice_date->format('Y-m-d') }}<br>
                @if($invoice->due_date)
                <strong>Due Date:</strong> {{ $invoice->due_date->format('Y-m-d') }}
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="section-title">From:</div>
                <strong>{{ $invoice->company->name }}</strong><br>
                @if($invoice->company->vat_number)
                    VAT: {{ $invoice->company->vat_number }}<br>
                @endif
                @if($invoice->company->address)
                    {{ $invoice->company->address }}<br>
                @endif
                @if($invoice->company->email)
                    {{ $invoice->company->email }}<br>
                @endif
                @if($invoice->company->phone)
                    {{ $invoice->company->phone }}
                @endif
            </div>
            
            <div class="col">
                <div class="section-title">To:</div>
                @if($invoice->client)
                    <strong>{{ $invoice->client->name }}</strong><br>
                    @if($invoice->client->vat_number)
                        VAT: {{ $invoice->client->vat_number }}<br>
                    @endif
                    @if($invoice->client->address)
                        {{ $invoice->client->address }}<br>
                    @endif
                    @if($invoice->client->email)
                        {{ $invoice->client->email }}<br>
                    @endif
                @else
                    <strong>{{ $invoice->client_name }}</strong><br>
                    @if($invoice->client_vat_number)
                        VAT: {{ $invoice->client_vat_number }}<br>
                    @endif
                    @if($invoice->client_address)
                        {{ $invoice->client_address }}<br>
                    @endif
                    @if($invoice->client_email)
                        {{ $invoice->client_email }}<br>
                    @endif
                @endif
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Tax Rate</th>
                    <th class="text-right">Tax Amount</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="text-right">{{ number_format($item->quantity, 2) }}</td>
                    <td class="text-right">{{ $invoice->currency }} {{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">{{ number_format($item->tax_rate, 0) }}%</td>
                    <td class="text-right">{{ $invoice->currency }} {{ number_format($item->tax_amount, 2) }}</td>
                    <td class="text-right">{{ $invoice->currency }} {{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <table>
                <tr>
                    <td><strong>Subtotal:</strong></td>
                    <td class="text-right">{{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Tax:</strong></td>
                    <td class="text-right">{{ $invoice->currency }} {{ number_format($invoice->tax_amount, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td><strong>Total:</strong></td>
                    <td class="text-right">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</td>
                </tr>
            </table>
        </div>

        @if($invoice->notes)
        <div class="notes">
            <div class="section-title">Notes:</div>
            <p>{!! nl2br(e($invoice->notes)) !!}</p>
        </div>
        @endif
    </div>
</body>
</html>