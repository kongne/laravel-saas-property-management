<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $currencies = \App\Models\Currency::all()->keyBy('code');

        Plan::create([
            'name' => 'Free',
            'slug' => 'free',
            'description' => 'Perfect for getting started with basic property management.',
            'monthly_price' => 0,
            'yearly_price' => 0,
            'prices' => [
                'USD' => ['monthly' => 0, 'yearly' => 0],
                'XAF' => ['monthly' => 0, 'yearly' => 0],
                'EUR' => ['monthly' => 0, 'yearly' => 0],
                'GBP' => ['monthly' => 0, 'yearly' => 0],
                'NGN' => ['monthly' => 0, 'yearly' => 0],
            ],
            'max_properties' => 1,
            'max_units' => 5,
            'max_tenants' => 5,
            'max_users' => 1,
            'can_export' => false,
            'can_access_audit' => false,
            'has_advanced_reports' => false,
            'has_api_access' => false,
            'has_priority_support' => false,
            'is_popular' => false,
            'is_active' => true,
            'trial_days' => 0,
            'sort_order' => 1,
        ]);

        $starterUsdMonthly = 19;
        $starterUsdYearly = 190;
        Plan::create([
            'name' => 'Starter',
            'slug' => 'starter',
            'description' => 'Essential tools for small property owners managing a few units.',
            'monthly_price' => $starterUsdMonthly,
            'yearly_price' => $starterUsdYearly,
            'prices' => [
                'USD' => ['monthly' => $starterUsdMonthly, 'yearly' => $starterUsdYearly],
                'XAF' => ['monthly' => round($starterUsdMonthly * 600), 'yearly' => round($starterUsdYearly * 600)],
                'EUR' => ['monthly' => round($starterUsdMonthly * 0.92, 2), 'yearly' => round($starterUsdYearly * 0.92, 2)],
                'GBP' => ['monthly' => round($starterUsdMonthly * 0.79, 2), 'yearly' => round($starterUsdYearly * 0.79, 2)],
                'NGN' => ['monthly' => round($starterUsdMonthly * 1500), 'yearly' => round($starterUsdYearly * 1500)],
            ],
            'max_properties' => 3,
            'max_units' => 20,
            'max_tenants' => 30,
            'max_users' => 2,
            'can_export' => true,
            'can_access_audit' => false,
            'has_advanced_reports' => false,
            'has_api_access' => false,
            'has_priority_support' => false,
            'is_popular' => false,
            'is_active' => true,
            'trial_days' => 0,
            'sort_order' => 2,
        ]);

        $proUsdMonthly = 49;
        $proUsdYearly = 490;
        Plan::create([
            'name' => 'Professional',
            'slug' => 'professional',
            'description' => 'For growing portfolios with advanced reporting and full team access.',
            'monthly_price' => $proUsdMonthly,
            'yearly_price' => $proUsdYearly,
            'prices' => [
                'USD' => ['monthly' => $proUsdMonthly, 'yearly' => $proUsdYearly],
                'XAF' => ['monthly' => round($proUsdMonthly * 600), 'yearly' => round($proUsdYearly * 600)],
                'EUR' => ['monthly' => round($proUsdMonthly * 0.92, 2), 'yearly' => round($proUsdYearly * 0.92, 2)],
                'GBP' => ['monthly' => round($proUsdMonthly * 0.79, 2), 'yearly' => round($proUsdYearly * 0.79, 2)],
                'NGN' => ['monthly' => round($proUsdMonthly * 1500), 'yearly' => round($proUsdYearly * 1500)],
            ],
            'max_properties' => 15,
            'max_units' => 100,
            'max_tenants' => 200,
            'max_users' => 10,
            'can_export' => true,
            'can_access_audit' => true,
            'has_advanced_reports' => true,
            'has_api_access' => false,
            'has_priority_support' => false,
            'is_popular' => true,
            'is_active' => true,
            'trial_days' => 0,
            'sort_order' => 3,
        ]);

        $entUsdMonthly = 99;
        $entUsdYearly = 990;
        Plan::create([
            'name' => 'Enterprise',
            'slug' => 'enterprise',
            'description' => 'Unlimited everything with API access and priority support.',
            'monthly_price' => $entUsdMonthly,
            'yearly_price' => $entUsdYearly,
            'prices' => [
                'USD' => ['monthly' => $entUsdMonthly, 'yearly' => $entUsdYearly],
                'XAF' => ['monthly' => round($entUsdMonthly * 600), 'yearly' => round($entUsdYearly * 600)],
                'EUR' => ['monthly' => round($entUsdMonthly * 0.92, 2), 'yearly' => round($entUsdYearly * 0.92, 2)],
                'GBP' => ['monthly' => round($entUsdMonthly * 0.79, 2), 'yearly' => round($entUsdYearly * 0.79, 2)],
                'NGN' => ['monthly' => round($entUsdMonthly * 1500), 'yearly' => round($entUsdYearly * 1500)],
            ],
            'max_properties' => null,
            'max_units' => null,
            'max_tenants' => null,
            'max_users' => null,
            'can_export' => true,
            'can_access_audit' => true,
            'has_advanced_reports' => true,
            'has_api_access' => true,
            'has_priority_support' => true,
            'is_popular' => false,
            'is_active' => true,
            'trial_days' => 0,
            'sort_order' => 4,
        ]);
    }
}
