<?php

namespace App\Livewire\Items;

use App\Models\Product;
use App\Models\Category;
use App\Models\Partie;
use Livewire\Component;
use Livewire\Attributes\On;

class CreateProduct extends Component
{
    // Form fields
    public $name = '';
    public $item_code = '';
    public $sku = '';
    public $barcode = '';
    public $description = '';
    public $brand = '';
    public $category_id = '';
    public $partie_id = 1;
    public $model_compatibility = '';
    public $purchase_price = '';
    public $selling_price = '';
    public $mrp = '';
    public $stock_quantity = 0;
    public $reorder_level = 10;
    public $unit = 'pcs';
    public $status = 'active';

    public $categories = [];
    public $parties = [];
    public $barcodeLabel;
    public $barcodePrintQty = 1;

    protected function rules()
    {
        return [
            'name' => 'required|min:3|max:255',
            'item_code' => 'required|unique:products,item_code|max:100',
            'sku' => 'required|unique:products,sku|max:100',
            'description' => 'nullable|string',
            'barcode' => 'nullable|unique:products,barcode|max:255',
            'brand' => 'nullable|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'partie_id' => 'required|integer|min:1',
            'model_compatibility' => 'nullable|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0|gte:selling_price',
            'stock_quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0|max:1000',
            'unit' => 'required|string|max:20',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function mount()
    {
        $this->loadData();
    }

    #[On('category-created')] 
    public function loadData()
    {
        $this->categories = Category::orderBy('name')->get();
        $this->parties = collect([
            (object)['id' => 1, 'name' => 'Default Supplier']
        ]);
    }

    public function save()
    {
        $this->validate();

        try {
            Product::create([
                'name' => $this->name,
                'item_code' => $this->item_code,
                'sku' => $this->sku,
                'barcode' => $this->barcode,
                'description' => $this->description,
                'brand' => $this->brand,
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

            session()->flash('message', 'Product created successfully!');
            return $this->redirect(route('items.manage'), navigate: true);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    public function generateSku()
    {
        if ($this->category_id && $this->name) {
            $category = Category::find($this->category_id);
            $categoryCode = strtoupper(substr($category->name ?? 'GEN', 0, 3));
            $productCode = strtoupper(substr(str_replace(' ', '', $this->name), 0, 3));
            $random = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
            $this->sku = $categoryCode . $productCode . $random;
        }
    }

    public function generateBarcode()
    {
        $this->barcode = '2' . str_pad(mt_rand(1, 999999999999), 12, '0', STR_PAD_LEFT);
    }

    public function generateBarcodeLabel()
    {
        if (empty($this->barcode) || empty($this->name)) {
            session()->flash('error', 'Barcode and product name are required to generate a label.');
            return;
        }
        
        $this->barcodeLabel = '<img src="https://barcodeapi.org/api/128/' . urlencode($this->barcode) . '" alt="Barcode" style="max-width:100%;">';
        session()->flash('success', 'Barcode label generated successfully.');
    }

    public function clearBarcode()
    {
        $this->barcode = '';
        $this->barcodeLabel = null;
        session()->forget('barcode_warning');
    }

    public function updatedName()
    {
        if ($this->name) {
            $this->item_code = strtoupper(str_replace(' ', '_', $this->name)) . '_' . time();
        }
    }

    public function updatedCategoryId()
    {
        $this->generateSku();
    }

    public function getUnitsProperty()
    {
        return [
            'pcs' => 'Pieces',
            'kg' => 'Kilograms',
            'ltr' => 'Liters',
            'mtr' => 'Meters',
            'box' => 'Box',
            'set' => 'Set'
        ];
    }

    public function render()
    {
        return view('livewire.items.create-product');
    }
}
