<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use Filament\Widgets\PieChartWidget;

class EmployeeStatusChart extends PieChartWidget
{
    protected static ?string $heading = 'Employee Status Breakdown';
    protected static ?int $sort = 0;
    protected function getData(): array
    {
        $active = Employee::where('status', 'active')->count();
        $inactive = Employee::where('status', 'inactive')->count();

        return [
            'labels' => ['Active', 'Inactive'],
            'datasets' => [
                [
                    'data' => [$active, $inactive],
                    'backgroundColor' => ['#10b981', '#ef4444'], // green & red
                ],
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'color' => '#e5e7eb', // gray-200 for dark mode
                        'font' => [
                            'size' => 12,
                        ],
                    ],
                ],
            ],
        ];
    }
}
