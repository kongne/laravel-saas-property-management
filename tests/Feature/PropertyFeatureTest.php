<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyFeatureTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_admin_can_view_properties_list()
    {
        Property::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('properties.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_property()
    {
        $response = $this->actingAs($this->admin)->post(route('properties.store'), [
            'name' => 'Sunset Towers',
            'type' => 'apartment',
            'address' => '123 Main St',
            'city' => 'New York',
            'state' => 'NY',
            'zip_code' => '10001',
            'country' => 'US',
            'total_units' => 10,
            'description' => 'A beautiful property',
        ]);

        $response->assertRedirect(route('properties.index'));
        $this->assertDatabaseHas('properties', ['name' => 'Sunset Towers']);
    }

    public function test_admin_can_view_property()
    {
        $property = Property::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('properties.show', $property));
        $response->assertStatus(200);
    }

    public function test_admin_can_update_property()
    {
        $property = Property::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($this->admin)->put(route('properties.update', $property), [
            'name' => 'New Name',
            'type' => $property->type,
            'address' => $property->address,
            'city' => $property->city,
            'state' => $property->state,
            'zip_code' => $property->zip_code,
            'country' => $property->country,
        ]);

        $response->assertRedirect(route('properties.index'));
        $this->assertDatabaseHas('properties', ['name' => 'New Name']);
    }

    public function test_admin_can_delete_property()
    {
        $property = Property::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('properties.destroy', $property));

        $response->assertRedirect(route('properties.index'));
        $this->assertSoftDeleted($property);
    }

    public function test_tenant_cannot_access_properties()
    {
        $tenant = User::factory()->tenant()->create();

        $response = $this->actingAs($tenant)->get(route('properties.index'));
        $response->assertForbidden();
    }
}
