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

    public $categories = [];
    public $parties = [];
    public $units = [
        'pcs' => 'Pieces',
        'box' => 'Box',
        'kg' => 'Kilogram',
        'ltr' => 'Litre',
        // ...add more as needed
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
        ]);

        session()->flash('success', 'Product updated successfully!');
        return redirect()->route('items.manage');
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
