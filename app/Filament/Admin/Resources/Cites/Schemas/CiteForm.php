<?php

namespace App\Filament\Admin\Resources\Cites\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Placeholder;
use App\Models\Patient;

class CiteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalles de la Cita')
                    ->description('Información básica del turno seleccionado.')
                    ->schema([
                        // Convertimos los Select en Placeholder para que solo informen
                        Placeholder::make('service_info')
                            ->label('Servicio')
                            ->content(fn($record) => $record?->service?->name ?? 'No asignado'),

                        Placeholder::make('room_info')
                            ->label('Habitación / Sala')
                            ->content(fn($record) => $record?->room?->name ?? 'No asignada'),

                        Placeholder::make('date_info')
                            ->label('Fecha')
                            ->content(fn($record) => $record?->date),

                        Placeholder::make('hours_info')
                            ->label('Horario')
                            ->content(fn($record) => "{$record?->start_time} - {$record?->end_time}"),
                    ])->columns(2),

                Section::make('Gestión de la Reserva')
                    ->schema([
                        // El status sí se puede cambiar
                        Select::make('status')
                            ->label('Estado de la Cita')
                            ->options([
                                'available' => 'Disponible',
                                'confirmed' => 'Reservada/Confirmada',
                                'cancelled' => 'Cancelada',
                                'completed' => 'Completada',
                            ])
                            ->required()
                            ->native(false),

                        // El paciente con buscador (searchable)
                        Select::make('patient_id')
                            ->label('Paciente Asignado')
                            ->placeholder('Escribe para buscar un paciente...')
                            ->searchable()
                            ->dehydrated(false)
                            ->options(
                                Patient::query()
                                    ->join('users', 'patients.user_id', '=', 'users.id')
                                    ->pluck('users.name', 'patients.user_id') // USAMOS user_id AQUÍ
                            )
                            // Cargamos el paciente actual si ya existe una reserva
                            ->afterStateHydrated(function (Select $component, $record) {
                                $component->state($record?->reservations()->first()?->patient_id);
                            })
                            // Guardamos la reserva al guardar la cita
                            ->saveRelationshipsUsing(function ($record, $state) {
                                if (blank($state)) return;

                                $record->reservations()->updateOrCreate(
                                    ['cite_id' => $record->id],
                                    [
                                        'patient_id' => $state,
                                        'status' => 'confirmed',
                                        'payment_status' => 'pending',
                                    ]
                                );
                            }),
                    ])->columns(2)
            ]);
    }
}
