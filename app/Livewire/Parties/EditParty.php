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
    public $editing = false;
    public $contact_person = '';
    public $address = '';

    public function mount(Partie $party)
    {
        $this->party = $party;
        $this->resetForm();
        $this->editing = false;
    }

    public function resetForm()
    {
        $this->name = $this->party->name;
        $this->phone = $this->party->phone;
        $this->email = $this->party->email;
        $this->gstin = $this->party->gstin;
        $this->pan = $this->party->pan;
        $this->contact_person = $this->party->contact_person ?? '';
        $this->address = $this->party->address ?? '';
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'gstin' => 'nullable|string|max:20',
            'pan' => 'nullable|string|max:20',
            'is_active' => 'boolean|nullable',
        ]);

        if($this->is_active === null) {
            $this->is_active = false; // Default to active if not set
        }   

        $this->party->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'gstin' => $this->gstin,
            'pan' => $this->pan,
            'is_active' => $this->is_active,
            'contact_person' => $this->contact_person,
            'address' => $this->address,
        ]);

        session()->flash('success', 'Party updated successfully!');
        $this->editing = false;
        return redirect()->route('parties.manage');
    }

    public function cancelEdit()
    {
        $this->resetForm();
        $this->editing = false;
    }

    public function toggleStatus($partyId)
    {
        $party = $this->party;
        if ($party && $party->id == $partyId) {
            $party->is_active = !$party->is_active;
            $party->save();
            $this->is_active = $party->is_active;
            session()->flash('success', 'Party status updated successfully!');
            // Optionally, you can refresh the party property if needed
            $this->party->refresh();
        }
    }

    public function deleteParty($partyId)
    {
        $party = $this->party;
        if ($party && $party->id == $partyId) {
            $party->delete();
            session()->flash('success', 'Party deleted successfully!');
            return redirect()->route('parties.manage');
        }
    }

    public function render()
    {
        return view('livewire.parties.edit-party');
    }
}
