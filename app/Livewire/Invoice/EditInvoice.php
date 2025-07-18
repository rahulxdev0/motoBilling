<?php

namespace App\Livewire\Invoice;

use App\Models\Invoice;
use App\Models\Partie;
use App\Models\Product;
use App\Models\InvoiceItem;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class EditInvoice extends Component
{
    public Invoice $invoice;

    // Editable fields
    public $invoice_number;
    public $partie_id;
    public $invoice_date;
    public $due_date;
    public $payment_terms;
    public $terms_conditions;
    public $notes;
    public $status;
    public $paid_amount;
    public $payment_method;
    public $subtotal;
    public $discount_percentage;
    public $discount_amount;
    public $tax_percentage;
    public $tax_amount;
    public $round_off;
    public $total;
    public $balance_amount;
    public $payment_status;

    public $invoice_items = [];
    public $parties;
    public $products;

    public $isEditing = false;

    public $search_product = '';
    public $filtered_products = [];

    public function mount(Invoice $invoice)
    {
        $invoice->load(['partie', 'items.product']);
        $this->invoice = $invoice;

        // Fill form fields from invoice
        $this->invoice_number = $invoice->invoice_number;
        $this->partie_id = $invoice->partie_id;
        $this->invoice_date = $invoice->invoice_date ? $invoice->invoice_date->format('Y-m-d') : '';
        $this->due_date = $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '';
        $this->payment_terms = $invoice->payment_terms;
        $this->terms_conditions = $invoice->terms_conditions;
        $this->notes = $invoice->notes;
        $this->status = $invoice->status;
        $this->paid_amount = $invoice->paid_amount;
        $this->payment_method = $invoice->payment_method;
        $this->subtotal = $invoice->subtotal;
        $this->discount_percentage = $invoice->discount_percentage;
        $this->discount_amount = $invoice->discount_amount;
        $this->tax_percentage = $invoice->tax_percentage;
        $this->tax_amount = $invoice->tax_amount;
        $this->round_off = $invoice->round_off;
        $this->total = $invoice->total;
        $this->balance_amount = $invoice->balance_amount;
        $this->payment_status = $invoice->payment_status;

        $this->invoice_items = [];
        foreach ($invoice->items as $item) {
            $this->invoice_items[] = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total' => $item->total,
            ];
        }

        $this->parties = Partie::active()->get();
        $this->products = Product::where('status', 'active')->get();
        $this->search_product = '';
        $this->filtered_products = $this->products->toArray();
    }

    public function enableEdit()
    {
        $this->isEditing = true;
    }

    public function save()
    {
        $this->validate([
            'partie_id' => 'required|exists:parties,id',
            'invoice_date' => 'required|date',
            'invoice_items' => 'required|array|min:1',
            'invoice_items.*.product_id' => 'required|exists:products,id',
            'invoice_items.*.quantity' => 'required|numeric|min:1',
            'invoice_items.*.unit_price' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string',
        ]);

        // Update invoice fields
        $this->invoice->update([
            'partie_id' => $this->partie_id,
            'invoice_date' => $this->invoice_date,
            'due_date' => $this->due_date,
            'payment_terms' => $this->payment_terms,
            'terms_conditions' => $this->terms_conditions,
            'notes' => $this->notes,
            'status' => $this->status,
            'paid_amount' => $this->paid_amount,
            'payment_method' => $this->payment_method,
            'subtotal' => $this->subtotal,
            'discount_percentage' => $this->discount_percentage,
            'discount_amount' => $this->discount_amount,
            'tax_percentage' => $this->tax_percentage,
            'tax_amount' => $this->tax_amount,
            'round_off' => $this->round_off,
            'total' => $this->total,
            'balance_amount' => $this->balance_amount,
            'payment_status' => $this->payment_status,
        ]);

        // Update invoice items
        foreach ($this->invoice_items as $itemData) {
            if (isset($itemData['id'])) {
                $item = InvoiceItem::find($itemData['id']);
                if ($item) {
                    $item->update([
                        'product_id' => $itemData['product_id'],
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $itemData['unit_price'],
                        'total' => $itemData['total'],
                    ]);
                }
            }
        }

        $this->isEditing = false;
        session()->flash('message', 'Invoice updated successfully!');
        // Optionally, reload invoice data
        $this->mount($this->invoice->fresh());
    }

    public function updatedInvoiceItems($value, $key)
    {
        // This method is called when any invoice_items field is updated (Livewire magic)
        $parts = explode('.', $key);
        $index = $parts[0];
        $field = $parts[1];

        // Update unit_price if product changes
        if ($field === 'product_id') {
            $product = Product::find($value);
            if ($product) {
                $this->invoice_items[$index]['unit_price'] = (float) $product->selling_price;
                $this->invoice_items[$index]['total'] =
                    (float) $this->invoice_items[$index]['quantity'] * (float) $product->selling_price;
            }
        }

        // Update total if qty or unit_price changes
        if (in_array($field, ['quantity', 'unit_price'])) {
            $quantity = (float) $this->invoice_items[$index]['quantity'];
            $unitPrice = (float) $this->invoice_items[$index]['unit_price'];
            $this->invoice_items[$index]['total'] = $quantity * $unitPrice;
        }

        $this->calculateTotals();
    }

    // Product search for each row (like CreateInvoice)
    public function updatedSearchProduct($value)
    {
        if (strlen($value) >= 2) {
            $this->filtered_products = Product::where('status', 'active')
                ->where(function ($query) use ($value) {
                    $query->where('name', 'like', '%' . $value . '%')
                          ->orWhere('item_code', 'like', '%' . $value . '%');
                })
                ->limit(10)
                ->get()
                ->toArray();
        } else {
            $this->filtered_products = $this->products->take(10)->toArray();
        }
    }

    // Live update for discount percentage with delayed calculation
    public function updatedDiscountPercentage($value)
    {
        $this->discount_percentage = $value;
        $this->dispatch('delay-calculate-discount-from-percentage');
    }

    // Live update for discount amount with delayed calculation
    public function updatedDiscountAmount($value)
    {
        $this->discount_amount = $value;
        $this->dispatch('delay-calculate-discount-from-amount');
    }

    // Delayed calculation methods
    public function calculateDiscountFromPercentage()
    {
        if ($this->subtotal > 0 && $this->discount_percentage !== '' && is_numeric($this->discount_percentage)) {
            $this->discount_amount = round((float)$this->subtotal * (float)$this->discount_percentage / 100, 2);
        } elseif ($this->discount_percentage === '') {
            $this->discount_amount = '';
        }
        $this->calculateTotals(false);
    }

    public function calculateDiscountFromAmount()
    {
        if ($this->subtotal > 0 && $this->discount_amount !== '' && is_numeric($this->discount_amount) && (float)$this->discount_amount >= 0) {
            $this->discount_percentage = round(((float)$this->discount_amount * 100) / (float)$this->subtotal, 2);
        } elseif ($this->discount_amount === '') {
            $this->discount_percentage = '';
        }
        $this->calculateTotals(true);
    }

    private function calculateTotals($recalculateDiscountAmount = true)
    {
        // Calculate subtotal
        $this->subtotal = collect($this->invoice_items)->sum(function ($item) {
            return (float) ($item['total'] ?? 0);
        });

        $discountPercentage = is_numeric($this->discount_percentage) ? max(0, (float) $this->discount_percentage) : 0;
        $discountAmount = is_numeric($this->discount_amount) ? max(0, (float) $this->discount_amount) : 0;
        $this->tax_percentage = max(0, (float) $this->tax_percentage);

        if ($recalculateDiscountAmount && $discountPercentage > 0 && $this->subtotal > 0) {
            $discountAmount = round(((float) $this->subtotal * $discountPercentage) / 100, 2);
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
        $this->tax_amount = round(($taxable_amount * (float) $this->tax_percentage) / 100, 2);

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
    }

    private function calculateDueAmount()
    {
        $paidAmount = is_numeric($this->paid_amount) ? (float) $this->paid_amount : 0;
        $this->balance_amount = max(0, (float) $this->total - $paidAmount);
    }

    public function render()
    {
        return view('livewire.invoice.edit-invoice', [
            'invoice' => $this->invoice,
            'isEditing' => $this->isEditing,
            'parties' => $this->parties,
            'products' => $this->products,
            'invoice_items' => $this->invoice_items,
        ]);
    }
}
