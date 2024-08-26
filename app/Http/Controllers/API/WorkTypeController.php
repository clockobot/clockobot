<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\WorkType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkTypeController extends Controller
{
    /**
     * List all work types.
     */
    public function list(): \Illuminate\Http\JsonResponse
    {
        $work_types = WorkType::orderBy('title', 'asc')->get();

        return response()->json(['success' => $work_types], $this->httpStatusOk);
    }

    /**
     * Create a new work type.
     */
    public function create_work_type(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = $this->validateWorkType($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $work_type = WorkType::create(['title' => $request->title]);

        return response()->json(['success' => $work_type], $this->httpStatusOk);
    }

    /**
     * Get a specific work type.
     */
    public function get_work_type($id): \Illuminate\Http\JsonResponse
    {
        $work_type = WorkType::find($id);

        if (! $work_type) {
            return response()->json(['error' => 'Work type not found'], 404);
        }

        return response()->json(['success' => $work_type], $this->httpStatusOk);
    }

    /**
     * Update a specific work type.
     */
    public function update_work_type(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $work_type = WorkType::find($id);

        if (! $work_type) {
            return response()->json(['error' => 'Work type not found'], 404);
        }

        $validator = $this->validateWorkType($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $work_type->update(['title' => $request->title]);

        return response()->json(['success' => $work_type], $this->httpStatusOk);
    }

    /**
     * Delete a specific work type.
     */
    public function delete_work_type($id): \Illuminate\Http\JsonResponse
    {
        $work_type = WorkType::find($id);

        if (! $work_type) {
            return response()->json(['error' => 'Work type not found'], 404);
        }

        $work_type->delete();

        return response()->json(['success' => 'Work type deleted successfully'], $this->httpStatusOk);
    }

    /**
     * Search work types.
     */
    public function search_work_type(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = $request->query('query', '');

        $work_types = WorkType::where('title', 'like', '%'.$query.'%')
            ->orderBy('title', 'asc')
            ->get();

        return response()->json(['success' => $work_types], $this->httpStatusOk);
    }

    /**
     * Validate work type data.
     */
    private function validateWorkType(Request $request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'title' => 'required|string|max:255',
        ]);
    }
}
