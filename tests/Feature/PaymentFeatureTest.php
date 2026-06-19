<?php

namespace Tests\Feature;

use App\Models\Lease;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentFeatureTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Lease $lease;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->lease = Lease::factory()->create(['user_id' => $this->admin->id]);
    }

    public function test_admin_can_view_payments_list()
    {
        Payment::factory()->count(3)->create(['lease_id' => $this->lease->id]);

        $response = $this->actingAs($this->admin)->get(route('payments.index'));
        $response->assertStatus(200);
    }

    public function test_tenant_can_view_payments_list()
    {
        $tenant = User::factory()->tenant()->create();

        $response = $this->actingAs($tenant)->get(route('payments.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_payment()
    {
        $response = $this->actingAs($this->admin)->post(route('payments.store'), [
            'lease_id' => $this->lease->id,
            'tenant_id' => $this->lease->tenant_id,
            'unit_id' => $this->lease->unit_id,
            'amount' => 1500.00,
            'due_date' => now()->addDays(30)->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('payments.index'));
        $this->assertDatabaseHas('payments', ['amount' => 1500.00]);
    }

    public function test_admin_can_mark_payment_as_paid()
    {
        $payment = Payment::factory()->create([
            'lease_id' => $this->lease->id,
            'amount' => 1500.00,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post(route('payments.mark-as-paid', $payment), [
            'paid_amount' => 1500.00,
            'payment_method' => 'bank_transfer',
        ]);

        $response->assertRedirect();
        $this->assertEquals('paid', $payment->fresh()->status);
        $this->assertEquals(1500.00, $payment->fresh()->paid_amount);
    }

    public function test_admin_can_view_payment()
    {
        $payment = Payment::factory()->create(['lease_id' => $this->lease->id]);

        $response = $this->actingAs($this->admin)->get(route('payments.show', $payment));
        $response->assertStatus(200);
    }

    public function test_admin_can_delete_payment()
    {
        $payment = Payment::factory()->create(['lease_id' => $this->lease->id]);

        $response = $this->actingAs($this->admin)->delete(route('payments.destroy', $payment));

        $response->assertRedirect(route('payments.index'));
        $this->assertSoftDeleted($payment);
    }

    public function test_tenant_cannot_create_payment()
    {
        $tenant = User::factory()->tenant()->create();

        $response = $this->actingAs($tenant)->get(route('payments.create'));
        $response->assertForbidden();
    }
}
