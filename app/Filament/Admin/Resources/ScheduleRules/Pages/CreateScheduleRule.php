<?php

namespace App\Filament\Admin\Resources\ScheduleRules\Pages;

use App\Filament\Admin\Resources\ScheduleRules\ScheduleRuleResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class CreateScheduleRule extends CreateRecord
{
    protected static string $resource = ScheduleRuleResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $days = is_array($data['day_of_week'])
            ? $data['day_of_week']
            : [$data['day_of_week']];

        $lastRecord = null;

        foreach ($days as $day) {
            $singleDayData = $data;
            $singleDayData['day_of_week'] = (int) $day;

            $lastRecord = static::getModel()::create($singleDayData);
        }

        Artisan::call('cites:generate', [
            'days' => 30,
        ]);

        return $lastRecord;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
