<?php

namespace App\Livewire;

use App\Models\Invoice;
use App\Models\Partie;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout("components.layouts.app")]
class Dashboard extends Component
{
    public $totalRevenue;
    public $monthlyRevenue;
    public $totalInvoices;
    public $pendingInvoices;
    public $totalCustomers;
    public $lowStockItems;
    public $recentInvoices;
    public $topProducts;
    public $revenueGrowth;
    public $stockItems = [];

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $this->totalRevenue = Invoice::sum('total');
        $this->monthlyRevenue = Invoice::whereYear('invoice_date', now()->year)
            ->whereMonth('invoice_date', now()->month)
            ->sum('total');
        $this->totalInvoices = Invoice::count();
        $this->pendingInvoices = Invoice::whereIn('payment_status', ['unpaid', 'partial'])->count();
        $this->totalCustomers = Partie::count();

        // Revenue Growth calculation
        $currentMonthRevenue = $this->monthlyRevenue;
        $lastMonthRevenue = Invoice::whereYear('invoice_date', now()->subMonth()->year)
            ->whereMonth('invoice_date', now()->subMonth()->month)
            ->sum('total');

        $this->revenueGrowth = $lastMonthRevenue > 0 ?
            round(($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue * 100, 2) :
            100;

        // Mock low stock items
        $this->lowStockItems = Product::whereColumn('stock_quantity', '<', 'reorder_level')
            ->select('name', 'stock_quantity', 'reorder_level', 'unit')
            ->orderBy('stock_quantity', 'asc')
            ->limit(4)
            ->get()
            ->toArray();

        // Mock recent invoices
        $this->recentInvoices = Invoice::with('partie')
            ->orderBy('invoice_date', 'desc')
            ->take(5)
            ->get()
            ->map(function ($invoice) {
                return [
                    'id' => $invoice->invoice_number,
                    'customer' => $invoice->partie->name,
                    'amount' => $invoice->total,
                    'status' => $invoice->payment_status,
                ];
            })
            ->toArray();

        $this->topProducts = StockMovement::where('movement_type', 'invoice')
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(4)
            ->get()
            ->map(function ($movement) {
                return [
                    'name' => $movement->product->name,
                    'quantity' => $movement->total_sold,
                    'revenue' => $movement->total_sold * $movement->product->price,
                ];
            })
            ->toArray();
    }

    public function refreshData()
    {
        $this->loadDashboardData();
        $this->dispatch('data-refreshed');
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
