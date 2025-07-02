<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
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
        }
        
        /* Simple header */
        .header {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: right;
        }
        
        /* Clean invoice title */
        .invoice-title {
            text-align: center;
            margin: 15px 0;
            padding: 8px 0;
            background-color: #f5f5f5;
            font-size: 16px;
            font-weight: bold;
        }
        
        /* Side-by-side boxes for Bill To and Invoice Details */
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
        }
        .info-box h3 {
            margin-top: 0;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            font-size: 14px;
        }
        .info-box p {
            margin: 5px 0;
        }
        
        /* Clean table design */
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th {
            background-color: #f2f2f2;
            color: #333;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .items-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        
        /* Simple GST summary */
        .gst-box {
            margin: 20px 0;
        }
        .gst-summary {
            width: 100%;
            border-collapse: collapse;
        }
        .gst-summary th {
            background-color: #f2f2f2;
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .gst-summary td {
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
        }
        
        /* Terms and conditions */
        .terms {
            margin: 20px 0;
        }
        
        /* Totals and signature */
        .totals {
            width: 300px;
            margin-left: auto;
            margin-right: 0;
        }
        .totals td {
            padding: 5px;
        }
        .totals tr.total {
            font-weight: bold;
        }
        
        .signature {
            margin-top: 40px;
            text-align: right;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #000;
            margin-left: auto;
            margin-right: 0;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <div>Your Company Name</div>
            <div>123 Business Street, City, State, PIN</div>
            <div>Phone: 123-456-7890 | Email: info@yourcompany.com</div>
            <div>GSTIN: 27AAAAA0000A1Z5</div>
        </div>
        
        <div class="invoice-title">
            {{ $invoice->isCashSale() ? 'CASH MEMO' : 'TAX INVOICE' }} #{{ $invoice->invoice_number }}
        </div>

        <!-- Two-column layout for Bill To and Invoice Details -->
        <div class="info-container">
            <div class="info-box">
                <h3>Bill To</h3>
                <p><strong>{{ $invoice->partie->name }}</strong></p>
                <p>{{ $invoice->partie->address }}</p>
                <p>Phone: {{ $invoice->partie->phone }}</p>
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
            <tr>
                <th>No</th>
                <th>Description</th>
                <th>HSN</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Disc</th>
                <th>Amount</th>
            </tr>
            @php $i = 1; @endphp
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->product->hsn_code ?? 'N/A' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rs. {{ number_format($item->unit_price, 2) }}</td>
                    <td>-</td>
                    <td>Rs. {{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </table>

        <div class="gst-box">
            <h3>GST Summary</h3>
            <table class="gst-summary">
                <tr>
                    <th>HSN</th>
                    <th>Taxable Value</th>
                    <th>CGST %</th>
                    <th>CGST Amt</th>
                    <th>SGST %</th>
                    <th>SGST Amt</th>
                    <th>Total Tax</th>
                </tr>
                <tr>
                    <td>Multiple</td>
                    <td>Rs. {{ number_format($invoice->subtotal - $invoice->discount_amount, 2) }}</td>
                    <td>{{ number_format($invoice->tax_percentage / 2, 2) }}%</td>
                    <td>Rs. {{ number_format($invoice->tax_amount / 2, 2) }}</td>
                    <td>{{ number_format($invoice->tax_percentage / 2, 2) }}%</td>
                    <td>Rs. {{ number_format($invoice->tax_amount / 2, 2) }}</td>
                    <td>Rs. {{ number_format($invoice->tax_amount, 2) }}</td>
                </tr>
            </table>
        </div>

        @if($invoice->terms_conditions)
        <div class="terms">
            <h3>Terms & Conditions</h3>
            <p>{{ $invoice->terms_conditions }}</p>
        </div>
        @endif

        <div style="clear: both;"></div>
        
        <table class="totals">
            <tr>
                <td>Subtotal:</td>
                <td align="right">Rs. {{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            @if($invoice->discount_amount > 0)
                <tr>
                    <td>Discount ({{ number_format($invoice->discount_percentage, 2) }}%):</td>
                    <td align="right">-Rs. {{ number_format($invoice->discount_amount, 2) }}</td>
                </tr>
            @endif
            <tr>
                <td>Tax ({{ number_format($invoice->tax_percentage, 2) }}%):</td>
                <td align="right">Rs. {{ number_format($invoice->tax_amount, 2) }}</td>
            </tr>
            @if($invoice->round_off != 0)
                <tr>
                    <td>Round Off:</td>
                    <td align="right">Rs. {{ number_format($invoice->round_off, 2) }}</td>
                </tr>
            @endif
            <tr class="total">
                <td>Total Amount:</td>
                <td align="right">Rs. {{ number_format($invoice->total, 2) }}</td>
            </tr>
            @if($invoice->payment_status === 'paid')
                <tr>
                    <td>Paid Amount:</td>
                    <td align="right">Rs. {{ number_format($invoice->paid_amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Balance Due:</td>
                    <td align="right">Rs. {{ number_format($invoice->balance_amount, 2) }}</td>
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
