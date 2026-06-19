<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_default_landlord_role()
    {
        $user = User::factory()->create();
        $this->assertEquals('landlord', $user->role);
    }

    public function test_admin_role_check()
    {
        $admin = User::factory()->admin()->create();
        $landlord = User::factory()->create();
        $tenant = User::factory()->tenant()->create();

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($landlord->isAdmin());
        $this->assertFalse($tenant->isAdmin());
    }

    public function test_landlord_role_check()
    {
        $admin = User::factory()->admin()->create();
        $landlord = User::factory()->create();
        $tenant = User::factory()->tenant()->create();

        $this->assertTrue($landlord->isLandlord());
        $this->assertFalse($admin->isLandlord());
        $this->assertFalse($tenant->isLandlord());
    }

    public function test_tenant_role_check()
    {
        $admin = User::factory()->admin()->create();
        $landlord = User::factory()->create();
        $tenant = User::factory()->tenant()->create();

        $this->assertTrue($tenant->isTenantUser());
        $this->assertFalse($admin->isTenantUser());
        $this->assertFalse($landlord->isTenantUser());
    }

    public function test_is_active()
    {
        $user = User::factory()->create(['is_active' => true]);
        $this->assertTrue($user->isActive());

        $user->is_active = false;
        $this->assertFalse($user->isActive());
    }

    public function test_has_two_factor_enabled()
    {
        $user = User::factory()->create(['two_factor_confirmed_at' => null]);
        $this->assertFalse($user->hasTwoFactorEnabled());

        $user->two_factor_confirmed_at = now();
        $this->assertTrue($user->hasTwoFactorEnabled());
    }

    public function test_user_has_properties_relationship()
    {
        $user = User::factory()->hasProperties(3)->create();
        $this->assertCount(3, $user->properties);
        $this->assertInstanceOf(\App\Models\Property::class, $user->properties->first());
    }

    public function test_user_has_tenants_relationship()
    {
        $user = User::factory()->hasTenants(2)->create();
        $this->assertCount(2, $user->tenants);
    }

    public function test_user_has_leases_relationship()
    {
        $user = User::factory()->hasLeases(2)->create();
        $this->assertCount(2, $user->leases);
    }

    public function test_user_has_maintenance_requests_relationship()
    {
        $user = User::factory()->hasMaintenanceRequests(3)->create();
        $this->assertCount(3, $user->maintenanceRequests);
    }

    public function test_user_has_activity_logs_relationship()
    {
        $user = User::factory()->create();
        \App\Models\ActivityLog::factory()->count(2)->create(['user_id' => $user->id]);
        $this->assertCount(2, $user->activityLogs);
    }
}
