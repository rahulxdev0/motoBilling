<?php

namespace App\Livewire\Items;

use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;
use App\Models\Product;

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
        // Dispatch event to open the create product modal
        $this->dispatch('open-product-modal');
    }

    public function handleEditItem($itemId)
    {
        // Redirect to the edit page for the item
        return redirect()->route('items.edit', ['item' => $itemId]);
    }

    public function handleDeleteItem($itemId)
    {
        $this->dispatch('confirm-delete', itemId: $itemId);
    }

    public function exportPdf()
    {
        $this->dispatch('export-pdf');
    }

    public function openAddCategoryModal()
    {
        $this->dispatch('open-category-modal');
    }

    #[On('category-added')]
    public function handleCategoryAdded($categoryData)
    {
        // Refresh the categories list
        $this->render();
        
        // Show success message
        session()->flash('success', 'Category "' . $categoryData['name'] . '" created successfully!');
    }

    #[On('product-created')]
    public function handleProductCreated()
    {
        // Refresh the product list when a new product is created
        $this->resetPage();
        
        // Show success message
        session()->flash('success', 'Product created successfully!');
    }

    /**
     * Get categories from database
     */
    public function getCategoriesProperty()
    {
        return Category::orderBy('name')->get();
    }

    public function getProductsProperty()
    {
        $query = Product::with(['category', 'partie']);

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('item_code', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%')
                  ->orWhere('brand', 'like', '%' . $this->search . '%');
            });
        }

        // Apply category filter
        if ($this->selectedCategory) {
            $query->whereHas('category', function ($q) {
                $q->where('name', $this->selectedCategory);
            });
        }

        // Apply status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        // Get paginated results
        return $query->paginate($this->perPage);
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
        $products = $this->products;
        
        return view('livewire.items.manage-items', [
            'products' => $products,
            'categories' => $this->categories,
            'productStats' => $this->productStats
        ]);
    }
}
