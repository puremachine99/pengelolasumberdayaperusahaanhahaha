<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use Filament\Widgets\ChartWidget;

class TopDepartmentsChart extends ChartWidget
{
    protected static ?string $heading = 'Top Departments by Headcount';
protected static ?int $sort = 9;
    protected function getData(): array
    {
        $top = Department::withCount('employees')
            ->orderByDesc('employees_count')
            ->take(5)
            ->get();

        return [
            'labels' => $top->pluck('name')->toArray(),
            'datasets' => [
                [
                    'label' => 'Employees',
                    'data' => $top->pluck('employees_count')->toArray(),
                    'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444'],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'labels' => [
                        'color' => '#e5e7eb', // Light color for dark mode
                        'font' => [
                            'size' => 12,
                        ],
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'ticks' => [
                        'color' => '#d1d5db', // gray-300
                        'precision' => 0,
                    ],
                ],
                'x' => [
                    'ticks' => [
                        'color' => '#d1d5db',
                    ],
                ],
            ],
        ];
    }
}
