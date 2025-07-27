<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class PeakHoursTodayChart extends ChartWidget
{
    protected static ?string $heading = 'Jam Sibuk Hari Ini';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $today = Carbon::today();
        $orders = OrderItem::query()
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as total')
            ->whereDate('created_at', $today)

            ->groupByRaw('HOUR(created_at)')
            ->orderBy('hour')
            ->get()
            ->keyBy('hour');

        $labels = [];
        $data = [];

        for ($i = 0; $i < 24; $i++) {
            $labels[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
            $data[] = $orders[$i]->total ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Order',
                    'data' => $data,
                    'backgroundColor' => '#10b981',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // atau 'line' kalau kamu mau
    }
}
