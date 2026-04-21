<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Área - JM Clínica</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-[#f3f4f6] text-[#374151]">

    <div id="overlay" class="fixed inset-0 z-[1150] hidden bg-black/40 transition-opacity" onclick="closeProfile()"></div>

    <div id="reserveOverlay" class="fixed inset-0 z-[1250] hidden bg-black/50 backdrop-blur-[1px]"></div>

    <nav class="sticky top-0 z-[1100] flex h-16 items-center justify-between border-b border-[#e5e7eb] bg-white px-8">
        <a href="{{ route('home') }}" class="text-xl font-extrabold no-underline text-[#6ab7bd]">JM Clínica</a>

        <div class="flex items-center gap-4">
            <button onclick="openProfile()" class="flex items-center gap-2 rounded-lg border border-[#e5e7eb] bg-[#f9fafb] px-4 py-2 font-semibold transition hover:bg-gray-100">
                <div class="flex h-6 w-6 items-center justify-center rounded-full bg-[#6ab7bd] text-[10px] text-white">
                    {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr($user->surname, 0, 1)) }}
                </div>
                <span class="text-sm">Mi Perfil</span>
            </button>

            <form method="POST" action="{{ route('patient.logout') }}" class="m-0">
                @csrf
                <button type="submit" class="text-sm font-semibold text-red-500 hover:underline">
                    Salir
                </button>
            </form>
        </div>
    </nav>

    <div class="mx-auto flex max-w-[1400px] flex-col items-start gap-5 p-8 lg:flex-row">

        <aside class="sticky top-24 w-full rounded-xl border border-[#e5e7eb] bg-white p-6 shadow-sm lg:w-[320px]">
            <h3 class="mb-4 flex items-center gap-2 border-b border-[#e5e7eb] pb-2 font-bold text-[#374151]">
                Mis citas cogidas
            </h3>

            <div id="citas-container" class="space-y-3">
                @forelse ($upcomingReservations as $reservation)
                @php
                $cite = $reservation->cite;
                $service = $cite?->service;
                @endphp

                <div class="rounded-lg border border-[#e5e7eb] bg-[#f9fafb] p-3">
                    <span class="block text-[0.75rem] font-bold uppercase tracking-wide text-[#6ab7bd]">
                        {{ $reservation->status === 'confirmed' ? 'Cita confirmada' : 'Cita pendiente' }}
                    </span>

                    <div class="mt-1 text-sm font-semibold text-[#374151]">
                        {{ $service?->name ?? 'Tratamiento' }}
                        -
                        {{ \Carbon\Carbon::parse($cite?->date)->format('d/m/Y') }}
                        a las
                        {{ \Carbon\Carbon::parse($cite?->start_time)->format('H:i') }}
                    </div>

                    <div class="mt-3 flex flex-wrap gap-2">
                        @if ($reservation->status === 'pending')
                        <form method="POST" action="{{ route('patient.dashboard.cancel', $reservation) }}" onsubmit="return confirm('¿Seguro que quieres anular esta cita?');">
                            @csrf
                            <button type="submit" class="rounded-md border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-bold text-red-600 transition hover:bg-red-100">
                                Anular
                            </button>
                        </form>

                        <form method="POST" action="{{ route('patient.dashboard.confirm', $reservation) }}" onsubmit="return confirm('¿Quieres confirmar definitivamente esta cita? Después ya no podrás anularla.');">
                            @csrf
                            <button type="submit" class="rounded-md bg-[#6ab7bd] px-3 py-1.5 text-xs font-bold text-white transition hover:opacity-90">
                                Confirmar
                            </button>
                        </form>
                        @else
                        <span class="rounded-md border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700">
                            Confirmada
                        </span>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-xs italic text-gray-400">No tienes citas pendientes o futuras.</p>
                @endforelse
            </div>
        </aside>

        <main class="min-w-0 w-full flex-1">

            @if (session('success'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 shadow-sm">
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700 shadow-sm">
                {{ session('error') }}
            </div>
            @endif

            <div class="mb-6 flex flex-col items-center justify-between gap-4 rounded-xl border border-[#e5e7eb] bg-white p-6 shadow-sm md:flex-row">
                <form method="GET" action="{{ route('patient.dashboard') }}" class="w-full md:w-auto">
                    <label class="mb-1 block text-xs font-bold uppercase text-gray-400">1. Elige tu tratamiento</label>
                    <div class="flex flex-col gap-3 md:flex-row md:items-center">
                        <select
                            id="serviceSelect"
                            name="service_id"
                            onchange="this.form.submit()"
                            class="w-full rounded-lg border border-[#e5e7eb] p-3 font-bold text-[#6ab7bd] outline-none transition-all focus:border-[#6ab7bd] md:w-72">
                            <option value="">Selecciona tratamiento...</option>
                            @foreach ($services as $service)
                            <option value="{{ $service->id }}" @selected($selectedServiceId===(int) $service->id)>
                                {{ $service->name }}
                            </option>
                            @endforeach
                        </select>

                        <input type="hidden" name="week_start" value="{{ $weekStart->toDateString() }}">
                    </div>
                </form>

                <div class="flex gap-2">
                    <a
                        href="{{ route('patient.dashboard', ['service_id' => $selectedServiceId, 'week_start' => $weekStart->copy()->subWeek()->toDateString()]) }}"
                        class="rounded-lg border border-[#e5e7eb] bg-white px-4 py-2 text-sm font-semibold transition hover:bg-gray-50">
                        ← Anterior
                    </a>

                    <a
                        href="{{ route('patient.dashboard', ['service_id' => $selectedServiceId, 'week_start' => $weekStart->copy()->addWeek()->toDateString()]) }}"
                        class="rounded-lg border border-[#e5e7eb] bg-white px-4 py-2 text-sm font-semibold transition hover:bg-gray-50">
                        Siguiente →
                    </a>
                </div>
            </div>

            @if (! $selectedServiceId)
            <div id="welcome-msg" class="rounded-xl border border-[#e5e7eb] bg-white py-20 text-center shadow-sm">
                <h3 class="text-xl font-bold text-[#374151]">👋 Bienvenido, {{ $user->name }}.</h3>
                <p class="mt-2 text-gray-400">Selecciona el tratamiento para el que quieres pedir cita.</p>
            </div>
            @elseif ($timeRows->isEmpty())
            <div class="rounded-xl border border-[#e5e7eb] bg-white py-20 text-center shadow-sm">
                <h3 class="text-xl font-bold text-[#374151]">No hay huecos disponibles esta semana</h3>
                <p class="mt-2 text-gray-400">Prueba con otra semana o con otro tratamiento.</p>
            </div>
            @else
            <div id="mainCalendar" class="grid overflow-hidden rounded-xl border border-[#e5e7eb] bg-white shadow-sm" style="grid-template-columns: 80px repeat(5, minmax(0, 1fr));">
                <div class="border-b border-[#e5e7eb] bg-[#f9fafb]"></div>

                @foreach ($days as $day)
                <div class="border-b border-l border-[#e5e7eb] bg-[#f9fafb] p-4 text-center text-sm font-bold uppercase text-[#6ab7bd]">
                    {{ $day->locale('es')->translatedFormat('D') }}<br>
                    <span class="text-xs text-[#9ca3af]">{{ $day->format('d/m') }}</span>
                </div>
                @endforeach

                @foreach ($timeRows as $time)
                <div class="flex h-[70px] items-center justify-center border-b border-[#f3f4f6] text-[0.75rem] font-bold text-[#9ca3af]">
                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $time)->format('H:i') }}
                </div>

                @foreach ($days as $day)
                @php
                $slot = $slotsMatrix[$time][$day->toDateString()] ?? null;
                @endphp

                <div class="h-[70px] border-b border-l border-[#f3f4f6] p-1">
                    @if ($slot)
                    <button
                        type="button"
                        onclick="openReserveModal(this)"
                        data-service-id="{{ $slot['service_id'] }}"
                        data-service-name="{{ $slot['service_name'] }}"
                        data-date="{{ $slot['date'] }}"
                        data-date-label="{{ \Carbon\Carbon::parse($slot['date'])->format('d/m/Y') }}"
                        data-start-time="{{ $slot['start_time'] }}"
                        data-end-time="{{ $slot['end_time'] }}"
                        data-time-label="{{ substr($slot['start_time'], 0, 5) }} - {{ substr($slot['end_time'], 0, 5) }}"
                        data-room-id="{{ $slot['room_id'] ?? '' }}"
                        data-occupancy="{{ $slot['occupancy_label'] }}"
                        data-week-start="{{ $weekStart->toDateString() }}"
                        class="flex h-full w-full items-center justify-center rounded-md border border-dashed border-[#cbd5e1] px-2 text-[0.65rem] font-bold uppercase text-[#6ab7bd] transition-all hover:border-[#6ab7bd] hover:bg-[#f0fdfa]">
                        Disponible
                    </button>
                    @endif
                </div>
                @endforeach
                @endforeach
            </div>
            @endif
        </main>
    </div>

    <div id="profilePanel" class="fixed top-0 right-[-420px] z-[1200] h-full w-full max-w-[380px] overflow-y-auto bg-white p-8 shadow-2xl transition-all duration-300">
        <div class="mb-6 flex items-center justify-between">
            <h3 class="text-xl font-bold">Mi Perfil</h3>
            <button onclick="closeProfile()" class="text-xl text-gray-400 hover:text-black">&times;</button>
        </div>

        <form action="{{ route('patient.dashboard.profile.update') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="mb-1 block text-[0.7rem] font-extrabold uppercase text-[#9ca3af]">Nombre</label>
                <input type="text" name="name" class="w-full rounded-lg border border-[#e5e7eb] bg-gray-50 p-2.5 text-sm" value="{{ old('name', $user->name) }}">
            </div>

            <div>
                <label class="mb-1 block text-[0.7rem] font-extrabold uppercase text-[#9ca3af]">Apellidos</label>
                <input type="text" name="surname" class="w-full rounded-lg border border-[#e5e7eb] bg-gray-50 p-2.5 text-sm" value="{{ old('surname', $user->surname) }}">
            </div>

            <div>
                <label class="mb-1 block text-[0.7rem] font-extrabold uppercase text-[#9ca3af]">Email</label>
                <input type="email" name="email" class="w-full rounded-lg border border-[#e5e7eb] bg-gray-50 p-2.5 text-sm" value="{{ old('email', $user->email) }}">
            </div>

            <div>
                <label class="mb-1 block text-[0.7rem] font-extrabold uppercase text-[#9ca3af]">Teléfono</label>
                <input type="text" name="phone" class="w-full rounded-lg border border-[#e5e7eb] bg-gray-50 p-2.5 text-sm" value="{{ old('phone', $user->phone) }}">
            </div>

            <div>
                <label class="mb-1 block text-[0.7rem] font-extrabold uppercase text-[#9ca3af]">Fecha de Nacimiento</label>
                <input type="date" name="birth_date" class="w-full rounded-lg border border-[#e5e7eb] bg-gray-50 p-2.5 text-sm" value="{{ old('birth_date', optional($patient->birth_date)->format('Y-m-d') ?? $patient->birth_date) }}">
            </div>


            <div>
                <label class="mb-1 block text-[0.7rem] font-extrabold uppercase text-[#9ca3af]">Nueva contraseña</label>
                <input type="password" name="password" class="w-full rounded-lg border border-[#e5e7eb] bg-gray-50 p-2.5 text-sm">
            </div>

            <div>
                <label class="mb-1 block text-[0.7rem] font-extrabold uppercase text-[#9ca3af]">Repetir nueva contraseña</label>
                <input type="password" name="password_confirmation" class="w-full rounded-lg border border-[#e5e7eb] bg-gray-50 p-2.5 text-sm">
            </div>


            <button type="submit" class="mt-6 w-full rounded-lg bg-[#6ab7bd] py-3 font-bold text-white shadow-lg transition hover:opacity-90">
                Guardar cambios
            </button>
        </form>
    </div>

    <div id="reserveModal" class="fixed inset-0 z-[1300] hidden items-center justify-center px-4">
        <div class="w-full max-w-lg rounded-2xl border border-[#e5e7eb] bg-white p-6 shadow-2xl">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-xl font-bold text-[#374151]">Confirmar reserva</h3>
                    <p class="mt-1 text-sm text-gray-500">Revisa los datos antes de reservar tu cita.</p>
                </div>

                <button type="button" onclick="closeReserveModal()" class="text-xl text-gray-400 hover:text-black">&times;</button>
            </div>

            <div class="mt-5 rounded-xl border border-[#e5e7eb] bg-[#f9fafb] p-4">
                <p class="text-xs font-extrabold uppercase text-[#9ca3af]">Tratamiento</p>
                <p id="modalService" class="mt-1 font-semibold text-[#374151]"></p>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-extrabold uppercase text-[#9ca3af]">Fecha</p>
                        <p id="modalDate" class="mt-1 font-semibold text-[#374151]"></p>
                    </div>
                    <div>
                        <p class="text-xs font-extrabold uppercase text-[#9ca3af]">Horario</p>
                        <p id="modalTime" class="mt-1 font-semibold text-[#374151]"></p>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-extrabold uppercase text-[#9ca3af]">Ocupación actual</p>
                    <p id="modalOccupancy" class="mt-1 font-semibold text-[#374151]"></p>
                </div>
            </div>

            <form method="POST" action="{{ route('patient.dashboard.reserve') }}" class="mt-6">
                @csrf
                <input type="hidden" name="service_id" id="reserveServiceId">
                <input type="hidden" name="room_id" id="reserveRoomId">
                <input type="hidden" name="date" id="reserveDate">
                <input type="hidden" name="start_time" id="reserveStartTime">
                <input type="hidden" name="end_time" id="reserveEndTime">
                <input type="hidden" name="week_start" value="{{ $weekStart->toDateString() }}">

                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <button
                        type="button"
                        onclick="closeReserveModal()"
                        class="rounded-lg border border-[#e5e7eb] px-4 py-2.5 text-sm font-semibold text-[#374151] transition hover:bg-gray-50">
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="rounded-lg bg-[#6ab7bd] px-5 py-2.5 text-sm font-bold text-white transition hover:opacity-90">
                        Reservar cita
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openProfile() {
            document.getElementById('profilePanel').style.right = '0';
            document.getElementById('overlay').classList.remove('hidden');
        }

        function closeProfile() {
            document.getElementById('profilePanel').style.right = '-420px';
            document.getElementById('overlay').classList.add('hidden');
        }

        function openReserveModal(button) {
            document.getElementById('modalService').textContent = button.dataset.serviceName;
            document.getElementById('modalDate').textContent = button.dataset.dateLabel;
            document.getElementById('modalTime').textContent = button.dataset.timeLabel;
            document.getElementById('modalOccupancy').textContent = button.dataset.occupancy;

            document.getElementById('reserveServiceId').value = button.dataset.serviceId;
            document.getElementById('reserveRoomId').value = button.dataset.roomId || '';
            document.getElementById('reserveDate').value = button.dataset.date;
            document.getElementById('reserveStartTime').value = button.dataset.startTime;
            document.getElementById('reserveEndTime').value = button.dataset.endTime;

            document.getElementById('reserveOverlay').classList.remove('hidden');
            document.getElementById('reserveModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeReserveModal() {
            document.getElementById('reserveOverlay').classList.add('hidden');
            document.getElementById('reserveModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        document.getElementById('reserveOverlay').addEventListener('click', closeReserveModal);

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeProfile();
                closeReserveModal();
            }
        });
    </script>
</body>

</html>
