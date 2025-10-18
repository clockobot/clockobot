<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TimeEntry;
use App\Services\TimeEntryService;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected const LATEST_WORK_LIMIT = 8;

    protected const LATEST_ACTIVITIES_LIMIT = 5;

    public function get_hours_counts(): \Illuminate\Http\JsonResponse
    {
        $service = new TimeEntryService();

        $monthly_count = $service->getTotalHours(now()->subMonth(), now());
        $weekly_count = $service->getTotalHours(now()->subWeek(), now());
        $daily_count = $service->getTotalHours(now()->startOfDay(), now());

        $data = [
            'monthly_count' => $monthly_count,
            'weekly_count' => $weekly_count,
            'daily_count' => $daily_count,
        ];

        return response()->json(['success' => $data], $this->httpStatusOk);
    }

    public function get_latest_work(): \Illuminate\Http\JsonResponse
    {
        $latest_work = TimeEntry::with(['client', 'work_type'])
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('time_entries')
                    ->whereNotNull('end')
                    ->groupBy('project_id');
            })
            ->orderBy('created_at', 'desc')
            ->take(self::LATEST_WORK_LIMIT)
            ->get();

        return response()->json(['success' => $latest_work], $this->httpStatusOk);
    }

    public function get_latest_activities(): \Illuminate\Http\JsonResponse
    {
        $latest_activities = TimeEntry::with(['client', 'project', 'work_type', 'user'])
            ->whereNotNull('end')
            ->latest('id')
            ->take(self::LATEST_ACTIVITIES_LIMIT)
            ->get();

        return response()->json(['success' => $latest_activities], $this->httpStatusOk);
    }
}
