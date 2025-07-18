<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenseClaim;
use App\Models\Employee;
use Carbon\Carbon;

class ExpenseClaimSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();

        foreach ($employees as $employee) {
            ExpenseClaim::create([
                'employee_id' => $employee->id,
                'date' => Carbon::now()->subDays(rand(1, 30)),
                'amount' => rand(50000, 500000),
                'description' => 'Reimbursement for work-related expense',
                'status' => 'pending',
                'notes' => null,
                'attachment' => null,
            ]);
        }
    }
}
