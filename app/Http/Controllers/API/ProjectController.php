<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * List all projects.
     */
    public function list(): \Illuminate\Http\JsonResponse
    {
        $projects = Project::orderBy('title', 'asc')->get();

        return response()->json(['success' => $projects], $this->httpStatusOk);
    }

    /**
     * Create a new project.
     */
    public function create_project(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = $this->validateProject($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $project = Project::create($request->only(['title', 'client_id', 'description', 'deadline', 'hour_estimate']));

        return response()->json(['success' => $project], $this->httpStatusOk);
    }

    /**
     * Get a specific project.
     */
    public function get_project($id): \Illuminate\Http\JsonResponse
    {
        $project = Project::find($id);

        if (! $project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        return response()->json(['success' => $project], $this->httpStatusOk);
    }

    /**
     * Update a specific project.
     */
    public function update_project(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $project = Project::find($id);

        if (! $project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        $validator = $this->validateProject($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $project->update($request->only(['title', 'client_id', 'description', 'deadline', 'hour_estimate']));

        return response()->json(['success' => $project], $this->httpStatusOk);
    }

    /**
     * Delete a specific project.
     */
    public function delete_project($id): \Illuminate\Http\JsonResponse
    {
        $project = Project::find($id);

        if (! $project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        $project->delete();

        return response()->json(['success' => 'Project deleted successfully'], $this->httpStatusOk);
    }

    /**
     * Search projects.
     *
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function search_project(): \Illuminate\Http\JsonResponse
    {
        $query = request()->input('query');

        $projects = Project::when($query, function ($q) use ($query) {
            $q->where('title', 'like', '%'.$query.'%')
                ->orWhere('description', 'like', '%'.$query.'%')
                ->orWhereHas('client', function ($query) {
                    $query->where('name', 'like', '%'.request('query').'%');
                });
        })->orderBy('title', 'asc')->get();

        return response()->json(['success' => $projects], $this->httpStatusOk);
    }

    /**
     * Validate project data.
     */
    private function validateProject(Request $request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'title' => 'required|string|max:120',
            'client_id' => 'required|integer',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'hour_estimate' => 'nullable|numeric',
        ]);
    }
}
