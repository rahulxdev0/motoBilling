<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Sidebar extends Component
{
    public $isItemsOpen = false;
    public $isSalesOpen = false;
    public $isPurchaseOpen = false;
    
    public function toggleItems()
    {
        $this->isItemsOpen = !$this->isItemsOpen;
    }

    public function toggleSales()
    {
        $this->isSalesOpen = !$this->isSalesOpen;
    }

    public function togglePurchase()
    {
        $this->isPurchaseOpen = !$this->isPurchaseOpen;
    }

    public function render()
    {
        return view('livewire.components.sidebar');
    }
}
