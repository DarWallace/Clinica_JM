<?php

namespace App\Filament\Admin\Resources\Cites\Tables;

use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CitesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),

                TextColumn::make('start_time')
                    ->label('Inicio'),

                TextColumn::make('end_time')
                    ->label('Fin'),

                TextColumn::make('service.name')
                    ->label('Servicio'),

                TextColumn::make('room.name')
                    ->label('Sala'),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'available' => 'success',
                        'confirmed' => 'primary',
                        'cancelled' => 'danger',
                        'completed' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Filter::make('date')
                    ->label('Fecha')
                    ->schema([
                        DatePicker::make('date')
                            ->default(today()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['date'] ?? null,
                            fn (Builder $query, $date): Builder => $query->whereDate('date', $date),
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! ($data['date'] ?? null)) {
                            return null;
                        }

                        return 'Fecha: ' . Carbon::parse($data['date'])->format('d/m/Y');
                    }),


                SelectFilter::make('status')
                    ->options([
                        'available' => 'Solo libres',
                        'confirmed' => 'Solo reservadas',
                    ]),
                    SelectFilter::make('service')
                    ->label('Servicio')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->preload(),

            ])
            ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Filtrar Citas')
                    ->icon('heroicon-m-funnel')
                    ->size('lg')
                    ->color('primary'),
            )
            ->recordActions([
                //EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
