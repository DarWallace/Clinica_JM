<?php

namespace App\Filament\Admin\Resources\ScheduleRules\Pages;

use App\Filament\Admin\Resources\ScheduleRules\ScheduleRuleResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateScheduleRule extends CreateRecord
{
    protected static string $resource = ScheduleRuleResource::class;

    // Sobreescribimos el método de creación
    protected function handleRecordCreation(array $data): Model
    {
        $days = $data['day_of_week']; // Aquí tenemos el array [1, 3, 5...]
        $lastRecord = null;

        foreach ($days as $day) {
            // Creamos una copia de los datos pero con un solo día
            $singleDayData = $data;
            $singleDayData['day_of_week'] = $day;

            // Guardamos el registro en la base de datos
            $lastRecord = static::getModel()::create($singleDayData);
        }

        // Devolvemos el último para que Filament no explote
        return $lastRecord;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
