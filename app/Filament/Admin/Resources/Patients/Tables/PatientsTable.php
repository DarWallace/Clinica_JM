<?php

namespace App\Filament\Admin\Resources\Patients\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PatientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Traemos datos de la relación 'user'
                TextColumn::make('user.name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.surname')
                    ->label('Apellidos')
                    ->searchable(),
                TextColumn::make('user.email')
                    ->label('Email'),
                TextColumn::make('user.phone')
                    ->label('Teléfono'),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                // Acción personalizada para el modal de "Ver más"
                Action::make('detalles')
                    ->label('Ver más')
                    ->icon('heroicon-o-information-circle')
                    ->color('info')
                    ->modalHeading('Información Clínica')
                    ->modalSubmitAction(false) // Solo lectura
                    ->schema([
                        TextInput::make('born_date')
                            ->label('Fecha de Nacimiento')
                            ->disabled(),
                        Textarea::make('medical_history')
                            ->label('Historial Médico')
                            ->disabled()
                            ->rows(5),
                    ])
                    // Rellenamos el modal con los datos del registro actual
                    ->mountUsing(fn($form, $record) => $form->fill([
                        'born_date' => $record->born_date,
                        'medical_history' => $record->medical_history,
                    ])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
