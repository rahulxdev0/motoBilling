<?php

namespace App\Livewire\Purchase;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Partie;
use App\Models\Product;
use App\Models\StockMovement;
use Livewire\Component;

class CreatePurchaseInvoice extends Component
{
    public $invoice_number;
    public $partie_id;
    public $invoice_date;
    public $due_date;
    public $payment_terms;
    public $terms_conditions;
    public $notes;
    public $status = 'draft';
    public $barcodeInput = '';
    public $showBarcodeScanner = false;

    // Calculation properties
    public $subtotal = 0;
    public $discount_percentage = 0;
    public $discount_amount = 0;
    public $tax_percentage = 0;
    public $tax_amount = 0;
    public $round_off = 0;
    public $total = 0;

    // Invoice items
    public $invoice_items = [];

    // Collections
    public $parties;
    public $products;

    public function mount()
    {
        $this->parties = Partie::all();
        $this->products = Product::where('status', 'active')->get();

        // Generate invoice number
        $this->invoice_number = $this->generateInvoiceNumber();

        // Set default dates
        $this->invoice_date = now()->format('Y-m-d');
        $this->due_date = now()->addDays(30)->format('Y-m-d');

        // Add initial empty row
        $this->addInvoiceItem();
    }

    public function toggleBarcodeScanner()
    {
        $this->showBarcodeScanner = !$this->showBarcodeScanner;
        if ($this->showBarcodeScanner) {
            $this->dispatch('focus-barcode-input');
        }
    }

    public function handleBarcodeScan()
    {
        $barcode = trim($this->barcodeInput);

        if (empty($barcode)) {
            return;
        }

        $this->resetErrorBag('barcodeInput');
        $product = Product::where('item_code', $barcode)
            ->orWhere('barcode', $barcode)
            ->first();

        if ($product) {
            $this->addProductToInvoice($product);
            $this->barcodeInput = '';
        } else {
            $this->addError('barcodeInput', "Product not found for barcode: $barcode");
        }
    }

    protected function addProductToInvoice(Product $product)
    {
        // Check if product already exists in invoice items
        $existingIndex = null;
        foreach ($this->invoice_items as $index => $item) {
            if ($item['product_id'] == $product->id) {
                $existingIndex = $index;
                break;
            }
        }

        if ($existingIndex !== null) {
            // Increment quantity of existing item
            $this->invoice_items[$existingIndex]['quantity']++;
            $this->invoice_items[$existingIndex]['total'] =
                $this->invoice_items[$existingIndex]['quantity'] *
                $this->invoice_items[$existingIndex]['unit_price'];
        } else {
            // Add new item
            $this->invoice_items[] = [
                'product_id' => $product->id,
                'quantity' => 1,
                'unit_price' => $product->purchase_price, // Use purchase price instead of selling price
                'total' => $product->purchase_price,
            ];
        }

        $this->calculateTotals();
    }

