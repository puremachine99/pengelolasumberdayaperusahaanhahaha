<?php

namespace App\Filament\Widgets;

use App\Models\Salary;
use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Support\Colors\Color;
class EmployeeMovementStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected function getCards(): array
    {
        $month = now()->month;
        $year = now()->year;

        return [
            Card::make('New Hires (This Month)', Employee::whereMonth('hire_date', $month)->whereYear('hire_date', $year)->count())
                ->description('Joined this month')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('success'),
            Card::make('Resigned (This Month)', Employee::whereMonth('resign_date', $month)->whereYear('resign_date', $year)->count())
                ->description('Resigned this month')
                ->descriptionIcon('heroicon-o-arrow-trending-down')
                ->color('danger'),
            Card::make('Total Employees', Employee::count())
                ->description('All statuses')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('primary'),
            Card::make('Total Salary Paid', number_format(Salary::sum('net_salary'), 0, ',', '.'))
                ->description('Sum of all net salaries')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color(Color::Indigo),

        ];
    }
}
