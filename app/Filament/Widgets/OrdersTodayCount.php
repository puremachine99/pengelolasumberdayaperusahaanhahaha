<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class OrdersTodayCount extends BaseWidget
{
    protected function getCards(): array
    {
        $today = now()->startOfDay();
        $totalRevenue = Order::whereBetween('created_at', [$today, now()])
            ->sum('total_price');

        $orderCount = Order::whereBetween('created_at', [$today, now()])
            ->count();

        $totalItems = Order::whereBetween('created_at', [$today, now()])
            ->with('items')
            ->get()
            ->flatMap->items
            ->sum('quantity');

        return [
            Card::make('Pendapatan Hari Ini', 'Rp' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Total dari semua pesanan hari ini')
                ->color('success')
                ->icon('heroicon-o-currency-dollar'),

            Card::make('Order Hari Ini', $orderCount)
                ->description('Total semua order masuk')
                ->descriptionIcon('heroicon-o-receipt-refund')
                ->color('primary'),

            Card::make('Menu Terjual Hari Ini', $totalItems)
                ->description('Total semua item disajikan')
                ->descriptionIcon('heroicon-o-fire')
                ->color('warning'),
        ];
    }

    public static function canView(): bool
    {
        return request()->routeIs('filament.admin.pages.restaurant-dashboard');
    }
}
