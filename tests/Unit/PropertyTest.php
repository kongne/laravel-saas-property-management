<?php

namespace Tests\Unit;

use App\Models\Property;
use App\Models\Unit;
use App\Models\User;
use App\Models\Lease;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyTest extends TestCase
{
    use RefreshDatabase;

    public function test_property_belongs_to_user()
    {
        $user = User::factory()->create();
        $property = Property::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $property->user);
        $this->assertTrue($property->user->is($user));
    }

    public function test_property_has_many_units()
    {
        $property = Property::factory()->hasUnits(3)->create();
        $this->assertCount(3, $property->units);
        $this->assertInstanceOf(Unit::class, $property->units->first());
    }

    public function test_available_units_scope()
    {
        $property = Property::factory()->create();
        Unit::factory()->count(2)->create(['property_id' => $property->id, 'status' => 'available']);
        Unit::factory()->occupied()->create(['property_id' => $property->id]);

        $this->assertCount(2, $property->availableUnits);
    }

    public function test_occupied_units_scope()
    {
        $property = Property::factory()->create();
        Unit::factory()->create(['property_id' => $property->id, 'status' => 'available']);
        Unit::factory()->occupied()->count(2)->create(['property_id' => $property->id]);

        $this->assertCount(2, $property->occupiedUnits);
    }

    public function test_active_leases_through_units()
    {
        $property = Property::factory()->create();
        $unit = Unit::factory()->create(['property_id' => $property->id]);
        Lease::factory()->count(2)->create(['unit_id' => $unit->id, 'status' => 'active']);
        Lease::factory()->expired()->create(['unit_id' => $unit->id]);

        $this->assertCount(2, $property->activeLeases);
    }

    public function test_property_soft_deletes()
    {
        $property = Property::factory()->create();
        $property->delete();

        $this->assertSoftDeleted($property);
    }

    public function test_property_fillable_attributes()
    {
        $property = Property::factory()->create([
            'name' => 'Sunset Towers',
            'type' => 'apartment',
        ]);

        $this->assertEquals('Sunset Towers', $property->name);
        $this->assertEquals('apartment', $property->type);
    }
}
