<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Apply authentication middleware
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Create User (Handled by AuthController's register)
    // However, if you need an admin to create users, implement here

    // Read a single user
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    // Read all users with filtering
    public function index(Request $request)
    {
        $query = User::query();

        // Apply filters with AND operation
        if ($request->has('first_name')) {
            $query->where('first_name', $request->first_name);
        }

        if ($request->has('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->has('date_of_birth')) {
            $query->whereDate('date_of_birth', $request->date_of_birth);
        }

        // Add more filters as needed

        $users = $query->get();

        return response()->json($users);
    }

    // Update User
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'date_of_birth' => 'sometimes|date',
            'gender' => 'sometimes|in:male,female,other',
            'email' => 'sometimes|email|unique:users,email,' . $request->id,
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        $user = User::find($request->id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->update($request->only(['first_name', 'last_name', 'date_of_birth', 'gender', 'email']));

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    // Delete User
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
        ]);

        $user = User::find($request->id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    // Assign a project to a user
    public function assignProject(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
        ]);

        $user = User::find($request->user_id);
        $project = \App\Models\Project::find($request->project_id);

        $user->projects()->attach($project);

        return response()->json(['message' => 'Project assigned to user successfully']);
    }

    // Remove a project from a user
    public function removeProject(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
        ]);

        $user = User::find($request->user_id);
        $project = \App\Models\Project::find($request->project_id);

        $user->projects()->detach($project);

        return response()->json(['message' => 'Project removed from user successfully']);
    }

}
