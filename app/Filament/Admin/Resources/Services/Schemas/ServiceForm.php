<?php

namespace App\Filament\Admin\Resources\Services\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select; // <--- Importante añadir este
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Cambiamos TextInput por Select para elegir al Especialista
                Select::make('specialist_id')
                    ->label('Especialista Responsable')
                    ->relationship('specialist', 'user_id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->user->name} {$record->user->surname}")
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('name')
                    ->label('Nombre del Servicio')
                    ->required(),

                Textarea::make('description')
                    ->label('Descripción')
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('duration')
                    ->label('Duración (min)')
                    ->required()
                    ->numeric(),

                TextInput::make('buffer_minutes')
                    ->label('Margen entre citas (min)')
                    ->required()
                    ->numeric()
                    ->default(0),

                TextInput::make('price')
                    ->label('Precio')
                    ->required()
                    ->numeric()
                    ->prefix('€'), // Cambiado a € si prefieres

                // Cambiamos TextInput por Select para el tipo de servicio
                Select::make('type')
                    ->label('Tipo de Sesión')
                    ->options([
                        'individual' => 'Individual',
                        'group' => 'Grupal',
                    ])
                    ->required(),

                TextInput::make('max_patients')
                    ->label('Máximo de Pacientes')
                    ->required()
                    ->numeric()
                    ->default(1),

                Toggle::make('active')
                    ->label('Servicio Activo')
                    ->default(true)
                    ->required(),
            ]);
    }
}
