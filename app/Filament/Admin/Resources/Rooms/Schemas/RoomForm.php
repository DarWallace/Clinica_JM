<?php

namespace App\Filament\Admin\Resources\Rooms\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
           ->components([
                Section::make('Información de la Sala')
                    ->description('Detalles principales de la ubicación de las sesiones.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre de la Sala')
                            ->placeholder('Ej: Sala de Fisioterapia 1, Gimnasio...')
                            ->required()
                            ->maxLength(255),

                        Select::make('type')
                            ->label('Tipo de Sala')
                            ->options([
                                'individual' => 'Individual',
                                'group' => 'Grupal',
                                'mixed' => 'Mixta',
                            ])
                            ->required()
                            ->native(false),

                        TextInput::make('capacity')
                            ->label('Capacidad Máxima')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->suffix('personas')
                            ->helperText('Número máximo de pacientes permitidos simultáneamente.')
                            ->required(),
                    ])->columns(2),
            ]);
    }
}
