<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'monthly_price',
        'yearly_price',
        'max_properties',
        'max_units',
        'max_tenants',
        'max_users',
        'can_export',
        'can_access_audit',
        'has_advanced_reports',
        'has_api_access',
        'has_priority_support',
        'is_popular',
        'is_active',
        'trial_days',
        'sort_order',
    ];

    protected $casts = [
        'monthly_price' => 'decimal:2',
        'yearly_price' => 'decimal:2',
        'max_properties' => 'integer',
        'max_units' => 'integer',
        'max_tenants' => 'integer',
        'max_users' => 'integer',
        'can_export' => 'boolean',
        'can_access_audit' => 'boolean',
        'has_advanced_reports' => 'boolean',
        'has_api_access' => 'boolean',
        'has_priority_support' => 'boolean',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'trial_days' => 'integer',
        'sort_order' => 'integer',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function hasUnlimitedProperties(): bool
    {
        return is_null($this->max_properties);
    }

    public function hasUnlimitedUnits(): bool
    {
        return is_null($this->max_units);
    }

    public function hasUnlimitedTenants(): bool
    {
        return is_null($this->max_tenants);
    }

    public function hasUnlimitedUsers(): bool
    {
        return is_null($this->max_users);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeSorted($query)
    {
        return $query->orderBy('sort_order');
    }
}
