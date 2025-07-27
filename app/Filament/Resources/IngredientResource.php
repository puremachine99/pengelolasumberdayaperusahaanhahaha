<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Ingredient;
use Filament\Tables\Table;
use Forms\Components\TextInput;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\IngredientResource\Pages;
use App\Filament\Resources\IngredientResource\RelationManagers;

class IngredientResource extends Resource
{
    protected static ?string $model = Ingredient::class;
    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?int $navigationSort = -1;

    public static function getNavigationGroup(): string
    {
        return 'Restaurant';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
   public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama Bahan')
                ->required(),
            Forms\Components\TextInput::make('unit')
                ->label('Satuan (misalnya: gram, liter)')
                ->required(),
            Forms\Components\TextInput::make('cost_per_unit')
                ->label('Harga per Satuan')
                ->prefix('Rp')
                ->numeric()
                ->required(),
            Forms\Components\TextInput::make('stock')
                ->label('Stok Tersedia')
                ->numeric()
                ->required(),
        ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('unit'),
                Tables\Columns\TextColumn::make('cost_per_unit')->money('IDR', true),
                Tables\Columns\TextColumn::make('stock'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIngredients::route('/'),
            'create' => Pages\CreateIngredient::route('/create'),
            'edit' => Pages\EditIngredient::route('/{record}/edit'),
        ];
    }
}
