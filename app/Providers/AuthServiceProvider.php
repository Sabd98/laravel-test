<?php

namespace App\Providers;


// app/Providers/AuthServiceProvider.php

use App\Models\Task;
use App\Models\User;
use App\Policies\TaskPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    // app/Providers/AuthServiceProvider.php
    protected $policies = [
        User::class => UserPolicy::class,
        Task::class => TaskPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        // Define Gates
        Gate::define('view-users', function (User $user) {
            return $user->isAdmin() || $user->isManager();
        });

        Gate::define('manage-tasks', function (User $user, ?Task $task = null) {
            if ($user->isAdmin()) return true;

            if ($user->isManager()) {
                return $task
                    ? $task->assignee->isStaff()
                    : true;
            }

            return $task
                ? $user->id === $task->assigned_to
                : false;
        });

        Gate::define('assign-tasks', function (User $user, User $assignee) {
            return $user->isManager() && $assignee->isStaff();
        });

        Gate::define('view-logs', function (User $user) {
            return $user->isAdmin();
        });
    }
}
