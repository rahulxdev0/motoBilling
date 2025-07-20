<?php

namespace App\Livewire\Items;

use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;
use App\Models\Product;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Exports\ProductsExport;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('components.layouts.app')]
class ManageItems extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategory = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $statusFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategory' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    #[On('sort-changed')]
    public function handleSortChange($data)
    {
        $this->sortBy = $data['sortBy'];
        $this->sortDirection = $data['sortDirection'];
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'selectedCategory', 'statusFilter']);
        $this->resetPage();
    }

    public function handleCreateItem()
    {
        $this->dispatch('open-product-modal');
    }

    public function handleEditItem($itemId)
    {
        return redirect()->route('items.edit', ['item' => $itemId]);
    }

    public function handleDeleteItem($itemId)
    {
        $this->dispatch('confirm-delete', itemId: $itemId);
    }

    public function exportExcel()
    {
        try {
            $export = new ProductsExport($this->getProductsQuery());
            $filename = 'products_export_' . now()->format('Ymd_His') . '.xlsx';
            return (new FastExcel($export->collection()))->download($filename);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to export Excel: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function exportPdf()
    {
        try {
            $products = $this->getProductsQuery()->get();
            $pdf = Pdf::loadView('exports.products-pdf', [
                'products' => $products,
                'productStats' => $this->productStats,
            ]);
            $filename = 'products_export_' . now()->format('Ymd_His') . '.pdf';
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, $filename);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to export PDF: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function openAddCategoryModal()
    {
        $this->dispatch('open-category-modal');
    }

    #[On('category-added')]
    public function handleCategoryAdded($categoryData)
    {
        session()->flash('success', 'Category "' . $categoryData['name'] . '" created successfully!');
    }

    #[On('product-created')]
    public function handleProductCreated()
    {
        $this->resetPage();
        session()->flash('success', 'Product created successfully!');
    }

    public function getCategoriesProperty()
    {
        return Category::orderBy('name')->get();
    }

    public function getProductsProperty()
    {
        return $this->getProductsQuery()->paginate($this->perPage);
    }

    protected function getProductsQuery()
    {
        $query = Product::with(['category', 'partie']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('item_code', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%')
                  ->orWhere('brand', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->selectedCategory) {
            $query->whereHas('category', function ($q) {
                $q->where('name', $this->selectedCategory);
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query;
    }

    public function getProductStatsProperty()
    {
        $allProducts = Product::with('category')->get();
        
        return [
            'total_items' => $allProducts->count(),
            'total_stock_value' => $allProducts->sum(function ($product) {
                return $product->stock_quantity * $product->purchase_price;
            }),
            'low_stock_items' => $allProducts->where('stock_quantity', '<=', 5)->count(),
            'total_stock_quantity' => $allProducts->sum('stock_quantity'),
        ];
    }

    public function render()
    {
        return view('livewire.items.manage-items', [
            'products' => $this->products,
            'categories' => $this->categories,
            'productStats' => $this->productStats
        ]);
    }
}