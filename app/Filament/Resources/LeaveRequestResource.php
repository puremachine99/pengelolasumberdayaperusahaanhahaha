<?php

namespace App\Filament\Resources;

use App\Models\LeaveRequest;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use App\Filament\Resources\LeaveRequestResource\Pages;

class LeaveRequestResource extends Resource
{
    protected static ?int $navigationSort = -2;
    protected static ?string $model = LeaveRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Izin';
    public static function getNavigationGroup(): string
    {
        return 'Human Resource';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-calendar-days';
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

            Select::make('leave_type_id')
                ->relationship('leaveType', 'name')
                ->required()
                ->searchable()
                ->preload(),

            DatePicker::make('start_date')->required(),
            DatePicker::make('end_date')->required(),

            Select::make('status')->options([
                'pending' => 'Pending',
                'approved' => 'Approved',
                'rejected' => 'Rejected',
            ])->default('pending')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('employee.name')->sortable()->searchable(),
            TextColumn::make('leaveType.name')->label('Leave Type'),
            TextColumn::make('start_date')->date(),
            TextColumn::make('end_date')->date(),
            BadgeColumn::make('status')->colors([
                'primary' => 'pending',
                'success' => 'approved',
                'danger' => 'rejected',
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaveRequests::route('/'),
            'create' => Pages\CreateLeaveRequest::route('/create'),
            'edit' => Pages\EditLeaveRequest::route('/{record}/edit'),
        ];
    }
}
