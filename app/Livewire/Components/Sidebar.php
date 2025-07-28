<?php

namespace App\Livewire\Components;

use App\Models\Company;
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

    public $company;
    
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

    // Helper method to check if current route matches
    public function isActive($routeName)
    {
        return request()->routeIs($routeName);
    }

    // Helper method to check if any of the given routes match
    public function isActiveAny($routeNames)
    {
        return request()->routeIs($routeNames);
    }

    // Helper method to get active link classes
    public function getActiveLinkClasses($routeName, $isSubmenu = false)
    {
        if ($this->isActive($routeName)) {
            if ($isSubmenu) {
                return 'flex items-center px-4 py-2 text-sm text-teal-700 bg-teal-100 rounded-lg hover:bg-teal-200 hover:text-teal-800 transition-colors border-l-2 border-teal-500';
            } else {
                return 'flex items-center px-4 py-3 text-gray-700 bg-teal-50 border-r-4 border-teal-500 rounded-l-lg hover:bg-teal-100 transition-colors duration-200';
            }
        } else {
            if ($isSubmenu) {
                return 'flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition-colors border-l-2 border-teal-200';
            } else {
                return 'flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition-colors duration-200';
            }
        }
    }

    // Helper method to get icon classes
    public function getIconClasses($routeName)
    {
        return $this->isActive($routeName) ? 'w-5 h-5 mr-3 text-teal-500' : 'w-5 h-5 mr-3';
    }

    // Helper method for submenu icon classes
    public function getSubmenuIconClasses($routeName)
    {
        return $this->isActive($routeName) ? 'w-4 h-4 mr-3 text-teal-500' : 'w-4 h-4 mr-3';
    }

    public function mount()
    {
        $this->user = Auth::user();
        $this->company = Company::getActive();

        
        // Auto-open expandable menus if any of their sub-routes are active
        if ($this->isActiveAny(['items.manage', 'items.create', 'items.edit'])) {
            $this->isItemsOpen = true;
        }
        
        if ($this->isActiveAny(['invoice.manage', 'invoice.create'])) {
            $this->isSalesOpen = true;
        }
        
        if ($this->isActiveAny(['invoice.purchase', 'invoice.purchase.create'])) {
            $this->isPurchaseOpen = true;
        }
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
