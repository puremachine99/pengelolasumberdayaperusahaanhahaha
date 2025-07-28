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

    protected static function booted()
    {
        static::updated(function ($claim) {
            if ($claim->wasChanged('status') && $claim->status === 'approved') {
                \App\Models\CashTransaction::create([
                    'type' => 'out',
                    'amount' => $claim->amount,
                    'category' => 'Reimburse',
                    'payment_method' => 'Manual',
                    'transaction_date' => $claim->claim_date,
                    'description' => "Klaim dari {$claim->employee->name}",
                    'source' => 'expense_claims',
                    'source_id' => $claim->id,
                ]);
            }
        });
    }

}
