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
    public $hsn_code = '';
    public $gst_rate = '';
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
            'hsn_code' => 'nullable|string|max:20',
            'gst_rate' => 'nullable|string|max:50',
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
        // dd($this->validate());

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
                'hsn_code' => $this->hsn_code,
                'gst_rate' => $this->gst_rate,
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
        // Generate EAN-13 barcode
        $this->barcode = $this->generateEAN13Barcode();
        
        // Clear existing label when generating new barcode
        $this->barcodeLabel = null;
        
        // $this->dispatch(event: 'barcode-generated');
        session()->flash('message', 'Barcode generated successfully!');
    }

    public function generateBarcodeLabel()
    {
        if (empty($this->barcode) || empty($this->name)) {
            session()->flash('error', 'Barcode and product name are required to generate a label.');
            return;
        }
        
        try {
            // Generate barcode using multiple methods for better compatibility
            $this->barcodeLabel = $this->createBarcodeHTML($this->barcode);
            
            session()->flash('message', 'Barcode label generated successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error generating barcode label: ' . $e->getMessage());
        }
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

    public function updatedBarcode($value)
    {
        if ($value && !empty($value)) {
            // Clear existing label when barcode is manually changed
            $this->barcodeLabel = null;
        }
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

    public function getGstRatesProperty()
    {
        return [
            '' => 'Select GST Rate',
            '0%' => '0% (Exempted / Nil Rated)',
            '0.1%' => '0.1% (0.05% CGST + 0.05% SGST)',
            '0.25%' => '0.25% (0.125% CGST + 0.125% SGST)',
            '0.5%' => '0.5% (0.25% CGST + 0.25% SGST)',
            '1%' => '1% (0.5% CGST + 0.5% SGST)',
            '1.5%' => '1.5% (0.75% CGST + 0.75% SGST)',
            '3%' => '3% (1.5% CGST + 1.5% SGST)',
            '5%' => '5% (2.5% CGST + 2.5% SGST)',
            '6%' => '6% (3% CGST + 3% SGST)',
            '12%' => '12% (6% CGST + 6% SGST)',
            '18%' => '18% (9% CGST + 9% SGST)',
            '28%' => '28% (14% CGST + 14% SGST)',
        ];
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

    private function createBarcodeHTML($barcode)
    {
        // Try multiple barcode generation methods
        
        // Method 1: External API (with fallback)
        $apiBarcode = $this->generateBarcodeFromAPI($barcode);
        if ($apiBarcode) {
            return $apiBarcode;
        }
        
        // Method 2: CSS-based barcode (fallback)
        return $this->generateCSSBarcode($barcode);
    }

    private function generateBarcodeFromAPI($barcode)
    {
        try {
            // Check if the API is accessible
            $url = 'https://barcodeapi.org/api/128/' . urlencode($barcode);
            
            // Use cURL to check if the service is available
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode == 200) {
                return '<img src="' . $url . '" alt="Barcode" style="max-width:100%; height: 60px;" onerror="this.style.display=\'none\'">';
            }
            
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function generateCSSBarcode($barcode)
    {
        // Create a simple CSS-based barcode representation
        $html = '<div class="css-barcode" style="display: flex; align-items: flex-end; justify-content: center; height: 60px; background: white; padding: 5px;">';
        
        // Start pattern
        $html .= '<div style="width: 2px; height: 50px; background: black; margin: 0 0.5px;"></div>';
        $html .= '<div style="width: 1px; height: 50px; background: white; margin: 0 0.5px;"></div>';
        $html .= '<div style="width: 2px; height: 50px; background: black; margin: 0 0.5px;"></div>';
        
        // Generate bars based on barcode digits
        for ($i = 0; $i < strlen($barcode); $i++) {
            $digit = intval($barcode[$i]);
            
            // Create different patterns for each digit
            for ($j = 0; $j < 4; $j++) {
                $width = ($digit % 2 === 0) ? '1px' : '2px';
                $height = (40 + ($digit * 2)) . 'px';
                $color = ($j % 2 === 0) ? 'black' : 'white';
                
                $html .= '<div style="width: ' . $width . '; height: ' . $height . '; background: ' . $color . '; margin: 0 0.5px;"></div>';
            }
        }
        
        // End pattern
        $html .= '<div style="width: 2px; height: 50px; background: black; margin: 0 0.5px;"></div>';
        $html .= '<div style="width: 1px; height: 50px; background: white; margin: 0 0.5px;"></div>';
        $html .= '<div style="width: 2px; height: 50px; background: black; margin: 0 0.5px;"></div>';
        
        $html .= '</div>';
        
        // Add barcode number below
        $html .= '<div style="text-align: center; font-family: monospace; font-size: 12px; margin-top: 5px; letter-spacing: 2px;">' . $barcode . '</div>';
        
        return $html;
    }

    public function render()
    {
        return view('livewire.items.create-product');
    }
}