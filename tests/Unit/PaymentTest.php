<?php

namespace Tests\Unit;

use App\Models\Lease;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_belongs_to_lease()
    {
        $lease = Lease::factory()->create();
        $payment = Payment::factory()->create(['lease_id' => $lease->id]);

        $this->assertInstanceOf(Lease::class, $payment->lease);
        $this->assertTrue($payment->lease->is($lease));
    }

    public function test_overdue_scope()
    {
        Payment::factory()->overdue()->count(2)->create();
        Payment::factory()->paid()->create();

        $this->assertCount(2, Payment::overdue()->get());
    }

    public function test_paid_scope()
    {
        Payment::factory()->paid()->count(3)->create();
        Payment::factory()->create();

        $this->assertCount(3, Payment::paid()->get());
    }

    public function test_mark_as_paid()
    {
        $payment = Payment::factory()->create([
            'amount' => 1500.00,
            'status' => 'pending',
        ]);

        $payment->markAsPaid(1500.00, 'bank_transfer', 'TXN-123');

        $this->assertEquals('paid', $payment->fresh()->status);
        $this->assertEquals(1500.00, $payment->fresh()->paid_amount);
        $this->assertEquals('bank_transfer', $payment->fresh()->payment_method);
        $this->assertEquals('TXN-123', $payment->fresh()->transaction_reference);
        $this->assertEquals(0, $payment->fresh()->balance);
    }

    public function test_mark_as_paid_with_partial_amount()
    {
        $payment = Payment::factory()->create([
            'amount' => 1500.00,
            'status' => 'pending',
        ]);

        $payment->markAsPaid(1000.00, 'cash', 'TXN-456');

        $this->assertEquals('paid', $payment->fresh()->status);
        $this->assertEquals(1000.00, $payment->fresh()->paid_amount);
    }

    public function test_invoice_number_is_unique()
    {
        $payment1 = Payment::factory()->create();
        $this->expectException(\Illuminate\Database\QueryException::class);
        Payment::factory()->create(['invoice_number' => $payment1->invoice_number]);
    }

    public function test_payment_soft_deletes()
    {
        $payment = Payment::factory()->create();
        $payment->delete();
        $this->assertSoftDeleted($payment);
    }
}
