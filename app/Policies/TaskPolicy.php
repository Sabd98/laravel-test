<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user)
    {
        return true; // Di-filter di controller
    }

    public function create(User $user)
    {
        return true; // Validasi di controller
    }

    public function update(User $user, Task $task)
    {
        return $user->isAdmin() ||
            ($user->isManager() && $task->assignee->isStaff()) ||
            $user->id === $task->assigned_to;
    }

    public function delete(User $user, Task $task)
    {
        return $user->isAdmin() || $user->id === $task->created_by;
    }
}
