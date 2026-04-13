<?php

namespace App\Filament\Admin\Resources\Patients\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PatientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // --- SECCIÓN DE DATOS DE USUARIO ---
                Section::make('Datos Personales')
                    ->description('Información básica de la cuenta')
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
                            ->required(),
                        TextInput::make('user.phone')
                            ->label('Teléfono'),

                        // Lógica condicional para la contraseña
                        TextInput::make('user.password')
                            ->label(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord
                                ? 'Contraseña'
                                : 'Nueva Contraseña')
                            ->password()
                            // Solo se incluye en la petición si tiene texto (evita sobrescribir con vacío al editar)
                            ->dehydrated(fn ($state) => filled($state))
                            // Obligatorio solo al CREAR
                            //->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                            // El texto informativo desaparece al editar
                            ->helperText(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord
                                ? 'La contraseña se generará automáticamente: Nombre + 3 primeros números del teléfono.'
                                : 'Deje vacío para mantener la contraseña actual.')
                            // El placeholder cambia según el contexto
                            ->placeholder(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord
                                ? 'Se autogenera al guardar'
                                : '••••••••'),
                    ])->columns(2),

                // --- SECCIÓN DE DATOS CLÍNICOS (Tabla Patients) ---
                Section::make('Perfil Clínico')
                    ->schema([
                        DatePicker::make('born_date')
                            ->label('Fecha de Nacimiento')
                            ->required(),
                        Textarea::make('medical_history')
                            ->label('Historial Médico')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
