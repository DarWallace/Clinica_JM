<x-filament-panels::page>
    @php
        $slots = $this->slots instanceof \Illuminate\Support\Collection
            ? $this->slots
            : collect($this->slots ?? []);

        $slotsCount = $slots->count();
    @endphp

    <div class="space-y-6">
        <x-filament::section>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <p class="text-sm text-gray-500">Huecos encontrados</p>
                    <p class="mt-1 text-3xl font-bold text-primary-600">{{ $slotsCount }}</p>
                    <p class="mt-1 text-xs text-gray-500">
                        Calculados desde reglas de horario, excepciones y citas reales.
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Rango consultado</p>
                    <p class="mt-1 text-lg font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse($this->from_date)->format('d/m/Y') }}
                        -
                        {{ \Carbon\Carbon::parse($this->until_date)->format('d/m/Y') }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        Solo se muestran huecos que encajan en ese periodo.
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Filtro activo</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <x-filament::badge color="gray">
                            Servicio:
                            {{ $this->service_id ? ($this->services[$this->service_id] ?? 'Seleccionado') : 'Todos' }}
                        </x-filament::badge>
                    </div>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                Filtros de disponibilidad
            </x-slot>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Desde</label>
                    <input
                        type="date"
                        wire:model.live="from_date"
                        class="block w-full rounded-lg border-gray-300 shadow-sm"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Hasta</label>
                    <input
                        type="date"
                        wire:model.live="until_date"
                        class="block w-full rounded-lg border-gray-300 shadow-sm"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Servicio</label>
                    <select
                        wire:model.live="service_id"
                        class="block w-full rounded-lg border-gray-300 shadow-sm"
                    >
                        <option value="">Todos</option>
                        @foreach ($this->services as $id => $label)
                            <option value="{{ $id }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                Huecos disponibles
            </x-slot>

            @if ($slotsCount > 0)
                <div class="overflow-hidden rounded-xl border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Fecha / hora</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Tratamiento</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Sala</th>
                                    <th class="px-4 py-3 text-center font-semibold text-gray-700">Tipo</th>
                                    <th class="px-4 py-3 text-center font-semibold text-gray-700">Ocupación</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Estado</th>
                                    <th class="px-4 py-3 text-right font-semibold text-gray-700">Acción</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach ($slots as $slot)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-900">
                                                {{ \Carbon\Carbon::parse($slot['date'])->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ substr($slot['start_time'], 0, 5) }} - {{ substr($slot['end_time'], 0, 5) }}
                                            </div>
                                        </td>

                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-900">
                                                {{ $slot['service_name'] }}
                                            </div>
                                        </td>

                                        <td class="px-4 py-3">
                                            <div class="text-sm text-gray-700">
                                                {{ $slot['room_name'] ?: 'Sin sala' }}
                                            </div>
                                        </td>

                                        <td class="px-4 py-3 text-center">
                                            @if ($slot['type'] === 'group')
                                                <x-filament::badge color="info">Grupal</x-filament::badge>
                                            @else
                                                <x-filament::badge color="gray">Individual</x-filament::badge>
                                            @endif
                                        </td>

                                        <td class="px-4 py-3 text-center">
                                            <x-filament::badge color="gray">
                                                {{ $slot['occupancy_label'] }}
                                            </x-filament::badge>
                                        </td>

                                        <td class="px-4 py-3">
                                            @if ($slot['is_available'])
                                                <x-filament::badge color="success">Disponible</x-filament::badge>
                                            @else
                                                <x-filament::badge color="danger">Completa</x-filament::badge>
                                            @endif
                                        </td>

                                        <td class="px-4 py-3 text-right">
                                            @if ($slot['is_available'])
                                                <x-filament::button size="sm" color="success">
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
</x-filament-panels::page>
