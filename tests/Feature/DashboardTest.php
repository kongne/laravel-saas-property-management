<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard()
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('dashboard'));
        $response->assertStatus(200);
    }

    public function test_landlord_can_access_dashboard()
    {
        $landlord = User::factory()->create(['role' => 'landlord']);

        $response = $this->actingAs($landlord)->get(route('dashboard'));
        $response->assertStatus(200);
    }

    public function test_tenant_can_access_dashboard()
    {
        $tenant = User::factory()->tenant()->create();

        $response = $this->actingAs($tenant)->get(route('dashboard'));
        $response->assertStatus(200);
    }

    public function test_dashboard_api_stats_returns_data()
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->getJson('/api/dashboard/stats');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'total_properties', 'total_units', 'total_tenants',
        ]);
    }

    public function test_unauthorized_user_cannot_access_dashboard_api()
    {
        $response = $this->getJson('/api/dashboard/stats');
        $response->assertStatus(401);
    }
}
