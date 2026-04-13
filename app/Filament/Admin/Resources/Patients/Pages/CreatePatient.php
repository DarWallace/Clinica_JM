<?php

namespace App\Filament\Admin\Resources\Patients\Pages;

use App\Filament\Admin\Resources\Patients\PatientResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePatient extends CreateRecord
{
    protected static string $resource = PatientResource::class;

    protected function handleRecordCreation(array $data): Model
{
    $userData = $data['user'];
    $userData['role'] = 'patient';

    // Lógica de contraseña automática: Nombre + 3 dígitos teléfono
    $prefijoTelefono = substr($userData['phone'] ?? '123', 0, 3);
    $passwordSencilla = $userData['name'] . $prefijoTelefono;

    $userData['password'] = bcrypt($passwordSencilla); // La encriptamos para la BD

    $user = User::create($userData);

    unset($data['user']);
    $data['user_id'] = $user->id;

    return static::getModel()::create($data);
}
}
