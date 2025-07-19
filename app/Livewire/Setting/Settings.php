<?php

namespace App\Livewire\Setting;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Settings extends Component
{
    public function render()
    {
        return view('livewire.setting.settings');
    }
}
