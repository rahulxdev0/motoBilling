<?php

namespace App\Livewire\Items;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;

#[Layout('components.layouts.app')]
class ManageItems extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategory = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategory' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    protected $listeners = [
        'sort-changed' => 'handleSortChange',
        'edit-item' => 'handleEditItem',
        'delete-item' => 'handleDeleteItem',
        'create-item' => 'handleCreateItem',
        'category-added' => 'handleCategoryAdded'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function handleSortChange($data)
    {
        $this->sortBy = $data['sortBy'];
        $this->sortDirection = $data['sortDirection'];
    }

    public function clearFilters()
    {
        $this->reset(['search', 'selectedCategory']);
        $this->resetPage();
    }

    public function handleCreateItem()
    {
        $this->dispatch('open-create-modal');
    }

    public function handleEditItem($itemId)
    {
        $this->dispatch('open-edit-modal', itemId: $itemId);
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

    public function handleCategoryAdded($categoryData)
    {
        // Refresh the categories list
        $this->render();
        
        // Show success message
        session()->flash('success', 'Category "' . $categoryData['name'] . '" created successfully!');
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
        // Mock data - replace with actual database query
        $products = collect([
            [
                'id' => 1,
                'name' => 'Motor Oil 5W-30',
                'item_code' => 'MO5W30001',
                'stock_quantity' => 45,
                'selling_price' => 25.99,
                'purchase_price' => 18.50,
                'mrp' => 29.99,
                'category' => 'Motor Oils',
                'low_stock' => false
            ],
            [
                'id' => 2,
                'name' => 'Brake Pads Front',
                'item_code' => 'BP001F',
                'stock_quantity' => 3,
                'selling_price' => 89.99,
                'purchase_price' => 65.00,
                'mrp' => 99.99,
                'category' => 'Brake Parts',
                'low_stock' => true
            ],
            [
                'id' => 3,
                'name' => 'Air Filter Standard',
                'item_code' => 'AF001S',
                'stock_quantity' => 8,
                'selling_price' => 15.99,
                'purchase_price' => 10.50,
                'mrp' => 19.99,
                'category' => 'Filters',
                'low_stock' => true
            ],
            [
                'id' => 4,
                'name' => 'Spark Plug Set',
                'item_code' => 'SP001SET',
                'stock_quantity' => 12,
                'selling_price' => 45.99,
                'purchase_price' => 32.00,
                'mrp' => 52.99,
                'category' => 'Engine Parts',
                'low_stock' => false
            ],
            [
                'id' => 5,
                'name' => 'LED Headlight Bulb',
                'item_code' => 'LH001LED',
                'stock_quantity' => 25,
                'selling_price' => 35.99,
                'purchase_price' => 24.50,
                'mrp' => 42.99,
                'category' => 'Electrical',
                'low_stock' => false
            ],
            [
                'id' => 6,
                'name' => 'Engine Oil Filter',
                'item_code' => 'EOF001',
                'stock_quantity' => 18,
                'selling_price' => 12.99,
                'purchase_price' => 8.50,
                'mrp' => 16.99,
                'category' => 'Filters',
                'low_stock' => false
            ],
        ]);

        // Apply filters
        if ($this->search) {
            $products = $products->filter(function ($product) {
                return str_contains(strtolower($product['name']), strtolower($this->search)) ||
                       str_contains(strtolower($product['item_code']), strtolower($this->search));
            });
        }

        if ($this->selectedCategory) {
            $products = $products->filter(function ($product) {
                return $product['category'] === $this->selectedCategory;
            });
        }

        // Apply sorting
        $products = $products->sortBy($this->sortBy, SORT_REGULAR, $this->sortDirection === 'desc');

        return $products->values();
    }

    public function render()
    {
        return view('livewire.items.manage-items', [
            'products' => $this->products->take($this->perPage),
            'categories' => $this->categories
        ]);
    }
}
