<?php

namespace App\Livewire\Invoice;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Partie;
use App\Models\Product;
use App\Models\StockMovement;
use Livewire\Component;

class EditInvoice extends Component
{
    // Invoice properties
    public $invoice;
    public $invoiceId;
    public $invoice_number;
    public $partie_id;
    public $invoice_date;
    public $due_date;
    public $payment_terms;
    public $terms_conditions;
    public $notes;
    public $status;
    public $barcodeInput = '';

    // Payment properties
    public $paid_amount = '';
    public $payment_method = '';
    public $due_amount = 0;
    public $change_amount = 0;

    // Calculation properties
    public $subtotal = 0;
    public $discount_percentage = '';
    public $discount_amount = '';
    public $tax_percentage = 18;
    public $tax_amount = 0;
    public $round_off = 0;
    public $total = 0;

    // Invoice items
    public $invoice_items = [];

    // Collections
    public $parties;
    public $products;
    public $cash_sale_customer;

    // Search properties
    public $search_product = '';
    public $filtered_products = [];

    // Edit mode
    public $isEditing = false;

    // Barcode scanner
    public $showBarcodeScanner = false;

    // Payment method options
    public $paymentMethods = [
        'cash' => 'Cash',
        'upi' => 'UPI',
        'card' => 'Card',
        'cheque' => 'Cheque',
        'bank_transfer' => 'Bank Transfer'
    ];

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice;
        
        // Initialize properties from existing invoice
        $this->invoice_number = $this->invoice->invoice_number;
        $this->partie_id = $this->invoice->partie_id;
        $this->invoice_date = $this->invoice->invoice_date->format('Y-m-d');
        $this->due_date = $this->invoice->due_date ? $this->invoice->due_date->format('Y-m-d') : null;
        $this->payment_terms = $this->invoice->payment_terms;
        $this->terms_conditions = $this->invoice->terms_conditions;
        $this->notes = $this->invoice->notes;
        $this->status = $this->invoice->status;
        $this->paid_amount = $this->invoice->paid_amount;
        $this->payment_method = $this->invoice->payment_method;
        $this->due_amount = $this->invoice->balance_amount;
        $this->subtotal = $this->invoice->subtotal;
        $this->discount_percentage = $this->invoice->discount_percentage;
        $this->discount_amount = $this->invoice->discount_amount;
        $this->tax_percentage = $this->invoice->tax_percentage;
        $this->tax_amount = $this->invoice->tax_amount;
        $this->round_off = $this->invoice->round_off;
        $this->total = $this->invoice->total;

        // Load invoice items
        $this->invoice_items = $this->invoice->items->map(function ($item) {
            return [
                'id' => $item->id, // Store item ID for updates
                'product_id' => $item->product_id,
                'product_name' => $item->product ? $item->product->name : 'Unknown Product',
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total' => $item->total,
            ];
        })->toArray();

        // Load collections
        $this->parties = Partie::active()->get();
        $this->products = Product::where('status', 'active')->get();
        $this->filtered_products = $this->products->toArray();

        // Find or create cash sale customer
        $this->cash_sale_customer = Partie::firstOrCreate(
            ['name' => 'Cash Sale Customer'],
            [
                'name' => 'Cash Sale Customer',
                'email' => null,
                'phone' => '0000000000',
                'address' => 'Walk-in Customer',
                'contact_person' => 'Cash Sale',
                'gstin' => null,
                'pan' => null,
                'is_active' => true,
            ]
        );

