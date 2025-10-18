<?php

namespace App\Services;

use App\Models\TimeEntry;

class TimeEntryService
{
    /**
     * Get total hours for time entries within a date range.
     *
     * This method calculates the total hours by summing up the duration
     * of all completed time entries (whereNotNull('end')) within the
     * specified date range.
     */
    public function getTotalHours($startDate, $endDate): float
    {
        return TimeEntry::whereBetween('start', [$startDate, $endDate])
            ->whereNotNull('end')
            ->get()
            ->sum(fn ($timeEntry) => $timeEntry->start->diffInMinutes($timeEntry->end)) / 60;
    }
}