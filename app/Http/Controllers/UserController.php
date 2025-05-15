<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
public function index()
{
return User::when(User::user()->isManager(), fn($q) =>
$q->where('role', UserRoleEnum::STAFF)
)->get();
}

public function store(Request $request)
{
$data = $request->validate([
'name' => 'required|string|max:255',
'email' => 'required|email|unique:users',
'password' => 'required|min:8',
'role' => ['required', Rule::in(UserRoleEnum::cases())]
]);

$user = User::create([
...$data,
'password' => bcrypt($data['password'])
]);

return response()->json($user, 201);
}
}