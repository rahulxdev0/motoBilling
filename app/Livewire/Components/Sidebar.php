<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Sidebar extends Component
{
    public $isItemsOpen = false;
    
    public function toggleItems()
    {
        $this->isItemsOpen = !$this->isItemsOpen;
    }
    
    public function render()
    {
        return view('livewire.components.sidebar');
    }
}
