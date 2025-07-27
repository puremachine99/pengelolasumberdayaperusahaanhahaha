<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Menu;
use Filament\Tables;
use App\Models\Order;
use App\Models\Promo;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
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
                Select::make('table_id')
                    ->relationship('table', 'id')
                    ->label('Meja')
                    ->required(),

                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->label('Pelanggan')
                    ->required(),

                TextInput::make('total_price')
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Total Harga')
                    ->required(),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'Diproses',
                        'completed' => 'Selesai',
                        'cancelled' => 'Batal',
                    ])
                    ->label('Status')
                    ->required(),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),
                TextColumn::make('table_id')->label('Meja'),
                TextColumn::make('customer.name')->label('Pelanggan'),
                TextColumn::make('total_price')
                    ->label('Total')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'primary' => 'pending',
                        'warning' => 'in_progress',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('lihatDetail')
                    ->label('Lihat Detail')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->modalHeading('Detail Order')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->form([
                        Textarea::make('invoice')
                            ->label('Pesanan')
                            ->disabled()
                            ->rows(30)
                            ->default(fn($record) => static::generateInvoiceText($record)),
                    ])
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
    protected static function generateInvoiceText($order): string
    {
        $orderDate = $order->created_at ?? now();

        $lines = [
            "ID Order: {$order->id}",
            "Tanggal: " . $orderDate->format('Y-m-d H:i'),
            "Meja: " . ($order->table->name ?? '-'),
            "Pelanggan: " . ($order->customer->name ?? '-'),
            "Total Harga: Rp" . number_format($order->total_price, 0, ',', '.'),
            "Status: " . ucfirst($order->status),
            "",
            "Item Pemesanan:",
        ];

        $activePromos = Promo::where('is_active', true)
            ->where('active_from', '<=', $orderDate)
            ->where('active_until', '>=', $orderDate)
            ->get();

        $discounts = [];
        $bonusItems = [];

        foreach ($order->items as $item) {
            $menuName = $item->menu->name;
            $qty = $item->quantity;
            $price = $item->menu->price;
            $subtotal = $qty * $price;
            $line = "- {$menuName} x {$qty}";

            foreach ($activePromos as $promo) {
                $conditions = is_string($promo->conditions)
                    ? collect(json_decode($promo->conditions, true))
                    : collect($promo->conditions);

                // Percent discount
                if (
                    $promo->type === 'percent' &&
                    $conditions->contains('key', 'menu_id') &&
                    $conditions->firstWhere('key', 'menu_id')['value'] == $item->menu_id
                ) {
                    $disc = ($promo->value / 100) * $subtotal;
                    $discounts[] = [$promo->name, $disc];
                    $line .= " (Diskon: -Rp" . number_format($disc, 0, ',', '.') . ")";
                }

                // Fixed amount discount
                if (
                    $promo->type === 'fixed' &&
                    $conditions->contains('key', 'menu_id') &&
                    $conditions->firstWhere('key', 'menu_id')['value'] == $item->menu_id
                ) {
                    $disc = $promo->value * $qty;
                    $discounts[] = [$promo->name, $disc];
                    $line .= " (Diskon: -Rp" . number_format($disc, 0, ',', '.') . ")";
                }

                // Buy 1 Get 1
                if (
                    $promo->type === 'b1g1' &&
                    $conditions->contains('key', 'menu_id') &&
                    $conditions->firstWhere('key', 'menu_id')['value'] == $item->menu_id
                ) {
                    $freeQty = floor($qty / 2);
                    $disc = $freeQty * $price;
                    $discounts[] = [$promo->name, $disc];
                    $line .= " (B1G1, Gratis {$freeQty})";
                }

                // Bonus item
                if (
                    $promo->type === 'bonus' &&
                    $conditions->contains('key', 'menu_id') &&
                    $conditions->firstWhere('key', 'menu_id')['value'] == $item->menu_id
                ) {
                    $bonusMenuId = $conditions->firstWhere('key', 'bonus_menu_id')['value'] ?? null;
                    if ($bonusMenuId) {
                        $bonusMenu = Menu::find($bonusMenuId);
                        if ($bonusMenu) {
                            $bonusItems[] = "- BONUS: {$bonusMenu->name} (dari beli {$menuName})";
                        }
                    }
                }
            }

            $lines[] = $line;
        }

        // Tambahkan bonus
        if (!empty($bonusItems)) {
            $lines[] = "";
            $lines[] = "Bonus Item:";
            foreach ($bonusItems as $bonus) {
                $lines[] = $bonus;
            }
        }

        // Cashback
        foreach ($activePromos->where('type', 'cashback') as $promo) {
            $conditions = is_string($promo->conditions)
                ? collect(json_decode($promo->conditions, true))
                : collect($promo->conditions);

            $minTotal = $conditions->firstWhere('key', 'min_total')['value'] ?? null;
            if ($minTotal && $order->total_price >= $minTotal) {
                $discounts[] = [$promo->name, $promo->value];
            }
        }

        // Ringkasan diskon
        if (!empty($discounts)) {
            $lines[] = "";
            $lines[] = "Diskon Diterapkan:";
            foreach ($discounts as [$name, $amount]) {
                $lines[] = "- {$name}: -Rp" . number_format($amount, 0, ',', '.');
            }
        }

        return implode("\n", $lines);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
