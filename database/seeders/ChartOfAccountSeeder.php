<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChartOfAccount;

class ChartOfAccountSeeder extends Seeder
{
    public function run(): void
    {
        ChartOfAccount::query()->delete();

        ChartOfAccount::insert([
            [
                'code' => '1101',
                'name' => 'Kas Tunai',
                'type' => 'asset',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '1102',
                'name' => 'Bank',
                'type' => 'asset',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '4100',
                'name' => 'Pendapatan Penjualan',
                'type' => 'revenue',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '5100',
                'name' => 'Gaji Karyawan',
                'type' => 'expense',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '5200',
                'name' => 'Pengeluaran Umum',
                'type' => 'expense',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
