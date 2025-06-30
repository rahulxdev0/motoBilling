<?php

namespace App\Livewire\Items\Components;

use App\Models\Product;
use App\Models\Category;
use App\Models\Partie;
use Livewire\Component;
use Livewire\Attributes\On;

class CreateProductModal extends Component
{
    public $showModal = false;
    
    // Form fields
    public $name = '';
    public $item_code = '';
    public $sku = '';
    public $barcode = '';
    public $description = '';
    public $brand = '';
    public $category_id = '';
    public $partie_id = 1; // Set default to 1
    public $model_compatibility = '';
    public $purchase_price = '';
    public $selling_price = '';
    public $mrp = '';
    public $stock_quantity = 0;
    public $reorder_level = 10; //field for low stock quantity
    public $unit = 'pcs';
    public $status = 'active';

    public $categories = [];
    public $parties = [];

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
        // For now, we'll create a simple parties array
        $this->parties = collect([
            (object)['id' => 1, 'name' => 'Default Supplier']
        ]);
    }

    #[On('open-product-modal')]
    public function openModal()
    {
        $this->resetForm();
        $this->resetValidation();
        $this->loadData();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function openAddCategoryModal()
    {
        $this->dispatch('open-category-modal');
    }

    public function resetForm()
    {
        $this->name = '';
        $this->item_code = '';
        $this->sku = '';
        $this->barcode = '';
        $this->description = '';
        $this->brand = '';
        $this->category_id = '';
        $this->partie_id = 1;
        $this->model_compatibility = '';
        $this->purchase_price = '';
        $this->selling_price = '';
        $this->mrp = '';
        $this->stock_quantity = 0;
        $this->reorder_level = 10;
        $this->unit = 'pcs';
        $this->status = 'active';
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
            $this->closeModal();
            $this->dispatch('product-created');
            
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

    public function updatedName()
    {
        if ($this->name) {
            $this->item_code = strtoupper(str_replace(' ', '_', $this->name)) . '_' . time();
        }
    }

    public function validateBarcode()
    {
        if ($this->barcode) {
            // Validate barcode format if needed (e.g., EAN-13, UPC-A, etc.)
            $this->validateOnly('barcode');
            
            // Optional: Check if barcode already exists and show warning
            $existingProduct = Product::where('barcode', $this->barcode)->first();
            if ($existingProduct) {
                session()->flash('barcode_warning', 'Warning: This barcode is already used by product: ' . $existingProduct->name);
            }
        }
    }

    public function clearBarcode()
    {
        $this->barcode = '';
        session()->forget('barcode_warning');
    }

    public function updatedBarcode()
    {
        if ($this->barcode) {
            // Remove any non-numeric characters for basic cleanup
            $this->barcode = preg_replace('/[^0-9]/', '', $this->barcode);
            
            // Optional: Validate barcode length (common formats)
            if (strlen($this->barcode) > 0 && !in_array(strlen($this->barcode), [8, 12, 13, 14])) {
                $this->addError('barcode', 'Barcode should be 8, 12, 13, or 14 digits long.');
            } else {
                $this->resetErrorBag('barcode');
            }
        }
    }

    public function generateBarcode()
    {
        // Generate a simple barcode (you can customize this logic)
        $this->barcode = '2' . str_pad(mt_rand(1, 999999999999), 12, '0', STR_PAD_LEFT);
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
        return view('livewire.items.components.create-product-modal');
    }
}
