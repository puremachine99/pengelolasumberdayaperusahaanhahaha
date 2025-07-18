<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyExpense extends Model
{
    protected $fillable = [
        'date',
        'amount',
        'description',
        'category',
        'payment_method',
        'receipt',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
