<?php

namespace App\Filament\Admin\Resources\Exceptions\Pages;

use App\Filament\Admin\Resources\Exceptions\ExceptionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateException extends CreateRecord
{
    protected static string $resource = ExceptionResource::class;

    protected array $specialistIds = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->specialistIds = $data['specialist_ids'] ?? [];

        unset($data['specialist_ids']);

        $data['specialist_id'] = null;

        if (! ($data['applies_to_all'] ?? false) && count($this->specialistIds) === 1) {
            $data['specialist_id'] = $this->specialistIds[0];
        }

        return $data;
    }

    protected function afterCreate(): void
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
