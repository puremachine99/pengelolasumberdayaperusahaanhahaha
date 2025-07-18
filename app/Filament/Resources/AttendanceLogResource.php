<?php

namespace App\Filament\Resources;

use App\Models\AttendanceLog;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\AttendanceLogResource\Pages;

class AttendanceLogResource extends Resource
{
    protected static ?int $navigationSort = -2;
    protected static ?string $model = AttendanceLog::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Absensi';
    public static function getNavigationGroup(): string
    {
        return 'Human Resource';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-clock';
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

            DatePicker::make('date')->required(),
            TimePicker::make('clock_in'),
            TimePicker::make('clock_out'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('employee.name')->sortable()->searchable(),
            TextColumn::make('date')->date(),
            TextColumn::make('clock_in'),
            TextColumn::make('clock_out'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendanceLogs::route('/'),
            'create' => Pages\CreateAttendanceLog::route('/create'),
            'edit' => Pages\EditAttendanceLog::route('/{record}/edit'),
        ];
    }
}
