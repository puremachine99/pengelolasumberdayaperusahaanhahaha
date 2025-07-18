<?php

namespace App\Filament\Resources;

use App\Models\LeaveType;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use App\Filament\Resources\LeaveTypeResource\Pages;

class LeaveTypeResource extends Resource
{
    protected static ?int $navigationSort = -2;
    protected static ?string $model = LeaveType::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Jenis Izin';
    public static function getNavigationGroup(): string
    {
        return 'Human Resource';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-list-bullet';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->required(),
            TextInput::make('quota_per_year')->numeric()->required(),
            Toggle::make('is_paid_leave'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->sortable()->searchable(),
            TextColumn::make('quota_per_year'),
            IconColumn::make('is_paid_leave')->boolean(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaveTypes::route('/'),
            'create' => Pages\CreateLeaveType::route('/create'),
            'edit' => Pages\EditLeaveType::route('/{record}/edit'),
        ];
    }
}
