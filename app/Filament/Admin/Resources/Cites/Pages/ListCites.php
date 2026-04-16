<?php

namespace App\Filament\Admin\Resources\Cites\Pages;

use App\Filament\Admin\Resources\Cites\CiteResource;
use Filament\Actions\Action; // Importante: usar la clase Action de Actions
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Artisan;
use Filament\Notifications\Notification;


class ListCites extends ListRecords
{
    protected static string $resource = CiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('availability')
                ->label('Disponibilidad')
                ->icon('heroicon-o-calendar-days')
                ->url(CiteResource::getUrl('availability'))
                ->color('gray'),
            Action::make('generateNextMonth')
                ->label('Generar próximo mes')
                ->color('info')
                ->icon('heroicon-m-calendar-days')
                ->requiresConfirmation() // Opcional: pide confirmar para evitar clicks accidentales
                ->modalHeading('Generar citas')
                ->modalDescription('¿Estás seguro de que quieres generar las citas para los próximos 30 días?')
                ->action(function () {
                    // Ejecutamos el comando
                    Artisan::call('cites:generate', [
                        'days' => 30,
                    ]);

                    // Enviamos una notificación de éxito al usuario
                    Notification::make()
                        ->title('Citas generadas correctamente')
                        ->success()
                        ->send();
                }),

            // CreateAction::make(),
        ];
    }
}
