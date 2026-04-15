<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ScheduleRule;
use App\Models\Cite;
use App\Models\Exception;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class GenerateCitesFromRules extends Command
{
    // Este es el nombre que usaremos en la terminal
    protected $signature = 'cites:generate {days=14}';
    protected $description = 'Genera huecos de citas respetando las reglas y las excepciones';

    public function handle()
    {
        $days = (int) $this->argument('days');
        $start = Carbon::today();
        $end = Carbon::today()->addDays($days);

        $period = CarbonPeriod::create($start, $end);

        // Cargamos las reglas con sus relaciones
        $rules = ScheduleRule::with('service')->get();

        $this->info("Iniciando generación de citas para $days días...");

        foreach ($period as $date) {
            $dayOfWeek = $date->dayOfWeekIso; // 1 (Lunes) a 7 (Domingo)
            $dateString = $date->format('Y-m-d');

            // Buscamos reglas para este día de la semana
            $activeRules = $rules->where('day_of_week', $dayOfWeek)
                ->filter(fn($r) =>
                    (is_null($r->valid_from) || $r->valid_from <= $dateString) &&
                    (is_null($r->valid_until) || $r->valid_until >= $dateString)
                );

            foreach ($activeRules as $rule) {
                $duration = $rule->service->duration;
                $startTime = Carbon::parse($dateString . ' ' . $rule->start_time);
                $endTime = Carbon::parse($dateString . ' ' . $rule->end_time);
                $specialistId = $rule->service->specialist_id;

                while ($startTime->copy()->addMinutes($duration) <= $endTime) {
                    $slotEnd = $startTime->copy()->addMinutes($duration);

                    // COMPROBAR EXCEPCIONES
                    $isBlocked = Exception::where('specialist_id', $specialistId)
                        ->where(function ($query) use ($startTime, $slotEnd) {
                            $query->whereBetween('start_datetime', [$startTime, $slotEnd])
                                  ->orWhereBetween('end_datetime', [$startTime, $slotEnd])
                                  ->orWhere(function ($q) use ($startTime, $slotEnd) {
                                      $q->where('start_datetime', '<=', $startTime)
                                        ->where('end_datetime', '>=', $slotEnd);
                                  });
                        })->exists();

                    if (!$isBlocked) {
                        Cite::firstOrCreate([
                            'service_id' => $rule->service_id,
                            'room_id' => $rule->room_id,
                            'date' => $dateString,
                            'start_time' => $startTime->format('H:i:s'),
                            'end_time' => $slotEnd->format('H:i:s'),
                            'schedule_rule_id' => $rule->id,
                        ]);
                    }

                    $startTime->addMinutes($duration);
                }
            }
        }

        $this->info("¡Listo! Las citas se han generado correctamente.");
    }
}
