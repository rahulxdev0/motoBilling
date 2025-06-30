<?php

namespace App\Livewire\Purchase;

use Livewire\Attributes\Layout;
use Livewire\Component;

class PurchaseInvoice extends Component
{
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.purchase.purchase-invoice');
    }
}
