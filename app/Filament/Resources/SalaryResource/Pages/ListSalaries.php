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
            Action::make('Generate Salaries')
                ->action(function () {
                    $month = now()->format('Y-m');
                    $total = 0;

                    $employees = Employee::with('position')->where('status', 'active')->get();

                    foreach ($employees as $employee) {
                        $salary = Salary::updateOrCreate(
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

                        $total += $salary->net_salary;
                    }

                    // Catat 1 transaksi kas untuk total gaji bulan ini
                    \App\Models\CashTransaction::create([
                        'type' => 'out',
                        'amount' => $total,
                        'category' => 'Gaji',
                        'transaction_date' => now()->startOfDay(),
                        'payment_method' => 'Transfer',
                        'description' => 'Total Gaji Bulan ' . now()->format('F Y'),
                        'source' => 'salaries',
                        'source_id' => null, // opsional, atau bisa pakai 'meta' JSON
                    ]);

                    Notification::make()
                        ->title("Salaries generated for $month")
                        ->success()
                        ->send();
                })

        ];
    }
}
