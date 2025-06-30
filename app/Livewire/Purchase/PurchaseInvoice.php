<?php

namespace App\Livewire\Purchase;

use App\Models\Invoice;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class PurchaseInvoice extends Component
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

    public function deletePurchaseInvoice($invoiceId)
    {
        $invoice = Invoice::where('invoice_category', 'purchase')->find($invoiceId);

        if ($invoice) {
            // Delete related stock_movements
            $invoice->stockMovements()->delete();

            // Restore stock for deleted purchase invoice (reduce stock as it was a purchase)
            foreach ($invoice->items as $item) {
                $product = $item->product;
                if ($product) {
                    // For purchase invoices, we need to reduce stock when deleting
                    $product->decrement('stock_quantity', $item->quantity);
                }
            }

            $invoice->delete();
            session()->flash('message', 'Purchase invoice deleted successfully!');
        }
    }

    public function render()
    {
        $invoices = Invoice::with(['partie', 'items'])
            ->where('invoice_category', 'purchase') // Only show purchase invoices
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
            'total' => Invoice::where('invoice_category', 'purchase')->count(),
            'total_purchases' => Invoice::where('invoice_category', 'purchase')->sum('total'),
            'paid_amount' => Invoice::where('invoice_category', 'purchase')->sum('paid_amount'),
            'unpaid_amount' => Invoice::where('invoice_category', 'purchase')->sum('balance_amount'),
        ];

        return view('livewire.purchase.purchase-invoice', compact('invoices', 'stats'));
    }
}
