<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TaskController extends Controller
{
    /**
     * Display a list of tasks with optional filters.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Task::class);

        $tasks = Task::query();

        if ($request->has('status')) {
            $tasks->where('status', $request->status);
        }

        if ($request->has('due_date')) {
            $tasks->whereDate('due_date', $request->due_date);
        }

        if ($request->has('assigned_user')) {
            $tasks->where('user_id', $request->assigned_user);
        }

        return response()->json($tasks->get());
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Task::class);

        // Validate the incoming data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'assigned_user_id' => 'nullable|exists:users,id'
        ]);

        // Create the task
        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'due_date' => $validated['due_date'],
            'user_id' => $validated['assigned_user_id'],
            'created_by' => Auth::id() // Use the logged-in user ID for created_by
        ]);

        return response()->json($task, 201);
    }

    /**
     * Display the specified task.
     */
    public function show($task)
    {
        $task = Task::find($task);

        $this->authorize('view', $task); // Ensure the user can view the task (check ownership or role)

        return response()->json($task->load('dependencies')); // Include task dependencies
    }

    /**
     * Update the specified task.
     */
    public function update(Request $request, $task)
    {
        $task = Task::find($task);

        $this->authorize('update', $task); // Only managers can update tasks

        // Validate the incoming data
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'due_date' => 'sometimes|nullable|date',
            'assigned_user_id' => 'sometimes|nullable|exists:users,id'
        ]);

        // Update the task with validated data
        $task->update($validated);

        return response()->json($task);
    }

    /**
     * Assign a user to a task.
     */
    public function assign(Request $request, $task)
    {
        $task = Task::find($task);

        // Ensure that the 'assigned_user_id' is passed in the request
        $assignedUserId = $request->input('assigned_user_id');

        // Find the user by ID
        $assignedUser = User::find($assignedUserId);

        if (!$assignedUser) {
            return response()->json([
                'error' => 'User not found.',
            ], 404);
        }

        // Assign the user to the task
        $task->user_id = $assignedUser->id;
        $task->save();

        return response()->json([
            'message' => 'Task successfully assigned.',
            'task' => $task,
        ]);
    }

    /**
     * Update only the status of a task.
     */
    public function updateStatus(Request $request, $task)
    {
        $task = Task::find($task);

        $this->authorize('updateStatus', $task); // Ensure the user can update the status

        // Validate the status update
        $validated = $request->validate([
            'status' => 'required|string|in:pending,completed,canceled'
        ]);

        // If the status is 'completed', ensure that all dependencies are completed
        if ($validated['status'] === 'completed') {
            foreach ($task->dependencies as $dependency) {
                if ($dependency->status !== 'completed') {
                    return response()->json(['message' => 'All dependencies must be completed before marking the task as completed.'], 400);
                }
            }
        }

        // Update the task's status
        $task->update(['status' => $validated['status']]);

        return response()->json($task);
    }

    /**
     * Add a dependency between tasks.
     */
    public function addDependency(Request $request, $task)
    {
        $task = Task::find($task);

        $this->authorize('update', $task); // Only managers can add dependencies

        $validated = $request->validate([
            'dependency_id' => 'required|exists:tasks,id'
        ]);

        // Ensure the task isn't already a dependency of the current task
        if ($task->dependencies->contains($validated['dependency_id'])) {
            return response()->json(['message' => 'This task is already a dependency.'], 400);
        }

        // Attach the dependency to the task
        $task->dependencies()->attach($validated['dependency_id']);

        return response()->json($task->load('dependencies'));
    }

    /**
     * Remove a dependency between tasks.
     */
    public function removeDependency(Request $request, $task)
    {
        $task = Task::find($task);

        $this->authorize('update', $task); // Only managers can remove dependencies

        $validated = $request->validate([
            'dependency_id' => 'required|exists:tasks,id'
        ]);

        // Detach the dependency from the task
        $task->dependencies()->detach($validated['dependency_id']);

        return response()->json($task->load('dependencies'));
    }
}
