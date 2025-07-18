<?php

namespace App\Filament\Resources;

use App\Models\OnboardingTask;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use App\Filament\Resources\OnboardingTaskResource\Pages;

class OnboardingTaskResource extends Resource
{
    protected static ?int $navigationSort = -2;
    protected static ?string $model = OnboardingTask::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationGroup(): string
    {
        return 'Human Resource';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-check-circle';
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

            Textarea::make('task_description')->required(),
            Toggle::make('completed'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('employee.name')->sortable()->searchable(),
            TextColumn::make('task_description')->limit(50),
            IconColumn::make('completed')->boolean(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOnboardingTasks::route('/'),
            'create' => Pages\CreateOnboardingTask::route('/create'),
            'edit' => Pages\EditOnboardingTask::route('/{record}/edit'),
        ];
    }
}
