<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Department;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $positions = Position::pluck('id', 'name');
        $departments = Department::all();

        $inactiveCounter = 0;

        // Step 1: Setiap departemen punya minimal 1 Staff
        foreach ($departments as $department) {
            $user = User::factory()->create();

            Employee::factory()->create([
                'user_id' => $user->id,
                'department_id' => $department->id,
                'position_id' => $positions['Staff'],
                'name' => $user->name,
                'email' => $user->email,
                'status' => $inactiveCounter < 3 ? 'inactive' : 'active',
            ]);

            $inactiveCounter++;
        }

        // Step 2: 1 orang per jabatan unik
        $uniquePositions = [
            'Chief Executive Officer',
            'Chief Operating Officer',
            'Director',
            'Division Head',
            'Department Head',
            'Manager',
            'Supervisor',
            'Intern',
        ];

        foreach ($uniquePositions as $posName) {
            $user = User::factory()->create();

            Employee::factory()->create([
                'user_id' => $user->id,
                'position_id' => $positions[$posName],
                'department_id' => $departments->random()->id,
                'name' => $user->name,
                'email' => $user->email,
                'status' => $inactiveCounter < 3 ? 'inactive' : 'active',
            ]);

            $inactiveCounter++;
        }

        // Step 3: Tambahan staff acak di 3 departemen
        foreach ($departments->random(3) as $extraDept) {
            for ($i = 0; $i < 3; $i++) {
                $user = User::factory()->create();

                Employee::factory()->create([
                    'user_id' => $user->id,
                    'position_id' => $positions['Staff'],
                    'department_id' => $extraDept->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => $inactiveCounter < 3 ? 'inactive' : 'active',
                ]);

                $inactiveCounter++;
            }
        }
    }
}
