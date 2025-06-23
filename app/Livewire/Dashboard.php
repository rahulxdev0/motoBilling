<?php

namespace App\Livewire;

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
    public $stockItems;

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        // Mock data - replace with actual database queries
        $this->totalRevenue = 125450.75;
        $this->monthlyRevenue = 23750.50;
        $this->totalInvoices = 1247;
        $this->pendingInvoices = 23;
        $this->totalCustomers = 189;
        $this->revenueGrowth = 12.5;

        // Mock low stock items
        $this->lowStockItems = [
            ['name' => 'Motor Oil 5W-30', 'current_stock' => 5, 'min_stock' => 20, 'unit' => 'bottles'],
            ['name' => 'Brake Pads', 'current_stock' => 3, 'min_stock' => 15, 'unit' => 'sets'],
            ['name' => 'Air Filter', 'current_stock' => 8, 'min_stock' => 25, 'unit' => 'pieces'],
            ['name' => 'Spark Plugs', 'current_stock' => 12, 'min_stock' => 30, 'unit' => 'pieces'],
        ];

        // Mock recent invoices
        $this->recentInvoices = [
            ['id' => 'INV-2025-001', 'customer' => 'John Doe', 'amount' => 245.50, 'status' => 'paid', 'date' => '2025-06-23'],
            ['id' => 'INV-2025-002', 'customer' => 'Jane Smith', 'amount' => 189.75, 'status' => 'pending', 'date' => '2025-06-22'],
            ['id' => 'INV-2025-003', 'customer' => 'Mike Johnson', 'amount' => 567.25, 'status' => 'paid', 'date' => '2025-06-22'],
            ['id' => 'INV-2025-004', 'customer' => 'Sarah Wilson', 'amount' => 123.00, 'status' => 'overdue', 'date' => '2025-06-21'],
            ['id' => 'INV-2025-005', 'customer' => 'Tom Brown', 'amount' => 334.80, 'status' => 'paid', 'date' => '2025-06-21'],
        ];

        // Mock top products
        $this->topProducts = [
            ['name' => 'Oil Change Service', 'quantity' => 45, 'revenue' => 2250.00],
            ['name' => 'Brake Service', 'quantity' => 23, 'revenue' => 1840.00],
            ['name' => 'Tire Replacement', 'quantity' => 18, 'revenue' => 2160.00],
            ['name' => 'Engine Tune-up', 'quantity' => 12, 'revenue' => 1800.00],
        ];

        // Mock stock overview
        $this->stockItems = [
            ['category' => 'Motor Oils', 'total_items' => 15, 'low_stock' => 3, 'value' => 12450.00],
            ['category' => 'Brake Parts', 'total_items' => 8, 'low_stock' => 2, 'value' => 8920.00],
            ['category' => 'Filters', 'total_items' => 12, 'low_stock' => 1, 'value' => 3240.00],
            ['category' => 'Engine Parts', 'total_items' => 25, 'low_stock' => 4, 'value' => 18760.00],
        ];
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
