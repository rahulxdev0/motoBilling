<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Inventory Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 8pt; /* Reduced font size for compactness */
            margin: 15mm; /* Smaller margins to maximize content area */
            color: #333;
        }
        h1 {
            text-align: center;
            font-size: 12pt; /* Smaller header font */
            color: #1F2937;
            margin-bottom: 10mm;
        }
        .stats-container {
            margin-bottom: 10mm;
        }
        .stats-container table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10mm;
            font-size: 8pt; /* Match body font size */
        }
        .stats-container th, .stats-container td {
            border: 1px solid #E5E7EB;
            padding: 2mm; /* Reduced padding */
            text-align: left;
        }
        .stats-container th {
            background-color: #F3F4F6;
            font-weight: bold;
        }
        table.products-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7pt; /* Smaller font for product table */
            table-layout: fixed; /* Fix column widths to prevent overflow */
        }
        table.products-table th, table.products-table td {
            border: 1px solid #E5E7EB;
            padding: 3mm 2mm; /* Reduced padding for compactness */
            text-align: left;
            word-wrap: break-word; /* Allow text to wrap */
        }
        table.products-table th {
            background-color: #F3F4F6;
            font-weight: bold;
            text-transform: uppercase;
        }
        table.products-table tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        .low-stock {
            color: #DC2626;
            font-weight: bold;
        }
        .status-active {
            color: #15803D;
            font-weight: bold;
        }
        .status-inactive {
            color: #DC2626;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 10mm;
            font-size: 7pt;
            color: #6B7280;
        }
        /* Column widths to prevent overflow - Adjusted for new columns */
        col.name { width: 26%; }
        col.item-code { width: 8%; }
        col.sku { width: 8%; }
        col.brand { width: 8%; }
        col.category { width: 8%; }
        col.stock { width: 4%; }
        col.unit { width: 6%; }
        col.selling-price { width: 7%; }
        col.purchase-price { width: 7%; }
        col.mrp { width: 7%; }
        col.hsn-code { width: 8%; }
        col.gst-rate { width: 6%; }
        /* Page break handling */
        tr { page-break-inside: avoid; }
    </style>
</head>
<body>
    <h1>Products Inventory Report</h1>
    <div class="stats-container">
        <table>
            <tr>
                <th>Total Items</th>
                <td>{{ $productStats['total_items'] }} Active products</td>
            </tr>
            <tr>
                <th>Total Stock Value</th>
                <td>₹{{ number_format($productStats['total_stock_value'], 2) }} (Purchase price basis)</td>
            </tr>
            <tr>
                <th>Low Stock Items</th>
                <td>{{ $productStats['low_stock_items'] }} Need restocking</td>
            </tr>
            <tr>
                <th>Total Stock Quantity</th>
                <td>{{ number_format($productStats['total_stock_quantity']) }} Units</td>
            </tr>
        </table>
    </div>
    <table class="products-table">
        <colgroup>
            <col class="name">
            <col class="item-code">
            <col class="sku">
            <col class="brand">
            <col class="category">
            <col class="stock">
            <col class="unit">
            <col class="selling-price">
            <col class="purchase-price">
            <col class="mrp">
            <col class="hsn-code">
            <col class="gst-rate">
            <col class="status">
        </colgroup>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Item Code</th>
                <th>SKU</th>
                <th>Brand</th>
                <th>Category</th>
                <th>Stock</th>
                <th>Unit</th>
                <th>Selling Price</th>
                <th>Purchase Price</th>
                <th>MRP</th>
                <th>HSN Code</th>
                <th>GST Rate</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->item_code }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->brand ?? 'N/A' }}</td>
                    <td>{{ $product->category ? $product->category->name : 'N/A' }}</td>
                    <td class="{{ $product->stock_quantity <= 5 ? 'low-stock' : '' }}">
                        {{ $product->stock_quantity }}
                        @if($product->stock_quantity <= 5)
                            (Low Stock)
                        @endif
                    </td>
                    <td>{{ $product->unit }}</td>
                    <td>₹{{ number_format($product->selling_price, 2) }}</td>
                    <td>₹{{ number_format($product->purchase_price, 2) }}</td>
                    <td>{{ $product->mrp ? '₹' . number_format($product->mrp, 2) : 'N/A' }}</td>
                    <td>{{ $product->hsn_code ?? 'N/A' }}</td>
                    <td>{{ $product->gst_rate ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        Generated on {{ now()->format('F j, Y, g:i A') }} by {{ config('app.name') }}
    </div>
</body>
</html>