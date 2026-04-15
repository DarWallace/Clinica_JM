<?php

namespace App\Filament\Admin\Resources\Cites\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class CiteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('service_id')
            ->relationship('service', 'name')
            ->required(),
        Select::make('room_id')
            ->relationship('room', 'name')
            ->required(),
        DatePicker::make('date')
            ->required(),
        TimePicker::make('start_time')
            ->required(),
        TimePicker::make('end_time')
            ->required(),
        Select::make('status')
            ->options([
                'available' => 'Disponible',
                'cancelled' => 'Cancelada',
                'completed' => 'Completada',
            ])
            ->default('available')
            ->required(),
    ]);
    }
}
