<?php

namespace App\Filament\Resources;

use App\Models\Employee;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\EmployeeResource\Pages;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?int $navigationSort = -2;
    protected static ?string $model = Employee::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationGroup(): string
    {
        return 'Human Resource';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-user-group';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('user_id')
                ->relationship('user', 'name')
                ->required()
                ->searchable()
                ->preload(),

            Select::make('department_id')
                ->relationship('department', 'name')
                ->required()
                ->searchable()
                ->preload(),

            Select::make('position_id')
                ->relationship('position', 'name')
                ->required()
                ->searchable()
                ->preload(),

            TextInput::make('name')->required(),
            TextInput::make('email')->email()->required(),

            Select::make('status')->options([
                'active' => 'Active',
                'inactive' => 'Inactive',
            ])->required(),

            DatePicker::make('hire_date')->required(),
            DatePicker::make('resign_date'),

            Select::make('contract_type')->options([
                'permanent' => 'Permanent',
                'contract' => 'Contract',
                'freelance' => 'Freelance',
            ])->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('email'),
            Tables\Columns\TextColumn::make('department.name'),
            Tables\Columns\TextColumn::make('position.name'),
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn(string $state) => match ($state) {
                    'active' => 'success',
                    'inactive' => 'danger',
                    default => 'gray',
                }),
            Tables\Columns\TextColumn::make('hire_date')->date(),
        ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->label('Status'),

                Tables\Filters\SelectFilter::make('department_id')
                    ->relationship('department', 'name')
                    ->label('Department'),

                Tables\Filters\SelectFilter::make('position_id')
                    ->relationship('position', 'name')
                    ->label('Position'),
            ])

            ->actions([
                Tables\Actions\ViewAction::make(), // Tambah tombol view
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
