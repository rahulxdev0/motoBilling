<?php

namespace App\Livewire\Invoice;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Partie;
use App\Models\Product;
use App\Models\StockMovement;
use Livewire\Component;

class CreateInvoice extends Component
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
    public $paid_amount = '';
    public $payment_method = '';
    public $due_amount = 0;
    public $change_amount = 0;
    public $subtotal = 0;
    public $discount_percentage = '';
    public $discount_amount = '';
    public $tax_amount = 0;
    public $round_off = 0;
    public $total = 0;
    public $gst_summary = [];
    public $invoice_items = [];
    public $parties;
    public $products;
    public $cash_sale_customer;
    public $search_product = '';
    public $filtered_products = [];
    protected static $staticName = 'invoice.create-invoice';
    public $showBarcodeScanner = false;
    public $paymentMethods = [
        'cash' => 'Cash',
        'upi' => 'UPI',
        'card' => 'Card',
        'cheque' => 'Cheque',
        'bank_transfer' => 'Bank Transfer'
    ];
    public $isFullyPaid = false;

    public function mount()
    {
        $this->parties = Partie::active()->get();
        $this->products = Product::where('status', 'active')->get();
        $this->filtered_products = $this->products->toArray();

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

        $this->invoice_number = $this->generateInvoiceNumber();
        $this->invoice_date = now()->format('Y-m-d');
        $this->due_date = now()->addDays(30)->format('Y-m-d');
        $this->payment_method = 'cash';
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
        \Log::info('Adding product to invoice', ['product_id' => $product->id, 'name' => $product->name]);

        $emptyIndex = null;
        foreach ($this->invoice_items as $index => $item) {
            if (empty($item['product_id'])) {
                $emptyIndex = $index;
                break;
            }
        }

        $existingIndex = null;
        foreach ($this->invoice_items as $index => $item) {
            if (!empty($item['product_id']) && $item['product_id'] == $product->id) {
                $existingIndex = $index;
                break;
            }
        }

        if ($existingIndex !== null) {
            $this->invoice_items[$existingIndex]['quantity']++;
            $this->updateItemCalculations($existingIndex);
            \Log::info('Updated existing item', ['index' => $existingIndex, 'item' => $this->invoice_items[$existingIndex]]);
        } elseif ($emptyIndex !== null) {
            $this->invoice_items[$emptyIndex] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'hsn_code' => $product->hsn_code,
                'gst_rate' => (float) $product->gst_rate,
                'quantity' => 1,
                'unit_price' => (float) $product->selling_price,
                'subtotal' => (float) $product->selling_price,
                'tax_amount' => (float) $product->selling_price * ($product->gst_rate / 100),
                'total' => (float) $product->selling_price * (1 + $product->gst_rate / 100),
            ];
            \Log::info('Filled empty row', ['index' => $emptyIndex, 'item' => $this->invoice_items[$emptyIndex]]);
        } else {
            $this->invoice_items[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'hsn_code' => $product->hsn_code,
                'gst_rate' => (float) $product->gst_rate,
                'quantity' => 1,
                'unit_price' => (float) $product->selling_price,
                'subtotal' => (float) $product->selling_price,
                'tax_amount' => (float) $product->selling_price * ($product->gst_rate / 100),
                'total' => (float) $product->selling_price * (1 + $product->gst_rate / 100),
            ];
            \Log::info('Added new item', ['item' => end($this->invoice_items)]);
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

    private function generateInvoiceNumber()
    {
        $lastInvoice = Invoice::orderBy('id', 'desc')->first();
        if (!$lastInvoice) {
            return 'INV-0001';
        }
        if (preg_match('/INV-(\d+)/', $lastInvoice->invoice_number, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        } else {
            $nextNumber = Invoice::count() + 1;
        }
        return 'INV-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function addInvoiceItem()
    {
        $this->invoice_items[] = [
            'product_id' => '',
            'product_name' => '',
            'hsn_code' => '',
            'gst_rate' => 0,
            'quantity' => 1,
            'unit_price' => 0,
            'subtotal' => 0,
            'tax_amount' => 0,
            'total' => 0,
        ];
        \Log::info('Added new invoice item', ['index' => count($this->invoice_items) - 1]);
    }

    public function removeInvoiceItem($index)
    {
        \Log::info('Removing invoice item', ['index' => $index]);
        unset($this->invoice_items[$index]);
        $this->invoice_items = array_values($this->invoice_items);
        $this->calculateTotals();
    }

    public function updatedInvoiceItems($value, $key)
    {
        \Log::info('Updated invoice items', ['key' => $key, 'value' => $value]);

        $parts = explode('.', $key);
        $index = $parts[0];
        $field = $parts[1];

        if ($field === 'product_id' && !empty($value)) {
            $product = Product::find($value);
            if ($product) {
                \Log::info('Product selected', ['product_id' => $value, 'name' => $product->name]);
                $this->invoice_items[$index]['product_id'] = $product->id;
                $this->invoice_items[$index]['product_name'] = $product->name;
                $this->invoice_items[$index]['hsn_code'] = $product->hsn_code;
                $this->invoice_items[$index]['gst_rate'] = (float) $product->gst_rate;
                $this->invoice_items[$index]['unit_price'] = (float) $product->selling_price;
                $this->updateItemCalculations($index);
            } else {
                \Log::error('Product not found', ['product_id' => $value]);
            }
        } elseif (in_array($field, ['quantity', 'unit_price'])) {
            $this->updateItemCalculations($index);
        }

        $this->calculateTotals();
    }

    protected function updateItemCalculations($index)
    {
        $quantity = (float) ($this->invoice_items[$index]['quantity'] ?? 1);
        $unitPrice = (float) ($this->invoice_items[$index]['unit_price'] ?? 0);
        $gstRate = (float) ($this->invoice_items[$index]['gst_rate'] ?? 0);

        $this->invoice_items[$index]['subtotal'] = $quantity * $unitPrice;
        $this->invoice_items[$index]['tax_amount'] = $this->invoice_items[$index]['subtotal'] * ($gstRate / 100);
        $this->invoice_items[$index]['total'] = $this->invoice_items[$index]['subtotal'] + $this->invoice_items[$index]['tax_amount'];

        \Log::info('Updated item calculations', ['index' => $index, 'item' => $this->invoice_items[$index]]);
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

    public function recalculate()
    {
        $this->calculateTotals();
    }

    private function calculateTotals($recalculateDiscountAmount = true)
    {
        $this->subtotal = collect($this->invoice_items)->sum(function ($item) {
            return (float) ($item['subtotal'] ?? 0);
        });

        $this->gst_summary = [];
        foreach ($this->invoice_items as $item) {
            if (!empty($item['product_id']) && !empty($item['hsn_code'])) {
                $hsn = $item['hsn_code'];
                $taxable_amount = (float) ($item['subtotal'] ?? 0);
                $gst_rate = (float) ($item['gst_rate'] ?? 0);
                $tax_amount = (float) ($item['tax_amount'] ?? 0);

                if (!isset($this->gst_summary[$hsn])) {
                    $this->gst_summary[$hsn] = [
                        'taxable_amount' => 0,
                        'gst_rate' => $gst_rate,
                        'tax_amount' => 0,
                    ];
                }

                $this->gst_summary[$hsn]['taxable_amount'] += $taxable_amount;
                $this->gst_summary[$hsn]['tax_amount'] += $tax_amount;
            }
        }

        $discountPercentage = is_numeric($this->discount_percentage) ? max(0, (float) $this->discount_percentage) : 0;
        $discountAmount = is_numeric($this->discount_amount) ? max(0, (float) $this->discount_amount) : 0;

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

        $this->tax_amount = collect($this->invoice_items)->sum(function ($item) {
            return (float) ($item['tax_amount'] ?? 0);
        });

        $taxable_amount = (float) $this->subtotal - $discountAmount;
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

        if ($this->isFullyPaid) {
            $this->paid_amount = $this->total;
        }

        $this->calculateDueAmount();

        if ($this->isCashSale() && ($this->paid_amount === '' || $this->isFullyPaid) && $this->total > 0) {
            $this->paid_amount = $this->total;
            $this->calculateDueAmount();
        }

        \Log::info('Calculated totals', [
            'subtotal' => $this->subtotal,
            'discount_amount' => $this->discount_amount,
            'tax_amount' => $this->tax_amount,
            'total' => $this->total,
            'gst_summary' => $this->gst_summary,
        ]);
    }

    public function updatedTotal($value)
    {
        if ($this->isFullyPaid && $value > 0) {
            $this->paid_amount = $value;
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
        $this->invoice_number = $this->generateInvoiceNumber();

        $paymentStatus = 'unpaid';
        if ($paidAmount >= $this->total) {
            $paymentStatus = 'paid';
        } elseif ($paidAmount > 0) {
            $paymentStatus = 'partial';
        }

        try {
            $invoice = Invoice::create([
                'invoice_number' => $this->invoice_number,
                'partie_id' => $this->partie_id,
                'invoice_date' => $this->invoice_date,
                'due_date' => $isCashSale ? $this->invoice_date : $this->due_date,
                'subtotal' => $this->subtotal,
                'discount_percentage' => is_numeric($this->discount_percentage) ? $this->discount_percentage : 0,
                'discount_amount' => is_numeric($this->discount_amount) ? $this->discount_amount : 0,
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

            foreach ($this->invoice_items as $item) {
                if ($item['product_id'] && $item['quantity'] > 0) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => $item['product_id'],
                        'hsn_code' => $item['hsn_code'],
                        'gst_rate' => $item['gst_rate'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal' => $item['subtotal'],
                        'tax_amount' => $item['tax_amount'],
                        'total' => $item['total'],
                    ]);

                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->decrement('stock_quantity', $item['quantity']);
                        StockMovement::create([
                            'product_id' => $item['product_id'],
                            'invoice_id' => $invoice->id,
                            'movement_type' => 'out',
                            'quantity' => $item['quantity'],
                            'reference_type' => 'sales_invoice',
                            'reference_id' => $invoice->id,
                            'notes' => 'Stock reduced for sales invoice ' . $invoice->invoice_number,
                        ]);
                    }
                }
            }

            $message = $isCashSale ? 'Cash sale completed successfully!' : 'Invoice created successfully!';
            session()->flash('message', $message);

            if ($action === 'save_and_send') {
                return redirect()->route('invoice.pdf.view', ['id' => $invoice->id]);
            }

            return redirect()->route('invoice.manage');
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry') && str_contains($e->getMessage(), 'invoice_number')) {
                $this->invoice_number = $this->generateUniqueInvoiceNumber();
                return $this->save($action);
            }

            \Log::error('Error saving invoice', ['error' => $e->getMessage()]);
            session()->flash('error', 'Error creating invoice: ' . $e->getMessage());
            throw $e;
        }
    }

    private function generateUniqueInvoiceNumber()
    {
        do {
            $lastInvoice = Invoice::orderBy('id', 'desc')->first();
            $nextNumber = $lastInvoice ? ((int) preg_match('/INV-(\d+)/', $lastInvoice->invoice_number, $matches) ? (int) $matches[1] + 1 : Invoice::count() + 1) : 1;
            $invoiceNumber = 'INV-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        } while (Invoice::where('invoice_number', $invoiceNumber)->exists());
        return $invoiceNumber;
    }

    public function updatedIsFullyPaid($value)
    {
        if ($value) {
            $this->paid_amount = $this->total;
            if (!$this->payment_method) {
                $this->payment_method = 'cash';
            }
        }
        $this->calculateDueAmount();
    }

    public function render()
    {
        return view('livewire.invoice.create-invoice');
    }
}