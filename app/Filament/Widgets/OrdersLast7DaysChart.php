<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class OrdersLast7DaysChart extends ChartWidget
{
    protected static ?string $heading = 'Order 7 Hari Terakhir';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $start = now()->copy()->subDays(6)->startOfDay();
        $end = now()->copy()->endOfDay();

        // Query dengan group by DATE (MySQL compatible)
        $orders = Order::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$start, $end])
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('DATE(created_at)')
            ->get()
            ->keyBy('date');

        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->copy()->subDays($i)->toDateString();
            $labels[] = now()->copy()->subDays($i)->format('d M'); // ex: 27 Jul
            $data[] = $orders[$date]->total ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Order',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.7)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Atau 'line'
    }
}
