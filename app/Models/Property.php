<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'description',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'latitude',
        'longitude',
        'total_units',
        'area_sqft',
        'amenities',
        'images',
        'status',
    ];

    protected $casts = [
        'amenities' => 'array',
        'images' => 'array',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'area_sqft' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function availableUnits()
    {
        return $this->units()->where('status', 'available');
    }

    public function occupiedUnits()
    {
        return $this->units()->where('status', 'occupied');
    }

    public function activeLeases()
    {
        return $this->hasManyThrough(Lease::class, Unit::class)
            ->where('leases.status', 'active');
    }
}
