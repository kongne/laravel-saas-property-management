<?php

namespace Tests\Feature;

use App\Models\Lease;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaseFeatureTest extends TestCase
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

    public function test_admin_can_view_leases_list()
    {
        Lease::factory()->count(3)->create([
            'unit_id' => $this->unit->id,
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('leases.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_lease()
    {
        $response = $this->actingAs($this->admin)->post(route('leases.store'), [
            'unit_id' => $this->unit->id,
            'tenant_id' => $this->tenant->id,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addYear()->format('Y-m-d'),
            'rent_amount' => 1500.00,
            'payment_frequency' => 'monthly',
            'due_day' => 5,
        ]);

        $response->assertRedirect(route('leases.index'));
        $this->assertDatabaseHas('leases', ['rent_amount' => 1500.00]);
    }

    public function test_admin_can_view_lease()
    {
        $lease = Lease::factory()->create([
            'unit_id' => $this->unit->id,
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('leases.show', $lease));
        $response->assertStatus(200);
    }

    public function test_admin_can_update_lease()
    {
        $lease = Lease::factory()->create([
            'unit_id' => $this->unit->id,
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->admin->id,
            'rent_amount' => 1000.00,
        ]);

        $response = $this->actingAs($this->admin)->put(route('leases.update', $lease), [
            'unit_id' => $this->unit->id,
            'tenant_id' => $this->tenant->id,
            'start_date' => $lease->start_date->format('Y-m-d'),
            'end_date' => $lease->end_date->format('Y-m-d'),
            'rent_amount' => 2000.00,
            'payment_frequency' => 'monthly',
            'due_day' => $lease->due_day,
        ]);

        $response->assertRedirect(route('leases.index'));
        $this->assertDatabaseHas('leases', ['rent_amount' => 2000.00]);
    }

    public function test_admin_can_terminate_lease()
    {
        $lease = Lease::factory()->create([
            'unit_id' => $this->unit->id,
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->admin->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->post(route('leases.terminate', $lease));

        $response->assertRedirect();
        $this->assertEquals('terminated', $lease->fresh()->status);
    }

    public function test_admin_can_renew_lease()
    {
        $lease = Lease::factory()->create([
            'unit_id' => $this->unit->id,
            'tenant_id' => $this->tenant->id,
            'user_id' => $this->admin->id,
            'status' => 'active',
            'end_date' => now()->addMonth(),
        ]);

        $response = $this->actingAs($this->admin)->post(route('leases.renew', $lease), [
            'end_date' => now()->addYear()->format('Y-m-d'),
            'rent_amount' => 1800.00,
        ]);

        $response->assertRedirect();
        $lease->refresh();
        $this->assertEquals(1800.00, $lease->rent_amount);
        $this->assertEquals('active', $lease->status);
    }

    public function test_tenant_cannot_access_leases()
    {
        $tenant = User::factory()->tenant()->create();

        $response = $this->actingAs($tenant)->get(route('leases.index'));
        $response->assertForbidden();
    }
}
