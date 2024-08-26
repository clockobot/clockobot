<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProjectsController extends Controller
{
    public function index(): View
    {
        return view('projects.index');
    }

    public function show($id): View
    {
        $project = Project::findOrFail($id);

        return view('projects.show', ['project' => $project]);
    }

    public function export($id): binaryFileResponse
    {
        $project = Project::findOrFail($id);

        return export_time_entries($project->time_entries);
    }
}
