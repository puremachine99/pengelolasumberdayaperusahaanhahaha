<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Menu;
use Filament\Tables;
use App\Models\Promo;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\PromoResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PromoResource\RelationManagers;

class PromoResource extends Resource
{
    protected static ?string $model = Promo::class;
    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?int $navigationSort = 0;

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
        return $form->schema([
            TextInput::make('name')->required()->label('Nama Promo'),

            Select::make('type')
                ->options([
                    'percent' => 'Diskon Persen',
                    'fixed' => 'Diskon Tetap (Rp)',
                    'b1g1' => 'Buy 1 Get 1',
                    'cashback' => 'Cashback',
                    'bonus' => 'Bonus Item',
                ])
                ->required(),


            TextInput::make('value')
                ->numeric()
                ->label('Nilai Promo')
                ->visible(fn($get) => in_array($get('type'), ['percent', 'fixed', 'cashback'])),

            // Rule builder
            Repeater::make('conditions')
                ->label('Kondisi Promo')
                ->schema([
                    Select::make('key')
                        ->options([
                            'menu_id' => 'Menu Utama (untuk B1G1, Diskon)',
                            'bonus_menu_id' => 'Bonus Menu (untuk Bonus)',
                        ])
                        ->required()
                        ->reactive(),

                    Select::make('value')
                        ->options(fn() => Menu::pluck('name', 'id'))
                        ->required()
                        ->label('Menu'),
                ])
                ->columns(2)
                ->collapsed()
                ->addActionLabel('Tambah Rule'),

            DateTimePicker::make('active_from')->label('Mulai Aktif'),
            DateTimePicker::make('active_until')->label('Berakhir'),
            Toggle::make('is_active')->label('Aktif')->default(true),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('value'),
                Tables\Columns\BooleanColumn::make('is_active'),
                Tables\Columns\TextColumn::make('active_from')->dateTime(),
                Tables\Columns\TextColumn::make('active_until')->dateTime(),
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
            'index' => Pages\ListPromos::route('/'),
            'create' => Pages\CreatePromo::route('/create'),
            'edit' => Pages\EditPromo::route('/{record}/edit'),
        ];
    }
}
