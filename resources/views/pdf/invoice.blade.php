<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }} - {{ $company->name ?? 'Your Company Name' }}</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('https://fonts.cdnfonts.com/s/15003/DejaVuSans.woff') format('woff');
            font-weight: normal;
            font-style: normal;
        }
        body {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #eee;
            background-color: #fff;
        }
        .header {
            display: table;
            width: 100%;
            border-bottom: 2px solid #1E40AF; /* Brand accent color */
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header-logo {
            display: table-cell;
            vertical-align: middle;
            width: 120px;
        }
        .header-logo img {
            max-width: 100px;
            max-height: 60px;
            object-fit: contain;
        }
        .header-info {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            padding-left: 20px;
            font-size: 10px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #1E40AF; /* Brand accent color */
            margin-bottom: 5px;
        }
        .invoice-title {
            text-align: center;
            margin: 15px 0;
            padding: 8px 0;
            background-color: #EFF6FF; /* Light brand color */
            font-size: 16px;
            font-weight: bold;
            color: #1F2937;
            border-radius: 4px;
        }
        .info-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-collapse: separate;
            border-spacing: 0;
        }
        .info-box {
            display: table-cell;
            width: 50%;
            padding: 10px;
            border: 1px solid #ddd;
            vertical-align: top;
            font-size: 10px;
        }
        .info-box h3 {
            margin: 0 0 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            font-size: 12px;
            color: #1E40AF; /* Brand accent */
        }
        .info-box p {
            margin: 5px 0;
        }
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 10px;
            table-layout: fixed;
        }
        .items-table th {
            background-color: #DBEAFE; /* Brand secondary color */
            color: #1F2937;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
            text-transform: uppercase;
        }
        .items-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .items-table tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        .items-table tr.subtotal {
            background-color: #E5E7EB;
            font-weight: bold;
        }
        .gst-box {
            margin: 20px 0;
        }
        table.gst-summary {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .gst-summary th {
            background-color: #DBEAFE; /* Brand secondary color */
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
            font-weight: bold;
            color: #1F2937;
        }
        .gst-summary td {
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .gst-summary tr.total {
            background-color: #EFF6FF; /* Light brand color */
            font-weight: bold;
        }
        .terms {
            margin: 20px 0;
            font-size: 10px;
        }
        .terms h3 {
            margin: 0 0 10px;
            font-size: 12px;
            color: #1E40AF; /* Brand accent */
        }
        table.totals {
            width: 300px;
            margin-left: auto;
            margin-right: 0;
            font-size: 10px;
            border-collapse: collapse;
        }
        .totals th, .totals td {
            padding: 5px;
            border: 1px solid #ddd;
        }
        .totals th {
            background-color: #DBEAFE; /* Brand secondary color */
            text-align: left;
        }
        .totals tr.total {
            font-weight: bold;
            background-color: #EFF6FF; /* Light brand color */
        }
        .signature {
            margin-top: 40px;
            text-align: right;
            font-size: 10px;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #1E40AF; /* Brand accent */
            margin-left: auto;
            margin-right: 0;
        }
        /* Column widths for items table */
        col.no { width: 5%; }
        col.description { width: 35%; }
        col.hsn { width: 15%; }
        col.quantity { width: 10%; }
        col.rate { width: 15%; }
        col.amount { width: 20%; }
        /* Column widths for GST summary */
        col.hsn-code { width: 15%; }
        col.taxable-value { width: 20%; }
        col.cgst-rate { width: 15%; }
        col.cgst-amount { width: 15%; }
        col.sgst-rate { width: 15%; }
        col.sgst-amount { width: 15%; }
        col.total-tax { width: 15%; }
        /* Page break handling */
        tr { page-break-inside: avoid; }
        table.items-table, table.gst-summary { page-break-inside: auto; }
        @media print {
            body { margin: 10mm; }
            .invoice-box { border: none; padding: 10mm; }
            .header-logo img { max-width: 80px; max-height: 48px; }
        }
    </style>
</head>
<body>
    @php
        $company = $invoice->getCompany();
        // Convert logo path to absolute file path for PDF libraries
        $logoPath = $company && $company->logo && file_exists(storage_path('app/public/' . $company->logo))
            ? public_path('storage/' . $company->logo)
            : null;
        $gstSummary = $invoice->items->groupBy('product.hsn_code')->map(function ($items, $hsn) use ($invoice) {
            $taxableValue = $items->sum(fn($item) => (float)$item->quantity * (float)$item->unit_price);
            $gstRate = (float)($items->first()->product->gst_rate ?? 0);
            $gstAmount = $items->sum(fn($item) => ((float)$item->quantity * (float)$item->unit_price) * ($gstRate / 100));
            return [
                'taxable_value' => $taxableValue,
                'cgst_rate' => $gstRate / 2,
                'cgst_amount' => $gstAmount / 2,
                'sgst_rate' => $gstRate / 2,
                'sgst_amount' => $gstAmount / 2,
                'total_tax' => $gstAmount,
            ];
        })->toArray();
    @endphp

    <div class="invoice-box">
        <div class="header">
            @if($logoPath)
                <div class="header-logo">
                    <img src="{{ $logoPath }}" alt="{{ $company->name ?? 'Company' }} Logo">
                </div>
            @else
                <div class="header-logo">
                    <span>{{ $company->name ?? 'Your Company Name' }}</span>
                </div>
            @endif
            <div class="header-info">
                <div class="company-name">{{ $company->name ?? 'Your Company Name' }}</div>
                <div>{{ $company->formatted_address ?? '123 Business Street, City, State, PIN' }}</div>
                @if($company && $company->phone)
                    <div>Phone: {{ $company->phone }}</div>
                @endif
                @if($company && $company->email)
                    <div>Email: {{ $company->email }}</div>
                @endif
                @if($company && $company->website)
                    <div>Website: {{ $company->website }}</div>
                @endif
                @if($company && $company->gstin)
                    <div>GSTIN: {{ $company->gstin }}</div>
                @endif
            </div>
        </div>

        <div class="invoice-title">
            {{ $invoice->isCashSale() ? 'CASH MEMO' : 'TAX INVOICE' }} #{{ $invoice->invoice_number }}
        </div>

        <div class="info-container">
            <div class="info-box">
                <h3>Bill To</h3>
                <p><strong>{{ $invoice->partie->name }}</strong></p>
                <p>{{ $invoice->partie->address ?? 'N/A' }}</p>
                <p>Phone: {{ $invoice->partie->phone ?? 'N/A' }}</p>
                @if($invoice->partie->gstin)
                    <p>GSTIN: {{ $invoice->partie->gstin }}</p>
                @endif
            </div>
            <div class="info-box">
                <h3>Invoice Details</h3>
                <p><strong>Date:</strong> {{ date('d-m-Y', strtotime($invoice->invoice_date)) }}</p>
                <p><strong>Due Date:</strong> {{ date('d-m-Y', strtotime($invoice->due_date)) }}</p>
                <p><strong>Status:</strong> {{ ucfirst($invoice->payment_status) }}</p>
                @if(!empty($invoice->payment_terms))
                    <p><strong>Terms:</strong> {{ $invoice->payment_terms }}</p>
                @endif
            </div>
        </div>

        <table class="items-table">
            <colgroup>
                <col class="no">
                <col class="description">
                <col class="hsn">
                <col class="quantity">
                <col class="rate">
                <col class="amount">
            </colgroup>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Description</th>
                    <th>HSN</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->product->hsn_code ?? 'N/A' }}</td>
                        <td>{{ number_format((float)$item->quantity, 0) }}</td>
                        <td>₹{{ number_format((float)$item->unit_price, 2) }}</td>
                        <td>₹{{ number_format((float)$item->total, 2) }}</td>
                    </tr>
                @endforeach
                <tr class="subtotal">
                    <td colspan="3">Subtotal</td>
                    <td>{{ number_format($invoice->items->sum(function($item) { return (float)$item->quantity; }), 0) }}</td>
                    <td>-</td>
                    <td>₹{{ number_format($invoice->items->sum(function($item) { return (float)$item->total; }), 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="gst-box">
            <h3>GST Summary</h3>
            <table class="gst-summary">
                <colgroup>
                    <col class="hsn-code">
                    <col class="taxable-value">
                    <col class="cgst-rate">
                    <col class="cgst-amount">
                    <col class="sgst-rate">
                    <col class="sgst-amount">
                    <col class="total-tax">
                </colgroup>
                <thead>
                    <tr>
                        <th>HSN</th>
                        <th>Taxable Value</th>
                        <th>CGST %</th>
                        <th>CGST Amt</th>
                        <th>SGST %</th>
                        <th>SGST Amt</th>
                        <th>Total Tax</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gstSummary as $hsn => $summary)
                        <tr>
                            <td>{{ $hsn ?? 'N/A' }}</td>
                            <td>₹{{ number_format((float)$summary['taxable_value'], 2) }}</td>
                            <td>{{ number_format((float)$summary['cgst_rate'], 2) }}%</td>
                            <td>₹{{ number_format((float)$summary['cgst_amount'], 2) }}</td>
                            <td>{{ number_format((float)$summary['sgst_rate'], 2) }}%</td>
                            <td>₹{{ number_format((float)$summary['sgst_amount'], 2) }}</td>
                            <td>₹{{ number_format((float)$summary['total_tax'], 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="total">
                        <td>Total</td>
                        <td>₹{{ number_format(array_sum(array_map(function($s) { return (float)$s['taxable_value']; }, $gstSummary)), 2) }}</td>
                        <td>-</td>
                        <td>₹{{ number_format(array_sum(array_map(function($s) { return (float)$s['cgst_amount']; }, $gstSummary)), 2) }}</td>
                        <td>-</td>
                        <td>₹{{ number_format(array_sum(array_map(function($s) { return (float)$s['sgst_amount']; }, $gstSummary)), 2) }}</td>
                        <td>₹{{ number_format(array_sum(array_map(function($s) { return (float)$s['total_tax']; }, $gstSummary)), 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($invoice->terms_conditions)
            <div class="terms">
                <h3>Terms & Conditions</h3>
                <p>{{ $invoice->terms_conditions }}</p>
            </div>
        @endif

        <table class="totals">
            <tr>
                <th>Subtotal:</th>
                <td align="right">₹{{ number_format((float)$invoice->subtotal, 2) }}</td>
            </tr>
            @if((float)$invoice->discount_amount > 0)
                <tr>
                    <th>Discount ({{ number_format((float)$invoice->discount_percentage, 2) }}%):</th>
                    <td align="right">₹{{ number_format((float)$invoice->discount_amount, 2) }}</td>
                </tr>
            @endif
            @php
                // Calculate total effective GST rate from GST summary
                $totalTaxableValue = array_sum(array_map(function($s) { return (float)$s['taxable_value']; }, $gstSummary));
                $totalTaxAmount = array_sum(array_map(function($s) { return (float)$s['total_tax']; }, $gstSummary));
                $effectiveGstRate = $totalTaxableValue > 0 ? ($totalTaxAmount / $totalTaxableValue) * 100 : 0;
            @endphp
            <tr>
                <!-- <th>Tax ({{ number_format($effectiveGstRate, 2) }}%):</th> -->
                <th>Tax :</th>
                <td align="right">₹{{ number_format((float)$invoice->tax_amount, 2) }}</td>
            </tr>
            @if((float)$invoice->round_off != 0)
                <tr>
                    <th>Round Off:</th>
                    <td align="right">₹{{ number_format((float)$invoice->round_off, 2) }}</td>
                </tr>
            @endif
            <tr class="total">
                <th>Total Amount:</th>
                <td align="right">₹{{ number_format((float)$invoice->total, 2) }}</td>
            </tr>
            @if($invoice->payment_status === 'paid')
                <tr>
                    <th>Paid Amount:</th>
                    <td align="right">₹{{ number_format((float)$invoice->paid_amount, 2) }}</td>
                </tr>
                <tr>
                    <th>Balance Due:</th>
                    <td align="right">₹{{ number_format((float)$invoice->balance_amount, 2) }}</td>
                </tr>
            @endif
        </table>

        <div class="signature">
            <div class="signature-line"></div>
            <p>Authorized Signatory</p>
        </div>
    </div>
</body>
</html>
