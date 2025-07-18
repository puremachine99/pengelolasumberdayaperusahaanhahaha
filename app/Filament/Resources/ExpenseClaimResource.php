<?php

namespace App\Filament\Resources;

use App\Models\ExpenseClaim;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\ExpenseClaimResource\Pages;

class ExpenseClaimResource extends Resource
{
    protected static ?string $model = ExpenseClaim::class;
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('employee_id')
                ->relationship('employee', 'name')
                ->required()
                ->searchable()
                ->preload(),

            DatePicker::make('date')
                ->required(),

            TextInput::make('amount')
                ->numeric()
                ->prefix('Rp')
                ->required(),

            Textarea::make('description')
                ->rows(3)
                ->required(),

            Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ])
                ->disabledOn('create') // hanya edit
                ->default('pending'),

            FileUpload::make('attachment')
                ->label('Receipt (optional)')
                ->directory('expense-claims')
                ->preserveFilenames(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('employee.name')->sortable()->searchable(),
            TextColumn::make('date')->date(),
            TextColumn::make('amount')->money('IDR', true),
            TextColumn::make('status')
                ->badge()
                ->color(fn($state) => match ($state) {
                    'approved' => 'success',
                    'pending' => 'warning',
                    'rejected' => 'danger',
                }),
        ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpenseClaims::route('/'),
            'create' => Pages\CreateExpenseClaim::route('/create'),
            'edit' => Pages\EditExpenseClaim::route('/{record}/edit'),
        ];
    }
}
