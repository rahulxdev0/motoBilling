<?php

namespace App\Livewire\Parties;

use App\Models\Partie;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout("components.layouts.app")]
class CreatePatie extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|email|unique:parties,email')]
    public string $email = '';

    #[Validate('nullable|string|max:20')]
    public string $phone = '';

    #[Validate('nullable|string')]
    public string $address = '';

    #[Validate('nullable|string|max:255')]
    public string $contact_person = '';

    #[Validate('nullable|string|max:15')]
    public string $gstin = '';

    #[Validate('nullable|string|max:10')]
    public string $pan = '';

    #[Validate('boolean')]
    public bool $is_active = true;

    public function save()
    {
        $this->validate();

        try {
            Partie::create([
                'name' => $this->name,
                'email' => $this->email ?: null,
                'phone' => $this->phone,
                'address' => $this->address,
                'contact_person' => $this->contact_person ?: null,
                'gstin' => $this->gstin ?: null,
                'pan' => $this->pan ?: null,
                'is_active' => $this->is_active,
            ]);

            session()->flash('success', 'Party created successfully!');
            
            // Redirect to manage parties page
            return $this->redirect(route('parties.manage'), navigate: true);

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create party. Please try again.');
        }
    }

    public function cancel()
    {
        return $this->redirect(route('parties.manage'), navigate: true);
    }

    public function resetForm()
    {
        $this->reset([
            'name',
            'email', 
            'phone',
            'address',
            'contact_person',
            'gstin',
            'pan'
        ]);
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.parties.create-patie');
    }
}
