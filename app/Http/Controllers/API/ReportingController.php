<?php

namespace App\Http\Controllers\API;

use App\Exports\ReportExport;
use App\Http\Controllers\Controller;
use App\Models\TimeEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportingController extends Controller
{
    /**
     * Reporting API: List time entries for the past week.
     */
    public function list(): \Illuminate\Http\JsonResponse
    {
        $startOfWeek = Carbon::now()->subWeek()->startOfDay();
        $endOfToday = Carbon::now()->endOfDay();

        $time_entries = TimeEntry::whereNotNull('client_id')
            ->whereBetween('start', [$startOfWeek, $endOfToday])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['success' => $time_entries], $this->httpStatusOk);
    }

    /**
     * Get filtered results based on provided criteria.
     */
    public function get_filtered_results(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = $this->validateFilters($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422); // 422 Unprocessable Entity
        }

        $query = TimeEntry::query();

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled(['start_date', 'start_time'])) {
            $compiled_start_date = Carbon::createFromFormat('Y-m-d H:i', $request->start_date.' '.$request->start_time)->format('Y-m-d H:i:s');
            $query->where('start', '>=', $compiled_start_date);
        }

        if ($request->filled(['end_date', 'end_time'])) {
            $compiled_end_date = Carbon::createFromFormat('Y-m-d H:i', $request->end_date.' '.$request->end_time)->format('Y-m-d H:i:s');
            $query->where('end', '<=', $compiled_end_date);
        }

        $time_entries = $query->orderBy('start', 'desc')->get()->each(function ($time_entry) {
            $time_entry->hourly_duration = $time_entry->calculateDurationInHours();
        });

        return response()->json(['success' => $time_entries], $this->httpStatusOk);
    }

    /**
     * Generate and export the report.
     *
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function get_export(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->has('data')) {
            $export = new ReportExport(json_decode($request->data));

            $fileName = 'report-'.now()->format('Y-m-d_H-i-s').'.xlsx';
            $filePath = 'exports/'.$fileName;
            $export->store($filePath, 'local');

            $url = url('storage/'.$filePath);

            return response()->json(['success' => $url], $this->httpStatusOk);
        }
    }

    /**
     * Validate the filter inputs.
     */
    private function validateFilters(Request $request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'client_id' => 'nullable|integer',
            'project_id' => 'nullable|integer',
            'user_id' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_date' => 'nullable|date',
            'end_time' => 'nullable|date_format:H:i',
        ]);
    }
}
