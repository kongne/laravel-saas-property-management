<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnitFeatureTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Property $property;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->property = Property::factory()->create();
    }

    public function test_admin_can_view_units_list()
    {
        Unit::factory()->count(3)->create(['property_id' => $this->property->id]);

        $response = $this->actingAs($this->admin)->get(route('units.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_unit()
    {
        $response = $this->actingAs($this->admin)->post(route('units.store'), [
            'property_id' => $this->property->id,
            'unit_number' => '101',
            'type' => 'one_bedroom',
            'bedrooms' => 1,
            'bathrooms' => 1,
            'rent_amount' => 1500.00,
        ]);

        $response->assertRedirect(route('units.index'));
        $this->assertDatabaseHas('units', ['unit_number' => '101']);
    }

    public function test_admin_can_view_unit()
    {
        $unit = Unit::factory()->create(['property_id' => $this->property->id]);

        $response = $this->actingAs($this->admin)->get(route('units.show', $unit));
        $response->assertStatus(200);
    }

    public function test_admin_can_update_unit()
    {
        $unit = Unit::factory()->create([
            'property_id' => $this->property->id,
            'rent_amount' => 1000.00,
        ]);

        $response = $this->actingAs($this->admin)->put(route('units.update', $unit), [
            'property_id' => $this->property->id,
            'unit_number' => $unit->unit_number,
            'type' => $unit->type,
            'bedrooms' => $unit->bedrooms,
            'bathrooms' => $unit->bathrooms,
            'rent_amount' => 2000.00,
        ]);

        $response->assertRedirect(route('units.index'));
        $this->assertDatabaseHas('units', ['rent_amount' => 2000.00]);
    }

    public function test_admin_can_delete_unit()
    {
        $unit = Unit::factory()->create(['property_id' => $this->property->id]);

        $response = $this->actingAs($this->admin)->delete(route('units.destroy', $unit));

        $response->assertRedirect(route('units.index'));
        $this->assertSoftDeleted($unit);
    }

    public function test_tenant_cannot_access_units()
    {
        $tenant = User::factory()->tenant()->create();

        $response = $this->actingAs($tenant)->get(route('units.index'));
        $response->assertForbidden();
    }
}
