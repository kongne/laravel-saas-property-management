<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    const STATUS_TRIAL = 'trial';
    const STATUS_ACTIVE = 'active';
    const STATUS_PAST_DUE = 'past_due';
    const STATUS_CANCELED = 'canceled';
    const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'trial_ends_at',
        'starts_at',
        'ends_at',
        'canceled_at',
        'renewed_at',
        'payment_method',
        'payment_provider_id',
        'billing_period',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'canceled_at' => 'datetime',
        'renewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function isTrial(): bool
    {
        return $this->status === self::STATUS_TRIAL;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isCanceled(): bool
    {
        return $this->status === self::STATUS_CANCELED;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    public function isPastDue(): bool
    {
        return $this->status === self::STATUS_PAST_DUE;
    }

    public function onTrial(): bool
    {
        if ($this->status !== self::STATUS_TRIAL) {
            return false;
        }
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function trialExpired(): bool
    {
        if ($this->status !== self::STATUS_TRIAL) {
            return false;
        }
        return $this->trial_ends_at && $this->trial_ends_at->isPast();
    }

    public function hasExpired(): bool
    {
        if ($this->status === self::STATUS_ACTIVE && $this->ends_at) {
            return $this->ends_at->isPast();
        }
        return $this->isExpired();
    }

    public function isValid(): bool
    {
        if ($this->isTrial()) {
            return $this->onTrial();
        }
        if ($this->isActive()) {
            return !$this->hasExpired();
        }
        return false;
    }

    public function daysRemaining(): int
    {
        if ($this->isTrial() && $this->trial_ends_at) {
            return max(0, now()->diffInDays($this->trial_ends_at, false));
        }
        if ($this->isActive() && $this->ends_at) {
            return max(0, now()->diffInDays($this->ends_at, false));
        }
        return 0;
    }

    public function scopeValid($query)
    {
        return $query->where(function ($q) {
            $q->where('status', self::STATUS_ACTIVE)
              ->where(function ($q2) {
                  $q2->whereNull('ends_at')
                     ->orWhere('ends_at', '>', now());
              })
              ->orWhere(function ($q3) {
                  $q3->where('status', self::STATUS_TRIAL)
                     ->where('trial_ends_at', '>', now());
              });
        });
    }
}
