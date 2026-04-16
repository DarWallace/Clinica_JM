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
                ->label('Nueva cita')
                ->icon('heroicon-o-calendar-days')
                ->url(CiteResource::getUrl('availability'))
                ->color('primary')
                ->size('lg')
                ->button()
                ->extraAttributes([
                    'class' => 'font-semibold shadow-sm',
                ]),
        ];
    }
}
