<?php

namespace App\Livewire\Parties;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout("components.layouts.app")]
class CreatePatie extends Component
{
    public function render()
    {
        return view('livewire.parties.create-patie');
    }
}
