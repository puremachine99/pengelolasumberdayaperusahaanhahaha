<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class FinanceDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Finance';
    protected static string $view = 'filament.pages.finance-dashboard';
    protected static ?int $navigationSort = -3;

    
}
