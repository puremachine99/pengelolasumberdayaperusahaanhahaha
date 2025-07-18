<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            \App\Models\Table::firstOrCreate([
                'number' => $i,
            ], [
                'qr_code_url' => url('/order/' . $i),
                'is_active' => true,
            ]);
        }
    }
}
