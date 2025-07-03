<?php

namespace App\Livewire\Report;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class ManageReport extends Component
{
    public function render()
    {
        return view('livewire.report.manage-report');
    }
}
