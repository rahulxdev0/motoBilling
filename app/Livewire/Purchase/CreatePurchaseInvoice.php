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
    // Invoice properties
    public $invoice_number;
    public $partie_id;
    public $invoice_date;
    public $due_date;
    public $payment_terms;
    public $terms_conditions;
    public $notes;
    public $status = 'draft';
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
    public $cash_sale_supplier;

    // Search properties
    public $search_product = '';
    public $filtered_products = [];

    public $showBarcodeScanner = false;

    // Payment method options
    public $paymentMethods = [
        'cash' => 'Cash',
        'upi' => 'UPI',
        'card' => 'Card',
        'cheque' => 'Cheque',
        'bank_transfer' => 'Bank Transfer'
    ];

    // Add isFullyPaid property
    public $isFullyPaid = false;

    public function mount()
    {
        $this->parties = Partie::active()->get();
        $this->products = Product::where('status', 'active')->get();
        $this->filtered_products = $this->products->toArray();

        // Find or create cash purchase supplier
        $this->cash_sale_supplier = Partie::firstOrCreate(
            ['name' => 'Cash Purchase Supplier'],
            [
                'name' => 'Cash Purchase Supplier',
                'email' => 'cash@purchase.local',
                'phone' => '0000000000',
                'address' => 'N/A',
                'status' => 'active'
            ]
        );

        // Generate invoice number
        $this->invoice_number = $this->generateInvoiceNumber();

        // Set default dates
        $this->invoice_date = now()->format('Y-m-d');
        $this->due_date = now()->addDays(30)->format('Y-m-d');

        // Set default payment method to cash
        $this->payment_method = 'cash';

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
        // Try to find an empty row (no product_id)
        $emptyIndex = null;
        foreach ($this->invoice_items as $index => $item) {
            if (empty($item['product_id'])) {
                $emptyIndex = $index;
                break;
            }
        }

        // Check if product already exists in invoice items
        $existingIndex = null;
        foreach ($this->invoice_items as $index => $item) {
            if (!empty($item['product_id']) && $item['product_id'] == $product->id) {
                $existingIndex = $index;
                break;
            }
        }

        if ($existingIndex !== null) {
            // Increment quantity of existing item
            $this->invoice_items[$existingIndex]['quantity']++;
            $this->invoice_items[$existingIndex]['total'] = 
                $this->invoice_items[$existingIndex]['quantity'] * $this->invoice_items[$existingIndex]['unit_price'];
            // Ensure product_name is set
            $this->invoice_items[$existingIndex]['product_name'] = $product->name;
        } elseif ($emptyIndex !== null) {
            // Fill the empty row with the scanned product
            $this->invoice_items[$emptyIndex] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => 1,
                'unit_price' => $product->purchase_price,
                'total' => $product->purchase_price,
            ];
        } else {
            // Add new item
            $this->invoice_items[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => 1,
                'unit_price' => $product->purchase_price,
                'total' => $product->purchase_price,
            ];
        }

        $this->calculateTotals();
    }

    // Method to check if current selection is cash purchase
    public function isCashPurchase()
    {
        return $this->partie_id == $this->cash_sale_supplier->id;
    }

    // Handle supplier selection changes
    public function updatedPartieId()
    {
        if ($this->isCashPurchase()) {
            // For cash purchases, set due date to same as invoice date
            $this->due_date = $this->invoice_date;
            $this->payment_terms = 'Cash Payment';
            $this->payment_method = 'cash';
            // For cash purchases, set paid amount to total when total is calculated
            if ($this->total > 0) {
                $this->paid_amount = $this->total;
            }
        } else {
            // For credit purchases, set due date to 30 days from invoice date
            $this->due_date = now()->parse($this->invoice_date)->addDays(30)->format('Y-m-d');
            $this->payment_terms = '';
            $this->payment_method = '';
            // Clear paid amount for credit purchases
            $this->paid_amount = '';
        }
        $this->calculateTotals();
    }

    // Update invoice date method to handle cash purchases
    public function updatedInvoiceDate()
    {
        if ($this->isCashPurchase()) {
            $this->due_date = $this->invoice_date;
        }
    }

    // Update paid amount with delayed calculation
    public function updatedPaidAmount()
    {
        if ($this->paid_amount !== '') {
            $this->paid_amount = max(0, (float) $this->paid_amount);
            // Only cap for credit purchases, allow overpayment for cash purchases
            if (!$this->isCashPurchase() && $this->paid_amount > $this->total) {
                $this->paid_amount = $this->total;
            }
        }

        // Use dispatch to delay calculation
        $this->dispatch('delay-calculate-due');
    }

    // Calculate due amount
    private function calculateDueAmount()
    {
        $paidAmount = is_numeric($this->paid_amount) ? (float) $this->paid_amount : 0;
        $this->due_amount = max(0, (float) $this->total - $paidAmount);

        // Calculate change if overpaid (for cash purchases)
        if ($this->isCashPurchase() && $paidAmount > $this->total) {
            $this->change_amount = $paidAmount - $this->total;
        } else {
            $this->change_amount = 0;
        }
    }

    private function generateInvoiceNumber()
    {
        // Get the latest purchase invoice number
        $lastInvoice = Invoice::where('invoice_number', 'like', 'PUR-%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastInvoice) {
            return 'PUR-0001';
        }

        // Extract the numeric part from the invoice number
        $lastNumber = $lastInvoice->invoice_number;

        // Use regex to extract the numeric part after 'PUR-'
        if (preg_match('/PUR-(\d+)/', $lastNumber, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        } else {
            // Fallback: count all purchase invoices and add 1
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
        // Allow deleting even if only one row remains
        unset($this->invoice_items[$index]);
        $this->invoice_items = array_values($this->invoice_items);
        $this->calculateTotals();
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
                $this->invoice_items[$index]['unit_price'] = (float) $product->purchase_price;
                $this->invoice_items[$index]['total'] = 
                    (float) $this->invoice_items[$index]['quantity'] * (float) $product->purchase_price;
            }
        }

        if (in_array($field, ['quantity', 'unit_price'])) {
            // Cast to float to ensure numeric calculation
            $quantity = (float) $this->invoice_items[$index]['quantity'];
            $unitPrice = (float) $this->invoice_items[$index]['unit_price'];

            $this->invoice_items[$index]['total'] = $quantity * $unitPrice;
        }

        $this->calculateTotals();
    }

    // Live update for discount percentage with delayed calculation
    public function updatedDiscountPercentage($value)
    {
        // Store the value but don't calculate immediately
        $this->discount_percentage = $value;

        // Use dispatch to delay calculation
        $this->dispatch('delay-calculate-discount-from-percentage');
    }

    // Live update for discount amount with delayed calculation
    public function updatedDiscountAmount($value)
    {
        // Store the value but don't calculate immediately
        $this->discount_amount = $value;

        // Use dispatch to delay calculation
        $this->dispatch('delay-calculate-discount-from-amount');
    }

    // Delayed calculation methods
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

    // Live update for tax percentage
    public function updatedTaxPercentage($value)
    {
        // Only process if the value is a valid number
        if (is_numeric($value)) {
            // We'll pass the raw value directly to calculate totals
            // Store the raw value to prevent losing decimal places during typing
            $this->tax_percentage = (float)$value;
            $this->calculateTotals();
        }
    }

    // Method to manually trigger calculation (useful for edge cases)
    public function recalculate()
    {
        $this->calculateTotals();
    }

    // Updated total calculation function to handle the fully paid checkbox
    private function calculateTotals($recalculateDiscountAmount = true)
    {
        // Calculate subtotal - ensure we're working with numbers
        $this->subtotal = collect($this->invoice_items)->sum(function ($item) {
            return (float) ($item['total'] ?? 0);
        });

        // Handle discount calculations only if values are not empty
        $discountPercentage = is_numeric($this->discount_percentage) ? max(0, (float) $this->discount_percentage) : 0;
        $discountAmount = is_numeric($this->discount_amount) ? max(0, (float) $this->discount_amount) : 0;
        $this->tax_percentage = max(0, (float) $this->tax_percentage);

        // Calculate discount amount if percentage is set and subtotal > 0
        // Only recalculate if flag is set (to avoid circular updates)
        if ($recalculateDiscountAmount && $discountPercentage > 0 && $this->subtotal > 0) {
            $discountAmount = ((float) $this->subtotal * $discountPercentage) / 100;
        }

        // Ensure discount amount doesn't exceed subtotal
        if ($discountAmount > (float) $this->subtotal) {
            $discountAmount = (float) $this->subtotal;
            $this->discount_amount = $discountAmount;
            $this->discount_percentage = 100;
        }

        // Calculate tax amount - ensure we preserve tax percentage precision during calculation
        $taxable_amount = (float) $this->subtotal - $discountAmount;
        $this->tax_amount = ($taxable_amount * (float) $this->tax_percentage) / 100;

        // Calculate total before round off
        $calculated_total = $taxable_amount + (float) $this->tax_amount;

        // Calculate round off
        $this->round_off = round($calculated_total) - $calculated_total;

        // Final total
        $this->total = round($calculated_total);

        // Format values for display - preserve values as they are for empty strings
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

        // Update isFullyPaid state when total changes
        if ($this->isFullyPaid) {
            $this->paid_amount = $this->total;
        }

        // Calculate due amount after total is calculated
        $this->calculateDueAmount();

        // For cash purchases, auto-set paid amount to total only if paid amount is empty or fully paid is checked
        if ($this->isCashPurchase() && ($this->paid_amount === '' || $this->isFullyPaid) && $this->total > 0) {
            $this->paid_amount = $this->total;
        }
    }

    // Update to ensure paid_amount stays in sync with isFullyPaid state
    public function updatedTotal($value)
    {
        if ($this->isFullyPaid && $value > 0) {
            $this->paid_amount = $value;
        }
    }

    public function searchProducts()
    {
        if (strlen($this->search_product) >= 2) {
            $this->filtered_products = $this->products
                ->filter(function ($product) {
                    return stripos($product->name, $this->search_product) !== false ||
                           stripos($product->item_code, $this->search_product) !== false;
                })
                ->toArray();
        } else {
            $this->filtered_products = $this->products->toArray();
        }
    }

    public function save($action = 'draft')
    {
        $paidAmount = is_numeric($this->paid_amount) ? (float) $this->paid_amount : 0;

        // Determine maximum allowed payment based on purchase type
        $maxPayment = $this->isCashPurchase() ? ($this->total + 1000) : $this->total; // Allow up to ₹1000 overpayment for cash purchases

        $this->validate([
            'partie_id' => 'required|exists:parties,id',
            'invoice_date' => 'required|date',
            'invoice_items' => 'required|array|min:1',
            'invoice_items.*.product_id' => 'required|exists:products,id',
            'invoice_items.*.quantity' => 'required|numeric|min:1',
            'invoice_items.*.unit_price' => 'required|numeric|min:0',
            'payment_method' => $paidAmount > 0 ? 'required|string' : 'nullable|string',
        ]);

        $this->status = $action === 'save_and_send' ? 'sent' : 'draft';
        $isCashPurchase = $this->isCashPurchase();

        // Generate a new invoice number just before saving to avoid duplicates
        $this->invoice_number = $this->generateInvoiceNumber();

        // Determine payment status
        $paymentStatus = 'unpaid';
        if ($paidAmount >= $this->total) {
            $paymentStatus = 'paid';
        } elseif ($paidAmount > 0) {
            $paymentStatus = 'partial';
        }

        try {
            // Create purchase invoice
            $invoice = Invoice::create([
                'invoice_number' => $this->invoice_number,
                'partie_id' => $this->partie_id,
                'invoice_date' => $this->invoice_date,
                'due_date' => $isCashPurchase ? $this->invoice_date : $this->due_date,
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
                'payment_terms' => $isCashPurchase ? 'Cash Payment' : $this->payment_terms,
                'payment_method' => $this->payment_method,
                'terms_conditions' => $this->terms_conditions,
                'notes' => $this->notes,
                'status' => $this->status,
                'invoice_category' => 'purchase',
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

            return redirect()->route('invoice.purchase');
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                $this->invoice_number = $this->generateUniqueInvoiceNumber();
                return $this->save($action);
            }

            session()->flash('error', 'Error creating purchase invoice: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate a truly unique invoice number by checking database
     */
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

    // Handle fully paid checkbox changes
    public function updatedIsFullyPaid($value)
    {
        if ($value) {
            $this->paid_amount = $this->total;
        } else {
            $this->paid_amount = '';
        }
        $this->calculateDueAmount();
    }

    public function render()
    {
        return view('livewire.purchase.create-purchase-invoice');
    }
}
