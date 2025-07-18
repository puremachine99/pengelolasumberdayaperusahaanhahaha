<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'department_id' => Department::inRandomOrder()->first()?->id ?? 1,
            'position_id' => Position::inRandomOrder()->first()?->id ?? 1,
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'hire_date' => $this->faker->date(),
            'resign_date' => null,
            'contract_type' => $this->faker->randomElement(['permanent', 'contract', 'freelance']),
        ];
    }
}
