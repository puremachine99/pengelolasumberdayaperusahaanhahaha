<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Journal;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ChartOfAccount;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\JournalResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\JournalResource\RelationManagers;

class JournalResource extends Resource
{
    protected static ?string $model = Journal::class;
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                DatePicker::make('journal_date')
                    ->label('Tanggal Jurnal')
                    ->required(),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(2),

                Repeater::make('entries')
                    ->relationship()
                    ->label('Entri Debit / Kredit')
                    ->schema([
                        Select::make('account_id')
                            ->label('Akun')
                            ->required()
                            ->options(ChartOfAccount::all()->pluck('name', 'id')),

                        TextInput::make('debit')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->required(),

                        TextInput::make('credit')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(3)
                    ->minItems(2)
                    ->createItemButtonLabel('Tambah Entri')
                    ->required(),
            ])
            ->columns(1)
            ->columns([
                'sm' => 1,
                'lg' => 1,
            ])
        ;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('journal_date')->date(),
                TextColumn::make('description')->limit(40),
                TextColumn::make('entries_sum_debit')->label('Total Debit')->money('IDR'),
                TextColumn::make('entries_sum_credit')->label('Total Kredit')->money('IDR'),
            ])
            ->defaultSort('journal_date', 'desc')
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
    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $entries = $data['entries'] ?? [];

        $totalDebit = collect($entries)->sum('debit');
        $totalCredit = collect($entries)->sum('credit');

        if ($totalDebit !== $totalCredit) {
            throw ValidationException::withMessages([
                'entries' => ['Total debit dan kredit harus balance.'],
            ]);
        }

        return $data;
    }
    public static function mutateFormDataBeforeSave(array $data): array
    {
        $entries = $data['entries'] ?? [];

        $totalDebit = collect($entries)->sum('debit');
        $totalCredit = collect($entries)->sum('credit');

        if ($totalDebit !== $totalCredit) {
            throw ValidationException::withMessages([
                'entries' => ['Total debit dan kredit harus balance.'],
            ]);
        }

        return $data;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJournals::route('/'),
            'create' => Pages\CreateJournal::route('/create'),
            'edit' => Pages\EditJournal::route('/{record}/edit'),
        ];
    }
}
