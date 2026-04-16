<x-filament-panels::page>
    @php
     $availableSlots = collect($this->availableSlots ?? []);
    $slotsCount = $availableSlots->count();
    $availableCount = $availableSlots->where('is_available', true)->count();
    @endphp

    <div class="space-y-6">
        <x-filament::section>
            <div style="display:grid; grid-template-columns:repeat(3, minmax(0, 1fr)); gap:18px;">
                <div style="
            background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
            border: 1px solid #dbeafe;
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 8px 20px rgba(30, 64, 175, 0.06);
        ">
                    <div style="font-size:13px; font-weight:600; color:#2563eb; text-transform:uppercase; letter-spacing:.04em;">
                        Huecos encontrados
                    </div>
                    <div style="margin-top:10px; font-size:34px; font-weight:800; color:#111827; line-height:1;">
                        {{ $availableCount  }}
                    </div>
                    <div style="margin-top:10px; font-size:13px; color:#6b7280;">
                        Disponibilidad calculada en tiempo real según reglas, reservas y excepciones.
                    </div>
                </div>

                <div style="
            background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
        ">
                    <div style="font-size:13px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:.04em;">
                        Rango consultado
                    </div>
                    <div style="margin-top:10px; font-size:22px; font-weight:700; color:#111827; line-height:1.2;">
                        {{ \Carbon\Carbon::parse($this->from_date)->format('d/m/Y') }}
                        <span style="color:#9ca3af; font-weight:500;">—</span>
                        {{ \Carbon\Carbon::parse($this->until_date)->format('d/m/Y') }}
                    </div>
                    <div style="margin-top:10px; font-size:13px; color:#6b7280;">
                        Se muestran únicamente los huecos que encajan dentro de este periodo.
                    </div>
                </div>

                <div style="
            background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
            border: 1px solid #bbf7d0;
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 8px 20px rgba(22, 163, 74, 0.05);
        ">
                    <div style="font-size:13px; font-weight:600; color:#15803d; text-transform:uppercase; letter-spacing:.04em;">
                        Filtro activo
                    </div>

                    <div style="margin-top:12px; display:flex; flex-wrap:wrap; gap:8px;">
                        <x-filament::badge color="success">
                            Servicio:
                            {{ $this->service_id ? ($this->services[$this->service_id] ?? 'Seleccionado') : 'Todos' }}
                        </x-filament::badge>
                    </div>

                    <div style="margin-top:10px; font-size:13px; color:#6b7280;">
                        La sala se determina automáticamente según el tratamiento y su regla de horario.
                    </div>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                Buscar disponibilidad
            </x-slot>

            <div style="
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%);
        padding: 22px;
    ">
                <div style="margin-bottom:18px;">
                    <div style="font-size:16px; font-weight:700; color:#111827;">
                        Selección de fecha y tratamiento
                    </div>

                </div>

                <div style="display:grid; grid-template-columns:repeat(3, minmax(0, 1fr)); gap:18px;">
                    <div>
                        <label style="display:block; margin-bottom:8px; font-size:13px; font-weight:700; color:#374151;">
                            Desde
                        </label>
                        <div style="
                    border:1px solid #d1d5db;
                    border-radius:14px;
                    background:white;
                    padding:10px 12px;
                    box-shadow: inset 0 1px 2px rgba(0,0,0,.03);
                ">
                            <input
                                type="date"
                                wire:model.live="from_date"
                                style="
                            width:100%;
                            border:0;
                            outline:none;
                            background:transparent;
                            font-size:14px;
                            color:#111827;
                        ">
                        </div>
                    </div>

                    <div>
                        <label style="display:block; margin-bottom:8px; font-size:13px; font-weight:700; color:#374151;">
                            Hasta
                        </label>
                        <div style="
                    border:1px solid #d1d5db;
                    border-radius:14px;
                    background:white;
                    padding:10px 12px;
                    box-shadow: inset 0 1px 2px rgba(0,0,0,.03);
                ">
                            <input
                                type="date"
                                wire:model.live="until_date"
                                style="
                            width:100%;
                            border:0;
                            outline:none;
                            background:transparent;
                            font-size:14px;
                            color:#111827;
                        ">
                        </div>
                    </div>

                    <div>
                        <label style="display:block; margin-bottom:8px; font-size:13px; font-weight:700; color:#374151;">
                            Tratamiento
                        </label>
                        <div style="
                    border:1px solid #d1d5db;
                    border-radius:14px;
                    background:white;
                    padding:10px 12px;
                    box-shadow: inset 0 1px 2px rgba(0,0,0,.03);
                ">
                            <select
                                wire:model.live="service_id"
                                style="
                            width:100%;
                            border:0;
                            outline:none;
                            background:transparent;
                            font-size:14px;
                            color:#111827;
                        ">
                                <option value="">Todos</option>
                                @foreach ($this->services as $id => $label)
                                <option value="{{ $id }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div style="
            margin-top:18px;
            display:flex;
            flex-wrap:wrap;
            gap:10px;
            align-items:center;
            justify-content:space-between;
        ">


                    <div style="font-size:12px; color:#6b7280;">
                        Los resultados se actualizan automáticamente al cambiar los filtros.
                    </div>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                Huecos disponibles
            </x-slot>


            @if ($slotsCount > 0)
            <div style="
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        overflow: hidden;
        background: white;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
    ">
                <div style="
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:16px 20px;
            border-bottom:1px solid #e5e7eb;
            background:#fafafa;
        ">
                    <div>
                        <div style="font-size:16px; font-weight:700; color:#111827;">Citas</div>

                    </div>

                    <div style="
                font-size:13px;
                color:#374151;
                background:#f3f4f6;
                border:1px solid #e5e7eb;
                border-radius:999px;
                padding:8px 12px;
                white-space:nowrap;
            ">
                        {{ $slotsCount }} huecos
                    </div>
                </div>

                <div style="overflow-x:auto;">
                    <table style="
                width:100%;
                min-width:1200px;
                border-collapse:separate;
                border-spacing:0;
                font-size:14px;
            ">
                        <thead>
                            <tr style="background:#f9fafb;">
                                <th style="padding:14px 18px; text-align:left; font-weight:700; color:#374151; border-bottom:1px solid #e5e7eb; width:180px;">
                                    Fecha / hora
                                </th>
                                <th style="padding:14px 18px; text-align:left; font-weight:700; color:#374151; border-bottom:1px solid #e5e7eb; width:260px;">
                                    Tratamiento
                                </th>
                                <th style="padding:14px 18px; text-align:left; font-weight:700; color:#374151; border-bottom:1px solid #e5e7eb; width:180px;">
                                    Sala
                                </th>
                                <th style="padding:14px 18px; text-align:center; font-weight:700; color:#374151; border-bottom:1px solid #e5e7eb; width:120px;">
                                    Tipo
                                </th>
                                <th style="padding:14px 18px; text-align:center; font-weight:700; color:#374151; border-bottom:1px solid #e5e7eb; width:130px;">
                                    Ocupación
                                </th>
                                <th style="padding:14px 18px; text-align:left; font-weight:700; color:#374151; border-bottom:1px solid #e5e7eb; width:140px;">
                                    Estado
                                </th>
                                <th style="padding:14px 18px; text-align:right; font-weight:700; color:#374151; border-bottom:1px solid #e5e7eb; width:140px;">
                                    Acción
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($availableSlots as $index => $slot)
                            <tr style="background:white;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='white'">
                                <td style="padding:16px 18px; border-bottom:1px solid #f3f4f6; vertical-align:top;">
                                    <div style="font-weight:700; color:#111827;">
                                        {{ \Carbon\Carbon::parse($slot['date'])->format('d/m/Y') }}
                                    </div>
                                    <div style="margin-top:4px; font-size:12px; color:#6b7280;">
                                        {{ substr($slot['start_time'], 0, 5) }} - {{ substr($slot['end_time'], 0, 5) }}
                                    </div>
                                </td>

                                <td style="padding:16px 18px; border-bottom:1px solid #f3f4f6; vertical-align:top;">
                                    <div style="font-weight:700; color:#111827;">
                                        {{ $slot['service_name'] }}
                                    </div>

                                </td>

                                <td style="padding:16px 18px; border-bottom:1px solid #f3f4f6; vertical-align:top; color:#374151;">
                                    {{ $slot['room_name'] ?: 'Sin sala' }}
                                </td>

                                <td style="padding:16px 18px; border-bottom:1px solid #f3f4f6; text-align:center; vertical-align:top;">
                                    @if ($slot['type'] === 'group')
                                    <x-filament::badge color="info">Grupal</x-filament::badge>
                                    @else
                                    <x-filament::badge color="gray">Individual</x-filament::badge>
                                    @endif
                                </td>

                                <td style="padding:16px 18px; border-bottom:1px solid #f3f4f6; text-align:center; vertical-align:top;">
                                    <x-filament::badge color="gray">
                                        {{ $slot['occupancy_label'] }}
                                    </x-filament::badge>
                                </td>

                                <td style="padding:16px 18px; border-bottom:1px solid #f3f4f6; vertical-align:top;">
                                    @if ($slot['is_available'])
                                    <x-filament::badge color="success">Disponible</x-filament::badge>
                                    @else
                                    <x-filament::badge color="danger">Completa</x-filament::badge>
                                    @endif
                                </td>

                                <td style="padding:16px 18px; border-bottom:1px solid #f3f4f6; text-align:right; vertical-align:top;">
                                    @if ($slot['is_available'])
                                    <x-filament::button
                                        type="button"
                                        size="sm"
                                        color="success"
                                        wire:click="openBookingModalByIndex({{ $index }})">
                                        Reservar
                                    </x-filament::button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="py-12 text-center">
                <div class="mx-auto max-w-2xl">
                    <h3 class="text-base font-semibold text-gray-900">No se encontraron huecos</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Revisa las reglas de horario, el rango de fechas, el tratamiento y las excepciones.
                    </p>

                    <div class="mt-6 space-y-2 text-sm text-gray-600">
                        <p>
                            <strong>Rango:</strong>
                            {{ \Carbon\Carbon::parse($this->from_date)->format('d/m/Y') }}
                            -
                            {{ \Carbon\Carbon::parse($this->until_date)->format('d/m/Y') }}
                        </p>
                        <p>
                            <strong>Servicio:</strong>
                            {{ $this->service_id ? ($this->services[$this->service_id] ?? 'Seleccionado') : 'Todos' }}
                        </p>
                        <p>
                            <strong>Huecos encontrados:</strong> {{ $slotsCount }}
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </x-filament::section>
    </div>




    @if ($showBookingModal && $selectedSlot)
    <div style="
        position: fixed;
        inset: 0;
        z-index: 99999;
        background: rgba(0,0,0,0.45);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    ">
        <div style="
            width: 100%;
            max-width: 800px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
            overflow: hidden;
        ">
            <div style="
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 20px 24px;
                border-bottom: 1px solid #e5e7eb;
            ">
                <div>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700; color: #111827;">
                        Reservar cita
                    </h3>
                    <p style="margin: 6px 0 0; font-size: 14px; color: #6b7280;">
                        Busca y selecciona el paciente para este hueco.
                    </p>
                </div>

                <button
                    type="button"
                    wire:click="closeBookingModal"
                    style="
                        border: 0;
                        background: transparent;
                        font-size: 14px;
                        color: #6b7280;
                        cursor: pointer;
                    ">
                    Cerrar
                </button>
            </div>

            <div style="padding: 24px; display: grid; gap: 20px;">
                <div style="
                    border: 1px solid #e5e7eb;
                    background: #f9fafb;
                    border-radius: 12px;
                    padding: 16px;
                    font-size: 14px;
                    color: #374151;
                ">
                    <div><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($selectedSlot['date'])->format('d/m/Y') }}</div>
                    <div><strong>Hora:</strong> {{ substr($selectedSlot['start_time'], 0, 5) }} - {{ substr($selectedSlot['end_time'], 0, 5) }}</div>
                    <div><strong>Tratamiento:</strong> {{ $selectedSlot['service_name'] }}</div>
                    <div><strong>Sala:</strong> {{ $selectedSlot['room_name'] ?: 'Sin sala' }}</div>
                    <div><strong>Ocupación:</strong> {{ $selectedSlot['occupancy_label'] }}</div>
                </div>

                <div>
                    <label style="display:block; margin-bottom:8px; font-size:14px; font-weight:600; color:#374151;">
                        Buscar paciente
                    </label>

                    <input
                        type="text"
                        wire:model.live.debounce.300ms="patientSearch"
                        placeholder="Escribe nombre, apellidos, email o teléfono..."
                        style="
                            width: 100%;
                            border: 1px solid #d1d5db;
                            border-radius: 10px;
                            padding: 12px 14px;
                            font-size: 14px;
                        ">
                </div>

                @if ($selectedPatientId && $selectedPatientLabel)
                <div style="
                        border: 1px solid #bbf7d0;
                        background: #f0fdf4;
                        color: #166534;
                        border-radius: 10px;
                        padding: 12px 14px;
                        font-size: 14px;
                    ">
                    <strong>Paciente seleccionado:</strong> {{ $selectedPatientLabel }}
                </div>
                @endif

                @if (! empty($patientResults))
                <div style="
                        max-height: 260px;
                        overflow-y: auto;
                        border: 1px solid #e5e7eb;
                        border-radius: 12px;
                    ">
                    @foreach ($patientResults as $patient)
                    <button
                        type="button"
                        wire:click="selectPatient({{ $patient['id'] }}, @js($patient['label']))"
                        style="
                                    width: 100%;
                                    text-align: left;
                                    background: white;
                                    border: 0;
                                    border-bottom: 1px solid #f3f4f6;
                                    padding: 14px 16px;
                                    cursor: pointer;
                                ">
                        <div style="font-weight: 600; color: #111827;">
                            {{ $patient['label'] }}
                        </div>
                        <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">
                            {{ $patient['email'] ?: 'Sin email' }}
                            @if($patient['phone'])
                            · {{ $patient['phone'] }}
                            @endif
                        </div>
                    </button>
                    @endforeach
                </div>
                @elseif(strlen(trim($patientSearch)) >= 2)
                <div style="
                        border: 1px solid #e5e7eb;
                        background: #f9fafb;
                        color: #6b7280;
                        border-radius: 10px;
                        padding: 12px 14px;
                        font-size: 14px;
                    ">
                    No se han encontrado pacientes con esa búsqueda.
                </div>
                @endif

                <div style="display:flex; justify-content:flex-end; gap:12px;">
                    <button
                        type="button"
                        wire:click="closeBookingModal"
                        style="
                            border: 1px solid #d1d5db;
                            background: white;
                            color: #374151;
                            border-radius: 10px;
                            padding: 10px 16px;
                            font-size: 14px;
                            cursor: pointer;
                        ">
                        Cancelar
                    </button>

                    <button
                        type="button"
                        wire:click="bookSelectedSlot"
                        style="
                            border: 0;
                            background: #16a34a;
                            color: white;
                            border-radius: 10px;
                            padding: 10px 16px;
                            font-size: 14px;
                            cursor: pointer;
                        ">
                        Confirmar reserva
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</x-filament-panels::page>
