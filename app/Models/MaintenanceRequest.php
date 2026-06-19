<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'unit_id',
        'tenant_id',
        'user_id',
        'title',
        'description',
        'category',
        'priority',
        'status',
        'requested_date',
        'resolved_date',
        'cost',
        'assigned_to',
        'images',
        'resolution_notes',
    ];

    protected $casts = [
        'images' => 'array',
        'cost' => 'decimal:2',
        'requested_date' => 'date',
        'resolved_date' => 'date',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress']);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function isOpen(): bool
    {
        return in_array($this->status, ['open', 'in_progress']);
    }

    public function isResolved(): bool
    {
        return in_array($this->status, ['resolved', 'closed']);
    }

    public function resolve($notes = null, $cost = null): void
    {
        $this->update([
            'status' => 'resolved',
            'resolved_date' => now(),
            'resolution_notes' => $notes ?? $this->resolution_notes,
            'cost' => $cost ?? $this->cost,
        ]);
    }
}
