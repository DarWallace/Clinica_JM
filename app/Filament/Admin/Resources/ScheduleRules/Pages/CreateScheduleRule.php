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
        // 1. Extraemos los días (que ahora vienen como [2, 3, 4] por el CheckboxList/Multiple)
        $days = $data['day_of_week'];
        $lastRecord = null;

        // 2. Recorremos cada día y creamos un registro independiente
        foreach ($days as $day) {
            $singleDayData = $data;
            $singleDayData['day_of_week'] = $day; // Asignamos el día actual del bucle

            // Guardamos en la tabla
            $lastRecord = static::getModel()::create($singleDayData);
        }

        // 3. Devolvemos el último para que Filament no se quede colgado
        return $lastRecord;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
