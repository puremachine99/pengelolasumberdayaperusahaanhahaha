<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DepartmentSeeder::class,
            PositionSeeder::class,
            EmployeeSeeder::class,
            LeaveTypeSeeder::class,
            ExpenseClaimSeeder::class,
            CompanyExpenseSeeder::class,
            MenuCategorySeeder::class,
            IngredientSeeder::class,
            MenuSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Nopel',
            'email' => 'n@gmail.com',
            'password' => bcrypt('qQ123123'), // Ensure to hash the password
        ]);
    }
}
