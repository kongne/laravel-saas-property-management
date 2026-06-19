<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log($user, string $action, string $description = null): self
    {
        return static::create([
            'user_id' => $user->id ?? null,
            'action' => $action,
            'description' => $description ?? $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function scopeRecent($query)
    {
        return $query->latest()->limit(50);
    }
}
