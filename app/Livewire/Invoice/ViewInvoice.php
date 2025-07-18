<?php

namespace App\Livewire\Invoice;

use App\Models\Invoice;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class ViewInvoice extends Component
{
    public Invoice $invoice;

    public function mount(Invoice $invoice)
    {
        // Eager load relations for the view
        $invoice->load(['partie', 'items.product']);
        $this->invoice = $invoice;
    }

    public function render()
    {
        return view('livewire.invoice.view-invoice', [
            'invoice' => $this->invoice,
        ]);
    }
}
