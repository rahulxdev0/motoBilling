<?php

namespace App\Livewire\Report;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\InvoiceItem;
use App\Models\StockMovement;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app')]
class ManageReport extends Component
{
    public $activeTab = 'balance-sheet';
    public $dateFrom;
    public $dateTo;
    public $partyFilter = '';
    public $categoryFilter = '';
    public $productFilter = '';

    public function mount()
    {
        $this->dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function getBalanceSheetData()
    {
        $salesTotal = Invoice::where('invoice_category', 'sales')
            ->whereBetween('invoice_date', [$this->dateFrom, $this->dateTo])
            ->sum('total');

        $purchaseTotal = Invoice::where('invoice_category', 'purchase')
            ->whereBetween('invoice_date', [$this->dateFrom, $this->dateTo])
            ->sum('total');

        $receivables = Invoice::where('invoice_category', 'sales')
            ->where('payment_status', '!=', 'paid')
            ->sum('balance_amount');

        $payables = Invoice::where('invoice_category', 'purchase')
            ->where('payment_status', '!=', 'paid')
            ->sum('balance_amount');

        $stockValue = Product::where('status', 'active')
            ->sum(DB::raw('stock_quantity * purchase_price'));

        return [
            'sales_total' => $salesTotal,
            'purchase_total' => $purchaseTotal,
            'receivables' => $receivables,
            'payables' => $payables,
            'stock_value' => $stockValue,
            'net_worth' => $salesTotal - $purchaseTotal + $stockValue - $payables
        ];
    }

    public function getProfitLossData()
    {
        $revenue = Invoice::where('invoice_category', 'sales')
            ->whereBetween('invoice_date', [$this->dateFrom, $this->dateTo])
            ->sum('total');

        $cogs = InvoiceItem::whereHas('invoice', function($query) {
                $query->where('invoice_category', 'sales')
                      ->whereBetween('invoice_date', [$this->dateFrom, $this->dateTo]);
            })
            ->join('products', 'invoice_items.product_id', '=', 'products.id')
            ->sum(DB::raw('invoice_items.quantity * products.purchase_price'));

        $grossProfit = $revenue - $cogs;
        $grossProfitMargin = $revenue > 0 ? ($grossProfit / $revenue) * 100 : 0;

        return [
            'revenue' => $revenue,
            'cogs' => $cogs,
            'gross_profit' => $grossProfit,
            'gross_profit_margin' => $grossProfitMargin,
            'net_profit' => $grossProfit // Simplified - would include other expenses
        ];
    }

    public function getSalesSummary()
    {
        return Invoice::where('invoice_category', 'sales')
            ->whereBetween('invoice_date', [$this->dateFrom, $this->dateTo])
            ->selectRaw('
                COUNT(*) as total_invoices,
                SUM(total) as total_amount,
                SUM(CASE WHEN payment_status = "paid" THEN total ELSE 0 END) as paid_amount,
                SUM(balance_amount) as pending_amount,
                AVG(total) as avg_invoice_value
            ')
            ->first();
    }

    public function getPurchaseSummary()
    {
        return Invoice::where('invoice_category', 'purchase')
            ->whereBetween('invoice_date', [$this->dateFrom, $this->dateTo])
            ->selectRaw('
                COUNT(*) as total_invoices,
                SUM(total) as total_amount,
                SUM(CASE WHEN payment_status = "paid" THEN total ELSE 0 END) as paid_amount,
                SUM(balance_amount) as pending_amount,
                AVG(total) as avg_invoice_value
            ')
            ->first();
    }

    public function getStockSummary()
    {
        return Product::where('status', 'active')
            ->selectRaw('
                COUNT(*) as total_products,
                SUM(stock_quantity) as total_quantity,
                SUM(stock_quantity * purchase_price) as stock_value,
                SUM(CASE WHEN stock_quantity <= reorder_level THEN 1 ELSE 0 END) as low_stock_items
            ')
            ->first();
    }

    public function getLowStockItems()
    {
        return Product::with('category')
            ->where('status', 'active')
            ->whereRaw('stock_quantity <= reorder_level')
            ->orderBy('stock_quantity', 'asc')
            ->get();
    }

    public function getTopSellingProducts()
    {
        return InvoiceItem::join('products', 'invoice_items.product_id', '=', 'products.id')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->where('invoices.invoice_category', 'sales')
            ->whereBetween('invoices.invoice_date', [$this->dateFrom, $this->dateTo])
            ->select('products.name', 'products.selling_price')
            ->selectRaw('SUM(invoice_items.quantity) as total_sold, SUM(invoice_items.total) as total_revenue')
            ->groupBy('products.id', 'products.name', 'products.selling_price')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();
    }

    public function getDaybookEntries()
    {
        return Invoice::with(['partie', 'items.product'])
            ->whereBetween('invoice_date', [$this->dateFrom, $this->dateTo])
            ->orderBy('invoice_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.report.manage-report', [
            'balanceSheet' => $this->getBalanceSheetData(),
            'profitLoss' => $this->getProfitLossData(),
            'salesSummary' => $this->getSalesSummary(),
            'purchaseSummary' => $this->getPurchaseSummary(),
            'stockSummary' => $this->getStockSummary(),
            'lowStockItems' => $this->getLowStockItems(),
            'topSellingProducts' => $this->getTopSellingProducts(),
            'daybookEntries' => $this->getDaybookEntries(),
        ]);
    }
}
