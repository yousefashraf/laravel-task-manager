<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('manager');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('task.view', 'api') && ($user->hasRole('manager', 'api') || $user->id === $task->assigned_to);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('task.create', 'api');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('task.update', 'api');
    }

    /**
     * Determine whether the user can assign the model.
     */
    public function assign(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('task.assign', 'api');
    }

    /**
     * Determine whether the user can update the model status.
     */
    public function updateStatus(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('task.status.update', 'api');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return false;
    }
}
