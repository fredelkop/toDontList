<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'active',
        'avoid_until',
        'points',
        'duration_minutes'
    ];

    protected $dates = ['avoid_until'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pointLogs()
    {
        return $this->hasMany(PointLog::class);
    }

    public function excuses()
    {
        return $this->hasMany(Excuse::class);
    }
}
