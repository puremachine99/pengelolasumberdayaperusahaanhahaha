<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        Position::insert([
            ['name' => 'Chief Executive Officer', 'level' => 1, 'base_salary' => 15000000],
            ['name' => 'Chief Operating Officer', 'level' => 2, 'base_salary' => 12000000],
            ['name' => 'Director', 'level' => 3, 'base_salary' => 10000000],
            ['name' => 'Division Head', 'level' => 4, 'base_salary' => 8500000],
            ['name' => 'Department Head', 'level' => 5, 'base_salary' => 7000000],
            ['name' => 'Manager', 'level' => 6, 'base_salary' => 6000000],
            ['name' => 'Supervisor', 'level' => 7, 'base_salary' => 5000000],
            ['name' => 'Staff', 'level' => 8, 'base_salary' => 4000000],
            ['name' => 'Intern', 'level' => 9, 'base_salary' => 2000000],
        ]);
    }
}
