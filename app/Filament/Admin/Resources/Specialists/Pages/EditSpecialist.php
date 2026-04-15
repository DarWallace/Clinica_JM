<?php

namespace App\Filament\Admin\Resources\Specialists\Pages;

use App\Filament\Admin\Resources\Specialists\SpecialistResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class EditSpecialist extends EditRecord
{
    protected static string $resource = SpecialistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->record->load('user');

        if ($this->record->user) {
            $data['user'] = [
                'name' => $this->record->user->name,
                'surname' => $this->record->user->surname,
                'email' => $this->record->user->email,
                'phone' => $this->record->user->phone,
            ];
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (isset($data['user'])) {
            $userData = $data['user'];

            if (empty($userData['password'])) {
                unset($userData['password']);
            } else {
                $userData['password'] = Hash::make($userData['password']);
            }

            $record->user->update($userData);
            unset($data['user']);
        }

        $record->update($data);

        return $record;
    }
    protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
}
