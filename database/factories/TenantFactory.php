<?php

namespace Database\Factories;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'unit_id' => Unit::factory(),
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => fake()->phoneNumber(),
            'lease_start' => fake()->dateTimeBetween('-1 year', 'now'),
            'lease_end' => fake()->dateTimeBetween('now', '+1 year'),
            'status' => 'active',
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function past(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'past']);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'pending']);
    }
}
