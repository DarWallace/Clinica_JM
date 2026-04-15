<?php

namespace App\Filament\Admin\Resources\Cites\Pages;

use App\Filament\Admin\Resources\Cites\CiteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCites extends ListRecords
{
    protected static string $resource = CiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
