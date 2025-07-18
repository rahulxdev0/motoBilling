<?php

namespace App\Livewire\Parties;

use App\Models\Partie;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout("components.layouts.app")]
class ManageParties extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'status')]
    public string $statusFilter = 'all';

    #[Url(as: 'sort')]
    public string $sortBy = 'name';

    #[Url(as: 'direction')]
    public string $sortDirection = 'asc';

    public int $perPage = 10;

    protected $paginationTheme = 'tailwind';

    // Add this property to allow referencing $this->id in the Blade file
    public $componentId;

    public function mount()
    {
        $this->componentId = $this->getId();
    }

    public function getId()
    {
        // Livewire automatically assigns an id property to each component instance
        return $this->id ?? $this->getName();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'statusFilter']);
        $this->resetPage();
    }

    public function handleCreateParty(): void
    {
        // Dispatch event to open create party modal
        $this->dispatch('open-create-party-modal');
    }

    public function editParty(int $partyId): void
    {
        // Dispatch event to open edit party modal
        $this->dispatch('open-edit-party-modal', partyId: $partyId);
    }

    public function toggleStatus(int $partyId): void
    {
        $party = Partie::findOrFail($partyId);
        $party->update(['is_active' => !$party->is_active]);
        
        session()->flash('success', 'Party status updated successfully!');
        
        $this->dispatch('party-updated', [
            'message' => 'Party status updated successfully!'
        ]);
    }

    public function deleteParty(int $partyId): void
    {
        Partie::findOrFail($partyId)->delete();
        
        session()->flash('success', 'Party deleted successfully!');
        
        $this->dispatch('party-deleted', [
            'message' => 'Party deleted successfully!'
        ]);
    }

    public function exportPdf(): void
    {
        // Handle PDF export functionality
        $this->dispatch('export-parties-pdf');
    }

    public function getPartiesProperty()
    {
        return Partie::query()
            ->when($this->search, fn($query) => $query->search($this->search))
            ->when($this->statusFilter === 'active', fn($query) => $query->where('is_active', true))
            ->when($this->statusFilter === 'inactive', fn($query) => $query->where('is_active', false))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getStatsProperty()
    {
        return [
            'total' => Partie::count(),
            'active' => Partie::where('is_active', true)->count(),
            'inactive' => Partie::where('is_active', false)->count(),
            'gst_registered' => Partie::whereNotNull('gstin')->count(),
        ];
    }

    public function render()
    {
        return view('livewire.parties.manage-parties', [
            'parties' => $this->parties,
            'stats' => $this->stats,
        ]);
    }
}
