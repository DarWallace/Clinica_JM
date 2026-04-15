<?php

namespace App\Filament\Admin\Resources\ScheduleRules\Pages;

use App\Filament\Admin\Resources\ScheduleRules\ScheduleRuleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListScheduleRules extends ListRecords
{
    protected static string $resource = ScheduleRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

}
