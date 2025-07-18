<?php

namespace App\Livewire\Parties;

use App\Models\Partie;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout("components.layouts.app")]
class EditParty extends Component
{
    public $party;
    public $name;
    public $phone;
    public $email;
    public $gstin;
    public $pan;
    public $is_active;

    public function mount(Partie $party)
    {
        $this->party = $party;
        $this->name = $party->name;
        $this->phone = $party->phone;
        $this->email = $party->email;
        $this->gstin = $party->gstin;
        $this->pan = $party->pan;
        $this->is_active = $party->is_active;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'gstin' => 'nullable|string|max:20',
            'pan' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $this->party->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'gstin' => $this->gstin,
            'pan' => $this->pan,
            'is_active' => $this->is_active,
        ]);

        session()->flash('success', 'Party updated successfully!');
        return redirect()->route('parties.manage');
    }

    public function render()
    {
        return view('livewire.parties.edit-party');
    }
}
