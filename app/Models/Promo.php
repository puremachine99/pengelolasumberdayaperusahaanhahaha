<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $casts = [
        'conditions' => 'array',
        'active_from' => 'datetime',
        'active_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $fillable = [
        'name',
        'type',
        'value',
        'conditions',
        'active_from',
        'active_until',
        'is_active'
    ];

    public static function activeNow()
    {
        $now = now();
        return static::where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('active_from')->orWhere('active_from', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('active_until')->orWhere('active_until', '>=', $now);
            });
    }
}


