<?php

namespace App\Models\Traits;

use App\Models\Plan;
use App\Models\Subscription;

trait HasSubscription
{
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->subscriptions()->valid()->latest()->first();
    }

    public function currentSubscription()
    {
        return $this->activeSubscription() ?? $this->subscriptions()->latest()->first();
    }

    public function currentPlan(): ?Plan
    {
        if ($this->isAdmin()) {
            return $this->adminPlan();
        }

        $sub = $this->currentSubscription();

        return $sub?->plan;
    }

    public function onTrial(): bool
    {
        $sub = $this->currentSubscription();
        return $sub && $sub->isTrial() && $sub->onTrial();
    }

    public function isSubscribed(): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $sub = $this->activeSubscription();
        return !is_null($sub);
    }

    public function subscriptionStatus(): string
    {
        if ($this->isAdmin()) {
            return 'active';
        }

        $sub = $this->currentSubscription();
        if (!$sub) {
            return 'none';
        }

        return $sub->status;
    }

    public function canAccessFeature(string $feature): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $plan = $this->currentPlan();
        if (!$plan) {
            return false;
        }

        $featureMap = [
            'export' => 'can_export',
            'audit' => 'can_access_audit',
            'advanced_reports' => 'has_advanced_reports',
            'api_access' => 'has_api_access',
            'priority_support' => 'has_priority_support',
        ];

        if (!isset($featureMap[$feature])) {
            return false;
        }

        return (bool) $plan->{$featureMap[$feature]};
    }

    public function featureLimit(string $resource): ?int
    {
        if ($this->isAdmin()) {
            return null;
        }

        $plan = $this->currentPlan();
        if (!$plan) {
            return 0;
        }

        $limitMap = [
            'properties' => 'max_properties',
            'units' => 'max_units',
            'tenants' => 'max_tenants',
            'users' => 'max_users',
        ];

        if (!isset($limitMap[$resource])) {
            return null;
        }

        return $plan->{$limitMap[$resource]};
    }

    public function hasReachedLimit(string $resource): bool
    {
        $limit = $this->featureLimit($resource);

        if (is_null($limit)) {
            return false;
        }

        $count = match ($resource) {
            'properties' => $this->properties()->count(),
            'units' => \App\Models\Unit::whereHas('property', fn($q) => $q->where('user_id', $this->id))->count(),
            'tenants' => $this->tenants()->count(),
            'users' => \App\Models\User::where('role', '!=', 'admin')->count(),
            default => 0,
        };

        return $count >= $limit;
    }

    public function remainingLimit(string $resource): int
    {
        $limit = $this->featureLimit($resource);

        if (is_null($limit)) {
            return PHP_INT_MAX;
        }

        $count = match ($resource) {
            'properties' => $this->properties()->count(),
            'units' => \App\Models\Unit::whereHas('property', fn($q) => $q->where('user_id', $this->id))->count(),
            'tenants' => $this->tenants()->count(),
            'users' => \App\Models\User::where('role', '!=', 'admin')->count(),
            default => 0,
        };

        return max(0, $limit - $count);
    }

    public function needsUpgrade(): bool
    {
        return !$this->isSubscribed() || $this->hasReachedLimit('properties') || $this->hasReachedLimit('units') || $this->hasReachedLimit('tenants');
    }

    public function adminPlan(): Plan
    {
        $plan = Plan::firstOrCreate(
            ['slug' => 'enterprise'],
            [
                'name' => 'Enterprise',
                'description' => 'Full access for administrators',
                'monthly_price' => 0,
                'yearly_price' => 0,
                'max_properties' => null,
                'max_units' => null,
                'max_tenants' => null,
                'max_users' => null,
                'can_export' => true,
                'can_access_audit' => true,
                'has_advanced_reports' => true,
                'has_api_access' => true,
                'has_priority_support' => true,
                'is_active' => false,
                'sort_order' => 99,
            ]
        );

        return $plan;
    }
}
