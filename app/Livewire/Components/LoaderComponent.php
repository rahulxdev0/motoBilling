<?php

namespace App\Livewire\Components;

use Livewire\Component;

class LoaderComponent extends Component
{
    public $isLoading = true;

    protected $listeners = [
        'showLoader' => 'showLoader',
        'hideLoader' => 'hideLoader',
    ];

    public function mount()
    {
        // Register hooks for page navigation events
        $this->dispatch('register-loader-hooks');
    }

    public function showLoader()
    {
        $this->isLoading = true;
    }

    public function hideLoader()
    {
        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.components.loader-component');
    }
}
