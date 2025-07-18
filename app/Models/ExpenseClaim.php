<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseClaim extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'amount',
        'description',
        'status',
        'approved_by',
        'approved_at',
        'notes',
        'attachment',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
