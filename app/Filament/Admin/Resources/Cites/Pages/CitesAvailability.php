<?php

namespace App\Filament\Admin\Resources\Cites\Pages;

use App\Filament\Admin\Resources\Cites\CiteResource;
use App\Models\Service;
use App\Services\Cites\BuildVirtualCiteSlotsService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use App\Models\Cite;
use App\Models\Patient;
use App\Models\Reservation;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Filament\Support\Enums\Width;

class CitesAvailability extends Page
{
    protected static string $resource = CiteResource::class;

    protected static ?string $title = 'Disponibilidad';

    protected string $view = 'filament.admin.resources.cites.pages.cites-availability';
    
    protected Width|string|null $maxContentWidth = Width::Full;

    public ?string $from_date = null;
    public ?string $until_date = null;
    public $service_id = null;

    public bool $showBookingModal = false;
    public ?array $selectedSlot = null;

    public string $patientSearch = '';
    public ?int $selectedPatientId = null;
    public ?string $selectedPatientLabel = null;

    public array $patientResults = [];

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

   public function openBookingModalByIndex(int $index): void
{
    $slot = $this->availableSlots[$index] ?? null;

    if (! $slot) {
        return;
    }

    if (! ($slot['is_available'] ?? false)) {
        return;
    }

    $this->selectedSlot = $slot;
    $this->showBookingModal = true;

    $this->patientSearch = '';
    $this->selectedPatientId = null;
    $this->selectedPatientLabel = null;
    $this->patientResults = [];
}

public function closeBookingModal(): void
{
    $this->showBookingModal = false;
    $this->selectedSlot = null;
    $this->patientSearch = '';
    $this->selectedPatientId = null;
    $this->selectedPatientLabel = null;
    $this->patientResults = [];
}

public function updatedPatientSearch(): void
{
    $search = trim($this->patientSearch);

    if (mb_strlen($search) < 2) {
        $this->patientResults = [];
        return;
    }

    $this->patientResults = Patient::query()
        ->join('users', 'patients.user_id', '=', 'users.id')
        ->where(function ($query) use ($search) {
            $query
                ->where('users.name', 'like', "%{$search}%")
                ->orWhere('users.surname', 'like', "%{$search}%")
                ->orWhereRaw("CONCAT(users.name, ' ', COALESCE(users.surname, '')) like ?", ["%{$search}%"])
                ->orWhere('users.email', 'like', "%{$search}%")
                ->orWhere('users.phone', 'like', "%{$search}%");
        })
        ->orderBy('users.name')
        ->limit(10)
        ->get([
            'patients.user_id as id',
            DB::raw("CONCAT(users.name, ' ', COALESCE(users.surname, '')) as label"),
            'users.email',
            'users.phone',
        ])
        ->map(fn ($row) => [
            'id' => (int) $row->id,
            'label' => $row->label,
            'email' => $row->email,
            'phone' => $row->phone,
        ])
        ->toArray();
}

public function selectPatient(int $patientId, string $label): void
{
    $this->selectedPatientId = $patientId;
    $this->selectedPatientLabel = $label;
    $this->patientSearch = $label;
    $this->patientResults = [];
}

public function bookSelectedSlot(): void
{
    if (! $this->selectedSlot) {
        Notification::make()
            ->title('No hay hueco seleccionado')
            ->danger()
            ->send();

        return;
    }

    if (! $this->selectedPatientId) {
        Notification::make()
            ->title('Selecciona un paciente')
            ->danger()
            ->send();

        return;
    }

    try {
        DB::transaction(function () {
            $slot = $this->selectedSlot;
            $patientId = $this->selectedPatientId;
            $capacity = max(1, (int) ($slot['capacity'] ?? 1));

            $citeQuery = Cite::query()
                ->where('service_id', $slot['service_id'])
                ->whereDate('date', $slot['date'])
                ->whereTime('start_time', $slot['start_time'])
                ->whereTime('end_time', $slot['end_time']);

            if (! empty($slot['room_id'])) {
                $citeQuery->where('room_id', $slot['room_id']);
            } else {
                $citeQuery->whereNull('room_id');
            }

            $cite = $citeQuery->lockForUpdate()->first();

            if ($cite && $cite->status === 'completed') {
                throw new \RuntimeException('La cita ya está completada.');
            }

            if (! $cite) {
                $cite = Cite::create([
                    'service_id' => $slot['service_id'],
                    'room_id' => $slot['room_id'],
                    'date' => $slot['date'],
                    'start_time' => $slot['start_time'],
                    'end_time' => $slot['end_time'],
                    'status' => 'confirmed',
                ]);
            } elseif ($cite->status === 'cancelled') {
                $cite->update([
                    'status' => 'confirmed',
                ]);
            }

            $patientAlreadyBooked = $cite->reservations()
                ->where('patient_id', $patientId)
                ->where('status', 'confirmed')
                ->exists();

            if ($patientAlreadyBooked) {
                throw new \RuntimeException('Ese paciente ya está asignado a este hueco.');
            }

            $activeReservations = $cite->reservations()
                ->where('status', 'confirmed')
                ->count();

            if ($activeReservations >= $capacity) {
                throw new \RuntimeException('Ese hueco ya no tiene plazas disponibles.');
            }

            Reservation::create([
                'cite_id' => $cite->id,
                'patient_id' => $patientId,
                'status' => 'confirmed',
                'payment_status' => 'pending',
            ]);
        });
    } catch (\Throwable $exception) {
        Notification::make()
            ->title('No se ha podido reservar la cita')
            ->body($exception->getMessage())
            ->danger()
            ->send();

        return;
    }

    $this->closeBookingModal();
    $this->refreshSlots();

    Notification::make()
        ->title('Cita reservada correctamente')
        ->success()
        ->send();
}
}
