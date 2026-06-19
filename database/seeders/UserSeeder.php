<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@propertymanager.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+1234567890',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => 'landlord',
            'phone' => '+1234567891',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Jane Tenant',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'role' => 'tenant',
            'phone' => '+1234567892',
            'is_active' => true,
        ]);

        User::factory()->count(5)->create();
        User::factory()->count(3)->tenant()->create();
    }
}
