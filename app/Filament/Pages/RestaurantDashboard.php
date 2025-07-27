<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class RestaurantDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort = -3;
    protected static string $view = 'filament.pages.restaurant-dashboard';

    protected static ?string $navigationLabel = 'Dashboard Restoran';
    protected static ?string $navigationGroup = 'Restaurant'; // opsional

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
    
}
