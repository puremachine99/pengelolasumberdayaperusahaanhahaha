<?php

namespace App\Filament\Resources\CompanyExpenseResource\Pages;

use App\Filament\Resources\CompanyExpenseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanyExpenses extends ListRecords
{
    protected static string $resource = CompanyExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
