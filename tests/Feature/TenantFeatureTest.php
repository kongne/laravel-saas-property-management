<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantFeatureTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Unit $unit;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->unit = Unit::factory()->create();
    }

    public function test_admin_can_view_tenants_list()
    {
        Tenant::factory()->count(3)->create(['unit_id' => $this->unit->id]);

        $response = $this->actingAs($this->admin)->get(route('tenants.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_tenant()
    {
        $tenantUser = User::factory()->tenant()->create();

        $response = $this->actingAs($this->admin)->post(route('tenants.store'), [
            'user_id' => $tenantUser->id,
            'unit_id' => $this->unit->id,
            'status' => 'active',
        ]);

        $response->assertRedirect(route('tenants.index'));
        $this->assertDatabaseHas('tenants', ['user_id' => $tenantUser->id]);
    }

    public function test_admin_can_view_tenant()
    {
        $tenant = Tenant::factory()->create(['unit_id' => $this->unit->id]);

        $response = $this->actingAs($this->admin)->get(route('tenants.show', $tenant));
        $response->assertStatus(200);
    }

    public function test_admin_can_update_tenant()
    {
        $tenant = Tenant::factory()->create([
            'unit_id' => $this->unit->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->put(route('tenants.update', $tenant), [
            'user_id' => $tenant->user_id,
            'unit_id' => $this->unit->id,
            'status' => 'active',
        ]);

        $response->assertRedirect(route('tenants.index'));
        $this->assertDatabaseHas('tenants', ['status' => 'active']);
    }

    public function test_admin_can_delete_tenant()
    {
        $tenant = Tenant::factory()->create(['unit_id' => $this->unit->id]);

        $response = $this->actingAs($this->admin)->delete(route('tenants.destroy', $tenant));

        $response->assertRedirect(route('tenants.index'));
        $this->assertSoftDeleted($tenant);
    }

    public function test_tenant_cannot_access_tenants_list()
    {
        $tenant = User::factory()->tenant()->create();

        $response = $this->actingAs($tenant)->get(route('tenants.index'));
        $response->assertForbidden();
    }
}
