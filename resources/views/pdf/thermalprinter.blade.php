
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans Mono';
            src: url('https://fonts.cdnfonts.com/s/15002/DejaVuSansMono.woff') format('woff');
            font-weight: normal;
            font-style: normal;
        }
        body {
            font-family: 'DejaVu Sans Mono', 'Courier New', monospace;
            font-size: 8pt;
            color: #000;
            line-height: 1.2;
            margin: 0;
            padding: 0;
            width: 48mm; /* 2 inches */
        }
        .invoice-box {
            width: 44mm; /* 48mm - 2mm margins */
            margin: 2mm;
        }
        .header {
            text-align: center;
            margin-bottom: 5px;
        }
        .header-logo img {
            width: 80px;
            height: auto;
        }
        .company-name {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .header-info {
            font-size: 7pt;
            margin-bottom: 3px;
        }
        .invoice-title {
            font-size: 9pt;
            font-weight: bold;
            text-align: center;
            margin: 5px 0;
        }
        .info-container {
            display: block;
            margin-bottom: 5px;
        }
        .info-box {
            font-size: 7pt;
            margin-bottom: 5px;
        }
        .info-box h3 {
            font-size: 8pt;
            font-weight: bold;
            margin: 0 0 3px;
        }
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7pt;
            margin: 5px 0;
        }
        .items-table th, .items-table td {
            padding: 2px;
            text-align: left;
        }
        .items-table th {
            font-weight: bold;
        }
        .items-table tr.subtotal {
            font-weight: bold;
        }
        .gst-box {
            margin: 5px 0;
        }
        table.gst-summary {
            width: 100%;
            border-collapse: collapse;
            font-size: 7pt;
        }
        .gst-summary th, .gst-summary td {
            padding: 2px;
            text-align: center;
        }
        .gst-summary th {
            font-weight: bold;
        }
        .gst-summary tr.total {
            font-weight: bold;
        }
        .terms {
            font-size: 7pt;
            margin: 5px 0;
        }
        .terms h3 {
            font-size: 8pt;
            margin: 0 0 3px;
        }
        table.totals {
            width: 100%;
            font-size: 7pt;
            border-collapse: collapse;
            margin: 5px 0;
        }
        .totals th, .totals td {
            padding: 2px;
        }
        .totals th {
            font-weight: bold;
            text-align: left;
        }
        .totals tr.total {
            font-weight: bold;
        }
        .signature {
            margin-top: 10px;
            text-align: center;
            font-size: 7pt;
        }
        .signature-line {
            width: 100px;
            border-top: 1px solid #000;
            margin: 0 auto;
        }
        /* Page break handling */
        tr { page-break-inside: avoid; }
        table { page-break-inside: auto; }
        @media print {
            body { margin: 0; width: 48mm; }
            .invoice-box { margin: 2mm; }
            .header-logo img { width: 80px; }
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
            $taxableValue = $items->sum(fn($item) => $item->quantity * $item->unit_price);
            $gstRate = $items->first()->product->gst_rate ?? ($invoice->tax_percentage ?? 0);
            $gstAmount = $items->sum(fn($item) => ($item->quantity * $item->unit_price) * ($gstRate / 100));
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
                <div class="company-name">{{ $company->name ?? 'Your Company Name' }}</div>
            @endif
            <div class="header-info">
                <div>{{ $company->formatted_address ?? '123 Business St, City' }}</div>
                @if($company && $company->gstin)
                    <div>GSTIN: {{ $company->gstin }}</div>
                @endif
                @if($company && $company->phone)
                    <div>Ph: {{ $company->phone }}</div>
                @endif
            </div>
        </div>

        <div class="invoice-title">
            {{ $invoice->isCashSale() ? 'CASH MEMO' : 'TAX INVOICE' }} #{{ $invoice->invoice_number }}
        </div>

        <div class="info-container">
            <div class="info-box">
                <h3>Bill To</h3>
                <p>{{ $invoice->partie->name }}</p>
                @if($invoice->partie->gstin)
                    <p>GSTIN: {{ $invoice->partie->gstin }}</p>
                @endif
            </div>
            <div class="info-box">
                <h3>Details</h3>
                <p>Date: {{ date('d-m-Y', strtotime($invoice->invoice_date)) }}</p>
                <p>Status: {{ ucfirst($invoice->payment_status) }}</p>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>HSN</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Amt</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ Str::limit($item->product->name, 12) }}</td>
                        <td>{{ $item->product->hsn_code ?? 'N/A' }}</td>
                        <td>{{ number_format($item->quantity, 0) }}</td>
                        <td>₹{{ number_format($item->unit_price, 2) }}</td>
                        <td>₹{{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
                <tr class="subtotal">
                    <td colspan="3">Subtotal</td>
                    <td>{{ number_format($invoice->items->sum('quantity'), 0) }}</td>
                    <td>-</td>
                    <td>₹{{ number_format($invoice->items->sum('total'), 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="gst-box">
            <h3>GST Summary</h3>
            <table class="gst-summary">
                <thead>
                    <tr>
                        <th>HSN</th>
                        <th>Tax Val</th>
                        <th>CGST%</th>
                        <th>CGST</th>
                        <th>SGST%</th>
                        <th>SGST</th>
                        <th>Tax</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gstSummary as $hsn => $summary)
                        <tr>
                            <td>{{ $hsn ?? 'N/A' }}</td>
                            <td>₹{{ number_format($summary['taxable_value'], 2) }}</td>
                            <td>{{ number_format($summary['cgst_rate'], 1) }}</td>
                            <td>₹{{ number_format($summary['cgst_amount'], 2) }}</td>
                            <td>{{ number_format($summary['sgst_rate'], 1) }}</td>
                            <td>₹{{ number_format($summary['sgst_amount'], 2) }}</td>
                            <td>₹{{ number_format($summary['total_tax'], 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="total">
                        <td>Total</td>
                        <td>₹{{ number_format(array_sum(array_column($gstSummary, 'taxable_value')), 2) }}</td>
                        <td>-</td>
                        <td>₹{{ number_format(array_sum(array_column($gstSummary, 'cgst_amount')), 2) }}</td>
                        <td>-</td>
                        <td>₹{{ number_format(array_sum(array_column($gstSummary, 'sgst_amount')), 2) }}</td>
                        <td>₹{{ number_format(array_sum(array_column($gstSummary, 'total_tax')), 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($invoice->terms_conditions)
            <div class="terms">
                <h3>Terms</h3>
                <p>{{ Str::limit($invoice->terms_conditions, 100) }}</p>
            </div>
        @endif

        <table class="totals">
            <tr>
                <th>Subtotal:</th>
                <td align="right">₹{{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            @if($invoice->discount_amount > 0)
                <tr>
                    <th>Discount ({{ number_format($invoice->discount_percentage, 1) }}%):</th>
                    <td align="right">₹{{ number_format($invoice->discount_amount, 2) }}</td>
                </tr>
            @endif
            <tr>
                <th>Tax ({{ number_format($invoice->tax_percentage, 1) }}%):</th>
                <td align="right">₹{{ number_format($invoice->tax_amount, 2) }}</td>
            </tr>
            @if($invoice->round_off != 0)
                <tr>
                    <th>Round Off:</th>
                    <td align="right">₹{{ number_format($invoice->round_off, 2) }}</td>
                </tr>
            @endif
            <tr class="total">
                <th>Total:</th>
                <td align="right">₹{{ number_format($invoice->total, 2) }}</td>
            </tr>
            @if($invoice->payment_status === 'paid')
                <tr>
                    <th>Paid:</th>
                    <td align="right">₹{{ number_format($invoice->paid_amount, 2) }}</td>
                </tr>
                <tr>
                    <th>Due:</th>
                    <td align="right">₹{{ number_format($invoice->balance_amount, 2) }}</td>
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