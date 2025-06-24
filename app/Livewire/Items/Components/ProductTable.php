<?php

namespace App\Livewire\Items\Components;

use Livewire\Component;

class ProductTable extends Component
{
    public $products;
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    public function mount($products, $sortBy = 'name', $sortDirection = 'asc')
    {
        $this->products = $products;
        $this->sortBy = $sortBy;
        $this->sortDirection = $sortDirection;
    }

    public function sortProducts($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }

        // Emit event to parent component to handle sorting
        $this->dispatch('sort-changed', [
            'sortBy' => $this->sortBy,
            'sortDirection' => $this->sortDirection
        ]);
    }

    public function editItem($id)
    {
        $this->dispatch('edit-item', itemId: $id);
    }

    public function deleteItem($id)
    {
        $this->dispatch('delete-item', itemId: $id);
    }

    public function createItem()
    {
        $this->dispatch('open-product-modal');
    }

    public function render()
    {
        return view('livewire.items.components.product-table');
    }
}
