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
    // Invoice properties
    public $invoice_number;
    public $partie_id;
    public $invoice_date;
    public $due_date;
    public $payment_terms;
    public $terms_conditions;
    public $notes;
    public $status = 'draft';

    // Calculation properties
    public $subtotal = 0;
    public $discount_percentage = 0;
    public $discount_amount = 0;
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

    public function mount()
    {
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

        // Generate invoice number
        $this->invoice_number = $this->generateInvoiceNumber();

        // Set default dates
        $this->invoice_date = now()->format('Y-m-d');
        $this->due_date = now()->addDays(30)->format('Y-m-d');

        // Add initial empty row
        $this->addInvoiceItem();
    }

    // Method to check if current selection is cash sale
    public function isCashSale()
    {
        return $this->partie_id == $this->cash_sale_customer->id;
    }

    // Handle customer selection changes
    public function updatedPartieId()
    {
        if ($this->isCashSale()) {
            // For cash sales, set due date to same as invoice date
            $this->due_date = $this->invoice_date;
            $this->payment_terms = 'Cash Payment';
        } else {
            // For credit sales, set due date to 30 days from invoice date
            $this->due_date = now()->parse($this->invoice_date)->addDays(30)->format('Y-m-d');
            $this->payment_terms = '';
        }
    }

    // Update invoice date method to handle cash sales
    public function updatedInvoiceDate()
    {
        if ($this->isCashSale()) {
            $this->due_date = $this->invoice_date;
        }
    }

    private function generateInvoiceNumber()
    {
        // Get the latest invoice number
        $lastInvoice = Invoice::orderBy('id', 'desc')->first();

        if (!$lastInvoice) {
            return 'INV-0001';
        }

        // Extract the numeric part from the invoice number
        $lastNumber = $lastInvoice->invoice_number;

        // Use regex to extract the numeric part after 'INV-'
        if (preg_match('/INV-(\d+)/', $lastNumber, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        } else {
            // Fallback: count all invoices and add 1
            $nextNumber = Invoice::count() + 1;
        }

        return 'INV-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
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
                $this->invoice_items[$index]['unit_price'] = (float) $product->selling_price;
                // Calculate total immediately when product is selected
                $this->invoice_items[$index]['total'] =
                    (float) $this->invoice_items[$index]['quantity'] * (float) $product->selling_price;
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

    public function updatedDiscountPercentage()
    {
        $this->discount_amount = ((float) $this->subtotal * (float) $this->discount_percentage) / 100;
        $this->calculateTotals();
    }

    public function updatedDiscountAmount()
    {
        if ($this->subtotal > 0) {
            $this->discount_percentage = ((float) $this->discount_amount * 100) / (float) $this->subtotal;
        }
        $this->calculateTotals();
    }

    public function updatedTaxPercentage()
    {
        $this->calculateTotals();
    }

    private function calculateTotals()
    {
        // Calculate subtotal - ensure we're working with numbers
        $this->subtotal = collect($this->invoice_items)->sum(function ($item) {
            return (float) $item['total'];
        });

        // Calculate discount amount if percentage is set
        if ($this->discount_percentage > 0) {
            $this->discount_amount = ((float) $this->subtotal * (float) $this->discount_percentage) / 100;
        }

        // Calculate tax amount
        $taxable_amount = (float) $this->subtotal - (float) $this->discount_amount;
        $this->tax_amount = ($taxable_amount * (float) $this->tax_percentage) / 100;

        // Calculate total before round off
        $calculated_total = $taxable_amount + (float) $this->tax_amount;

        // Calculate round off
        $this->round_off = round($calculated_total) - $calculated_total;

        // Final total
        $this->total = round($calculated_total);
    }

    public function searchProducts()
    {
        if (strlen($this->search_product) >= 2) {
            $this->filtered_products = Product::where('status', 'active')
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search_product . '%')
                        ->orWhere('item_code', 'like', '%' . $this->search_product . '%')
                        ->orWhere('sku', 'like', '%' . $this->search_product . '%');
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
        $this->validate([
            'partie_id' => 'required|exists:parties,id',
            'invoice_date' => 'required|date',
            'invoice_items' => 'required|array|min:1',
            'invoice_items.*.product_id' => 'required|exists:products,id',
            'invoice_items.*.quantity' => 'required|numeric|min:1',
            'invoice_items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $this->status = $action === 'save_and_send' ? 'sent' : 'draft';
        $isCashSale = $this->isCashSale();

        // Generate a new invoice number just before saving to avoid duplicates
        $this->invoice_number = $this->generateInvoiceNumber();

        try {
            // Create invoice
            $invoice = Invoice::create([
                'invoice_number' => $this->invoice_number,
                'partie_id' => $this->partie_id,
                'invoice_date' => $this->invoice_date,
                'due_date' => $isCashSale ? $this->invoice_date : $this->due_date,
                'subtotal' => $this->subtotal,
                'discount_percentage' => $this->discount_percentage,
                'discount_amount' => $this->discount_amount,
                'tax_percentage' => $this->tax_percentage,
                'tax_amount' => $this->tax_amount,
                'round_off' => $this->round_off,
                'total' => $this->total,
                'paid_amount' => $isCashSale ? $this->total : 0,
                'balance_amount' => $isCashSale ? 0 : $this->total,
                'payment_status' => $isCashSale ? 'paid' : 'unpaid',
                'payment_terms' => $isCashSale ? 'Cash Payment' : $this->payment_terms,
                'terms_conditions' => $this->terms_conditions,
                'notes' => $this->notes,
                'status' => $this->status,
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

                    // Update product stock
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->decrement('stock_quantity', $item['quantity']);

                        // Create stock movement record
                        StockMovement::create([
                            'product_id' => $item['product_id'],
                            'quantity' => -$item['quantity'],
                            'movement_type' => 'invoice',
                            'invoice_id' => $invoice->id,
                            'notes' => 'Stock reduced for invoice: ' . $this->invoice_number . ($isCashSale ? ' (Cash Sale)' : ''),
                        ]);
                    }
                }
            }

            $message = $isCashSale ? 'Cash sale completed successfully!' : 'Invoice created successfully!';
            session()->flash('message', $message);

            if ($action === 'save_and_send') {
                $message = $isCashSale ? 'Cash sale completed and receipt generated!' : 'Invoice created and sent successfully!';
                session()->flash('message', $message);
            }

            return redirect()->route('invoice.manage');

        } catch (\Exception $e) {
            // If there's still a duplicate entry error, generate a new number and try again
            if (str_contains($e->getMessage(), 'Duplicate entry') && str_contains($e->getMessage(), 'invoice_number')) {
                $this->invoice_number = $this->generateUniqueInvoiceNumber();
                return $this->save($action);
            }

            session()->flash('error', 'Error creating invoice: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate a truly unique invoice number by checking database
     */
    private function generateUniqueInvoiceNumber()
    {
        do {
            $lastInvoice = Invoice::orderBy('id', 'desc')->first();

            if (!$lastInvoice) {
                $nextNumber = 1;
            } else {
                // Extract the numeric part from the invoice number
                if (preg_match('/INV-(\d+)/', $lastInvoice->invoice_number, $matches)) {
                    $nextNumber = (int) $matches[1] + 1;
                } else {
                    $nextNumber = Invoice::count() + 1;
                }
            }

            $invoiceNumber = 'INV-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // Check if this number already exists
            $exists = Invoice::where('invoice_number', $invoiceNumber)->exists();

        } while ($exists);

        return $invoiceNumber;
    }

    public function render()
    {
        return view('livewire.invoice.create-invoice');
    }
}
