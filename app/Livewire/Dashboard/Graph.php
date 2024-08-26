<?php

namespace App\Livewire\Dashboard;

use App\Models\TimeEntry;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Graph extends Component
{
    protected $listeners = [
        'refreshGraphOnDashboard' => '$refresh',
    ];

    #[Computed]
    public function time_entries()
    {
        $entries = TimeEntry::whereBetween('start', [now()->subDays(15), now()])
            ->whereNotNull('end')
            ->orderBy('start')
            ->get();

        $groupedEntries = $entries->groupBy(function ($timeEntry) {
            return Carbon::parse($timeEntry->start)->format('d.m');
        })->map(function ($dayEntries) {
            return $dayEntries->groupBy('user_id')->map(function ($userEntries) {
                return [
                    'user' => $userEntries->first()->user->name,
                    'total_duration' => round($userEntries->sum->calculateDurationInDecimal(), 2),
                ];
            });
        });

        return $groupedEntries;
    }

    public function render()
    {
        $labels = [];

        $startDate = Carbon::now()->subDays(14);
        $endDate = Carbon::now(); // End date is today

        $period = CarbonPeriod::create($startDate, $endDate);

        $labels = collect($period)->map(function ($date) {
            return $date->format('d.m');
        });

        return view('livewire.dashboard.graph', [
            'labels' => $labels,
            'entries' => $this->time_entries(),
        ]);
    }
}
