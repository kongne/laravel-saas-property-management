<?php

namespace Tests\Unit;

use App\Models\MaintenanceRequest;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaintenanceRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_belongs_to_unit()
    {
        $unit = Unit::factory()->create();
        $request = MaintenanceRequest::factory()->create(['unit_id' => $unit->id]);

        $this->assertInstanceOf(Unit::class, $request->unit);
        $this->assertTrue($request->unit->is($unit));
    }

    public function test_request_belongs_to_tenant()
    {
        $tenant = Tenant::factory()->create();
        $request = MaintenanceRequest::factory()->create(['tenant_id' => $tenant->id]);

        $this->assertInstanceOf(Tenant::class, $request->tenant);
        $this->assertTrue($request->tenant->is($tenant));
    }

    public function test_request_belongs_to_user()
    {
        $user = User::factory()->create();
        $request = MaintenanceRequest::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $request->user);
        $this->assertTrue($request->user->is($user));
    }

    public function test_open_scope()
    {
        MaintenanceRequest::factory()->count(2)->create(['status' => 'open']);
        MaintenanceRequest::factory()->inProgress()->count(1)->create();
        MaintenanceRequest::factory()->resolved()->count(2)->create();

        $this->assertCount(3, MaintenanceRequest::open()->get());
    }

    public function test_by_priority_scope()
    {
        MaintenanceRequest::factory()->emergency()->count(2)->create();
        MaintenanceRequest::factory()->count(3)->create(['priority' => 'low']);

        $this->assertCount(2, MaintenanceRequest::byPriority('emergency')->get());
        $this->assertCount(3, MaintenanceRequest::byPriority('low')->get());
    }

    public function test_is_open()
    {
        $open = MaintenanceRequest::factory()->create(['status' => 'open']);
        $inProgress = MaintenanceRequest::factory()->inProgress()->create();
        $resolved = MaintenanceRequest::factory()->resolved()->create();

        $this->assertTrue($open->isOpen());
        $this->assertTrue($inProgress->isOpen());
        $this->assertFalse($resolved->isOpen());
    }

    public function test_is_resolved()
    {
        $resolved = MaintenanceRequest::factory()->resolved()->create();
        $open = MaintenanceRequest::factory()->create(['status' => 'open']);

        $this->assertTrue($resolved->isResolved());
        $this->assertFalse($open->isResolved());

        $closed = MaintenanceRequest::factory()->create(['status' => 'closed']);
        $this->assertTrue($closed->isResolved());
    }

    public function test_resolve_method()
    {
        $request = MaintenanceRequest::factory()->create(['status' => 'open']);

        $request->resolve('Fixed the leak', 250.00);

        $this->assertEquals('resolved', $request->fresh()->status);
        $this->assertEquals('Fixed the leak', $request->fresh()->resolution_notes);
        $this->assertEquals(250.00, $request->fresh()->cost);
        $this->assertNotNull($request->fresh()->resolved_date);
    }

    public function test_request_soft_deletes()
    {
        $request = MaintenanceRequest::factory()->create();
        $request->delete();
        $this->assertSoftDeleted($request);
    }
}
