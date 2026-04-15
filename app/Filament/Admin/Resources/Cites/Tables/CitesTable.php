<?php

namespace App\Filament\Admin\Resources\Cites\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;



class CitesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')->date()->sortable(),
        TextColumn::make('start_time')->label('Inicio'),
        TextColumn::make('end_time')->label('Fin'),
        TextColumn::make('service.name')->label('Servicio'),
        TextColumn::make('room.name')->label('Sala'),
        BadgeColumn::make('status')
            ->colors([
                'success' => 'available',
                'danger' => 'cancelled',
                'warning' => 'completed',
            ]),
    ])
            ->filters([
               Filter::make('date')
        ->form([
            DatePicker::make('date')->default(now()),
        ])
        ->query(function (Builder $query, array $data): Builder {
            return $query->when($data['date'], fn($q) => $q->whereDate('date', $data['date']));
        }),
    SelectFilter::make('status')
        ->options([
            'available' => 'Solo Libres',
            'confirmed' => 'Solo Reservadas',
        ])
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
