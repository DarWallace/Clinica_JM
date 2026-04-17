<?php

namespace App\Services\Cites;

use App\Models\Cite;
use App\Models\Exception as ScheduleException;
use App\Models\ScheduleRule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Services\Cites\CiteConflictService;

class BuildVirtualCiteSlotsService
{
    public function handle(
        Carbon|string $from,
        Carbon|string $until,
        ?int $serviceId = null,
        ?int $roomId = null,
    ): array {
        $from = $from instanceof Carbon
            ? $from->copy()->startOfDay()
            : Carbon::parse($from)->startOfDay();

        $until = $until instanceof Carbon
            ? $until->copy()->endOfDay()
            : Carbon::parse($until)->endOfDay();

        $rules = ScheduleRule::query()
            ->with(['service', 'room'])
            ->whereHas('service', fn($query) => $query->where('active', true))
            ->when($serviceId, fn($query) => $query->where('service_id', $serviceId))
            ->when($roomId, fn($query) => $query->where('room_id', $roomId))
            ->whereDate('valid_from', '<=', $until->toDateString())
            ->where(function ($query) use ($from) {
                $query->whereNull('valid_until')
                    ->orWhereDate('valid_until', '>=', $from->toDateString());
            })
            ->get();

        logger()->info('virtual-slots.rules', [
            'from' => $from->toDateString(),
            'until' => $until->toDateString(),
            'service_id' => $serviceId,
            'room_id' => $roomId,
            'rules_count' => $rules->count(),
            'rule_ids' => $rules->pluck('id')->all(),
        ]);

        $existingCites = Cite::query()
            ->with([
                'reservations' => fn($query) => $query->where('status', 'confirmed'),
            ])
            ->whereDate('date', '>=', $from->toDateString())
            ->whereDate('date', '<=', $until->toDateString())
            ->when($serviceId, fn($query) => $query->where('service_id', $serviceId))
            ->when($roomId, fn($query) => $query->where('room_id', $roomId))
            ->get()
            ->keyBy(function (Cite $cite) {
                $date = $cite->date instanceof Carbon
                    ? $cite->date->toDateString()
                    : (string) $cite->date;

                return $this->makeKey(
                    serviceId: (int) $cite->service_id,
                    roomId: $cite->room_id ? (int) $cite->room_id : null,
                    date: $date,
                    startTime: $this->normalizeTime((string) $cite->start_time),
                    endTime: $this->normalizeTime((string) $cite->end_time),
                );
            });

        $slots = [];

        $period = CarbonPeriod::create(
            $from->copy()->startOfDay(),
            $until->copy()->startOfDay()
        );

        foreach ($period as $day) {
            $dayOfWeek = (int) $day->dayOfWeekIso;

            foreach ($rules as $rule) {
                if ((int) $rule->day_of_week !== $dayOfWeek) {
                    continue;
                }

                if ($day->lt(Carbon::parse($rule->valid_from)->startOfDay())) {
                    continue;
                }

                if ($rule->valid_until && $day->gt(Carbon::parse($rule->valid_until)->endOfDay())) {
                    continue;
                }

                $service = $rule->service;

                if (! $service || ! $service->active) {
                    continue;
                }

                $duration = (int) ($service->duration ?? 0);

                if ($duration <= 0) {
                    continue;
                }

                $buffer = (int) ($service->buffer_minutes ?? 0);
                $stepMinutes = max(1, $duration + $buffer);

                $slotStart = Carbon::parse(
                    $day->toDateString() . ' ' . $this->normalizeTime((string) $rule->start_time)
                );

                $ruleEnd = Carbon::parse(
                    $day->toDateString() . ' ' . $this->normalizeTime((string) $rule->end_time)
                );

                while ($slotStart->copy()->addMinutes($duration)->lte($ruleEnd)) {
                    $slotEnd = $slotStart->copy()->addMinutes($duration);

                    if ($this->isBlockedByException(
                        specialistId: (int) $service->specialist_id,
                        startAt: $slotStart,
                        endAt: $slotEnd,
                    )) {
                        $slotStart->addMinutes($stepMinutes);
                        continue;
                    }

                    $key = $this->makeKey(
                        serviceId: (int) $service->id,
                        roomId: $rule->room_id ? (int) $rule->room_id : null,
                        date: $day->toDateString(),
                        startTime: $slotStart->format('H:i:s'),
                        endTime: $slotEnd->format('H:i:s'),
                    );

                    /** @var \App\Models\Cite|null $cite */
                    $cite = $existingCites->get($key);

                    $reservedCount = $cite
                        ? $cite->reservations->count()
                        : 0;

                    $capacity = $service->type === 'group'
                        ? max(1, (int) ($service->max_patients ?: 1))
                        : 1;

                    $isCancelled = $cite?->status === 'cancelled';
                    $isCompleted = $cite?->status === 'completed';

                    $hasBlockingConflict = app(CiteConflictService::class)->hasBlockingConflict(
                        date: $day->toDateString(),
                        startTime: $slotStart->format('H:i:s'),
                        endTime: $slotEnd->format('H:i:s'),
                        specialistId: (int) $service->specialist_id,
                        roomId: $rule->room_id ? (int) $rule->room_id : null,
                        ignoreCiteId: $cite?->id,
                    );

                    $isAvailable = ! $hasBlockingConflict && ! $isCompleted && (
                        $isCancelled || $reservedCount < $capacity
                    );

                    $slots[] = [
                        'key' => $key,
                        'cite_id' => $cite?->id,
                        'service_id' => (int) $service->id,
                        'service_name' => (string) $service->name,
                        'specialist_id' => (int) $service->specialist_id,
                        'room_id' => $rule->room_id ? (int) $rule->room_id : null,
                        'room_name' => $rule->room?->name,
                        'date' => $day->toDateString(),
                        'start_time' => $slotStart->format('H:i:s'),
                        'end_time' => $slotEnd->format('H:i:s'),
                        'type' => (string) $service->type,
                        'capacity' => $capacity,
                        'reserved_count' => $reservedCount,
                        'occupancy_label' => $reservedCount . '/' . $capacity,
                        'is_available' => $isAvailable,
                        'status_label' => $isAvailable ? 'Disponible' : 'Completa',
                    ];

                    $slotStart->addMinutes($stepMinutes);
                }
            }
        }

        usort($slots, function (array $a, array $b) {
            return [$a['date'], $a['start_time'], $a['service_name']]
                <=> [$b['date'], $b['start_time'], $b['service_name']];
        });

        logger()->info('virtual-slots.result', [
            'slots_count' => count($slots),
            'sample' => array_slice($slots, 0, 3),
        ]);

        return $slots;
    }

    protected function isBlockedByException(
        int $specialistId,
        Carbon $startAt,
        Carbon $endAt,
    ): bool {
        return ScheduleException::query()
            ->where('specialist_id', $specialistId)
            ->where('start_datetime', '<', $endAt)
            ->where('end_datetime', '>', $startAt)
            ->exists();
    }

    protected function makeKey(
        int $serviceId,
        ?int $roomId,
        string $date,
        string $startTime,
        string $endTime,
    ): string {
        return implode('|', [
            $serviceId,
            $roomId ?? 'null',
            $date,
            $this->normalizeTime($startTime),
            $this->normalizeTime($endTime),
        ]);
    }

    protected function normalizeTime(string $value): string
    {
        return strlen($value) === 5 ? "{$value}:00" : $value;
    }
}
