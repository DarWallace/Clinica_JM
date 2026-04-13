<?php

namespace App\Filament\Admin\Resources\Specialists\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle; // El interruptor para 'active'
use Filament\Schemas\Schema;

class SpecialistForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos del Especialista')
                    ->description('Información personal y de acceso')
                    ->schema([
                        TextInput::make('user.name')
                            ->label('Nombre')
                            ->required(),
                        TextInput::make('user.surname')
                            ->label('Apellidos')
                            ->required(),
                        TextInput::make('user.email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique('users', 'email', ignoreRecord: true),
                        TextInput::make('user.phone')
                            ->label('Teléfono'),

                        TextInput::make('user.password')
                            ->label(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord ? 'Contraseña' : 'Nueva Contraseña')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            // Quitamos el required para que no salte el error HTML5
                            ->helperText(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord
                                ? 'Autogenerada: Nombre + 3 números del móvil.'
                                : 'Deje vacío para mantener la actual.'),

                        // AQUÍ EL CAMPO ACTIVE
                        Toggle::make('active')
                            ->label('¿Especialista en activo?')
                            ->default(true),
                    ])->columns(2),

                Section::make('Perfil Profesional')
                    ->schema([
                        // Ajusta estos nombres según tu migración real
                        TextInput::make('speciality')
                            ->label('Especialidad (ej: Fisioterapia deportiva)'),

                    ]),
            ]);
    }
}
