<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyExpense;
use App\Models\User;
use Illuminate\Support\Carbon;

class CompanyExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $categories = ['Utilities', 'Rent', 'Office Supplies', 'Software', 'Maintenance', 'Internet'];

        foreach (range(1, 20) as $i) {
            CompanyExpense::create([
                'date' => Carbon::now()->subDays(rand(1, 90)),
                'amount' => rand(100_000, 3_000_000),
                'description' => fake()->sentence(),
                'category' => fake()->randomElement($categories),
                'payment_method' => fake()->randomElement(['Cash', 'Transfer', 'Credit Card']),
                'created_by' => $users->random()->id,
            ]);
        }
    }
}
