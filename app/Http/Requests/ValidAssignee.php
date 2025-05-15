<?php
// app/Rules/ValidAssignee.php

use App\Enums\UserRoleEnum;
use Illuminate\Contracts\Validation\Rule;

class ValidAssignee implements Rule
{
    public function passes($attribute, $value, $fail = null)
    {
        $assigner = User::user();
        $assignee = User::find($value);

        // Validasi status user
        if (!$assignee || !$assignee->status) {
            $fail('User tujuan tidak aktif');
            return false;
        }

        // Logic berdasarkan role
        return match ($assigner->role) {
            UserRoleEnum::ADMIN => true,
            UserRoleEnum::MANAGER => $assignee->isStaff(),
            UserRoleEnum::STAFF => $assignee->id === $assigner->id,
            default => false
        };
    }

    public function message()
    {
        return 'Tidak memiliki izin untuk assign task ke user ini';
    }
}

// // Penggunaan di Controller
// public function store(Request $request)
// {
//     $request->validate([
//         'assigned_to' => ['required', 'exists:users,id', new ValidAssignee]
//     ]);
// }