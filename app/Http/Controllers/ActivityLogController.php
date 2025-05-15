<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogController extends Controller
{
    public function index()
    {
        return ActivityLog::query()
            ->when(User::user()->isManager(), function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('role', UserRoleEnum::STAFF);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate();
    }
}
