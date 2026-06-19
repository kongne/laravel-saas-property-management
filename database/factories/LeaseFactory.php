<?php

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'unit_id' => Unit::factory(),
            'tenant_id' => Tenant::factory(),
            'user_id' => User::factory(),
            'start_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'end_date' => fake()->dateTimeBetween('+1 month', '+2 years'),
            'rent_amount' => fake()->randomFloat(2, 500, 5000),
            'security_deposit' => fake()->randomFloat(2, 500, 3000),
            'payment_frequency' => 'monthly',
            'due_day' => fake()->numberBetween(1, 28),
            'status' => 'active',
            'terms' => fake()->optional()->paragraph(),
        ];
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'expired',
            'end_date' => fake()->dateTimeBetween('-1 year', '-1 day'),
        ]);
    }

    public function terminated(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'terminated']);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'pending']);
    }

    public function expiringSoon(): static
    {
        return $this->state(fn (array $attributes) => [
            'end_date' => fake()->dateTimeBetween('now', '+30 days'),
        ]);
    }
}
