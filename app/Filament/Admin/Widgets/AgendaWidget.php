<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Cite;
use App\Models\Patient;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\ScheduleRule;
use App\Models\Service;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
                ->options(fn (array $get): array =>
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
        $start = Carbon::parse($fetchInfo['start'])->toDateString();
        $end = Carbon::parse($fetchInfo['end'])->toDateString();

        return Cite::query()
            ->with([
                'service:id,name',
                'reservations:id,cite_id',
            ])
            ->whereDate('date', '>=', $start)
            ->whereDate('date', '<', $end)
            ->when(
                filled($this->serviceId),
                fn (Builder $query): Builder => $query->where('service_id', $this->serviceId)
            )
            ->get()
            ->map(function (Cite $cite): array {
                $isBooked = $cite->reservations->isNotEmpty();

                return [
                    'id' => (string) $cite->id,
                    'title' => $isBooked
                        ? 'Ocupado · ' . ($cite->service?->name ?? 'Sin servicio')
                        : 'Disponible · ' . ($cite->service?->name ?? 'Sin servicio'),
                    'start' => Carbon::parse("{$cite->date} {$cite->start_time}")->toIso8601String(),
                    'end' => Carbon::parse("{$cite->date} {$cite->end_time}")->toIso8601String(),
                    'color' => $isBooked ? '#94a3b8' : '#10b981',
                    'extendedProps' => [
                        'status' => $cite->status,
                        'is_booked' => $isBooked,
                        'service_name' => $cite->service?->name,
                    ],
                ];
            })
            ->all();
    }

    protected function viewAction(): Action
    {
        return CalendarActions\ViewAction::make()
            ->modalHeading(fn (Cite $record): string => 'Cita del ' . Carbon::parse($record->date)->format('d/m/Y'))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Cerrar')
            ->schema([
                Placeholder::make('service')
                    ->label('Tratamiento')
                    ->content(fn (Cite $record): string => $record->service?->name ?? 'Sin servicio'),

                Placeholder::make('status')
                    ->label('Estado')
                    ->content(fn (Cite $record): string => $record->reservations()->exists() ? 'Reservada' : 'Disponible'),

                Placeholder::make('schedule')
                    ->label('Horario')
                    ->content(fn (Cite $record): string => substr((string) $record->start_time, 0, 5) . ' - ' . substr((string) $record->end_time, 0, 5)),
            ])
            ->extraModalFooterActions(fn (): array => [
                Action::make('reservar')
                    ->label('Asignar paciente')
                    ->icon('heroicon-o-user-plus')
                    ->color('success')
                    ->visible(fn (Cite $record): bool => ! $record->reservations()->exists())
                    ->schema([
                        Select::make('patient_id')
                            ->label('Paciente')
                            ->options($this->getPatientOptions())
                            ->searchable()
                            ->required(),
                    ])
                    ->modalHeading('Nueva reserva')
                    ->modalSubmitActionLabel('Confirmar cita')
                    ->action(function (array $data, Cite $record): void {
                        $this->finalizarReserva(
                            citeId: $record->id,
                            patientId: (int) $data['patient_id'],
                        );
                    }),
            ]);
    }

    protected function getPatientOptions(): array
    {
        return Patient::query()
            ->with('user:id,name')
            ->get()
            ->mapWithKeys(function (Patient $patient): array {
                return [
                    $patient->id => "{$patient->id} - " . ($patient->user?->name ?? "Paciente #{$patient->id}"),
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
                    ->with('reservations:id,cite_id')
                    ->findOrFail($citeId);

                if ($cite->reservations->isNotEmpty()) {
                    throw new \RuntimeException('La cita ya se reservó mientras estabas operando.');
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
}
