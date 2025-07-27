<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\OrderItem;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrderItemResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderItemResource\RelationManagers;

class OrderItemResource extends Resource
{
    protected static ?string $model = OrderItem::class;
    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';
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
        return $form
            ->schema([
                Select::make('order_id')
                    ->relationship('order', 'id') // Bisa diganti jadi kode atau timestamp order jika ada
                    ->required()
                    ->label('Order'),

                Select::make('menu_id')
                    ->relationship('menu', 'name') // pastikan menu punya field `name`
                    ->required()
                    ->label('Menu'),

                TextInput::make('quantity')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->label('Jumlah'),

                TextInput::make('price')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->label('Harga Satuan'),

                Select::make('type')
                    ->options([
                        'dine_in' => 'Dine In',
                        'takeaway' => 'Takeaway',
                        'delivery' => 'Delivery',
                    ])
                    ->label('Tipe')
                    ->required(),

                TextInput::make('discount_value')
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Diskon'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order.table_id')->label('Meja')->sortable(),
                TextColumn::make('order.id')->label('Order')->sortable(),
                TextColumn::make('menu.name')->label('Menu')->sortable(),
                TextColumn::make('quantity')->label('Qty')->sortable(),
                TextColumn::make('price')
                    ->money('IDR', locale: 'id')
                    ->label('Harga'),
                TextColumn::make('discount_value')
                    ->money('IDR', locale: 'id')
                    ->label('Diskon'),
                TextColumn::make('total')
                    ->label('Total')
                    ->formatStateUsing(function ($record) {
                        $total = ($record->price * $record->quantity) - $record->discount_value;
                        return number_format($total, 0, ',', '.');
                    })
                    ->suffix(' Rp'),
                BadgeColumn::make('type')
                    ->label('Tipe')
                    ->colors([
                        'primary' => 'dine_in',
                        'warning' => 'takeaway',
                        'success' => 'delivery',
                    ]),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListOrderItems::route('/'),
            'create' => Pages\CreateOrderItem::route('/create'),
            'edit' => Pages\EditOrderItem::route('/{record}/edit'),
        ];
    }
}
