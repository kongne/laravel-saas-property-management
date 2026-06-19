<?php

namespace Tests\Unit;

use App\Models\Lease;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_lease_belongs_to_unit()
    {
        $unit = Unit::factory()->create();
        $lease = Lease::factory()->create(['unit_id' => $unit->id]);

        $this->assertInstanceOf(Unit::class, $lease->unit);
        $this->assertTrue($lease->unit->is($unit));
    }

    public function test_lease_belongs_to_tenant()
    {
        $tenant = Tenant::factory()->create();
        $lease = Lease::factory()->create(['tenant_id' => $tenant->id]);

        $this->assertInstanceOf(Tenant::class, $lease->tenant);
        $this->assertTrue($lease->tenant->is($tenant));
    }

    public function test_lease_belongs_to_user()
    {
        $user = User::factory()->create();
        $lease = Lease::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $lease->user);
        $this->assertTrue($lease->user->is($user));
    }

    public function test_active_scope()
    {
        Lease::factory()->count(3)->create(['status' => 'active']);
        Lease::factory()->expired()->count(2)->create();

        $this->assertCount(3, Lease::active()->get());
    }

    public function test_expiring_soon_scope()
    {
        Lease::factory()->expiringSoon()->count(2)->create();
        Lease::factory()->create(['status' => 'active', 'end_date' => now()->addYear()]);

        $this->assertCount(2, Lease::expiringSoon()->get());
    }

    public function test_is_active()
    {
        $activeLease = Lease::factory()->create(['status' => 'active']);
        $expiredLease = Lease::factory()->expired()->create();

        $this->assertTrue($activeLease->isActive());
        $this->assertFalse($expiredLease->isActive());
    }

    public function test_is_expired()
    {
        $expiredLease = Lease::factory()->expired()->create();
        $activeLease = Lease::factory()->create(['status' => 'active']);

        $this->assertTrue($expiredLease->isExpired());
        $this->assertFalse($activeLease->isExpired());
    }

    public function test_lease_soft_deletes()
    {
        $lease = Lease::factory()->create();
        $lease->delete();
        $this->assertSoftDeleted($lease);
    }
}
