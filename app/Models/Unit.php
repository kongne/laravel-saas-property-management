<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'unit_number',
        'type',
        'bedrooms',
        'bathrooms',
        'rent_amount',
        'security_deposit',
        'area_sqft',
        'description',
        'amenities',
        'images',
        'status',
    ];

    protected $casts = [
        'amenities' => 'array',
        'images' => 'array',
        'rent_amount' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'area_sqft' => 'decimal:2',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public function activeTenant()
    {
        return $this->hasOne(Tenant::class)->where('status', 'active');
    }

    public function leases()
    {
        return $this->hasMany(Lease::class);
    }

    public function activeLease()
    {
        return $this->hasOne(Lease::class)->where('status', 'active');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function isOccupied(): bool
    {
        return $this->status === 'occupied';
    }
}
