<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OnboardingTask extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}