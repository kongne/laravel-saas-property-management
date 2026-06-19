<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        Currency::create([
            'code' => 'USD',
            'name' => 'US Dollar',
            'symbol' => '$',
            'exchange_rate' => 1.000000,
            'is_base' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Currency::create([
            'code' => 'XAF',
            'name' => 'Central African CFA Franc',
            'symbol' => 'FCFA',
            'exchange_rate' => 600.000000,
            'is_base' => false,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Currency::create([
            'code' => 'EUR',
            'name' => 'Euro',
            'symbol' => '€',
            'exchange_rate' => 0.920000,
            'is_base' => false,
            'is_active' => true,
            'sort_order' => 3,
        ]);

        Currency::create([
            'code' => 'GBP',
            'name' => 'British Pound',
            'symbol' => '£',
            'exchange_rate' => 0.790000,
            'is_base' => false,
            'is_active' => true,
            'sort_order' => 4,
        ]);

        Currency::create([
            'code' => 'NGN',
            'name' => 'Nigerian Naira',
            'symbol' => '₦',
            'exchange_rate' => 1500.000000,
            'is_base' => false,
            'is_active' => true,
            'sort_order' => 5,
        ]);
    }
}
