<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopMenusChart extends ChartWidget
{
    protected static ?string $heading = 'Top 5 Menu Terlaris';
    protected static ?int $sort = 3; // urutan di dashboard

    protected function getData(): array
    {
        $topMenus = OrderItem::select('menu_id', DB::raw('SUM(quantity) as total'))
            ->groupBy('menu_id')
            ->orderByDesc('total')
            ->with('menu')
            ->limit(5)
            ->get();

        return [
            'labels' => $topMenus->pluck('menu.name')->toArray(),
            'datasets' => [
                [
                    'label' => 'Jumlah Terjual',
                    'data' => $topMenus->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#6366F1', '#10B981', '#F59E0B', '#EF4444', '#3B82F6',
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // bisa diganti 'pie' atau 'doughnut' jika mau
    }
}
