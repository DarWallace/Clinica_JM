<x-filament-panels::page>
    @php
        $availableSlots = collect($this->availableSlots ?? []);
        $slotsCount = $availableSlots->count();
        $availableCount = $availableSlots->where('is_available', true)->count();
    @endphp

    <div class="space-y-6">
        {{-- Cabecera de Estadísticas con Degradados --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">

            <div style="background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%); border: 1px solid #dbeafe; border-radius: 18px; padding: 24px; box-shadow: 0 10px 25px rgba(37, 99, 235, 0.1);">
                <div style="font-size: 12px; font-weight: 700; color: #2563eb; text-transform: uppercase; letter-spacing: .05em;">
                    Huecos Libres
                </div>
                <div style="margin-top: 12px; font-size: 40px; font-weight: 800; color: #111827; line-height: 1;">
                    {{ $availableCount }}
                </div>
                <div style="margin-top: 12px; font-size: 13px; color: #64748b; line-height: 1.5;">
                    Disponibilidad calculada en tiempo real.
                </div>
            </div>

            <div style="background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%); border: 1px solid #e2e8f0; border-radius: 18px; padding: 24px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03);">
                <div style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .05em;">
                    Periodo de Consulta
                </div>
                <div style="margin-top: 12px; font-size: 20px; font-weight: 700; color: #1e293b; line-height: 1.2;">
                    {{ \Carbon\Carbon::parse($this->from_date)->format('d/m/Y') }}
                    <span style="color: #cbd5e1; margin: 0 4px;">—</span>
                    {{ \Carbon\Carbon::parse($this->until_date)->format('d/m/Y') }}
                </div>
                <div style="margin-top: 12px; font-size: 13px; color: #64748b;">
                    Solo se muestran huecos en este rango.
                </div>
            </div>

            <div style="background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%); border: 1px solid #bbf7d0; border-radius: 18px; padding: 24px; box-shadow: 0 10px 25px rgba(22, 163, 74, 0.05);">
                <div style="font-size: 12px; font-weight: 700; color: #166534; text-transform: uppercase; letter-spacing: .05em;">
                    Tratamiento Seleccionado
                </div>
                <div style="margin-top: 12px;">
                    <x-filament::badge color="success" size="lg">
                        {{ $this->service_id ? ($this->services[$this->service_id] ?? 'Seleccionado') : 'Todos los servicios' }}
                    </x-filament::badge>
                </div>
                <div style="margin-top: 12px; font-size: 13px; color: #64748b;">
                    La sala se asigna según la regla de horario.
                </div>
            </div>
        </div>

        {{-- Formulario de Búsqueda --}}
        <x-filament::section>
            <x-slot name="heading">
                <span style="font-weight: 800; letter-spacing: -0.02em;">Panel de Búsqueda Avanzada</span>
            </x-slot>

            <div style="background: linear-gradient(180deg, #ffffff 0%, #f9fafb 100%); border: 1px solid #e5e7eb; border-radius: 20px; padding: 24px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 700; color: #374151;">Fecha Inicial</label>
                        <x-filament::input.wrapper>
                            <x-filament::input type="date" wire:model.live="from_date" />
                        </x-filament::input.wrapper>
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 700; color: #374151;">Fecha Final</label>
                        <x-filament::input.wrapper>
                            <x-filament::input type="date" wire:model.live="until_date" />
                        </x-filament::input.wrapper>
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 700; color: #374151;">Tratamiento</label>
                        <x-filament::input.wrapper>
                            <x-filament::input.select wire:model.live="service_id">
                                <option value="">Todos los tratamientos</option>
                                @foreach ($this->services as $id => $label)
                                    <option value="{{ $id }}">{{ $label }}</option>
                                @endforeach
                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                    </div>
                </div>
            </div>
        </x-filament::section>

        {{-- Tabla de Resultados Estilizada --}}
        <x-filament::section>
            <x-slot name="heading">
                <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                    <span style="font-weight: 800; letter-spacing: -0.02em;">Listado de Huecos</span>

                    <div style="font-size: 13px; color: #374151; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 999px; padding: 4px 14px; font-weight: 600;">
                        {{ $slotsCount }} huecos encontrados
                    </div>
                </div>
            </x-slot>
            @if ($slotsCount > 0)
                <div style="border: 1px solid #e5e7eb; border-radius: 20px; overflow: hidden; background: white; box-shadow: 0 15px 35px rgba(0,0,0,0.05);">
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
                            <thead>
                                <tr style="background: #f8fafc;">
                                    <th style="padding: 16px 20px; text-align: left; font-size: 12px; font-weight: 800; color: #64748b; text-transform: uppercase; border-bottom: 2px solid #f1f5f9;">FECHA Y HORA</th>
                                    <th style="padding: 16px 20px; text-align: left; font-size: 12px; font-weight: 800; color: #64748b; text-transform: uppercase; border-bottom: 2px solid #f1f5f9;">TRATAMIENTO / SALA</th>
                                    <th style="padding: 16px 20px; text-align: center; font-size: 12px; font-weight: 800; color: #64748b; text-transform: uppercase; border-bottom: 2px solid #f1f5f9;">TIPO / OCUPACIÓN</th>
                                    <th style="padding: 16px 20px; text-align: center; font-size: 12px; font-weight: 800; color: #64748b; text-transform: uppercase; border-bottom: 2px solid #f1f5f9;">ESTADO</th>
                                    <th style="padding: 16px 20px; text-align: right; border-bottom: 2px solid #f1f5f9;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($availableSlots as $index => $slot)
                                    <tr style="transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f1f5f9'" onmouseout="this.style.backgroundColor='transparent'">
                                        <td style="padding: 18px 20px; border-bottom: 1px solid #f1f5f9;">
                                            <div style="font-weight: 700; color: #1e293b; font-size: 15px;">{{ \Carbon\Carbon::parse($slot['date'])->format('d/m/Y') }}</div>
                                            <div style="font-size: 13px; color: #64748b; margin-top: 2px;">{{ substr($slot['start_time'], 0, 5) }} - {{ substr($slot['end_time'], 0, 5) }}</div>
                                        </td>
                                        <td style="padding: 18px 20px; border-bottom: 1px solid #f1f5f9;">
                                            <div style="font-weight: 600; color: #334155;">{{ $slot['service_name'] }}</div>
                                            <div style="font-size: 12px; color: #94a3b8; margin-top: 2px;">Sala: {{ $slot['room_name'] ?: 'No asignada' }}</div>
                                        </td>
                                        <td style="padding: 18px 20px; border-bottom: 1px solid #f1f5f9; text-align: center;">
                                            <div style="display: flex; flex-direction: column; gap: 4px; align-items: center;">
                                                <x-filament::badge color="gray" size="sm">{{ $slot['type'] === 'group' ? 'Grupal' : 'Individual' }}</x-filament::badge>
                                                <span style="font-size: 11px; font-weight: 600; color: #64748b;">{{ $slot['occupancy_label'] }}</span>
                                            </div>
                                        </td>
                                        <td style="padding: 18px 20px; border-bottom: 1px solid #f1f5f9; text-align: center;">
                                            @if ($slot['is_available'])
                                                <x-filament::badge color="success" icon="heroicon-m-check-circle">Disponible</x-filament::badge>
                                            @else
                                                <x-filament::badge color="danger" icon="heroicon-m-x-circle">Completo</x-filament::badge>
                                            @endif
                                        </td>
                                        <td style="padding: 18px 20px; border-bottom: 1px solid #f1f5f9; text-align: right;">
                                            @if ($slot['is_available'])
                                                <x-filament::button
                                                    wire:click="openBookingModalByIndex({{ $index }})"
                                                    color="success"
                                                    size="sm"
                                                    icon="heroicon-m-calendar-days"
                                                    style="border-radius: 10px; box-shadow: 0 4px 12px rgba(22, 163, 74, 0.2);"
                                                >
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
                {{-- Empty State con diseño --}}
                <div style="text-align: center; padding: 60px 20px; background: #f9fafb; border-radius: 20px; border: 2px dashed #e2e8f0;">
                    <div style="font-size: 48px; margin-bottom: 16px;">🔍</div>
                    <h3 style="font-size: 18px; font-weight: 700; color: #1e293b;">No hay huecos disponibles</h3>
                    <p style="color: #64748b; max-width: 400px; margin: 8px auto;">Intenta ampliar el rango de fechas o seleccionar otro tratamiento.</p>
                </div>
            @endif
        </x-filament::section>
    </div>

    {{-- Modal de Reserva Estilizado --}}
    @if ($showBookingModal && $selectedSlot)
    <div style="position: fixed; inset: 0; z-index: 9999; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; padding: 20px;">
        <div style="width: 100%; max-width: 600px; background: white; border-radius: 24px; box-shadow: 0 30px 60px rgba(0,0,0,0.3); overflow: hidden; animation: modalIn 0.3s ease-out;">

            <div style="padding: 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: flex-start; background: linear-gradient(to right, #ffffff, #f8fafc);">
                <div>
                    <h3 style="font-size: 22px; font-weight: 800; color: #1e293b; letter-spacing: -0.02em;">Nueva Reserva</h3>
                    <p style="font-size: 14px; color: #64748b; margin-top: 4px;">Completa los datos del paciente para confirmar.</p>
                </div>
                <button wire:click="closeBookingModal" style="color: #94a3b8; border: 0; background: transparent; cursor: pointer; padding: 4px;">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            </div>

            <div style="padding: 30px; display: grid; gap: 24px;">
                {{-- Resumen del Slot --}}
                <div style="background: #f1f5f9; border-radius: 16px; padding: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 13px;">
                    <div><span style="display:block; color:#64748b; font-weight:600; margin-bottom:2px;">FECHA</span> <span style="font-weight:700; color:#1e293b;">{{ \Carbon\Carbon::parse($selectedSlot['date'])->format('d/m/Y') }}</span></div>
                    <div><span style="display:block; color:#64748b; font-weight:600; margin-bottom:2px;">HORARIO</span> <span style="font-weight:700; color:#1e293b;">{{ substr($selectedSlot['start_time'], 0, 5) }} - {{ substr($selectedSlot['end_time'], 0, 5) }}</span></div>
                    <div style="grid-column: span 2;"><span style="display:block; color:#64748b; font-weight:600; margin-bottom:2px;">TRATAMIENTO</span> <span style="font-weight:700; color:#1e293b;">{{ $selectedSlot['service_name'] }}</span></div>
                </div>

                {{-- Buscador --}}
                <div>
                    <label style="display: block; margin-bottom: 10px; font-size: 14px; font-weight: 700; color: #334155;">Buscar Paciente</label>
                    <x-filament::input.wrapper icon="heroicon-m-magnifying-glass">
                        <x-filament::input
                            type="text"
                            wire:model.live.debounce.300ms="patientSearch"
                            placeholder="Nombre, DNI o teléfono..."
                        />
                    </x-filament::input.wrapper>
                </div>

                {{-- Resultados de búsqueda --}}
                @if (!empty($patientResults))
                    <div style="max-height: 200px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 12px;">
                        @foreach ($patientResults as $patient)
                            <button wire:click="selectPatient({{ $patient['id'] }}, @js($patient['label']))"
                                style="width:100%; text-align:left; padding:12px 16px; border-bottom:1px solid #f1f5f9; background:white; cursor:pointer;"
                                onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                                <div style="font-weight:700; color:#1e293b;">{{ $patient['label'] }}</div>
                                <div style="font-size:12px; color:#64748b;">{{ $patient['email'] }} · {{ $patient['phone'] }}</div>
                            </button>
                        @endforeach
                    </div>
                @endif

                {{-- Paciente Seleccionado --}}
                @if ($selectedPatientId)
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 16px; border-radius: 12px; display: flex; align-items: center; gap: 12px;">
                        <div style="color: #16a34a;"><x-heroicon-s-check-circle class="w-6 h-6" /></div>
                        <div>
                            <div style="font-size:12px; color:#166534; font-weight:700;">PACIENTE SELECCIONADO</div>
                            <div style="font-weight:700; color:#064e3b;">{{ $selectedPatientLabel }}</div>
                        </div>
                    </div>
                @endif

                {{-- Botonera --}}
                <div style="display: flex; gap: 12px; margin-top: 10px;">
                    <x-filament::button color="gray" wire:click="closeBookingModal" style="flex: 1; border-radius: 12px;">
                        Cancelar
                    </x-filament::button>
                    <x-filament::button
                        color="success"
                        wire:click="bookSelectedSlot"
                        style="flex: 2; border-radius: 12px; box-shadow: 0 10px 20px rgba(22, 163, 74, 0.2);"
                        :disabled="!$selectedPatientId"
                    >
                        Confirmar Reserva Ahora
                    </x-filament::button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        @keyframes modalIn {
            from { opacity: 0; transform: translateY(20px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
    </style>
</x-filament-panels::page>
