<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'assigned_to',
        'created_by'
    ];

    protected $casts = [
        'status' => TaskStatusEnum::class,
        'due_date' => 'date:Y-m-d',
    ];

    // Relationships
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', TaskStatusEnum::PENDING);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now());
    }
}
