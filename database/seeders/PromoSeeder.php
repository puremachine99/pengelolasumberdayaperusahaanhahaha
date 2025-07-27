<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    public function run(): void
    {
        // Percent discount: 20% off Espresso
        Promo::create([
            'name' => 'Diskon Espresso 20%',
            'type' => 'percent',
            'value' => 20,
            'conditions' => [
                ['key' => 'menu_id', 'value' => '4'], // Espresso
            ],
            'active_from' => now()->subDay(),
            'active_until' => now()->addDays(7),
            'is_active' => true,
        ]);

        // Fixed discount: Potongan Rp5000 untuk Ayam Goreng
        Promo::create([
            'name' => 'Hemat 5rb Ayam Goreng',
            'type' => 'fixed',
            'value' => 5000,
            'conditions' => [
                ['key' => 'menu_id', 'value' => '2'], // Ayam Goreng
            ],
            'active_from' => now(),
            'active_until' => now()->addWeek(),
            'is_active' => true,
        ]);

        // Buy 1 Get 1 for Telur Dadar
        Promo::create([
            'name' => 'Telur Dadar Beli 1 Gratis 1',
            'type' => 'b1g1',
            'value' => null,
            'conditions' => [
                ['key' => 'menu_id', 'value' => '3'], // Telur Dadar
            ],
            'active_from' => now(),
            'active_until' => now()->addWeek(),
            'is_active' => true,
        ]);

        // Bonus: Beli Nasi Goreng dapat gratis Espresso
        Promo::create([
            'name' => 'Nasi Goreng Bonus Espresso',
            'type' => 'bonus',
            'value' => null,
            'conditions' => [
                ['key' => 'menu_id', 'value' => '1'], // Nasi Goreng
                ['key' => 'bonus_menu_id', 'value' => '4'], // Espresso
            ],
            'active_from' => now(),
            'active_until' => now()->addWeek(),
            'is_active' => true,
        ]);

        // Cashback: Pesan minimal 80rb dapat cashback 10rb
        Promo::create([
            'name' => 'Cashback 10rb Minimal 80rb',
            'type' => 'cashback',
            'value' => 10000,
            'conditions' => [
                ['key' => 'min_total', 'value' => 80000],
            ],
            'active_from' => now(),
            'active_until' => now()->addWeek(),
            'is_active' => true,
        ]);
    }
}
