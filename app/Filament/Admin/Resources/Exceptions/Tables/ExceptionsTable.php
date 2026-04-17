<?php

namespace App\Filament\Admin\Resources\Exceptions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Exception as ScheduleException;


class ExceptionsTable
{
    public static function configure(Table $table): Table
    {
      return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['specialists', 'specialist']))
            ->columns([
                TextColumn::make('reason')
                    ->label('Motivo')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(fn (ScheduleException $record): ?string => $record->reason)
                    ->wrap(),

                IconColumn::make('applies_to_all')
                    ->label('Todos')
                    ->boolean()
                    ->alignCenter(),

                TextColumn::make('specialists_summary')
                    ->label('Especialistas')
                    ->state(function (ScheduleException $record): string {
                        if ($record->applies_to_all) {
                            return 'Todos';
                        }

                        $names = $record->specialists
                            ->map(fn ($user) => trim(($user->name ?? '') . ' ' . ($user->surname ?? '')))
                            ->filter()
                            ->values();

                        if ($names->isNotEmpty()) {
                            return $names->implode(', ');
                        }

                        if ($record->specialist) {
                            return trim(($record->specialist->name ?? '') . ' ' . ($record->specialist->surname ?? ''));
                        }

                        return 'Sin especialistas';
                    })
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Todos' ? 'success' : 'gray')
                    ->wrap(),

                TextColumn::make('start_datetime')
                    ->label('Inicio')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('end_datetime')
                    ->label('Fin')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('start_datetime', 'desc')
            ->recordActions([
                EditAction::make(),
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
