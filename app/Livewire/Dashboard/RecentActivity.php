<?php

namespace App\Livewire\Dashboard;

use App\Models\TimeEntry;
use Livewire\Component;

class RecentActivity extends Component
{
    protected $listeners = [
        'refreshLastFiveEntriesOnDashboard' => '$refresh',
    ];

    public function render()
    {
        return view('livewire.dashboard.recent-activity', [
            'last_five_entries' => TimeEntry::orderBy('id', 'desc')
                ->whereNotNull('end')
                ->take(5)
                ->get(),
        ]);
    }
}
