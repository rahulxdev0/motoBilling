<?php

namespace App\Livewire\Setting;

use App\Models\Company;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
class Settings extends Component
{
    use WithFileUploads;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('nullable|string')]
    public $address = '';

    #[Validate('nullable|string|max:100')]
    public $city = '';

    #[Validate('nullable|string|max:100')]
    public $state = '';

    #[Validate('nullable|string|max:10')]
    public $pincode = '';

    #[Validate('nullable|string|max:100')]
    public $country = 'India';

    #[Validate('nullable|string|max:20')]
    public $phone = '';

    #[Validate('nullable|email|max:255')]
    public $email = '';

    #[Validate('nullable|url|max:255')]
    public $website = '';

    #[Validate('nullable|string|max:15')]
    public $gstin = '';

    #[Validate('nullable|string|max:10')]
    public $pan = '';

    #[Validate('nullable|image|max:2048')]
    public $logo;

    #[Validate('required|string|max:10')]
    public $currency = 'INR';

    #[Validate('required|string|max:10')]
    public $currency_symbol = 'Rs.';

    #[Validate('required|numeric|min:0|max:100')]
    public $tax_percentage = 18.00;

    #[Validate('nullable|string')]
    public $terms_conditions = '';

    public $companyId = null;
    public $currentLogo = null;
    public $isEditing = false;

    public function mount()
    {
        $company = Company::getActive();
        
        if ($company) {
            $this->companyId = $company->id;
            $this->name = $company->name;
            $this->address = $company->address ?? '';
            $this->city = $company->city ?? '';
            $this->state = $company->state ?? '';
            $this->pincode = $company->pincode ?? '';
            $this->country = $company->country ?? 'India';
            $this->phone = $company->phone ?? '';
            $this->email = $company->email ?? '';
            $this->website = $company->website ?? '';
            $this->gstin = $company->gstin ?? '';
            $this->pan = $company->pan ?? '';
            $this->currentLogo = $company->logo;
            $this->currency = $company->currency ?? 'INR';
            $this->currency_symbol = $company->currency_symbol ?? 'Rs.';
            $this->tax_percentage = $company->tax_percentage ?? 18.00;
            $this->terms_conditions = $company->terms_conditions ?? '';
            $this->isEditing = true;
        }
    }

    public function save()
    {
        $this->validate();

        // Initialize logo path with current logo
        $logoPath = $this->currentLogo;

        // Handle new logo upload
        if ($this->logo) {
            // Delete old logo if exists
            if ($this->currentLogo && \Storage::disk('public')->exists($this->currentLogo)) {
                \Storage::disk('public')->delete($this->currentLogo);
            }
            
            // Store new logo
            $logoPath = $this->logo->store('company-logos', 'public');
        }

        $companyData = [
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'pincode' => $this->pincode,
            'country' => $this->country,
            'phone' => $this->phone,
            'email' => $this->email,
            'website' => $this->website,
            'gstin' => $this->gstin,
            'pan' => $this->pan,
            'logo' => $logoPath,
            'currency' => $this->currency,
            'currency_symbol' => $this->currency_symbol,
            'tax_percentage' => $this->tax_percentage,
            'terms_conditions' => $this->terms_conditions,
            'is_active' => true,
        ];

        if ($this->companyId) {
            Company::where('id', $this->companyId)->update($companyData);
            session()->flash('success', 'Company details updated successfully!');
        } else {
            // Deactivate all other companies
            Company::where('is_active', true)->update(['is_active' => false]);
            
            $company = Company::create($companyData);
            $this->companyId = $company->id;
            $this->isEditing = true;
            session()->flash('success', 'Company details created successfully!');
        }

        // Update current logo and reset logo upload field
        $this->currentLogo = $logoPath;
        $this->logo = null;
    }

    public function removeLogo()
    {
        if ($this->currentLogo && \Storage::disk('public')->exists($this->currentLogo)) {
            \Storage::disk('public')->delete($this->currentLogo);
            
            if ($this->companyId) {
                Company::where('id', $this->companyId)->update(['logo' => null]);
            }
            
            $this->currentLogo = null;
            session()->flash('success', 'Logo removed successfully!');
        }
    }

    public function render()
    {
        return view('livewire.setting.settings');
    }
}
