<?php

namespace App\Livewire\Dashboard;

use App\Services\TimeEntryService;
use Livewire\Component;

class HoursCount extends Component
{
    protected $listeners = [
        'refreshHoursCountOnDashboard' => '$refresh',
    ];

    public function render()
    {
        $service = new TimeEntryService();

        $monthlyCount = $service->getTotalHours(now()->subMonth(), now());
        $weeklyCount = $service->getTotalHours(now()->subWeek(), now());
        $dailyCount = $service->getTotalHours(now()->startOfDay(), now());

        return view('livewire.dashboard.hours-count', [
            'monthly_count' => $monthlyCount,
            'weekly_count' => $weeklyCount,
            'daily_count' => $dailyCount,
        ]);
    }
}
