<?php
public function handle()
{
Task::where('due_date', '<', now())
    ->where('status', '!=', 'done')
    ->each(function ($task) {
    ActivityLog::create([
    'user_id' => $task->created_by,
    'action' => 'task_overdue',
    'description' => "Task overdue: {$task->id}",
    'logged_at' => now(),
    ]);
    });
    }
