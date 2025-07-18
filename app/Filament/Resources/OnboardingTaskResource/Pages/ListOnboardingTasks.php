<?php

namespace App\Filament\Resources\OnboardingTaskResource\Pages;

use App\Filament\Resources\OnboardingTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOnboardingTasks extends ListRecords
{
    protected static string $resource = OnboardingTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
