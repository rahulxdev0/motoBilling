<?php

namespace App\Livewire\Components;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Sidebar extends Component
{
    public $user;
    public $isItemsOpen = false;
    public $isSalesOpen = false;
    public $isPurchaseOpen = false;
    public $isMobileOpen = false;
    
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

     #[On('toggle-mobile-sidebar')]
    public function handleMobileToggle()
    {
        $this->toggleMobile();
    }

    public function toggleMobile()
    {
        $this->isMobileOpen = !$this->isMobileOpen;
    }

    public function closeMobile()
    {
        $this->isMobileOpen = false;
    }

    public function mount()
    {
        // Load the authenticated user data when the component is initialized
        $this->user = Auth::user();
    }

    public function logout()
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.components.sidebar');
    }
}
