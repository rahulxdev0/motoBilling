<?php

namespace App\Livewire\Components;

use Livewire\Attributes\On;
use Livewire\Component;

class LoaderComponent extends Component
{
    public $isLoading = true; // Changed from true to false

    protected $listeners = [
        'showLoader' => 'showLoader',
        'hideLoader' => 'hideLoader',
    ];


    #[On('showLoader')]
    public function showLoader()
    {
        $this->isLoading = true;
    }

    #[On('hideLoader')]
    public function hideLoader()
    {
        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.components.loader-component');
    }
}
