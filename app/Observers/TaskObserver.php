<?php

// app/Observers/TaskObserver.php

use App\Models\Task;
use Illuminate\Auth\Access\AuthorizationException;

class TaskObserver
{
public function updating(Task $task)
{
$allowedTransitions = [
'pending' => ['in_progress'],
'in_progress' => ['done', 'pending'],
'done' => []
];

$originalStatus = $task->getOriginal('status');
$newStatus = $task->status;

if (!in_array($newStatus, $allowedTransitions[$originalStatus])) {
throw new InvalidStatusTransitionException(
"Transisi status tidak valid: $originalStatus → $newStatus"
);
}

// Hanya admin/manajer yang bisa mark as done
if ($newStatus === 'done' && !User::user()->isAdminOrManager()) {
throw new AuthorizationException();
}
}
}

// // Registrasi di AppServiceProvider
// public function boot()
// {
// Task::observe(TaskObserver::class);
// }