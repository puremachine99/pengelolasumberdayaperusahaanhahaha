<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
    ];

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class, 'account_id');
    }
}
