<?php

namespace App\Livewire\TimeEntries;

use App\Models\TimeEntry;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class TimeEntriesIndex extends Component
{
    use WithPagination;

    protected $listeners = [
        'refreshTimeEntriesList' => '$refresh',
    ];

    #[Computed]
    public function time_entries()
    {
        return TimeEntry::with(['client', 'project', 'work_type', 'user'])
            ->whereNotNull(['client_id', 'project_id', 'end', 'work_type_id'])
            ->latest('created_at')
            ->paginate(15);
    }

    public function render()
    {
        return view('livewire.time-entries.time-entries-index');
    }
}
