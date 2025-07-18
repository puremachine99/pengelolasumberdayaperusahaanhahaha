<?php

namespace App\Filament\Resources;

use App\Models\CompanyExpense;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\CompanyExpenseResource\Pages;

class CompanyExpenseResource extends Resource
{
    protected static ?string $model = CompanyExpense::class;
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form->schema([
            DatePicker::make('date')->required(),
            TextInput::make('amount')->numeric()->prefix('Rp')->required(),
            Textarea::make('description')->rows(3)->required(),
            TextInput::make('category')->required(),
            Select::make('payment_method')
                ->options([
                    'Cash' => 'Cash',
                    'Transfer' => 'Transfer',
                    'Credit Card' => 'Credit Card',
                ])
                ->required(),

            FileUpload::make('receipt')
                ->label('Receipt (optional)')
                ->directory('company-expenses')
                ->preserveFilenames(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')->date(),
                TextColumn::make('amount')->money('IDR', true),
                TextColumn::make('category')->sortable()->searchable(),
                TextColumn::make('payment_method')->label('Method'),
                TextColumn::make('creator.name')->label('Recorded By'),
            ])
            ->filters([
                Tables\Filters\Filter::make('date')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($q, $from) => $q->whereDate('date', '>=', $from))
                            ->when($data['until'], fn($q, $until) => $q->whereDate('date', '<=', $until));
                    }),
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
            'index' => Pages\ListCompanyExpenses::route('/'),
            'create' => Pages\CreateCompanyExpense::route('/create'),
            'edit' => Pages\EditCompanyExpense::route('/{record}/edit'),
        ];
    }
}
