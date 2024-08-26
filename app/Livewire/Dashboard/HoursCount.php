<?php

namespace App\Livewire\Dashboard;

use App\Models\TimeEntry;
use Livewire\Component;

class HoursCount extends Component
{
    protected $listeners = [
        'refreshHoursCountOnDashboard' => '$refresh',
    ];

    private function getTimeEntriesCount($startDate, $endDate): mixed
    {
        return TimeEntry::whereBetween('start', [$startDate, $endDate])
            ->whereNotNull('end')
            ->get()
            ->sum(function ($timeEntry) {
                return $timeEntry->calculateDurationInDecimal();
            });

    }

    public function render()
    {
        $monthlyCount = $this->getTimeEntriesCount(now()->subMonth(), now());
        $weeklyCount = $this->getTimeEntriesCount(now()->subWeek(), now());
        $dailyCount = $this->getTimeEntriesCount(now()->startOfDay(), now());

        return view('livewire.dashboard.hours-count', [
            'monthly_count' => $monthlyCount,
            'weekly_count' => $weeklyCount,
            'daily_count' => $dailyCount,
        ]);
    }
}
