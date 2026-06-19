<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $professionalPlan = Plan::where('slug', 'professional')->first();
        if (!$professionalPlan) {
            return;
        }

        $adminUsers = User::where('role', 'admin')->get();
        foreach ($adminUsers as $user) {
            if (!$user->subscriptions()->exists()) {
                Subscription::create([
                    'user_id' => $user->id,
                    'plan_id' => $user->adminPlan()->id,
                    'status' => Subscription::STATUS_ACTIVE,
                    'starts_at' => now(),
                ]);
            }
        }

        $landlordUsers = User::where('role', 'landlord')->get();
        foreach ($landlordUsers as $user) {
            if (!$user->subscriptions()->exists()) {
                Subscription::create([
                    'user_id' => $user->id,
                    'plan_id' => $professionalPlan->id,
                    'status' => Subscription::STATUS_TRIAL,
                    'trial_ends_at' => now()->addDays(14),
                    'starts_at' => now(),
                ]);
            }
        }
    }
}
