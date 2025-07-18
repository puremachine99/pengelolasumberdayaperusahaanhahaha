<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use Filament\Widgets\ChartWidget;

class ContractTypeChart extends ChartWidget
{
    protected static ?string $heading = 'Contract Type Distribution';
    protected static ?int $sort = 1;
    protected function getData(): array
    {
        $types = ['permanent', 'contract', 'freelance'];

        return [
            'labels' => $types,
            'datasets' => [
                [
                    'data' => array_map(fn($type) => Employee::where('contract_type', $type)->count(), $types),
                    'backgroundColor' => ['#10b981', '#3b82f6', '#f59e0b'], // green, blue, amber
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
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
