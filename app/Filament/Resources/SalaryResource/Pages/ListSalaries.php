<?php

namespace App\Filament\Resources\SalaryResource\Pages;

use App\Models\Salary;
use App\Models\Employee;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\SalaryResource;
use Filament\Notifications\Notification;

class ListSalaries extends ListRecords
{
    protected static string $resource = SalaryResource::class;

    /**
     * Tambahkan tombol custom di header halaman "Salaries"
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('Generate Salaries') // Nama aksi
                ->label('Generate Salaries for This Month') // Label di tombol
                ->color('success') // Warna hijau
                ->icon('heroicon-o-plus-circle') // Ikon tambah
                ->requiresConfirmation() // Tampilkan konfirmasi sebelum jalan
                ->action(function () {
                    $month = now()->format('Y-m'); // Ambil bulan sekarang dalam format YYYY-MM

                    // Ambil semua employee aktif beserta posisi mereka
                    $employees = Employee::with('position')
                        ->where('status', 'active')
                        ->get();

                    foreach ($employees as $employee) {
                        // Buat atau update salary untuk bulan ini
                        Salary::updateOrCreate(
                            [
                                'employee_id' => $employee->id,
                                'month' => $month,
                            ],
                            [
                                'base_salary' => $employee->position->base_salary ?? 0,
                                'allowance_total' => 0,
                                'deduction_total' => 0,
                                'net_salary' => $employee->position->base_salary ?? 0,
                            ]
                        );
                    }

                    // Tampilkan notifikasi sukses setelah generate
                    Notification::make()
                        ->title('Salaries generated for ' . $month)
                        ->success()
                        ->send();
                }),
        ];
    }
}
