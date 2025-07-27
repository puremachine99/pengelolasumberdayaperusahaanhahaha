<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Models\Menu;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Filament\Resources\MenuResource;
use Filament\Resources\Pages\ListRecords;

class ListMenus extends ListRecords
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('Update COGS')
                ->label('Update Semua COGS')
                ->icon('heroicon-o-calculator')
                ->color('warning')
                ->action(function () {
                    Menu::with('recipes.ingredient')->get()->each(
                        fn($menu) => $menu->updateCogs()
                    );
                    Notification::make()
                        ->title('COGS berhasil diperbarui')
                        ->success()
                        ->send();
                })->requiresConfirmation()
                ->modalHeading('Yakin update semua COGS?')
                ->modalDescription('Ini akan menghitung ulang seluruh menu berdasarkan harga bahan saat ini.')
                ->disabled(fn() => Menu::count() === 0)
            ,
        ];
    }
}
