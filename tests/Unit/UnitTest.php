<?php

namespace Tests\Unit;

use App\Models\Property;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_unit_belongs_to_property()
    {
        $property = Property::factory()->create();
        $unit = Unit::factory()->create(['property_id' => $property->id]);

        $this->assertInstanceOf(Property::class, $unit->property);
        $this->assertTrue($unit->property->is($property));
    }

    public function test_unit_has_many_tenants()
    {
        $unit = Unit::factory()->hasTenants(2)->create();
        $this->assertCount(2, $unit->tenants);
    }

    public function test_active_tenant_scope()
    {
        $unit = Unit::factory()->create();
        Tenant::factory()->create(['unit_id' => $unit->id, 'status' => 'active']);
        Tenant::factory()->past()->create(['unit_id' => $unit->id]);

        $this->assertNotNull($unit->activeTenant);
        $this->assertEquals('active', $unit->activeTenant->status);
    }

    public function test_is_available()
    {
        $unit = Unit::factory()->create(['status' => 'available']);
        $this->assertTrue($unit->isAvailable());
        $this->assertFalse($unit->isOccupied());
    }

    public function test_is_occupied()
    {
        $unit = Unit::factory()->occupied()->create();
        $this->assertTrue($unit->isOccupied());
        $this->assertFalse($unit->isAvailable());
    }

    public function test_unit_soft_deletes()
    {
        $unit = Unit::factory()->create();
        $unit->delete();
        $this->assertSoftDeleted($unit);
    }
}
