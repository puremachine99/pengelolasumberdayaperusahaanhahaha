<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Makanan',
            'Minuman',
            'Cemilan',
            'Dessert',
            'Paket Hemat'
        ];

        foreach ($categories as $name) {
            MenuCategory::firstOrCreate(['name' => $name]);
        }
    }
}
