<?php

namespace App\Livewire\Items;

use App\Models\Product;
use App\Models\Category;
use App\Models\Partie;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class EditProduct extends Component
{
    public Product $item;

    public $name;
    public $brand;
    public $item_code;
    public $sku;
    public $barcode;
    public $description;
    public $category_id;
    public $model_compatibility;
    public $purchase_price;
    public $selling_price;
    public $mrp;
    public $stock_quantity;
    public $reorder_level;
    public $unit;
    public $status;
    public $hsn_code;
    public $gst_rate;

    public $categories = [];
    public $parties = [];
    public $units = [
        'pcs' => 'Pieces',
        'box' => 'Box',
        'kg' => 'Kilogram',
        'ltr' => 'Litre',
        // ...add more as needed
    ];

    public $barcodeLabel;
    public $barcodePrintQty = 1;

    // GST rates for dropdown
    public $gstRates = [
        '' => 'Select GST Rate',
        '0.1' => '0.1% (0.05% CGST + 0.05% SGST | 0.1% IGST)',
        '0.25' => '0.25% (0.125% CGST + 0.125% SGST | 0.25% IGST)',
        '0.5' => '0.5% (0.25% CGST + 0.25% SGST | 0.5% IGST)',
        '1' => '1% (0.5% CGST + 0.5% SGST | 1% IGST)',
        '1.5' => '1.5% (0.75% CGST + 0.75% SGST | 1.5% IGST)',
        '3' => '3% (1.5% CGST + 1.5% SGST | 3% IGST)',
        '5' => '5% (2.5% CGST + 2.5% SGST | 5% IGST)',
        '6' => '6% (3% CGST + 3% SGST | 6% IGST)',
        '12' => '12% (6% CGST + 6% SGST | 12% IGST)',
        '18' => '18% (9% CGST + 9% SGST | 18% IGST)',
        '28' => '28% (14% CGST + 14% SGST | 28% IGST)',
    ];

    public function mount(Product $item)
    {
        $this->item = $item;
        $this->name = $item->name;
        $this->brand = $item->brand;
        $this->item_code = $item->item_code;
        $this->sku = $item->sku;
        $this->barcode = $item->barcode;
        $this->description = $item->description;
        $this->category_id = $item->category_id;
        $this->model_compatibility = $item->model_compatibility;
        $this->purchase_price = $item->purchase_price;
        $this->selling_price = $item->selling_price;
        $this->mrp = $item->mrp;
        $this->stock_quantity = $item->stock_quantity;
        $this->reorder_level = $item->reorder_level;
        $this->unit = $item->unit;
        $this->status = $item->status;
        $this->hsn_code = $item->hsn_code;
        $this->gst_rate = $item->gst_rate;

        $this->categories = Category::orderBy('name')->get();
        $this->parties = Partie::orderBy('name')->get();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'item_code' => 'required|string|max:255',
            'sku' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'model_compatibility' => 'nullable|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'unit' => 'required|string|max:20',
            'status' => 'required|in:active,inactive',
            'hsn_code' => 'nullable|string|max:255',
            'gst_rate' => 'nullable|numeric|min:0',
        ]);

        $this->item->update([
            'name' => $this->name,
            'brand' => $this->brand,
            'item_code' => $this->item_code,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'model_compatibility' => $this->model_compatibility,
            'purchase_price' => $this->purchase_price,
            'selling_price' => $this->selling_price,
            'mrp' => $this->mrp,
            'stock_quantity' => $this->stock_quantity,
            'reorder_level' => $this->reorder_level,
            'unit' => $this->unit,
            'status' => $this->status,
            'hsn_code' => $this->hsn_code,
            'gst_rate' => $this->gst_rate,
        ]);

        session()->flash('success', 'Product updated successfully!');
        return redirect()->route('items.manage');
    }

    public function generateBarcode()
    {
        // Generate EAN-13 barcode
        $this->barcode = $this->generateEAN13Barcode();
        session()->flash('message', 'Barcode generated successfully!');
    }

    private function generateEAN13Barcode()
    {
        // Generate 12 random digits
        $barcode = '';
        for ($i = 0; $i < 12; $i++) {
            $barcode .= rand(0, 9);
        }

        // Calculate check digit
        $checkDigit = $this->calculateEAN13CheckDigit($barcode);

        return $barcode . $checkDigit;
    }

    private function calculateEAN13CheckDigit($barcode)
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $digit = intval($barcode[$i]);
            $sum += ($i % 2 === 0) ? $digit : $digit * 3;
        }

        $checkDigit = (10 - ($sum % 10)) % 10;
        return $checkDigit;
    }

    public function generateBarcodeLabel()
    {
        if (empty($this->barcode) || empty($this->name)) {
            session()->flash('error', 'Barcode and product name are required to generate a label.');
            return;
        }

        try {
            $this->barcodeLabel = $this->createBarcodeHTML($this->barcode);
            session()->flash('message', 'Barcode label generated successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error generating barcode label: ' . $e->getMessage());
        }
    }

    private function createBarcodeHTML($barcode)
    {
        $url = 'https://barcodeapi.org/api/128/' . urlencode($barcode);
        return '<img src="' . $url . '" alt="Barcode" style="max-width:100%; height: 70px;" onerror="this.style.display=\'none\'">';
    }

    public function render()
    {
        return view('livewire.items.edit-product', [
            'categories' => $this->categories,
            'parties' => $this->parties,
            'units' => $this->units,
        ]);
    }
}
