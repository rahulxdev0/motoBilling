<?php

namespace App\Livewire;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Partie;
use App\Models\Product;
use Carbon\Carbon;
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

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function refreshData()
    {
        $this->loadDashboardData();
        session()->flash('message', 'Dashboard data refreshed successfully!');
    }

    protected function loadDashboardData()
    {
        // Calculate total revenue from sales invoices
        $this->totalRevenue = Invoice::where('invoice_category', 'sales')
            ->where('status', '!=', 'cancelled')
            ->sum('total');
            
        // Calculate monthly revenue (current month)
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        $this->monthlyRevenue = Invoice::where('invoice_category', 'sales')
            ->where('status', '!=', 'cancelled')
            ->whereBetween('invoice_date', [$startOfMonth, $endOfMonth])
            ->sum('total');
            
        // Calculate previous month's revenue for growth percentage
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
        
        $lastMonthRevenue = Invoice::where('invoice_category', 'sales')
            ->where('status', '!=', 'cancelled')
            ->whereBetween('invoice_date', [$startOfLastMonth, $endOfLastMonth])
            ->sum('total');
            
        // Calculate revenue growth
        $this->revenueGrowth = $lastMonthRevenue > 0 
            ? round((($this->monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : ($this->monthlyRevenue > 0 ? 100 : 0);

        // Count total invoices
        $this->totalInvoices = Invoice::where('invoice_category', 'sales')
            ->where('status', '!=', 'cancelled')
            ->count();
            
        // Count pending invoices
        $this->pendingInvoices = Invoice::where('invoice_category', 'sales')
            ->where('status', '!=', 'cancelled')
            ->whereIn('payment_status', ['unpaid', 'partial', 'overdue'])
            ->count();
            
        // Count total customers (excluding cash sale customer)
        $this->totalCustomers = Partie::where('is_active', true)
            ->where('name', '!=', 'Cash Sale Customer')
            ->count();
            
        // Get low stock items
        $this->lowStockItems = Product::where('status', 'active')
            ->where('type', 'product')
            ->whereColumn('stock_quantity', '<=', 'reorder_level')
            ->orderBy('stock_quantity', 'asc')
            ->limit(5)
            ->get()
            ->map(function($product) {
                return [
                    'name' => $product->name,
                    'stock_quantity' => $product->stock_quantity,
                    'reorder_level' => $product->reorder_level,
                    'unit' => $product->unit
                ];
            })
            ->toArray();
            
        // Get recent invoices
        $recentInvoices = Invoice::with('partie')
            ->where('invoice_category', 'sales')
            ->orderBy('invoice_date', 'desc')
            ->limit(5)
            ->get();
            
        $this->recentInvoices = $recentInvoices->map(function($invoice) {
            return [
                'id' => $invoice->invoice_number,
                'customer' => $invoice->partie->name,
                'amount' => $invoice->total,
                'status' => $invoice->payment_status
            ];
        })->toArray();
        
        // Get top selling products
        $topProducts = InvoiceItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(total) as total_revenue'))
            ->with('product')
            ->whereHas('invoice', function($query) {
                $query->where('invoice_category', 'sales')
                      ->where('status', '!=', 'cancelled')
                      ->whereBetween('invoice_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            })
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();
            
        $this->topProducts = $topProducts->map(function($item) {
            return [
                'name' => $item->product->name ?? 'Unknown Product',
                'quantity' => $item->total_quantity,
                'revenue' => $item->total_revenue
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
