<?php

namespace App\Services\Cites;

use App\Models\Cite;


class CiteConflictService
{
    public function findBlockingConflict(
        string $date,
        string $startTime,
        string $endTime,
        int $specialistId,
        ?int $roomId = null,
        ?int $ignoreCiteId = null,
    ): ?Cite {
        return Cite::query()
            ->with(['service', 'room'])
            ->when($ignoreCiteId, fn ($query) => $query->where('id', '!=', $ignoreCiteId))
            ->whereDate('date', $date)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) {
                $query
                    ->where('status', 'completed')
                    ->orWhereHas('reservations', fn ($reservationQuery) => $reservationQuery->where('status', 'confirmed'));
            })
            ->where(function ($query) use ($roomId, $specialistId) {
                if ($roomId) {
                    $query->where('room_id', $roomId)
                        ->orWhereHas('service', fn ($serviceQuery) => $serviceQuery->where('specialist_id', $specialistId));
                } else {
                    $query->whereHas('service', fn ($serviceQuery) => $serviceQuery->where('specialist_id', $specialistId));
                }
            })
            ->first();
    }

    public function hasBlockingConflict(
        string $date,
        string $startTime,
        string $endTime,
        int $specialistId,
        ?int $roomId = null,
        ?int $ignoreCiteId = null,
    ): bool {
        return (bool) $this->findBlockingConflict(
            date: $date,
            startTime: $startTime,
            endTime: $endTime,
            specialistId: $specialistId,
            roomId: $roomId,
            ignoreCiteId: $ignoreCiteId,
        );
    }
}
