<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Filament\Resources\MenuResource\RelationManagers;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MenuResource extends Resource
{
    protected static ?int $navigationSort = -3;
    protected static ?string $model = Menu::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';


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
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\Textarea::make('description'),
                Forms\Components\TextInput::make('price')->numeric()->required(),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->nullable(),
                Forms\Components\FileUpload::make('images')
                    ->label('Foto Menu')
                    ->multiple()
                    ->image()
                    ->imagePreviewHeight('100')
                    ->downloadable()
                    ->reorderable()
                    ->preserveFilenames()
                    ->directory('menus')
                    ->visibility('public'),
                Forms\Components\Toggle::make('is_available')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('description')->searchable(),
                Tables\Columns\TextColumn::make('price')->money('IDR', true),
                Tables\Columns\TextColumn::make('category.name')->label('Category'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('toggleAvailability')
                    ->icon(fn($record) => $record->is_available ? 'heroicon-o-eye' : 'heroicon-o-eye-slash')
                    ->color(fn($record) => $record->is_available ? 'success' : 'gray')
                    ->tooltip(fn($record) => $record->is_available ? 'Tandai sebagai tidak tersedia' : 'Tandai sebagai tersedia')
                    ->label('Tersedia') // Kosongkan label biar cuma icon
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Perubahan Status')
                    ->modalSubheading(fn($record) =>
                        $record->is_available
                        ? "Menu \"{$record->name}\" akan disembunyikan?"
                        : "Menu \"{$record->name}\" akan ditampilkan?")
                    ->modalButton('Ya, Lanjutkan')
                    ->action(function ($record) {
                        $record->is_available = !$record->is_available;
                        $record->save();
                    }),
                Tables\Actions\EditAction::make(),
            ])


            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    BulkAction::make('toggleAvailability')
                        ->label('Ubah Status Tersedia')
                        ->icon('heroicon-o-eye')
                        ->color('info')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi Ubah Status')
                        ->modalSubheading('Yakin ingin mengubah status ketersediaan menu yang dipilih?')
                        ->modalButton('Ya, Ubah')
                        ->action(function (\Illuminate\Support\Collection $records) {
                            foreach ($records as $record) {
                                $record->update([
                                    'is_available' => !$record->is_available,
                                ]);
                            }
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['images']) && is_array($data['images'])) {
            $data['images'] = array_values($data['images']); // Buat jadi array numerik biasa
        }

        return $data;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
