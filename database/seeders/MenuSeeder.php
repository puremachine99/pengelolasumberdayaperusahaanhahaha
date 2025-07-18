<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Recipe;
use App\Models\Ingredient;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            ['name' => 'Nasi Goreng', 'price' => 25000],
            ['name' => 'Ayam Goreng', 'price' => 30000],
            ['name' => 'Telur Dadar', 'price' => 15000],
        ];

        foreach ($menus as $menuData) {
            $menu = Menu::firstOrCreate([
                'name' => $menuData['name'],
            ], [
                'price' => $menuData['price'],
                'is_available' => true,
            ]);

            // Create recipes for each menu
            match ($menu->name) {
                'Nasi Goreng' => $this->createRecipe($menu, [
                    ['name' => 'Nasi', 'quantity' => 200],
                    ['name' => 'Telur', 'quantity' => 1],
                    ['name' => 'Minyak Goreng', 'quantity' => 10],
                    ['name' => 'Bumbu', 'quantity' => 5],
                ]),
                'Ayam Goreng' => $this->createRecipe($menu, [
                    ['name' => 'Ayam', 'quantity' => 250],
                    ['name' => 'Minyak Goreng', 'quantity' => 15],
                    ['name' => 'Bumbu', 'quantity' => 5],
                ]),
                'Telur Dadar' => $this->createRecipe($menu, [
                    ['name' => 'Telur', 'quantity' => 2],
                    ['name' => 'Minyak Goreng', 'quantity' => 10],
                    ['name' => 'Bumbu', 'quantity' => 3],
                ]),
            };
        }
    }

    private function createRecipe($menu, $ingredients)
    {
        foreach ($ingredients as $item) {
            $ingredient = Ingredient::where('name', $item['name'])->first();

            if ($ingredient) {
                Recipe::create([
                    'menu_id' => $menu->id,
                    'ingredient_id' => $ingredient->id,
                    'quantity' => $item['quantity'],
                    'unit' => $ingredient->unit,
                ]);
            }
        }
    }
}
