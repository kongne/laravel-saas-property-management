<?php

namespace Database\Factories;

use App\Models\Lease;
use App\Models\Tenant;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    private static int $invoiceCounter = 1000;

    public function definition(): array
    {
        return [
            'lease_id' => Lease::factory(),
            'tenant_id' => Tenant::factory(),
            'unit_id' => Unit::factory(),
            'invoice_number' => 'INV-' . now()->format('Ymd') . '-' . (++self::$invoiceCounter),
            'amount' => fake()->randomFloat(2, 500, 5000),
            'paid_amount' => null,
            'late_fee' => 0,
            'balance' => 0,
            'due_date' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'paid_date' => null,
            'status' => 'pending',
            'payment_method' => null,
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'paid_amount' => $attributes['amount'] ?? fake()->randomFloat(2, 500, 5000),
            'paid_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'status' => 'paid',
            'payment_method' => fake()->randomElement(['cash', 'check', 'bank_transfer', 'credit_card', 'mobile_money']),
            'transaction_reference' => fake()->uuid(),
            'balance' => 0,
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'due_date' => fake()->dateTimeBetween('-2 months', '-1 day'),
            'status' => 'pending',
        ]);
    }

    public function partial(): static
    {
        return $this->state(fn (array $attributes) => [
            'paid_amount' => fake()->randomFloat(2, 50, ($attributes['amount'] ?? 1000) - 1),
            'paid_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'status' => 'partial',
            'payment_method' => 'cash',
            'balance' => fn (array $attrs) => ($attrs['amount'] ?? 1000) - ($attrs['paid_amount'] ?? 0),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'cancelled']);
    }
}
