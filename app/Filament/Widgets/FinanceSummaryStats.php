<?php

namespace App\Filament\Widgets;

use App\Models\Salary;
use App\Models\ExpenseClaim;
use App\Models\CompanyExpense;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Carbon;

class FinanceSummaryStats extends BaseWidget
{
    protected static ?int $sort = -1;
    protected function getCards(): array
    {
        $month = now()->format('Y-m');

        return [
            Card::make('Salaries Paid (This Month)', 'Rp ' . number_format(
                Salary::where('month', $month)->sum('net_salary'),
                0,
                ',',
                '.'
            ))
                ->description('Based on net salary')
                ->color('primary')
                ->descriptionIcon('heroicon-o-currency-dollar'),

            Card::make('Approved Reimbursements', 'Rp ' . number_format(
                ExpenseClaim::where('status', 'approved')->whereMonth('date', now()->month)->sum('amount'),
                0,
                ',',
                '.'
            ))
                ->description('Reimburse this month')
                ->color('success')
                ->descriptionIcon('heroicon-o-check-circle'),

            Card::make('Company Expenses', 'Rp ' . number_format(
                CompanyExpense::whereMonth('date', now()->month)->sum('amount'),
                0,
                ',',
                '.'
            ))
                ->description('Operational cost this month')
                ->color('danger')
                ->descriptionIcon('heroicon-o-building-library'),
        ];
    }
}
