<?php

namespace App\Filament\Admin\Resources\Exceptions\Schemas;

use App\Models\Specialist;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ExceptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('applies_to_all')
                    ->label('Aplicar a todos los especialistas')
                    ->live()
                    ->default(false),

                Select::make('specialist_ids')
                    ->label('Especialistas')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->options(static::getSpecialistOptions())
                    ->disabled(fn (callable $get): bool => (bool) $get('applies_to_all'))
                    ->required(fn (callable $get): bool => ! (bool) $get('applies_to_all'))
                    ->helperText('Si marcas “todos”, este campo queda inhabilitado.'),

                DateTimePicker::make('start_datetime')
                    ->label('Inicio')
                    ->seconds(false)
                    ->required(),

                DateTimePicker::make('end_datetime')
                    ->label('Fin')
                    ->seconds(false)
                    ->required()
                    ->after('start_datetime'),

                Textarea::make('reason')
                    ->label('Motivo')
                    ->rows(4)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    protected static function getSpecialistOptions(): array
    {
        return Specialist::query()
            ->with('user')
            ->where('active', true)
            ->get()
            ->mapWithKeys(function (Specialist $specialist): array {
                $label = trim(
                    ($specialist->user?->name ?? '') . ' ' . ($specialist->user?->surname ?? '')
                );

                return [
                    $specialist->user_id => $label !== ''
                        ? $label
                        : "Usuario {$specialist->user_id}",
                ];
            })
            ->all();
    }
}
