<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lease_id',
        'tenant_id',
        'unit_id',
        'invoice_number',
        'amount',
        'paid_amount',
        'late_fee',
        'balance',
        'due_date',
        'paid_date',
        'status',
        'payment_method',
        'mobile_money_number',
        'transaction_reference',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'balance' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    public function lease()
    {
        return $this->belongsTo(Lease::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function scopeOverdue($query)
    {
        return $query->whereIn('status', ['pending', 'partial'])
            ->where('due_date', '<', now());
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function markAsPaid($paidAmount = null, $method = null, $reference = null): void
    {
        $this->update([
            'paid_amount' => $paidAmount ?? $this->amount,
            'paid_date' => now(),
            'payment_method' => $method ?? $this->payment_method,
            'transaction_reference' => $reference ?? $this->transaction_reference,
            'balance' => 0,
            'status' => 'paid',
        ]);
    }
}
