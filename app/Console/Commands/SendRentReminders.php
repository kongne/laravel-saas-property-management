<?php

namespace App\Console\Commands;

use App\Models\Lease;
use App\Models\Payment;
use Illuminate\Console\Command;

class SendRentReminders extends Command
{
    protected $signature = 'reminders:rent';
    protected $description = 'Send rent reminders for upcoming and overdue payments';

    public function handle(): void
    {
        $today = now()->day;

        $activeLeases = Lease::with(['tenant.user', 'unit'])
            ->where('status', 'active')
            ->where('due_day', $today)
            ->get();

        foreach ($activeLeases as $lease) {
            $existingPayment = Payment::where('lease_id', $lease->id)
                ->whereMonth('due_date', now()->month)
                ->whereYear('due_date', now()->year)
                ->first();

            if (!$existingPayment) {
                Payment::create([
                    'lease_id' => $lease->id,
                    'tenant_id' => $lease->tenant_id,
                    'unit_id' => $lease->unit_id,
                    'invoice_number' => 'INV-' . strtoupper(uniqid()),
                    'amount' => $lease->rent_amount,
                    'due_date' => now()->day($lease->due_day),
                    'status' => 'pending',
                ]);

                $this->info("Payment created for lease #{$lease->id}");
            }
        }

        Payment::where('due_date', '<', now())
            ->whereIn('status', ['pending', 'partial'])
            ->update(['status' => 'overdue']);

        $this->info('Rent reminders processed successfully.');
    }
}
