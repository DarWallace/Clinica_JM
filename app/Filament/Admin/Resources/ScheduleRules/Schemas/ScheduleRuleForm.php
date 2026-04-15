<?php

namespace App\Filament\Admin\Resources\ScheduleRules\Schemas;

use App\Models\ScheduleRule;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get; // La clave en v5
use Closure;

class ScheduleRuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Asignación de Servicio')
                    ->description('Selecciona qué servicio y en qué lugar se impartirá.')
                    ->schema([
                        Select::make('service_id')
                            ->label('Servicio Médico')
                            ->relationship('service', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('room_id')
                            ->label('Sala / Habitación')
                            ->relationship('room', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Opcional si el servicio no requiere sala fija.'),
                    ])->columns(2),

                Section::make('Horario de Disponibilidad')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                // Selección de días
                                CheckboxList::make('day_of_week')
                                    ->label('Días de la Semana')
                                    ->options([
                                        1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles',
                                        4 => 'Jueves', 5 => 'Viernes',
                                    ])

                                    ->columns(2)
                                    ->required()
                                    ->extraAttributes(['class' => 'bg-gray-50/50 p-4 rounded-xl border border-gray-100'])
                                    ->formatStateUsing(fn ($state) => filled($state) ? [(string) $state] : []),

                                // Selección de horas con validación v5
                                Grid::make(1)
                                    ->schema([
                                        TimePicker::make('start_time')
                                            ->label('Hora de Inicio')
                                            ->seconds(false)
                                            ->required()
                                            ->live(), // Necesario para que Get obtenga el estado actual


                                        TimePicker::make('end_time')
                                            ->label('Hora de Fin')
                                            ->seconds(false)
                                            ->required()
                                            ->live(), // También live para cruzar datos
                                    ])
                                    ->columnSpan(1),
                            ]),
                    ]),

                Section::make('Vigencia de la Regla')
                    ->schema([
                        DatePicker::make('valid_from')
                            ->label('Desde el día')
                            ->default(now())
                            ->required(),

                        DatePicker::make('valid_until')
                            ->label('Hasta el día')
                            ->placeholder('Indefinida'),
                    ])
                    ->columns(2),
            ]);
    }
}
