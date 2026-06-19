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
        'district',
        'state',
        'zip_code',
        'country',
        'latitude',
        'longitude',
        'total_units',
        'area_sqft',
        'amenities',
        'images',
        'nearby_places',
        'video_url',
        'featured',
        'documents',
        'status',
        'purpose',
        'price',
        'region',
        'area',
        'category',
        'bedrooms',
        'bathrooms',
    ];

    protected $casts = [
        'amenities' => 'array',
        'images' => 'array',
        'nearby_places' => 'array',
        'documents' => 'array',
        'featured' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'area_sqft' => 'decimal:2',
        'price' => 'decimal:2',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
    ];

    protected $appends = ['primary_image'];

    public function getPrimaryImageAttribute(): string
    {
        $images = $this->images ?? [];
        if (!empty($images) && isset($images[0])) {
            return $images[0];
        }
        return 'https://placehold.co/800x600?text=No+Image';
    }

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

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeInCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function scopeInDistrict($query, $district)
    {
        return $query->where('district', $district);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public static function cities(): array
    {
        return [
            'Douala', 'Yaoundé', 'Bafoussam', 'Garoua', 'Maroua',
            'Bamenda', 'Kribi', 'Limbe', 'Buea', 'Nkongsamba',
            'Kumba', 'Dschang', 'Edéa', 'Bertoua', 'Ngaoundéré',
            'Sangmélima', 'Ebolowa', 'Foumban', 'Mbouda', 'Kousséri',
        ];
    }

    public static function districtsForCity(?string $city): array
    {
        $districts = [
            'Douala' => ['Bonanjo', 'Bonabéri', 'Akwa', 'Deido', 'Bali', 'Bonamoussadi', 'Makepe', 'Bonapriso', 'Logpom', 'Ndogbati', 'Cité Sic', 'Nzengue', 'Bepanda', 'Oyack'],
            'Yaoundé' => ['Mfoundi', 'Biyem-Assi', 'Mvog-Mbi', 'Nlongkak', 'Elig-Esso', 'Mokolo', 'Mendong', 'Nkolbisson', 'Essos', 'Nkoabang', 'Ekounou', 'Obili', 'Mvan', 'Nsimeyong'],
            'Bafoussam' => ['Banengo', 'Kamkop', 'Djinang', 'Famla', 'Kougoui', 'Bandjoun', 'Baleng'],
            'Bamenda' => ['Nkwen', 'Mankon', 'Mendankwe', 'Ntarikon', 'Ntamulung', 'Up Station', 'Down Town'],
            'Buea' => ['Molyko', 'Great Soppo', 'Bokwaongo', 'Bwitingi', 'Muea', 'Small Soppo'],
            'Limbe' => ['Bota', 'Limbe Town', 'Wovia', 'Mile 4', 'New Town', 'Idenau', 'Batoke'],
            'Kribi' => ['Mboamanga', 'Mbanga', 'Londji', 'Grand Batanga', 'Eboundja'],
            'Maroua' => ['Dougoui', 'Domayo', 'Kongola', 'Madawal', 'Djarengol'],
        ];
        return $districts[$city] ?? [];
    }

    public static function propertyTypes(): array
    {
        return [
            'apartment' => 'Apartment',
            'studio' => 'Studio',
            'house' => 'House',
            'villa' => 'Villa',
            'commercial' => 'Commercial',
            'land' => 'Land',
            'office' => 'Office',
            'warehouse' => 'Warehouse',
        ];
    }

    public static function amenityOptions(): array
    {
        return [
            'air_conditioning' => 'Air Conditioning',
            'wifi' => 'WiFi',
            'parking' => 'Parking',
            'swimming_pool' => 'Swimming Pool',
            'gym' => 'Gym',
            'security' => 'Security',
            'generator' => 'Generator',
            'water_tank' => 'Water Tank',
            'elevator' => 'Elevator',
            'furnished' => 'Furnished',
            'balcony' => 'Balcony',
            'garden' => 'Garden',
            'cctv' => 'CCTV',
            'solar_power' => 'Solar Power',
            'cable_tv' => 'Cable TV',
            'staff_quarters' => 'Staff Quarters',
        ];
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public static function categories(): array
    {
        return [
            'standard' => 'Standard Room',
            'moderne_simple' => 'Chambre moderne et simple',
            'studio_moderne' => 'Studio moderne et simple',
            'appartement_moderne' => 'Appartement moderne et simple',
        ];
    }

    public function scopeInRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    public function scopeInArea($query, $area)
    {
        return $query->where('area', $area);
    }

    public function scopeForPurpose($query, $purpose)
    {
        return $query->where('purpose', $purpose);
    }

    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeWithBedrooms($query, $bedrooms)
    {
        return $query->where('bedrooms', $bedrooms);
    }
}