        // Ensure at least one item
        if (empty($this->invoice_items)) {
            $this->addInvoiceItem();
        }
    }

    public function enableEdit()
    {
        $this->isEditing = true;
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
                'id' => null,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => 1,
                'unit_price' => $product->selling_price,
                'total' => $product->selling_price,
            ];
        }

        $this->calculateTotals();
    }

    public function isCashSale()
    {
        return $this->partie_id == $this->cash_sale_customer->id;
    }

    public function updatedPartieId()
    {
        if ($this->isCashSale()) {
            $this->due_date = $this->invoice_date;
            $this->payment_terms = 'Cash Payment';
            $this->payment_method = 'cash';
            if ($this->total > 0) {
                $this->paid_amount = $this->total;
            }
        } else {
            $this->due_date = now()->parse($this->invoice_date)->addDays(30)->format('Y-m-d');
            $this->payment_terms = '';
            $this->payment_method = '';
            $this->paid_amount = '';
        }
        $this->calculateTotals();
    }

    public function updatedInvoiceDate()
    {
        if ($this->isCashSale()) {
            $this->due_date = $this->invoice_date;
        }
    }

    public function updatedPaidAmount()
    {
        if ($this->paid_amount !== '') {
            $this->paid_amount = max(0, (float) $this->paid_amount);
            if (!$this->isCashSale() && $this->paid_amount > $this->total) {
                $this->paid_amount = $this->total;
            }
        }
        
        $this->dispatch('delay-calculate-due');
    }

    private function calculateDueAmount()
    {
        $paidAmount = is_numeric($this->paid_amount) ? (float) $this->paid_amount : 0;
        $this->due_amount = max(0, (float) $this->total - $paidAmount);
        
        if ($this->isCashSale() && $paidAmount > $this->total) {
            $this->change_amount = $paidAmount - $this->total;
        } else {
            $this->change_amount = 0;
        }
    }

    public function addInvoiceItem()
    {
        $this->invoice_items[] = [
            'id' => null,
            'product_id' => '',
            'product_name' => '',
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
                $this->invoice_items[$index]['product_name'] = $product->name;
                $this->invoice_items[$index]['unit_price'] = (float) $product->selling_price;
                $this->invoice_items[$index]['total'] = 
                    (float) $this->invoice_items[$index]['quantity'] * (float) $product->selling_price;
            }
        }

        if (in_array($field, ['quantity', 'unit_price'])) {
            $quantity = (float) $this->invoice_items[$index]['quantity'];
            $unitPrice = (float) $this->invoice_items[$index]['unit_price'];
            $this->invoice_items[$index]['total'] = $quantity * $unitPrice;
        }

        $this->calculateTotals();
    }

    public function updatedDiscountPercentage($value)
    {
        $this->discount_percentage = $value;
        $this->dispatch('delay-calculate-discount-from-percentage');
    }

    public function updatedDiscountAmount($value)
    {
        $this->discount_amount = $value;
        $this->dispatch('delay-calculate-discount-from-amount');
    }

    public function calculateDiscountFromPercentage()
    {
        if ($this->subtotal > 0 && $this->discount_percentage !== '' && is_numeric($this->discount_percentage)) {
            $this->discount_amount = (float)($this->subtotal * (float)$this->discount_percentage) / 100;
            $this->calculateTotals(false);
        } elseif ($this->discount_percentage === '') {
            $this->discount_amount = '';
            $this->calculateTotals(false);
        }
    }

    public function calculateDiscountFromAmount()
    {
        if ($this->subtotal > 0 && $this->discount_amount !== '' && is_numeric($this->discount_amount) && (float)$this->discount_amount >= 0) {
            $this->discount_percentage = ((float)$this->discount_amount * 100) / (float)$this->subtotal;
            $this->calculateTotals(true);
        } elseif ($this->discount_amount === '') {
            $this->discount_percentage = '';
            $this->calculateTotals(true);
        }
    }

    public function calculateDueFromPaid()
    {
        $this->calculateDueAmount();
    }

    public function updatedTaxPercentage($value)
    {
        if (is_numeric($value)) {
            $this->tax_percentage = (float)$value;
            $this->calculateTotals();
        }
    }

    public function recalculate()
    {
        $this->calculateTotals();
    }

    private function calculateTotals($recalculateDiscountAmount = true)
    {
        $this->subtotal = collect($this->invoice_items)->sum(function ($item) {
            return (float) ($item['total'] ?? 0);
        });

        $discountPercentage = is_numeric($this->discount_percentage) ? max(0, (float) $this->discount_percentage) : 0;
        $discountAmount = is_numeric($this->discount_amount) ? max(0, (float) $this->discount_amount) : 0;
        $this->tax_percentage = max(0, (float) $this->tax_percentage);

        if ($recalculateDiscountAmount && $discountPercentage > 0 && $this->subtotal > 0) {
            $discountAmount = ((float) $this->subtotal * $discountPercentage) / 100;
            $this->discount_amount = $discountAmount;
        }

        if ($discountAmount > (float) $this->subtotal) {
            $discountAmount = $this->subtotal;
            $this->discount_amount = $this->subtotal;
            if ($this->subtotal > 0) {
                $this->discount_percentage = 100;
            }
        }

        $taxable_amount = (float) $this->subtotal - $discountAmount;
        $this->tax_amount = ($taxable_amount * (float) $this->tax_percentage) / 100;

        $calculated_total = $taxable_amount + (float) $this->tax_amount;
        $this->round_off = round($calculated_total) - $calculated_total;
        $this->total = round($calculated_total);

        $this->subtotal = round((float) $this->subtotal, 2);
        if (is_numeric($this->discount_amount)) {
            $this->discount_amount = round((float) $this->discount_amount, 2);
        }
        if (is_numeric($this->discount_percentage)) {
            $this->discount_percentage = round((float) $this->discount_percentage, 2);
        }
        $this->tax_amount = round((float) $this->tax_amount, 2);
        $this->round_off = round((float) $this->round_off, 2);
        $this->total = round((float) $this->total, 2);

        $this->calculateDueAmount();

        if ($this->isCashSale() && $this->paid_amount === '' && $this->total > 0) {
            $this->paid_amount = $this->total;
            $this->calculateDueAmount();
        }
    }

    public function searchProducts()
    {
        if (strlen($this->search_product) >= 2) {
            $this->filtered_products = Product::where('status', 'active')
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search_product . '%')
                          ->orWhere('item_code', 'like', '%' . $this->search_product . '%');
                })
                ->limit(10)
                ->get()
                ->toArray();
        } else {
            $this->filtered_products = $this->products->take(10)->toArray();
        }
    }

    public function save($action = 'draft')
    {
        if (!$this->isEditing) {
            return;
        }

        $paidAmount = is_numeric($this->paid_amount) ? (float) $this->paid_amount : 0;
        $maxPayment = $this->isCashSale() ? ($this->total + 1000) : $this->total;

        $this->validate([
            'partie_id' => 'required|exists:parties,id',
            'invoice_date' => 'required|date',
            'invoice_items' => 'required|array|min:1',
            'invoice_items.*.product_id' => 'required|exists:products,id',
            'invoice_items.*.quantity' => 'required|numeric|min:1',
            'invoice_items.*.unit_price' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0|max:' . $maxPayment,
            'payment_method' => $paidAmount > 0 ? 'required|string' : 'nullable|string',
        ]);

        $this->status = $action === 'save_and_send' ? 'sent' : 'draft';
        $isCashSale = $this->isCashSale();

        $paymentStatus = 'unpaid';
        if ($paidAmount >= $this->total) {
            $paymentStatus = 'paid';
        } elseif ($paidAmount > 0) {
            $paymentStatus = 'partial';
        }

        try {
            // Update invoice
            $this->invoice->update([
                'partie_id' => $this->partie_id,
                'invoice_date' => $this->invoice_date,
                'due_date' => $isCashSale ? $this->invoice_date : $this->due_date,
                'subtotal' => $this->subtotal,
                'discount_percentage' => is_numeric($this->discount_percentage) ? $this->discount_percentage : 0,
                'discount_amount' => is_numeric($this->discount_amount) ? $this->discount_amount : 0,
                'tax_percentage' => $this->tax_percentage,
                'tax_amount' => $this->tax_amount,
                'round_off' => $this->round_off,
                'total' => $this->total,
                'paid_amount' => $paidAmount,
                'balance_amount' => $this->due_amount,
                'payment_status' => $paymentStatus,
                'payment_terms' => $isCashSale ? 'Cash Payment' : $this->payment_terms,
                'payment_method' => $this->payment_method,
                'terms_conditions' => $this->terms_conditions,
                'notes' => $this->notes,
                'status' => $this->status,
                'invoice_category' => 'sales',
            ]);

            // Sync invoice items
            $existingItemIds = collect($this->invoice_items)->pluck('id')->filter()->toArray();
            InvoiceItem::where('invoice_id', $this->invoice->id)
                ->whereNotIn('id', $existingItemIds)
                ->delete();

            foreach ($this->invoice_items as $item) {
                if ($item['product_id'] && $item['quantity'] > 0) {
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $originalItem = InvoiceItem::where('invoice_id', $this->invoice->id)
                            ->where('product_id', $item['product_id'])
                            ->first();

                        if ($item['id']) {
                            // Update existing item
                            $invoiceItem = InvoiceItem::find($item['id']);
                            if ($invoiceItem) {
                                $originalQuantity = $invoiceItem->quantity;
                                $invoiceItem->update([
                                    'quantity' => $item['quantity'],
                                    'unit_price' => $item['unit_price'],
                                    'total' => $item['total'],
                                ]);

                                // Adjust stock
                                $quantityDifference = $item['quantity'] - $originalQuantity;
                                if ($quantityDifference != 0) {
                                    $product->decrement('stock_quantity', $quantityDifference);

                                    StockMovement::create([
                                        'product_id' => $item['product_id'],
                                        'invoice_id' => $this->invoice->id,
                                        'movement_type' => $quantityDifference > 0 ? 'out' : 'in',
                                        'quantity' => abs($quantityDifference),
                                        'reference_type' => 'sales_invoice',
                                        'reference_id' => $this->invoice->id,
                                        'notes' => 'Stock adjusted for updated sales invoice ' . $this->invoice->invoice_number,
                                    ]);
                                }
                            }
                        } else {
                            // Create new item
                            InvoiceItem::create([
                                'invoice_id' => $this->invoice->id,
                                'product_id' => $item['product_id'],
                                'quantity' => $item['quantity'],
                                'unit_price' => $item['unit_price'],
                                'total' => $item['total'],
                            ]);

                            // Update stock
                            $product->decrement('stock_quantity', $item['quantity']);

                            StockMovement::create([
                                'product_id' => $item['product_id'],
                                'invoice_id' => $this->invoice->id,
                                'movement_type' => 'out',
                                'quantity' => $item['quantity'],
                                'reference_type' => 'sales_invoice',
                                'reference_id' => $this->invoice->id,
                                'notes' => 'Stock reduced for updated sales invoice ' . $this->invoice->invoice_number,
                            ]);
                        }
                    }
                }
            }

            $message = $isCashSale ? 'Cash sale updated successfully!' : 'Invoice updated successfully!';
            session()->flash('message', $message);

            if ($action === 'save_and_send') {
                return redirect()->route('invoice.pdf.view', ['id' => $this->invoice->id]);
            }

            return redirect()->route('invoice.manage');
        } catch (\Exception $e) {
            session()->flash('error', 'Error updating invoice: ' . $e->getMessage());
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.invoice.edit-invoice');
    }
}