<?php

namespace App\Filament\Admin\Resources\Cites\Pages;

use App\Filament\Admin\Resources\Cites\CiteResource;
use App\Models\Service;
use App\Services\Cites\BuildVirtualCiteSlotsService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;

class CitesAvailability extends Page
{
    protected static string $resource = CiteResource::class;

    protected static ?string $title = 'Disponibilidad';

    protected string $view = 'filament.admin.resources.cites.pages.cites-availability';

    public ?string $from_date = null;
    public ?string $until_date = null;
    public $service_id = null;

    public array $availableSlots = [];

    public function mount(): void
    {
        $this->from_date = now()->toDateString();
        $this->until_date = now()->copy()->addDays(7)->toDateString();

        $this->refreshSlots();
    }

    public function updated($property): void
    {
        if (in_array($property, ['from_date', 'until_date', 'service_id'])) {
            $this->refreshSlots();
        }
    }

    protected function refreshSlots(): void
    {
        $this->availableSlots = app(BuildVirtualCiteSlotsService::class)->handle(
            from: Carbon::parse($this->from_date ?: now()->toDateString()),
            until: Carbon::parse($this->until_date ?: now()->copy()->addDays(7)->toDateString()),
            serviceId: filled($this->service_id) ? (int) $this->service_id : null,
            roomId: null,
        );
    }

    public function getServicesProperty(): array
    {
        return Service::query()
            ->where('active', true)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('backToIndex')
                ->label('Ver citas reales')
                ->url(CiteResource::getUrl('index'))
                ->color('gray'),
        ];
    }
}
