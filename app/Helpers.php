<?php

use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;

if (! function_exists('process_hours_total')) {
    function process_hours_total($entries)
    {
        $totalMinutes = 0;

        foreach ($entries as $time_entry) {
            [$hours, $minutes] = explode(':', $time_entry->calculateDurationInHours());
            $totalMinutes += $hours * 60 + $minutes;
        }

        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        return $hours.':'.str_pad($minutes, 2, '0', STR_PAD_LEFT);
    }
}

if (! function_exists('export_time_entries')) {
    function export_time_entries($entries)
    {
        return Excel::download(new ReportExport($entries), 'report.xlsx');
    }
}
