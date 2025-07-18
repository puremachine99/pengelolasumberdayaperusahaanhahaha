<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = ['number', 'qr_code_url', 'is_active'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    protected static function booted()
    {
        static::creating(function ($table) {
            $table->qr_code_url = url('/table/' . $table->number);
        });

        static::updating(function ($table) {
            $table->qr_code_url = url('/table/' . $table->number);
        });
    }
}
