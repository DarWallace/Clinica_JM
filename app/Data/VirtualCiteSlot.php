<?php

namespace App\Data;

class VirtualCiteSlot
{
    public function __construct(
        public readonly int $serviceId,
        public readonly string $serviceName,
        public readonly ?int $roomId,
        public readonly ?string $roomName,
        public readonly string $date,
        public readonly string $startTime,
        public readonly string $endTime,
        public readonly string $type, // individual | group
        public readonly int $capacity,
        public readonly int $reservedCount,
        public readonly bool $isAvailable,
        public readonly ?int $citeId = null,
    ) {}

    public function key(): string
    {
        return implode('|', [
            $this->serviceId,
            $this->roomId ?? 'null',
            $this->date,
            $this->startTime,
            $this->endTime,
        ]);
    }

    public function occupancyLabel(): string
    {
        return "{$this->reservedCount}/{$this->capacity}";
    }
}
