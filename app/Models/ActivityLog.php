<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'user_id',
        'action',
        'description'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helpers
    public static function logAction(User $user, string $action, string $description): void
    {
        self::create([
            'user_id' => $user->id,
            'action' => $action,
            'description' => $description
        ]);
    }
}
