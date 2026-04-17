<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Cite;
use App\Models\Patient;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Service;
use App\Services\Cites\BuildVirtualCiteSlotsService;
use App\Services\Cites\CiteConflictService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Saade\FilamentFullCalendar\Actions as CalendarActions;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class AgendaWidget extends FullCalendarWidget
{
    public Model | string | null $model = Cite::class;

    protected static ?string $heading = 'Agenda Operativa';

    protected int | string | array $columnSpan = 'full';

    public ?int $serviceId = null;

    protected function getCalendarOptions(): array
    {
        return [
            'selectable' => true,
        ];
    }

    protected function headerActions(): array
    {
        return [
            Action::make('filterService')
                ->label($this->serviceId ? 'Cambiar tratamiento' : 'Filtrar tratamiento')
                ->icon('heroicon-o-funnel')
                ->fillForm([
                    'service_id' => $this->serviceId,
                ])
                ->schema([
                    Select::make('service_id')
                        ->label('Tratamiento')
                        ->options(
                            Service::query()
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->all()
                        )
                        ->searchable()
                        ->placeholder('Todos'),
                ])
                ->action(function (array $data): void {
                    $this->serviceId = filled($data['service_id'] ?? null)
                        ? (int) $data['service_id']
                        : null;

                    $this->refreshRecords();
                }),

            Action::make('clearServiceFilter')
                ->label('Quitar filtro')
                ->icon('heroicon-o-x-mark')
                ->color('gray')
                ->visible(fn (): bool => filled($this->serviceId))
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->serviceId = null;
                    $this->refreshRecords();
                }),
        ];
    }

    protected function selectAction(): Action
    {
        return CalendarActions\CreateAction::make()
            ->label('Nueva reserva')
            ->icon('heroicon-o-plus')
            ->fillForm(function (array $arguments = []): array {
                $data = [];

                if (isset($arguments['start'])) {
                    $data['date'] = Carbon::parse($arguments['start'])->toDateString();
                    $data['start_time'] = Carbon::parse($arguments['start'])->toTimeString();
                    $data['end_time'] = isset($arguments['end'])
                        ? Carbon::parse($arguments['end'])->toTimeString()
                        : Carbon::parse($arguments['start'])->addHour()->toTimeString();
                }

                if ($this->serviceId) {
                    $data['service_id'] = $this->serviceId;
                }

                return $data;
            })
            ->after(function (Cite $cite, array $data): void {
                Reservation::create([
                    'cite_id' => $cite->id,
                    'patient_id' => $data['patient_id'],
                    'status' => 'confirmed',
                    'payment_status' => 'pending',
                ]);

                $cite->update([
                    'status' => 'confirmed',
                ]);
            });
    }

    public function getFormSchema(): array
    {
        return [
            Select::make('service_id')
                ->label('Servicio')
                ->options(Service::query()->orderBy('name')->pluck('name', 'id')->all())
                ->searchable()
                ->required()
                ->reactive(),

            Select::make('room_id')
                ->label('Habitación')
                ->options(
                    fn (array $get): array =>
                    filled($get('service_id'))
                        ? Room::whereHas('scheduleRules', fn ($q) => $q->where('service_id', $get('service_id')))
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->all()
                        : []
                )
                ->searchable()
                ->required()
                ->placeholder('Selecciona un servicio primero'),

            Select::make('patient_id')
                ->label('Paciente')
                ->options($this->getPatientOptions())
                ->searchable()
                ->required(),

            DatePicker::make('date')
                ->label('Fecha')
                ->required(),

            TimePicker::make('start_time')
                ->label('Hora de inicio')
                ->required(),

            TimePicker::make('end_time')
                ->label('Hora de fin')
                ->required(),
        ];
    }

    public function fetchEvents(array $fetchInfo): array
    {
        $start = Carbon::parse($fetchInfo['start'])->startOfDay();
        $endExclusive = Carbon::parse($fetchInfo['end'])->startOfDay();
        $endInclusive = $endExclusive->copy()->subDay();

        $realCites = Cite::query()
            ->with([
                'service:id,name,type,max_patients,specialist_id',
                'service.specialist.user:id,name,surname',
                'room:id,name',
                'reservations:id,cite_id,patient_id,status',
                'reservations.patient.user:id,name,surname,email,phone',
            ])
            ->whereDate('date', '>=', $start->toDateString())
            ->whereDate('date', '<', $endExclusive->toDateString())
            ->when(
                filled($this->serviceId),
                fn (Builder $query): Builder => $query->where('service_id', $this->serviceId)
            )
            ->get()
            ->map(function (Cite $cite): array {
                $confirmedReservations = $cite->reservations->where('status', 'confirmed');
                $service = $cite->service;
                $isGroup = $service?->type === 'group';
                $capacity = $isGroup ? max(1, (int) ($service?->max_patients ?: 1)) : 1;
                $count = $confirmedReservations->count();

                return [
                    'id' => (string) $cite->id,
                    'title' => $isGroup
                        ? ($service?->name ?? 'Sin servicio') . " ({$count}/{$capacity})"
                        : (($count > 0 ? 'Ocupado · ' : 'Disponible · ') . ($service?->name ?? 'Sin servicio')),
                    'start' => Carbon::parse("{$cite->date} {$cite->start_time}")->toIso8601String(),
                    'end' => Carbon::parse("{$cite->date} {$cite->end_time}")->toIso8601String(),
                    'color' => $count > 0 ? '#94a3b8' : '#10b981',
                    'extendedProps' => [
                        'kind' => 'cite',
                        'status' => $cite->status,
                        'is_booked' => $count > 0,
                        'service_name' => $service?->name,
                        'specialist_name' => trim(
                            ($cite->service?->specialist?->user?->name ?? '') . ' ' .
                            ($cite->service?->specialist?->user?->surname ?? '')
                        ) ?: 'Sin especialista',
                    ],
                ];
            });

        $virtualSlots = collect(
            app(BuildVirtualCiteSlotsService::class)->handle(
                from: $start->toDateString(),
                until: $endInclusive->toDateString(),
                serviceId: $this->serviceId ?: null,
                roomId: null,
            )
        )
            ->filter(fn (array $slot): bool => ($slot['is_available'] ?? false) && empty($slot['cite_id']))
            ->map(function (array $slot): array {
                $isGroup = ($slot['type'] ?? 'individual') === 'group';

                return [
                    'id' => 'virtual::' . $slot['key'],
                    'title' => $isGroup
                        ? "Libre · {$slot['service_name']} ({$slot['occupancy_label']})"
                        : "Libre · {$slot['service_name']}",
                    'start' => Carbon::parse("{$slot['date']} {$slot['start_time']}")->toIso8601String(),
                    'end' => Carbon::parse("{$slot['date']} {$slot['end_time']}")->toIso8601String(),
                    'color' => '#22c55e',
                    'textColor' => '#14532d',
                    'extendedProps' => [
                        'kind' => 'virtual_slot',
                        'slot_key' => $slot['key'],
                        'service_id' => $slot['service_id'],
                        'service_name' => $slot['service_name'],
                        'specialist_id' => $slot['specialist_id'] ?? null,
                        'specialist_name' => $slot['specialist_name'] ?? 'Sin especialista',
                        'room_id' => $slot['room_id'],
                        'room_name' => $slot['room_name'],
                        'date' => $slot['date'],
                        'start_time' => $slot['start_time'],
                        'end_time' => $slot['end_time'],
                        'type' => $slot['type'],
                        'capacity' => $slot['capacity'],
                        'reserved_count' => $slot['reserved_count'],
                        'occupancy_label' => $slot['occupancy_label'],
                    ],
                ];
            });

        return $realCites
            ->concat($virtualSlots)
            ->values()
            ->all();
    }

    public function onEventClick(array $event): void
    {
        $kind = data_get($event, 'extendedProps.kind');

        if ($kind === 'virtual_slot') {
            $this->mountAction('reserveVirtualSlot', [
                'slot' => data_get($event, 'extendedProps', []),
            ]);

            return;
        }

        parent::onEventClick($event);
    }

    protected function reserveVirtualSlotAction(): Action
    {
        return Action::make('reserveVirtualSlot')
            ->label('Reservar hueco')
            ->modalHeading('Reservar hueco disponible')
            ->fillForm(function (array $arguments): array {
                $slot = $arguments['slot'] ?? [];

                $date = isset($slot['date'])
                    ? Carbon::parse($slot['date'])->format('d/m/Y')
                    : '-';

                $start = isset($slot['start_time']) ? substr((string) $slot['start_time'], 0, 5) : '-';
                $end = isset($slot['end_time']) ? substr((string) $slot['end_time'], 0, 5) : '-';
                $service = $slot['service_name'] ?? 'Sin tratamiento';
                $specialist = $slot['specialist_name'] ?? 'Sin especialista';

                return [
                    'slot_info' => "{$date} · {$start} - {$end} · {$service} · {$specialist}",
                ];
            })
            ->schema([
                TextInput::make('slot_info')
                    ->label('Hueco')
                    ->disabled()
                    ->dehydrated(false),

                Select::make('patient_id')
                    ->label('Paciente')
                    ->options($this->getPatientOptions())
                    ->searchable()
                    ->required(),
            ])
            ->action(function (array $data, array $arguments): void {
                $slot = $arguments['slot'] ?? null;

                if (! is_array($slot)) {
                    Notification::make()
                        ->title('No se ha podido leer el hueco seleccionado')
                        ->danger()
                        ->send();

                    return;
                }

                $this->finalizarReservaVirtual(
                    slot: $slot,
                    patientId: (int) $data['patient_id'],
                );
            });
    }

    private function finalizarReservaVirtual(array $slot, int $patientId): void
    {
        try {
            DB::transaction(function () use ($slot, $patientId): void {
                $conflict = app(CiteConflictService::class)->findBlockingConflict(
                    date: $slot['date'],
                    startTime: $slot['start_time'],
                    endTime: $slot['end_time'],
                    specialistId: (int) ($slot['specialist_id'] ?? 0),
                    roomId: filled($slot['room_id'] ?? null) ? (int) $slot['room_id'] : null,
                    ignoreCiteId: null,
                );

                if ($conflict) {
                    throw new \RuntimeException('Ese hueco ya no está disponible.');
                }

                $cite = Cite::query()
                    ->lockForUpdate()
                    ->where('service_id', $slot['service_id'])
                    ->whereDate('date', $slot['date'])
                    ->whereTime('start_time', $slot['start_time'])
                    ->whereTime('end_time', $slot['end_time'])
                    ->when(
                        filled($slot['room_id'] ?? null),
                        fn ($query) => $query->where('room_id', $slot['room_id']),
                        fn ($query) => $query->whereNull('room_id'),
                    )
                    ->first();

                if (! $cite) {
                    $cite = Cite::create([
                        'service_id' => $slot['service_id'],
                        'room_id' => $slot['room_id'] ?? null,
                        'date' => $slot['date'],
                        'start_time' => $slot['start_time'],
                        'end_time' => $slot['end_time'],
                        'status' => 'confirmed',
                    ]);
                } elseif ($cite->status === 'cancelled') {
                    $cite->update([
                        'status' => 'confirmed',
                    ]);
                }

                $cite->load([
                    'service',
                    'reservations' => fn ($query) => $query->where('status', 'confirmed'),
                ]);

                $alreadyExists = $cite->reservations
                    ->contains(fn ($reservation) => (int) $reservation->patient_id === $patientId);

                if ($alreadyExists) {
                    throw new \RuntimeException('Ese paciente ya está asignado a esta cita.');
                }

                $capacity = $this->getCiteCapacity($cite);
                $confirmedCount = $cite->reservations->count();

                if ($confirmedCount >= $capacity) {
                    throw new \RuntimeException('La cita ya no tiene plazas disponibles.');
                }

                Reservation::create([
                    'cite_id' => $cite->id,
                    'patient_id' => $patientId,
                    'status' => 'confirmed',
                    'payment_status' => 'pending',
                ]);

                $cite->update([
                    'status' => 'confirmed',
                ]);
            });

            Notification::make()
                ->title('Reserva completada')
                ->success()
                ->send();

            $this->refreshRecords();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('No se pudo completar la reserva')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getCiteCapacity(Cite $cite): int
    {
        $service = $cite->service;

        if (! $service) {
            return 1;
        }

        if ($service->type !== 'group') {
            return 1;
        }

        return max(1, (int) ($service->max_patients ?: 1));
    }

    protected function getConfirmedReservationsCount(Cite $cite): int
    {
        return $cite->reservations
            ->where('status', 'confirmed')
            ->count();
    }

    protected function canAddMorePatients(Cite $cite): bool
    {
        $cite->loadMissing([
            'service',
            'reservations' => fn ($query) => $query->where('status', 'confirmed'),
        ]);

        return $cite->reservations->count() < $this->getCiteCapacity($cite);
    }

    protected function getReservationsSummary(Cite $cite): HtmlString
    {
        $cite->loadMissing([
            'service.specialist.user',
            'room',
            'reservations.patient.user',
        ]);

        $confirmedReservations = $cite->reservations->where('status', 'confirmed');

        if ($confirmedReservations->isEmpty()) {
            return new HtmlString('<div style="color:#64748b;">No hay pacientes asignados todavía.</div>');
        }

        $html = '<div style="display:grid; gap:10px;">';

        foreach ($confirmedReservations as $reservation) {
            $name = trim(($reservation->patient?->user?->name ?? '') . ' ' . ($reservation->patient?->user?->surname ?? ''));
            $email = $reservation->patient?->user?->email ?? 'Sin email';
            $phone = $reservation->patient?->user?->phone ?? null;

            $html .= '<div style="border:1px solid #e2e8f0; border-radius:12px; padding:12px 14px; background:white;">';
            $html .= '<div style="font-weight:700; color:#1e293b;">' . e($name ?: 'Paciente sin nombre') . '</div>';
            $html .= '<div style="font-size:12px; color:#64748b; margin-top:4px;">' . e($email);

            if ($phone) {
                $html .= ' · ' . e($phone);
            }

            $html .= '</div></div>';
        }

        $html .= '</div>';

        return new HtmlString($html);
    }

    protected function viewAction(): Action
    {
        return CalendarActions\ViewAction::make()
            ->modalHeading(fn (Cite $record): string => 'Cita del ' . Carbon::parse($record->date)->format('d/m/Y'))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Cerrar')
            ->modalWidth('4xl')
            ->schema([
                Placeholder::make('service')
                    ->label('Tratamiento')
                    ->content(fn (Cite $record): string => $record->service?->name ?? 'Sin servicio'),

                Placeholder::make('specialist')
                    ->label('Especialista')
                    ->content(function (Cite $record): string {
                        $user = $record->service?->specialist?->user;

                        if (! $user) {
                            return 'Sin especialista';
                        }

                        return trim(($user->name ?? '') . ' ' . ($user->surname ?? ''));
                    }),

                Placeholder::make('status')
                    ->label('Estado')
                    ->content(function (Cite $record): string {
                        $count = $record->reservations->where('status', 'confirmed')->count();
                        $capacity = $this->getCiteCapacity($record);

                        if (($record->service?->type ?? 'individual') === 'group') {
                            return "Grupo ({$count}/{$capacity})";
                        }

                        return $count > 0 ? 'Reservada' : 'Disponible';
                    }),

                Placeholder::make('schedule')
                    ->label('Horario')
                    ->content(fn (Cite $record): string => substr((string) $record->start_time, 0, 5) . ' - ' . substr((string) $record->end_time, 0, 5)),

                Placeholder::make('room')
                    ->label('Sala')
                    ->content(fn (Cite $record): string => $record->room?->name ?? 'No asignada'),

                Placeholder::make('patients')
                    ->label(fn (Cite $record): string => ($record->service?->type ?? 'individual') === 'group' ? 'Integrantes del grupo' : 'Paciente')
                    ->content(fn (Cite $record): HtmlString => $this->getReservationsSummary($record)),
            ]);
    }

    protected function getPatientOptions(): array
    {
        return Patient::query()
            ->with('user:id,name,surname')
            ->get()
            ->mapWithKeys(function (Patient $patient): array {
                $label = trim(($patient->user?->name ?? '') . ' ' . ($patient->user?->surname ?? ''));

                return [
                    $patient->getKey() => $label !== '' ? $label : "Paciente #{$patient->getKey()}",
                ];
            })
            ->all();
    }

    private function finalizarReserva(int $citeId, int $patientId): void
    {
        try {
            DB::transaction(function () use ($citeId, $patientId): void {
                $cite = Cite::query()
                    ->lockForUpdate()
                    ->with([
                        'service',
                        'reservations' => fn ($query) => $query->where('status', 'confirmed'),
                    ])
                    ->findOrFail($citeId);

                $alreadyExists = $cite->reservations
                    ->contains(fn ($reservation) => (int) $reservation->patient_id === $patientId);

                if ($alreadyExists) {
                    throw new \RuntimeException('Ese paciente ya está asignado a esta cita.');
                }

                $capacity = $this->getCiteCapacity($cite);
                $confirmedCount = $cite->reservations->count();

                if ($confirmedCount >= $capacity) {
                    throw new \RuntimeException('La cita ya no tiene plazas disponibles.');
                }

                Reservation::create([
                    'cite_id' => $cite->id,
                    'patient_id' => $patientId,
                    'status' => 'confirmed',
                    'payment_status' => 'pending',
                ]);

                $cite->update([
                    'status' => 'confirmed',
                ]);
            });

            Notification::make()
                ->title('Reserva completada')
                ->success()
                ->send();

            $this->refreshRecords();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('No se pudo completar la reserva')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function modalActions(): array
    {
        if (! isset($this->record) || ! $this->record instanceof Cite) {
            return [];
        }

        return [
            Action::make('assignPatient')
                ->label('Asignar paciente')
                ->icon('heroicon-o-user-plus')
                ->color('success')
                ->visible(fn (): bool => $this->canAddMorePatients($this->record))
                ->schema([
                    Select::make('patient_id')
                        ->label('Paciente')
                        ->options($this->getPatientOptions())
                        ->searchable()
                        ->required(),
                ])
                ->modalHeading('Asignar paciente a la cita')
                ->modalSubmitActionLabel('Confirmar cita')
                ->action(function (array $data): void {
                    $this->finalizarReserva(
                        citeId: $this->record->id,
                        patientId: (int) $data['patient_id'],
                    );
                }),
        ];
    }
}
