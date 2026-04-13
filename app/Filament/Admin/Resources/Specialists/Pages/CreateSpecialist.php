<?php

namespace App\Filament\Admin\Resources\Specialists\Pages;


use App\Models\User;
use App\Filament\Admin\Resources\Specialists\SpecialistResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateSpecialist extends CreateRecord
{
    protected static string $resource = SpecialistResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // 1. Extraemos los datos del usuario
        $userData = $data['user'];
        $userData['role'] = 'specialist'; // ROL FIJO

        // 2. Lógica de contraseña automática
        $prefijoTelefono = substr($userData['phone'] ?? '123', 0, 3);
        $passwordSencilla = ($userData['name'] ?? 'user') . $prefijoTelefono;
        $userData['password'] = bcrypt($passwordSencilla);

        // 3. Creamos el usuario
        $user = User::create($userData);

        // 4. Vinculamos al especialista
        unset($data['user']);
        $data['user_id'] = $user->id;

        return static::getModel()::create($data);
    }
}
