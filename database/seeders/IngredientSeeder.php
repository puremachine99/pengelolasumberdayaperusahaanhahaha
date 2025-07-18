<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = [
            ['name' => 'Nasi', 'unit' => 'gram', 'cost_per_unit' => 0.05, 'stock' => 10000],
            ['name' => 'Telur', 'unit' => 'pcs', 'cost_per_unit' => 2000, 'stock' => 100],
            ['name' => 'Minyak Goreng', 'unit' => 'ml', 'cost_per_unit' => 0.02, 'stock' => 5000],
            ['name' => 'Ayam', 'unit' => 'gram', 'cost_per_unit' => 0.08, 'stock' => 3000],
            ['name' => 'Bumbu', 'unit' => 'gram', 'cost_per_unit' => 0.10, 'stock' => 1000],
        ];

        foreach ($ingredients as $item) {
            Ingredient::firstOrCreate(['name' => $item['name']], $item);
        }
    }
}
