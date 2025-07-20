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
    public $dateRange = [];
    public $quickDate = '';

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

    public function updatingDateRange()
    {
        $this->resetPage();
    }

    public function updatingQuickDate()
    {
        $this->resetPage();
    }

    public function setQuickDate($range)
    {
        $this->quickDate = $range;
        $this->dateRange = [];
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
        $this->dateRange = [];
        $this->quickDate = '';
        $this->resetPage();
    }

    public function deleteInvoice($invoiceId)
    {
        $invoice = Invoice::find($invoiceId);

        if ($invoice) {
            // Delete related stock_movements
            $invoice->stockMovements()->delete();

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

    /**
     * View PDF for an invoice
     */
    public function viewPdf($invoiceId)
    {
        return redirect()->route('invoice.pdf.view', ['id' => $invoiceId]);
    }

    /**
     * Download PDF for an invoice
     */
    public function downloadPdf($invoiceId)
    {
        return redirect()->route('invoice.pdf.download', ['id' => $invoiceId]);
    }

    public function render()
    {
        $invoices = Invoice::with(['partie', 'items'])
            ->where('invoice_category', 'sales') // Only show sales invoices
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
            // Date range filter
            ->when($this->dateRange && count($this->dateRange) === 2, function ($query) {
                $from = $this->dateRange[0];
                $to = $this->dateRange[1];
                $query->whereDate('invoice_date', '>=', $from)
                      ->whereDate('invoice_date', '<=', $to);
            })
            // Quick date filter
            ->when($this->quickDate, function ($query) {
                $today = now()->toDateString();
                $yesterday = now()->subDay()->toDateString();
                $startOfWeek = now()->startOfWeek()->toDateString();
                $endOfWeek = now()->endOfWeek()->toDateString();
                $startOfLastWeek = now()->subWeek()->startOfWeek()->toDateString();
                $endOfLastWeek = now()->subWeek()->endOfWeek()->toDateString();
                $startOfMonth = now()->startOfMonth()->toDateString();
                $endOfMonth = now()->endOfMonth()->toDateString();
                $startOfLastMonth = now()->subMonth()->startOfMonth()->toDateString();
                $endOfLastMonth = now()->subMonth()->endOfMonth()->toDateString();
                $startOfYear = now()->startOfYear()->toDateString();
                $endOfYear = now()->endOfYear()->toDateString();

                switch ($this->quickDate) {
                    case 'today':
                        $query->whereDate('invoice_date', $today);
                        break;
                    case 'yesterday':
                        $query->whereDate('invoice_date', $yesterday);
                        break;
                    case 'this_week':
                        $query->whereBetween('invoice_date', [$startOfWeek, $endOfWeek]);
                        break;
                    case 'last_week':
                        $query->whereBetween('invoice_date', [$startOfLastWeek, $endOfLastWeek]);
                        break;
                    case 'this_month':
                        $query->whereBetween('invoice_date', [$startOfMonth, $endOfMonth]);
                        break;
                    case 'last_month':
                        $query->whereBetween('invoice_date', [$startOfLastMonth, $endOfLastMonth]);
                        break;
                    case 'this_year':
                        $query->whereBetween('invoice_date', [$startOfYear, $endOfYear]);
                        break;
                }
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $stats = [
            'total' => Invoice::where('invoice_category', 'sales')->count(),
            'total_sales' => Invoice::where('invoice_category', 'sales')->sum('total'),
            'paid_amount' => Invoice::where('invoice_category', 'sales')->sum('paid_amount'),
            'unpaid_amount' => Invoice::where('invoice_category', 'sales')->sum('balance_amount'),
        ];

        return view('livewire.invoice.manage-invoice', compact('invoices', 'stats'));
    }
}
