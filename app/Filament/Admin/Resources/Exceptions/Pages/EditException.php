<?php

namespace App\Filament\Admin\Resources\Exceptions\Pages;

use App\Filament\Admin\Resources\Exceptions\ExceptionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditException extends EditRecord
{
    protected static string $resource = ExceptionResource::class;

    protected array $specialistIds = [];

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['specialist_ids'] = $this->record->specialists()->pluck('users.id')->all();

        if (($data['specialist_id'] ?? null) && empty($data['specialist_ids'])) {
            $data['specialist_ids'] = [$data['specialist_id']];
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->specialistIds = $data['specialist_ids'] ?? [];

        unset($data['specialist_ids']);

        $data['specialist_id'] = null;

        if (! ($data['applies_to_all'] ?? false) && count($this->specialistIds) === 1) {
            $data['specialist_id'] = $this->specialistIds[0];
        }

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->record->applies_to_all) {
            $this->record->specialists()->sync([]);

            return;
        }

        $this->record->specialists()->sync($this->specialistIds);
    }
    protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
}
