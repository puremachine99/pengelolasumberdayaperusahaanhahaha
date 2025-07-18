<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class EmployeeDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Human Resource';
    protected static ?string $title = 'Employee Dashboard';
    protected static ?int $navigationSort = -5;
    protected static string $view = 'filament.pages.employee-dashboard';
}