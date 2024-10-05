<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    // Apply authentication middleware
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Create Project
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planned,in_progress,completed,on_hold',
        ]);

        $project = Project::create($request->all());

        return response()->json(['message' => 'Project created successfully', 'project' => $project], 201);
    }

    // Read a single project
    public function show($id)
    {
        $project = Project::find($id);
        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }
        return response()->json($project);
    }

    // Read all projects with filtering
    public function index(Request $request)
    {
        $query = Project::query();

        // Apply filters with AND operation
        if ($request->has('name')) {
            $query->where('name', $request->name);
        }

        if ($request->has('department')) {
            $query->where('department', $request->department);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Add more filters as needed

        $projects = $query->get();

        return response()->json($projects);
    }

    // Update Project
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:projects,id',
            'name' => 'sometimes|string|max:255',
            'department' => 'sometimes|string|max:255',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'status' => 'sometimes|in:planned,in_progress,completed,on_hold',
        ]);

        $project = Project::find($request->id);
        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $project->update($request->all());

        return response()->json(['message' => 'Project updated successfully', 'project' => $project]);
    }

    // Delete Project
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:projects,id',
        ]);

        $project = Project::find($request->id);
        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }
}
