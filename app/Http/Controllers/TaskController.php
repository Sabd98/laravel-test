<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Enums\UserRoleEnum;
use App\Models\ActivityLog;

class TaskController extends Controller
{
    public function index()
    {
        return Task::with(['assignee', 'creator'])
            ->when(User::user()->isStaff(), fn($q) => $q->where('assigned_to', User::id()))
            ->when(User::user()->isManager(), fn($q) => $q->whereHas(
                'assignee',
                fn($sq) =>
                $sq->where('role', UserRoleEnum::STAFF)
            ))
            ->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to' => [
                'required',
                Rule::exists('users', 'id')->where('role', UserRoleEnum::STAFF)
            ],
            'due_date' => 'required|date|after:today'
        ]);

        $task = Task::create([
            ...$data,
            'created_by' => User::id(),
            'status' => 'pending'
        ]);

        $request->validate([
            'role' => [
                'required',
                Rule::in(UserRoleEnum::cases()),
                function ($attr, $value, $fail) use ($request) {
                    if (
                        $value === UserRoleEnum::ADMIN &&
                        !$request->user()->isAdmin()
                    ) {
                        $fail('Hanya admin yang bisa membuat user admin');
                    }
                }
            ]
        ]);
        
        ActivityLog::logAction(
            User::user(),
            'create_task',
            "Created task: {$task->title}"
        );

        return response()->json($task, 201);
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status' => 'sometimes|in:pending,in_progress,done',
            'assigned_to' => [
                'sometimes',
                Rule::exists('users', 'id')->where('role', UserRoleEnum::STAFF)
            ],
            'due_date' => 'sometimes|date|after:today'
        ]);

        $task->update($data);

        ActivityLog::logAction(
            User::user(),
            'update_task',
            "Updated task: {$task->title}"
        );

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        ActivityLog::logAction(
            User::user(),
            'delete_task',
            "Deleted task: {$task->title}"
        );

        return response()->noContent();
    }
}