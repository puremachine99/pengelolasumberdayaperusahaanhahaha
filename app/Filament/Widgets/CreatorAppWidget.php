<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class CreatorAppWidget extends Widget
{
    protected static string $view = 'filament.widgets.creator-app-widget';
    // protected int|string|array $columnSpan = 'full'; // full width
    protected static ?int $sort = -2;
    protected static ?string $title = 'Copyright © Nopel';
}
