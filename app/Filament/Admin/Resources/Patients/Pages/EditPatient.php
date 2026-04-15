<?php

namespace App\Filament\Admin\Resources\Patients\Pages;

use App\Filament\Admin\Resources\Patients\PatientResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class EditPatient extends EditRecord
{
    protected static string $resource = PatientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * PASO 1: Cargar los datos del Usuario al abrir el formulario
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Cargamos la relación si no está ya en memoria
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

    /**
     * PASO 2: Guardar los cambios en el Usuario al dar a "Save"
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // 1. Extraemos y actualizamos los datos del usuario vinculado
        if (isset($data['user'])) {
            $userData = $data['user'];

            // Gestión de la contraseña: si no escriben nada nuevo, no la tocamos
            if (empty($userData['password'])) {
                unset($userData['password']);
            } else {
                $userData['password'] = Hash::make($userData['password']);
            }

            $record->user->update($userData);

            // Limpiamos 'user' del array principal para que no estorbe en el update de Patient
            unset($data['user']);
        }

        // 2. Actualizamos los datos propios del paciente (born_date, history, etc.)
        $record->update($data);

        return $record;
    }
    protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
}
