<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),
            'unit_number' => fake()->unique()->bothify('##-###'),
            'type' => fake()->randomElement(['studio', 'one_bedroom', 'two_bedroom', 'three_bedroom', 'penthouse']),
            'bedrooms' => fake()->numberBetween(0, 4),
            'bathrooms' => fake()->numberBetween(1, 3),
            'rent_amount' => fake()->randomFloat(2, 500, 5000),
            'security_deposit' => fake()->randomFloat(2, 500, 3000),
            'area_sqft' => fake()->randomFloat(2, 250, 2000),
            'description' => fake()->paragraph(),
            'amenities' => fake()->randomElements(['AC', 'Heating', 'Balcony', 'Dishwasher', 'Washer/Dryer', 'Furnished'], rand(1, 4)),
            'status' => 'available',
        ];
    }

    public function occupied(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'occupied']);
    }

    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'maintenance']);
    }

    public function reserved(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'reserved']);
    }
}
