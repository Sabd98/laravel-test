<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => UserRoleEnum::ADMIN->value,
            'status' => true
        ]);

        // Create Manager User
        User::create([
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'password' => bcrypt('password'),
            'role' => UserRoleEnum::MANAGER->value,
            'status' => true
        ]);

        // Create Staff User
        User::create([
            'name' => 'Staff',
            'email' => 'staff@example.com',
            'password' => bcrypt('password'),
            'role' => UserRoleEnum::STAFF->value,
            'status' => true
        ]);
    }
}
