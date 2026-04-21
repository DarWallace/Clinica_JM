<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cite;
use App\Models\Patient;
use App\Models\Reservation;
use App\Models\Service;
use App\Services\Cites\BuildVirtualCiteSlotsService;
use App\Services\Cites\CiteConflictService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;



class PatientDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $patient = Patient::query()
            ->where('user_id', $user->id)
            ->firstOrFail();

        $services = Service::query()
            ->where('active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $selectedServiceId = $request->filled('service_id')
            ? (int) $request->input('service_id')
            : null;

        $weekStart = $request->filled('week_start')
            ? Carbon::parse((string) $request->input('week_start'))->startOfWeek(Carbon::MONDAY)
            : now()->startOfWeek(Carbon::MONDAY);

        $weekEnd = $weekStart->copy()->addDays(4);

        $days = collect(range(0, 4))
            ->map(fn (int $offset) => $weekStart->copy()->addDays($offset));

        $upcomingReservations = Reservation::query()
            ->select('reservations.*')
            ->join('cites', 'cites.id', '=', 'reservations.cite_id')
            ->where('reservations.patient_id', $patient->getKey())
            ->whereIn('reservations.status', ['pending', 'confirmed'])
            ->whereDate('cites.date', '>=', now()->toDateString())
            ->where('cites.status', '!=', 'cancelled')
            ->with([
                'cite.service',
            ])
            ->orderBy('cites.date')
            ->orderBy('cites.start_time')
            ->get();

        $slots = collect();
        $timeRows = collect();
        $slotsMatrix = [];

        if ($selectedServiceId) {
            $slots = collect(
                app(BuildVirtualCiteSlotsService::class)->handle(
                    from: $weekStart->toDateString(),
                    until: $weekEnd->toDateString(),
                    serviceId: $selectedServiceId,
                    roomId: null,
                )
            )
                ->filter(fn (array $slot): bool => (bool) ($slot['is_available'] ?? false))
                ->sortBy([
                    ['date', 'asc'],
                    ['start_time', 'asc'],
                ])
                ->values();

            $timeRows = $slots->pluck('start_time')
                ->unique()
                ->sort()
                ->values();

            foreach ($slots as $slot) {
                $slotsMatrix[$slot['start_time']][$slot['date']] = $slot;
            }
        }

        return view('patient.dashboard_p', [
            'user' => $user,
            'patient' => $patient,
            'services' => $services,
            'selectedServiceId' => $selectedServiceId,
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
            'days' => $days,
            'upcomingReservations' => $upcomingReservations,
            'slotsMatrix' => $slotsMatrix,
            'timeRows' => $timeRows,
        ]);
    }

    public function reserve(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $patient = Patient::query()
            ->where('user_id', $user->id)
            ->firstOrFail();

        $data = $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'room_id' => ['nullable', 'integer'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i:s'],
            'end_time' => ['required', 'date_format:H:i:s'],
            'week_start' => ['nullable', 'date'],
        ]);

        try {
            DB::transaction(function () use ($data, $patient): void {
                $service = Service::query()->findOrFail((int) $data['service_id']);

                $roomId = filled($data['room_id'] ?? null)
                    ? (int) $data['room_id']
                    : null;

                $conflict = app(CiteConflictService::class)->findBlockingConflict(
                    date: $data['date'],
                    startTime: $data['start_time'],
                    endTime: $data['end_time'],
                    specialistId: (int) $service->specialist_id,
                    roomId: $roomId,
                    ignoreCiteId: null,
                );

                if ($conflict) {
                    throw new \RuntimeException('Ese hueco ya no está disponible.');
                }

                $cite = Cite::query()
                    ->lockForUpdate()
                    ->where('service_id', $service->id)
                    ->whereDate('date', $data['date'])
                    ->whereTime('start_time', $data['start_time'])
                    ->whereTime('end_time', $data['end_time'])
                    ->when(
                        filled($roomId),
                        fn ($query) => $query->where('room_id', $roomId),
                        fn ($query) => $query->whereNull('room_id'),
                    )
                    ->first();

                if (! $cite) {
                    $cite = Cite::create([
                        'service_id' => $service->id,
                        'room_id' => $roomId,
                        'date' => $data['date'],
                        'start_time' => $data['start_time'],
                        'end_time' => $data['end_time'],
                        'status' => 'active',
                    ]);
                } elseif ($cite->status === 'cancelled') {
                    $cite->update([
                        'status' => 'active',
                    ]);
                }

                $activeReservations = Reservation::query()
                    ->where('cite_id', $cite->id)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->get();

                $alreadyExists = $activeReservations
                    ->contains(fn ($reservation) => (int) $reservation->patient_id === (int) $patient->getKey());

                if ($alreadyExists) {
                    throw new \RuntimeException('Ya tienes una reserva para ese hueco.');
                }

                $capacity = $service->type === 'group'
                    ? max(1, (int) ($service->max_patients ?: 1))
                    : 1;

                if ($activeReservations->count() >= $capacity) {
                    throw new \RuntimeException('La cita ya no tiene plazas disponibles.');
                }

                Reservation::create([
                    'cite_id' => $cite->id,
                    'patient_id' => $patient->getKey(),
                    'status' => 'pending',
                    'payment_status' => 'pending',
                ]);
            });

            return redirect()
                ->route('patient.dashboard', [
                    'service_id' => $data['service_id'],
                    'week_start' => $data['week_start'] ?? null,
                ])
                ->with('success', 'Tu cita se ha reservado correctamente. Ahora puedes confirmarla o anularla.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('patient.dashboard', [
                    'service_id' => $data['service_id'],
                    'week_start' => $data['week_start'] ?? null,
                ])
                ->with('error', $e->getMessage());
        }
    }

    public function confirm(Reservation $reservation): RedirectResponse
    {
        $patient = Patient::query()
            ->where('user_id', Auth::id())
            ->firstOrFail();

        abort_unless((int) $reservation->patient_id === (int) $patient->getKey(), 403);

        $reservation->load('cite');

        if (! $reservation->cite || Carbon::parse($reservation->cite->date)->lt(now()->startOfDay())) {
            return redirect()
                ->route('patient.dashboard')
                ->with('error', 'No se puede confirmar una cita pasada.');
        }

        if ($reservation->status !== 'pending') {
            return redirect()
                ->route('patient.dashboard')
                ->with('error', 'Solo se pueden confirmar citas pendientes.');
        }

        $reservation->update([
            'status' => 'confirmed',
        ]);

        if ($reservation->cite->status === 'cancelled') {
            $reservation->cite->update([
                'status' => 'active',
            ]);
        }

        return redirect()
            ->route('patient.dashboard')
            ->with('success', 'La cita ha quedado confirmada.');
    }

    public function cancel(Reservation $reservation): RedirectResponse
    {
        $patient = Patient::query()
            ->where('user_id', Auth::id())
            ->firstOrFail();

        abort_unless((int) $reservation->patient_id === (int) $patient->getKey(), 403);

        $reservation->load('cite');

        if (! $reservation->cite || Carbon::parse($reservation->cite->date)->lt(now()->startOfDay())) {
            return redirect()
                ->route('patient.dashboard')
                ->with('error', 'No se puede anular una cita pasada.');
        }

        if ($reservation->status !== 'pending') {
            return redirect()
                ->route('patient.dashboard')
                ->with('error', 'Solo se pueden anular citas pendientes.');
        }

        DB::transaction(function () use ($reservation): void {
            $reservation->update([
                'status' => 'cancelled',
            ]);

            $activeReservationsCount = Reservation::query()
                ->where('cite_id', $reservation->cite_id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->count();

            if ($activeReservationsCount === 0 && $reservation->cite) {
                $reservation->cite->update([
                    'status' => 'cancelled',
                ]);
            }
        });

        return redirect()
            ->route('patient.dashboard')
            ->with('success', 'La cita se ha anulado correctamente.');
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            abort(403);
        }

        $patient = Patient::query()
            ->where('user_id', $user->id)
            ->firstOrFail();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30', Rule::unique('users', 'phone')->ignore($user->id)],
            'birth_date' => ['nullable', 'date'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'] ?: null,
            'phone' => $data['phone'] ?: null,
        ]);

        if (! empty($data['password'])) {
            $user->update([
                'password' => Hash::make($data['password']),
            ]);
        }

        $patient->update([
            'birth_date' => $data['birth_date'] ?: null,

        ]);

        return redirect()
            ->route('patient.dashboard')
            ->with('success', 'Tus datos se han actualizado correctamente.');
    }
}
