<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TimeEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TimeEntryController extends Controller
{
    /**
     * List all time entries with necessary conditions.
     */
    public function list(): \Illuminate\Http\JsonResponse
    {
        $time_entries = TimeEntry::whereNotNull(['client_id', 'project_id', 'work_type_id', 'end'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->each(function ($time_entry) {
                $time_entry->hourly_duration = $time_entry->calculateDurationInHours();
            });

        return response()->json(['success' => $time_entries], $this->httpStatusOk);
    }

    /**
     * Initialize a new time entry.
     */
    public function init_time_entry(Request $request): \Illuminate\Http\JsonResponse
    {
        $time_entry_check = TimeEntry::where('user_id', $request->user_id)
            ->whereNull('end')
            ->get();

        if ($time_entry_check->count() === 0) {
            $validator = $this->validateTimeEntry($request, [
                'start' => 'required',
                'user_id' => 'required|integer',
                'client_id' => 'nullable|integer',
                'project_id' => 'nullable|integer',
                'work_type_id' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422); // 422 Unprocessable Entity
            }

            $time_entry = TimeEntry::create($request->only(['start', 'user_id', 'client_id', 'project_id', 'work_type_id']));
        } else {
            $time_entry = $time_entry_check;
        }

        return response()->json(['success' => $time_entry], $this->httpStatusOk);
    }

    /**
     * Create a new time entry.
     */
    public function create_time_entry(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = $this->validateTimeEntry($request, [
            'start' => 'required',
            'end' => 'required',
            'user_id' => 'required|integer',
            'client_id' => 'required|integer',
            'project_id' => 'required|integer',
            'work_type_id' => 'required|integer',
            'description' => 'nullable',
            'link' => 'nullable',
            'billable' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422); // 422 Unprocessable Entity
        }

        $time_entry = TimeEntry::create($request->only(['start', 'end', 'user_id', 'client_id', 'project_id', 'work_type_id', 'description', 'link', 'billable']));

        return response()->json(['success' => $time_entry], $this->httpStatusOk);
    }

    /**
     * Get a specific time entry.
     */
    public function get_time_entry($id): \Illuminate\Http\JsonResponse
    {
        $time_entry = TimeEntry::find($id);

        if (! $time_entry) {
            return response()->json(['error' => 'Time entry not found'], 404);
        }

        return response()->json(['success' => $time_entry], $this->httpStatusOk);
    }

    /**
     * Update a specific time entry.
     */
    public function update_time_entry(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $time_entry = TimeEntry::find($id);

        if (! $time_entry) {
            return response()->json(['error' => 'Time entry not found'], 404);
        }

        $validator = $this->validateTimeEntry($request, [
            'start' => 'required',
            'end' => 'required',
            'client_id' => 'required|integer',
            'project_id' => 'required|integer',
            'work_type_id' => 'required|integer',
            'description' => 'nullable',
            'link' => 'nullable',
            'billable' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422); // 422 Unprocessable Entity
        }

        $time_entry->update($request->only(['start', 'end', 'client_id', 'project_id', 'user_id', 'work_type_id', 'description', 'link', 'billable']));

        return response()->json(['success' => $time_entry], $this->httpStatusOk);
    }

    /**
     * Delete a specific time entry.
     */
    public function delete_time_entry($id): \Illuminate\Http\JsonResponse
    {
        $time_entry = TimeEntry::find($id);

        if (! $time_entry) {
            return response()->json(['error' => 'Time entry not found'], 404);
        }

        $time_entry->delete();

        return response()->json(['success' => 'Time entry deleted successfully'], $this->httpStatusOk);
    }

    /**
     * Get the duration of a time entry in decimal format.
     */
    public function get_time_entry_decimal_duration($id): \Illuminate\Http\JsonResponse
    {
        $time_entry = TimeEntry::find($id);

        if (! $time_entry) {
            return response()->json(['error' => 'Time entry not found'], 404);
        }

        return response()->json(['success' => $time_entry->calculateDurationInDecimal()], $this->httpStatusOk);
    }

    /**
     * Get the duration of a time entry in hours format.
     */
    public function get_time_entry_duration_in_hours($id): \Illuminate\Http\JsonResponse
    {
        $time_entry = TimeEntry::find($id);

        if (! $time_entry) {
            return response()->json(['error' => 'Time entry not found'], 404);
        }

        return response()->json(['success' => $time_entry->calculateDurationInHours()], $this->httpStatusOk);
    }

    /**
     * Get ongoing time entries for a specific user.
     */
    public function get_ongoing_time_entry_for_user($user_id): \Illuminate\Http\JsonResponse
    {
        $time_entry = TimeEntry::where('user_id', $user_id)
            ->whereNull('end')
            ->first();

        return response()->json(['success' => $time_entry ?? []], $this->httpStatusOk);
    }

    /**
     * Validate time entry data.
     */
    private function validateTimeEntry(Request $request, array $rules): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), $rules);
    }
}
