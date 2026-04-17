<?php

namespace App\Filament\Admin\Resources\Services\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Servicio')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('specialist.user.name')
                    ->label('Especialista')
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Precio')
                    ->money('eur')
                    ->sortable(),

                TextColumn::make('duration')
                    ->label('Duración')
                    ->suffix(' min'),

                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'individual' ? 'gray' : 'info'),

                IconColumn::make('active')
                    ->label('Activo')
                    ->boolean(),
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
