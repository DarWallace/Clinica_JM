<?php

namespace App\Filament\Admin\Resources\ScheduleRules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ScheduleRulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('service.name')
                    ->label('Servicio')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('day_of_week')
                    ->label('Día')
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => 'Lunes',
                        2 => 'Martes',
                        3 => 'Miércoles',
                        4 => 'Jueves',
                        5 => 'Viernes',
                        //6 => 'Sábado',
                       // 7 => 'Domingo',
                        default => 'Desconocido',
                    })
                    ->badge()
                    ->color('info'),

                TextColumn::make('start_time')
                    ->label('Inicio')
                    ->time('H:i'),

                TextColumn::make('end_time')
                    ->label('Fin')
                    ->time('H:i'),

                TextColumn::make('room.name')
                    ->label('Sala')
                    ->placeholder('Sin sala asignada'),

                TextColumn::make('valid_from')
                    ->label('Vigencia')
                    ->date('d/m/Y')
                    ->description(fn ($record) => $record->valid_until ? 'Hasta ' . $record->valid_until->format('d/m/Y') : 'Indefinida'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ])
                ->label('Borrar seleccionados') // <-- Cambia el texto del botón principal
            ->icon('heroicon-o-trash'),
            ]);
    }
}
