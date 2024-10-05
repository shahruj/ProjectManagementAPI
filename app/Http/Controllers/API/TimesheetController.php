<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timesheet;

class TimesheetController extends Controller
{
    // Apply authentication middleware
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Create Timesheet
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
            'task_name' => 'required|string|max:255',
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0',
        ]);

        // Optional: Ensure the user is assigned to the project
        $user = \App\Models\User::find($request->user_id);
        if (!$user->projects->contains($request->project_id)) {
            return response()->json(['message' => 'User is not assigned to this project'], 400);
        }

        $timesheet = Timesheet::create($request->all());

        return response()->json(['message' => 'Timesheet created successfully', 'timesheet' => $timesheet], 201);
    }

    // Read a single timesheet
    public function show($id)
    {
        $timesheet = Timesheet::find($id);
        if (!$timesheet) {
            return response()->json(['message' => 'Timesheet not found'], 404);
        }
        return response()->json($timesheet);
    }

    // Read all timesheets with filtering
    public function index(Request $request)
    {
        $query = Timesheet::query();

        // Apply filters with AND operation
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }

        // Add more filters as needed

        $timesheets = $query->get();

        return response()->json($timesheets);
    }

    // Update Timesheet
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:timesheets,id',
            'user_id' => 'sometimes|exists:users,id',
            'project_id' => 'sometimes|exists:projects,id',
            'task_name' => 'sometimes|string|max:255',
            'date' => 'sometimes|date',
            'hours' => 'sometimes|numeric|min:0',
        ]);

        $timesheet = Timesheet::find($request->id);
        if (!$timesheet) {
            return response()->json(['message' => 'Timesheet not found'], 404);
        }

        // Optional: Ensure the user is assigned to the project if user_id or project_id is being updated
        if ($request->has('user_id') || $request->has('project_id')) {
            $user_id = $request->has('user_id') ? $request->user_id : $timesheet->user_id;
            $project_id = $request->has('project_id') ? $request->project_id : $timesheet->project_id;

            $user = \App\Models\User::find($user_id);
            if (!$user->projects->contains($project_id)) {
                return response()->json(['message' => 'User is not assigned to this project'], 400);
            }
        }

        $timesheet->update($request->all());

        return response()->json(['message' => 'Timesheet updated successfully', 'timesheet' => $timesheet]);
    }

    // Delete Timesheet
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:timesheets,id',
        ]);

        $timesheet = Timesheet::find($request->id);
        if (!$timesheet) {
            return response()->json(['message' => 'Timesheet not found'], 404);
        }

        $timesheet->delete();

        return response()->json(['message' => 'Timesheet deleted successfully']);
    }
}
