<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'code',
        'name',
        'symbol',
        'exchange_rate',
        'is_base',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'exchange_rate' => 'decimal:6',
            'is_base' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public static function getBase(): ?self
    {
        return static::where('is_base', true)->first();
    }

    public static function getActive(): array
    {
        return static::where('is_active', true)->orderBy('sort_order')->get()->all();
    }
}
