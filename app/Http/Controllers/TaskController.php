<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

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
            $tasks->where('assigned_user_id', $request->assigned_user);
        }

        return response()->json($tasks->get());
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Task::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'assigned_user_id' => 'nullable|exists:users,id'
        ]);

        $task = Task::create($validated);

        return response()->json($task, 201);
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return response()->json($task);
    }

    /**
     * Update the specified task.
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'due_date' => 'sometimes|nullable|date',
            'assigned_user_id' => 'sometimes|nullable|exists:users,id'
        ]);

        $task->update($validated);

        return response()->json($task);
    }

    /**
     * Update only the status of a task.
     */
    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('updateStatus', $task);

        $validated = $request->validate([
            'status' => 'required|string|in:pending,completed,canceled'
        ]);

        $task->update(['status' => $validated['status']]);

        return response()->json($task);
    }
}
