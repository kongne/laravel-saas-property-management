<?php

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaintenanceRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'unit_id' => Unit::factory(),
            'tenant_id' => Tenant::factory(),
            'user_id' => User::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'category' => fake()->randomElement(['plumbing', 'electrical', 'hvac', 'appliance', 'structural', 'pest_control', 'general']),
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'emergency']),
            'status' => 'open',
            'requested_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'resolved_date' => null,
            'cost' => null,
            'assigned_to' => null,
            'resolution_notes' => null,
        ];
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
            'assigned_to' => fake()->name(),
        ]);
    }

    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'resolved',
            'resolved_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'cost' => fake()->randomFloat(2, 50, 2000),
            'resolution_notes' => fake()->paragraph(),
        ]);
    }

    public function emergency(): static
    {
        return $this->state(fn (array $attributes) => ['priority' => 'emergency']);
    }
}
