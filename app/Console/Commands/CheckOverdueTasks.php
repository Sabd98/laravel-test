<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\ActivityLog;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckOverdueTasks extends Command
{
    protected $signature = 'tasks:check-overdue';
    protected $description = 'Check and log overdue tasks';

    public function handle()
    {
        $now = Carbon::now()->toDateTimeString();

        Task::query()
            ->with('creator')
            ->where('due_date', '<', $now)
            ->whereNotIn('status', ['done', 'overdue'])
            ->chunkById(100, function ($tasks) {
                foreach ($tasks as $task) {
                    // Catat activity log
                    ActivityLog::create([
                        'user_id' => $task->created_by,
                        'action' => 'task_overdue',
                        'description' => "Task overdue: {$task->title} (ID: {$task->id})",
                        'logged_at' => now()
                    ]);

                    // Update status task
                    $task->update([
                        'status' => 'overdue',
                        'due_date' => $task->due_date // Maintain original due date
                    ]);
                }
            });

        $this->info('Overdue tasks checked: ' . now()->toDateTimeString());
    }
}
