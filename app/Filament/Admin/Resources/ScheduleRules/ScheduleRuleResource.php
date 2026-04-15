<?php

namespace App\Filament\Admin\Resources\ScheduleRules;

use App\Filament\Admin\Resources\ScheduleRules\Pages\CreateScheduleRule;
use App\Filament\Admin\Resources\ScheduleRules\Pages\CalendarSchedule;
use App\Filament\Admin\Resources\ScheduleRules\Pages\EditScheduleRule;
use App\Filament\Admin\Resources\ScheduleRules\Pages\ListScheduleRules;
use App\Filament\Admin\Resources\ScheduleRules\Schemas\ScheduleRuleForm;
use App\Filament\Admin\Resources\ScheduleRules\Tables\ScheduleRulesTable;
use App\Models\ScheduleRule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;


class ScheduleRuleResource extends Resource
{
    protected static ?string $model = ScheduleRule::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';


    protected static ?string $navigationLabel = 'Reglas de Horario';

    //protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ScheduleRuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ScheduleRulesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            \App\Filament\Admin\Resources\ScheduleRules\Widgets\CalendarWidget::class,
        ];
    }

    public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}

    public static function getPages(): array
    {
        return [
            'index' => CalendarSchedule::route('/'),
            'list' => ListScheduleRules::route('/list'),
            'create' => CreateScheduleRule::route('/create'),
            'edit' => EditScheduleRule::route('/{record}/edit'),
        ];
    }
}
