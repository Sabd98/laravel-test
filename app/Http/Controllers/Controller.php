<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function store(Request $request)
    {
        $request->merge(['created_by' => auth()->id()]);

        if (auth()->user()->isManager()) {
            $staff = User::findOrFail($request->assigned_to);
            abort_if(!$staff->isStaff(), 403, 'Hanya boleh assign ke staff');
        }

        if (auth()->user()->isStaff()) {
            $request->merge(['assigned_to' => auth()->id()]);
        }

        return Task::create($request->validate([
            'title' => 'required',
            'description' => 'required',
            'assigned_to' => 'required|exists:users,id',
            'due_date' => 'required|date',
        ]));
    }
}
