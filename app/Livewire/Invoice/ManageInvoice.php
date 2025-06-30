<?php

namespace App\Livewire\Invoice;

use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;

class ManageInvoice extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $paymentStatusFilter = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPaymentStatusFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->paymentStatusFilter = '';
        $this->resetPage();
    }

    public function deleteInvoice($invoiceId)
    {
        $invoice = Invoice::find($invoiceId);

        if ($invoice) {
            // Restore stock for deleted invoice
            foreach ($invoice->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->increment('stock_quantity', $item->quantity);
                }
            }

            $invoice->delete();
            session()->flash('message', 'Invoice deleted successfully!');
        }
    }

    public function duplicateInvoice($invoiceId)
    {
        $originalInvoice = Invoice::with('items')->find($invoiceId);

        if ($originalInvoice) {
            // Generate new invoice number
            $lastInvoice = Invoice::orderBy('id', 'desc')->first();
            $nextNumber = $lastInvoice ? (int)str_replace('INV-', '', $lastInvoice->invoice_number) + 1 : 1;
            $newInvoiceNumber = 'INV-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // Create duplicate invoice
            $newInvoice = $originalInvoice->replicate();
            $newInvoice->invoice_number = $newInvoiceNumber;
            $newInvoice->status = 'draft';
            $newInvoice->payment_status = 'unpaid';
            $newInvoice->paid_amount = 0;
            $newInvoice->balance_amount = $newInvoice->total;
            $newInvoice->invoice_date = now();
            $newInvoice->due_date = now()->addDays(30);
            $newInvoice->save();

            // Duplicate invoice items
            foreach ($originalInvoice->items as $item) {
                $newItem = $item->replicate();
                $newItem->invoice_id = $newInvoice->id;
                $newItem->save();
            }

            session()->flash('message', 'Invoice duplicated successfully! New invoice: ' . $newInvoiceNumber);
        }
    }

    public function render()
    {
        $invoices = Invoice::with(['partie', 'items'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('invoice_number', 'like', '%' . $this->search . '%')
                        ->orWhereHas('partie', function ($partieQuery) {
                            $partieQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->paymentStatusFilter, function ($query) {
                $query->where('payment_status', $this->paymentStatusFilter);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $stats = [
            'total' => Invoice::count(),
            'total_sales' => Invoice::sum('total'),
            'paid_amount' => Invoice::sum('paid_amount'),
            'unpaid_amount' => Invoice::sum('balance_amount'),
        ];

        return view('livewire.invoice.manage-invoice', compact('invoices', 'stats'));
    }
}
