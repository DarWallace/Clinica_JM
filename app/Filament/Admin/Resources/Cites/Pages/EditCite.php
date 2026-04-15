<?php

namespace App\Filament\Admin\Resources\Cites\Pages;

use App\Filament\Admin\Resources\Cites\CiteResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCite extends EditRecord
{
    protected static string $resource = CiteResource::class;

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

    // Opcional: Si quieres que el mensaje de "Guardado" sea personalizado
    protected function getSavedNotificationTitle(): ?string
    {
        return 'Cita reservada correctamente';
    }

}
