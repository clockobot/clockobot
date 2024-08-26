<?php

namespace App\Livewire\Dashboard;

use App\Models\TimeEntry;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TimeNow extends Component
{
    protected $listeners = [
        'refreshLatestWorkOnDashboard' => '$refresh',
    ];

    public function render()
    {
        return view('livewire.dashboard.time-now', [
            'latest_work' => TimeEntry::with(['client', 'work_type'])
                ->select('time_entries.*')
                ->join(DB::raw('(SELECT MAX(id) as max_id FROM time_entries WHERE end IS NOT NULL GROUP BY project_id) as sub'), function ($join) {
                    $join->on('time_entries.id', '=', 'sub.max_id');
                })
                ->orderBy('time_entries.created_at', 'desc')
                ->take(4)
                ->get(),
        ]);
    }
}
