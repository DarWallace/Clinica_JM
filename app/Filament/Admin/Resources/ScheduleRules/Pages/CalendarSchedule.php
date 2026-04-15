<?php

namespace App\Filament\Admin\Resources\ScheduleRules\Pages;


use App\Filament\Admin\Resources\ScheduleRules\ScheduleRuleResource;
use App\Filament\Admin\Resources\ScheduleRules\Widgets\CalendarWidget;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;

class CalendarSchedule extends Page
{
    protected static string $resource = ScheduleRuleResource::class;

    protected string $view = 'filament.admin.resources.schedule-rules.pages.calendar-schedule';

    protected function getHeaderActions(): array
{
    return [
        Action::make('verLista')
            ->label('Ver Tabla')
            ->color('gray')
            ->icon('heroicon-m-list-bullet')
            ->url(ScheduleRuleResource::getUrl('list')),

        Action::make('crearRegla')
            ->label('Nueva Regla')
            ->icon('heroicon-m-plus')
            ->url(ScheduleRuleResource::getUrl('create')),
    ];
}

    protected function getHeaderWidgets(): array
    {
        return [
            CalendarWidget::class,
        ];
    }
}
