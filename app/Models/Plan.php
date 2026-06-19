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
        'prices',
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
        'prices' => 'array',
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

    public function getPrice(?string $currency = null, string $period = 'monthly'): float
    {
        if ($currency && $this->prices && isset($this->prices[$currency][$period])) {
            return (float) $this->prices[$currency][$period];
        }

        return $period === 'yearly' ? (float) $this->yearly_price : (float) $this->monthly_price;
    }

    public function getFormattedPrice(?string $currency = null, string $period = 'monthly'): string
    {
        $price = $this->getPrice($currency, $period);

        if (!$currency || $currency === 'USD') {
            return '$' . number_format($price, 2);
        }

        $currencyModel = Currency::where('code', $currency)->first();
        $symbol = $currencyModel?->symbol ?? $currency;

        return $symbol . number_format($price, 2);
    }

    public function getPricesByCurrency(?string $currency = null): array
    {
        if ($currency && $this->prices && isset($this->prices[$currency])) {
            return $this->prices[$currency];
        }

        return [
            'monthly' => (float) $this->monthly_price,
            'yearly' => (float) $this->yearly_price,
        ];
    }

    public function getAvailableCurrencies(): array
    {
        if (!$this->prices) {
            return ['USD'];
        }

        return array_keys($this->prices);
    }
}
