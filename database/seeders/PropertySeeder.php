<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $property = Property::create([
            'user_id' => 2,
            'name' => 'Sunrise Apartments',
            'type' => 'apartment',
            'description' => 'A beautiful apartment complex in the heart of the city.',
            'address' => '123 Main Street',
            'city' => 'New York',
            'state' => 'NY',
            'zip_code' => '10001',
            'country' => 'US',
            'total_units' => 3,
            'status' => 'active',
        ]);

        foreach (['1A', '1B', '2A'] as $num) {
            Unit::create([
                'property_id' => $property->id,
                'unit_number' => $num,
                'type' => 'one_bedroom',
                'bedrooms' => 1,
                'bathrooms' => 1,
                'rent_amount' => 1500,
                'security_deposit' => 1500,
                'area_sqft' => 750,
                'status' => 'available',
            ]);
        }

        Property::create([
            'user_id' => 2,
            'name' => 'Green Valley Homes',
            'type' => 'house',
            'description' => 'Quiet suburban homes with large yards.',
            'address' => '456 Oak Avenue',
            'city' => 'Los Angeles',
            'state' => 'CA',
            'zip_code' => '90001',
            'country' => 'US',
            'total_units' => 1,
            'status' => 'active',
        ]);
    }
}
