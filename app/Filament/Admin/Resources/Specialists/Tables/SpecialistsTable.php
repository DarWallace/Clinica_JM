<?php

namespace App\Filament\Admin\Resources\Specialists\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SpecialistsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.surname')
                    ->label('Apellidos')
                    ->searchable(),
                TextColumn::make('speciality')
                    ->label('Especialidad'),

                // Columna visual para el estado activo
                IconColumn::make('active')
                    ->label('Estado')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('user.phone')
                    ->label('Teléfono'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('detalles')
                    ->label('Ver más')
                    ->icon('heroicon-o-information-circle')
                    ->color('info')
                    ->modalHeading('Perfil Profesional')
                    ->modalSubmitAction(false),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
