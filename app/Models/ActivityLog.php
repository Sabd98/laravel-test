<?php
// app/Models/ActivityLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
use HasFactory;

protected $keyType = 'string';
public $incrementing = false;

protected $fillable = [
'user_id',
'action',
'description',
'logged_at'
];

public function user()
{
return $this->belongsTo(User::class);
}
}