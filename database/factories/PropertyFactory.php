<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->company() . ' ' . fake()->randomElement(['Apartments', 'Suites', 'Towers', 'Heights', 'Villas']),
            'type' => fake()->randomElement(['apartment', 'studio', 'house', 'villa', 'commercial', 'land', 'office', 'warehouse']),
            'description' => fake()->paragraph(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'zip_code' => fake()->postcode(),
            'country' => 'US',
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'total_units' => fake()->numberBetween(1, 50),
            'area_sqft' => fake()->randomFloat(2, 500, 10000),
            'amenities' => fake()->randomElements(['Parking', 'Gym', 'Pool', 'Laundry', 'Elevator', 'Security', 'Garden'], rand(2, 5)),
            'status' => 'active',
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'inactive']);
    }

    public function underMaintenance(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'under_maintenance']);
    }
}
