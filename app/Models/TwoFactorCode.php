<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TwoFactorCode extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isValid(): bool
    {
        return !$this->used_at && $this->expires_at && $this->expires_at->isFuture();
    }

    public static function generateForUser($user): self
    {
        static::where('user_id', $user->id)->whereNull('used_at')->update(['used_at' => now()]);

        return static::create([
            'user_id' => $user->id,
            'code' => str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'expires_at' => now()->addMinutes(10),
        ]);
    }
}
