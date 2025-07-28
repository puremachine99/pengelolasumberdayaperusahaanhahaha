<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashTransaction extends Model
{
    protected $fillable = [
        'type',
        'amount',
        'category',
        'payment_method',
        'transaction_date',
        'description',
        'source',
        'source_id',
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];
}
