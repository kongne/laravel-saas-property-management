<?php

namespace Tests\Feature;

use App\Models\MaintenanceRequest;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaintenanceFeatureTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Unit $unit;
    private Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->unit = Unit::factory()->create();
        $this->tenant = Tenant::factory()->create(['unit_id' => $this->unit->id]);
    }

    public function test_admin_can_view_maintenance_list()
    {
        MaintenanceRequest::factory()->count(3)->create([
            'unit_id' => $this->unit->id,
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('maintenance.index'));
        $response->assertStatus(200);
    }

    public function test_tenant_can_view_maintenance_list()
    {
        $tenant = User::factory()->tenant()->create();

        $response = $this->actingAs($tenant)->get(route('maintenance.index'));
        $response->assertStatus(200);
    }

    public function test_tenant_can_create_maintenance_request()
    {
        $tenant = User::factory()->tenant()->create();

        $response = $this->actingAs($tenant)->post(route('maintenance.store'), [
            'unit_id' => $this->unit->id,
            'tenant_id' => $this->tenant->id,
            'title' => 'Leaking faucet',
            'description' => 'The kitchen faucet is leaking',
            'category' => 'plumbing',
            'priority' => 'medium',
            'requested_date' => now()->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('maintenance.index'));
        $this->assertDatabaseHas('maintenance_requests', ['title' => 'Leaking faucet']);
    }

    public function test_admin_can_resolve_maintenance_request()
    {
        $request = MaintenanceRequest::factory()->create([
            'unit_id' => $this->unit->id,
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->admin->id,
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->admin)->post(route('maintenance.resolve', $request), [
            'resolution_notes' => 'Fixed the issue',
            'cost' => 150.00,
        ]);

        $response->assertRedirect();
        $this->assertEquals('resolved', $request->fresh()->status);
        $this->assertEquals(150.00, $request->fresh()->cost);
    }

    public function test_admin_can_assign_maintenance_request()
    {
        $request = MaintenanceRequest::factory()->create([
            'unit_id' => $this->unit->id,
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->admin->id,
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->admin)->post(route('maintenance.assign', $request), [
            'assigned_to' => 'John the Handyman',
        ]);

        $response->assertRedirect();
        $this->assertEquals('in_progress', $request->fresh()->status);
        $this->assertEquals('John the Handyman', $request->fresh()->assigned_to);
    }

    public function test_admin_can_view_maintenance_request()
    {
        $request = MaintenanceRequest::factory()->create([
            'unit_id' => $this->unit->id,
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('maintenance.show', $request));
        $response->assertStatus(200);
    }

    public function test_admin_can_delete_maintenance_request()
    {
        $request = MaintenanceRequest::factory()->create([
            'unit_id' => $this->unit->id,
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('maintenance.destroy', $request));

        $response->assertRedirect(route('maintenance.index'));
        $this->assertSoftDeleted($request);
    }
}
