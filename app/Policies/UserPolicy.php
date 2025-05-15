<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user)
    {
        return $user->isAdmin() || $user->isManager();
    }

    public function create(User $user)
    {
        return $user->isAdmin();
    }

    public function view(User $user, User $model)
    {
        return $user->isAdmin() ||
            ($user->isManager() && $model->isStaff());
    }
}
