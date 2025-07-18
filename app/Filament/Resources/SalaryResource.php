<?php

namespace App\Filament\Resources;

use App\Models\Salary;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Filament\Resources\SalaryResource\Pages;
use Filament\Tables\Columns\TextColumn;

class SalaryResource extends Resource
{
    protected static ?int $navigationSort = -2;
    protected static ?string $model = Salary::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationGroup(): string
    {
        return 'Finance';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-currency-dollar';
    }

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

            TextInput::make('month')->required()->placeholder('YYYY-MM'),
            TextInput::make('base_salary')->numeric()->required(),
            TextInput::make('allowance_total')->numeric()->default(0),
            TextInput::make('deduction_total')->numeric()->default(0),
            TextInput::make('net_salary')->numeric()->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('employee.name')->sortable()->searchable(),
            TextColumn::make('month'),
            TextColumn::make('net_salary')->money('IDR', true),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSalaries::route('/'),
            'create' => Pages\CreateSalary::route('/create'),
            'edit' => Pages\EditSalary::route('/{record}/edit'),
        ];
    }
}
