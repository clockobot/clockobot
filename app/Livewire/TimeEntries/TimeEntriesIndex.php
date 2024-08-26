<?php

namespace App\Livewire\TimeEntries;

use App\Models\TimeEntry;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class TimeEntriesIndex extends Component
{
    use WithPagination;

    public string $query = '';

    protected $listeners = [
        'refreshTimeEntriesList' => '$refresh',
    ];

    #[Computed]
    public function time_entries()
    {
        return TimeEntry::whereNotNull('client_id')->whereNotNull('project_id')->whereNotNull('end')->whereNotNull('work_type_id')->orderBy('created_at', 'desc')->paginate(15);
    }

    public function render()
    {
        return view('livewire.time-entries.time-entries-index');
    }
}
