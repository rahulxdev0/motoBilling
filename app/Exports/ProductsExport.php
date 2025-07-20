<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductsExport
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        // Get the product data
        $products = $this->query->get();
        
        // Create a new collection with headings as the first row
        $collection = new Collection();
        $collection->push($this->headings());
        
        // Add mapped product data
        $products->each(function ($product) use ($collection) {
            $collection->push($this->map($product));
        });

        return $collection;
    }

    public function headings(): array
    {
        return [
            'Product Name',
            'Item Code',
            'SKU',
            'Brand',
            'Category',
            'Stock Quantity',
            'Unit',
            'Selling Price',
            'Purchase Price',
            'MRP',
            'Status',
        ];
    }

    public function map($product): array
    {
        return [
            $product->name,
            $product->item_code,
            $product->sku,
            $product->brand ?? 'N/A',
            $product->category ? $product->category->name : 'N/A',
            $product->stock_quantity,
            $product->unit,
            number_format($product->selling_price, 2),
            number_format($product->purchase_price, 2),
            $product->mrp ? number_format($product->mrp, 2) : 'N/A',
            ucfirst($product->status),
        ];
    }
}
