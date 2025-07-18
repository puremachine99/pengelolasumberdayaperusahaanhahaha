<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('leave_types')->insert([
            [
                'name' => 'Cuti Tahunan',
                'quota_per_year' => 12,
                'is_paid_leave' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Cuti Sakit',
                'quota_per_year' => 0, // Tidak dibatasi kuota tahunan
                'is_paid_leave' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Izin Menikah',
                'quota_per_year' => 3,
                'is_paid_leave' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Cuti Melahirkan',
                'quota_per_year' => 90,
                'is_paid_leave' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Cuti Duka (Keluarga)',
                'quota_per_year' => 2,
                'is_paid_leave' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Tanpa Keterangan',
                'quota_per_year' => 0,
                'is_paid_leave' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
