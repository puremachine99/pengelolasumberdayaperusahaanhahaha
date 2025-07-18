<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveType extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }
}