    private function generateInvoiceNumber()
    {
        // Get the latest purchase invoice number (by prefix)
        $lastInvoice = Invoice::where('invoice_number', 'like', 'PUR-%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastInvoice) {
            return 'PUR-0001';
        }

        // Extract the numeric part
        if (preg_match('/PUR-(\d+)/', $lastInvoice->invoice_number, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        } else {
            $nextNumber = Invoice::where('invoice_number', 'like', 'PUR-%')->count() + 1;
        }

        return 'PUR-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function addInvoiceItem()
    {
        $this->invoice_items[] = [
            'product_id' => '',
            'quantity' => 1,
            'unit_price' => 0,
            'total' => 0,
        ];
    }

    public function removeInvoiceItem($index)
    {
        if (count($this->invoice_items) > 1) {
            unset($this->invoice_items[$index]);
            $this->invoice_items = array_values($this->invoice_items);
            $this->calculateTotals();
        }
    }

    public function updatedInvoiceItems($value, $key)
    {
        $parts = explode('.', $key);
        $index = $parts[0];
        $field = $parts[1];

        if ($field === 'product_id') {
            $product = Product::find($value);
            if ($product) {
                $this->invoice_items[$index]['unit_price'] = (float) $product->purchase_price; // Use purchase price
                $this->invoice_items[$index]['total'] =
                    (float) $this->invoice_items[$index]['quantity'] * (float) $product->purchase_price;
            }
        }

        if (in_array($field, ['quantity', 'unit_price'])) {
            $quantity = (float) $this->invoice_items[$index]['quantity'];
            $unitPrice = (float) $this->invoice_items[$index]['unit_price'];

            $this->invoice_items[$index]['total'] = $quantity * $unitPrice;
        }

        $this->calculateTotals();
    }

    // ... (Keep the same calculation methods as in sales invoice)

    public function save($action = 'draft')
    {
        $this->validate([
            'partie_id' => 'required|exists:parties,id',
            'invoice_date' => 'required|date',
            'invoice_items' => 'required|array|min:1',
            'invoice_items.*.product_id' => 'required|exists:products,id',
            'invoice_items.*.quantity' => 'required|numeric|min:1',
            'invoice_items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $this->status = $action === 'save_and_send' ? 'sent' : 'draft';

        // Generate a new invoice number just before saving
        $this->invoice_number = $this->generateInvoiceNumber();

        try {
            // Create purchase invoice
            $invoice = Invoice::create([
                'invoice_number' => $this->invoice_number,
                'partie_id' => $this->partie_id,
                'invoice_date' => $this->invoice_date,
                'due_date' => $this->due_date,
                'subtotal' => $this->subtotal,
                'discount_percentage' => $this->discount_percentage,
                'discount_amount' => $this->discount_amount,
                'tax_percentage' => $this->tax_percentage,
                'tax_amount' => $this->tax_amount,
                'round_off' => $this->round_off,
                'total' => $this->total,
                'paid_amount' => 0,
                'balance_amount' => $this->total,
                'payment_status' => 'unpaid',
                'status' => $this->status,
                'payment_terms' => $this->payment_terms,
                'terms_conditions' => $this->terms_conditions,
                'notes' => $this->notes,
                'type' => 'purchase', // Mark as purchase invoice
            ]);

            // Create invoice items and update stock
            foreach ($this->invoice_items as $item) {
                if ($item['product_id'] && $item['quantity'] > 0) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'total' => $item['total'],
                    ]);

                    // Update product stock - INCREASE for purchases
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->increment('stock_quantity', $item['quantity']);

                        // Create stock movement record
                        StockMovement::create([
                            'product_id' => $item['product_id'],
                            'quantity' => $item['quantity'], // Positive for purchases
                            'movement_type' => 'purchase',
                            'invoice_id' => $invoice->id,
                            'notes' => 'Stock added for purchase invoice: ' . $this->invoice_number,
                        ]);
                    }
                }
            }

            session()->flash('message', 'Purchase invoice created successfully!');

            if ($action === 'save_and_send') {
                // Add any additional logic for sending
            }

            return redirect()->route('invoice.manage');
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                $this->invoice_number = $this->generateUniqueInvoiceNumber();
                return $this->save($action);
            }

            session()->flash('error', 'Error creating purchase invoice: ' . $e->getMessage());
            throw $e;
        }
    }

    private function generateUniqueInvoiceNumber()
    {
        do {
            $lastInvoice = Invoice::where('invoice_number', 'like', 'PUR-%')
                ->orderBy('id', 'desc')
                ->first();

            if (!$lastInvoice) {
                $nextNumber = 1;
            } else {
                if (preg_match('/PUR-(\d+)/', $lastInvoice->invoice_number, $matches)) {
                    $nextNumber = (int) $matches[1] + 1;
                } else {
                    $nextNumber = Invoice::where('invoice_number', 'like', 'PUR-%')->count() + 1;
                }
            }

            $invoiceNumber = 'PUR-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $exists = Invoice::where('invoice_number', $invoiceNumber)->exists();
        } while ($exists);

        return $invoiceNumber;
    }
    public function render()
    {
        return view('livewire.purchase.create-purchase-invoice');
    }
}
