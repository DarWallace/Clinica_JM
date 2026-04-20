<?php

namespace App\Filament\Admin\Resources\ScheduleRules\Pages;

use App\Filament\Admin\Resources\ScheduleRules\ScheduleRuleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditScheduleRule extends EditRecord
{
    protected static string $resource = ScheduleRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
protected function mutateFormDataBeforeSave(array $data): array
{
    unset($data['day_of_week']);

    return $data;
}
}
