{{-- resources/views/filament/admin/resources/schedule-rules/pages/view-session.blade.php --}}
<div style="display: flex; flex-direction: column; gap: 20px; padding: 10px;">

    <div style="display: flex; align-items: center; gap: 15px;">
        <div style="flex-shrink: 0; color: #9ca3af;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
        </div>
        <div style="display: flex; flex-direction: column;">
            <span style="font-size: 10px; font-weight: bold; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px;">Servicio</span>
            <span style="font-size: 15px; font-weight: 600; color: #111827;">{{ $rule->service->name }}</span>
        </div>
    </div>

    <div style="display: flex; align-items: center; gap: 15px;">
        <div style="flex-shrink: 0; color: #9ca3af;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <div style="display: flex; flex-direction: column;">
            <span style="font-size: 10px; font-weight: bold; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px;">Especialista</span>
            <span style="font-size: 15px; font-weight: 600; color: #111827;">{{ $rule->service->specialist?->name ?? $rule->service->specialist?->user?->name ?? 'No asignado' }}</span>
        </div>
    </div>

    <div style="display: flex; align-items: center; gap: 15px;">
        <div style="flex-shrink: 0; color: #9ca3af;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16"/><path d="M2 11h18"/><path d="M2 17h20"/><path d="M22 8v12"/><path d="M7 8v3"/></svg>
        </div>
        <div style="display: flex; flex-direction: column;">
            <span style="font-size: 10px; font-weight: bold; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px;">Lugar</span>
            <span style="font-size: 15px; font-weight: 600; color: #111827;">{{ $rule->room?->name ?? 'Sin sala' }}</span>
        </div>
    </div>

    <div style="margin-top: 10px; padding: 12px; background-color: #f9fafb; border-radius: 8px; border: 1px solid #f3f4f6;">
        <p style="font-size: 10px; font-weight: bold; color: #9ca3af; text-transform: uppercase; margin-bottom: 4px;">Descripción / Notas</p>
        <p style="font-size: 13px; color: #4b5563; font-style: italic; margin: 0;">
            {{ $rule->service->description ?: 'No hay descripción escrita para esta regla.' }}
        </p>
    </div>

    <div style="display: flex; justify-content: flex-end; margin-top: 15px; border-top: 1px solid #f3f4f6; padding-top: 15px;">
        <a href="{{ \App\Filament\Admin\Resources\ScheduleRules\ScheduleRuleResource::getUrl('edit', ['record' => (int) $rule->id]) }}"
           style="display: inline-flex; align-items: center; gap: 8px; background-color: white; border: 1px solid #d1d5db; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; color: #374151; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Editar esta regla de horario
        </a>
    </div>

</div>
