<?php

namespace App\Filament\Admin\Resources\ScheduleRules\Widgets;

use App\Models\ScheduleRule;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use App\Filament\Admin\Resources\ScheduleRules\ScheduleRuleResource;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Action;

class CalendarWidget extends FullCalendarWidget implements HasActions
{
    use InteractsWithActions;

    public function fetchEvents(array $fetchInfo): array
    {
        return ScheduleRule::query()
            ->with(['service', 'room'])
            ->get()
            ->map(function ($rule) {
                $roomColors = [
                    1 => '#3b82f6', // Azul
                    2 => '#10b981', // Verde
                    3 => '#f59e0b', // Naranja
                    4 => '#8b5cf6', // Violeta
                    5 => '#ec4899', // Rosa
                ];

                $color = $roomColors[$rule->room_id] ?? '#64748b';


                return [
                    'id'    => $rule->id,
                    'title' => ($rule->service?->name ?? 'Servicio') . ($rule->room ? " - {$rule->room->name}" : ""),
                    'startRecur' => $rule->valid_from?->format('Y-m-d'), // Fecha en la que empieza a ser válida la regla
                    'endRecur'   => $rule->valid_until?->format('Y-m-d'), // Fecha en la que termina la validez

                    'startTime'  => $rule->start_time,
                    'endTime'    => $rule->end_time,
                    'daysOfWeek' => [(int)($rule->day_of_week == 7 ? 0 : $rule->day_of_week)],
                    'backgroundColor' => $color,
                    'borderColor'     => $color,
                    'textColor'       => '#ffffff',
                    'allDay'          => false,
                    'editable' => false,
                    'startEditable' => false,
                    'durationEditable' => false,
                ];
            })
            ->toArray();
    }

    public function onEventClick(array $info): void
    {
        // Extraemos el ID del evento
        $recordId = $info['event']['id'] ?? $info['id'] ?? null;

        if ($recordId) {
            // Esto dispara el modal definido en getFormActions
            $this->mountAction('viewSession', ['record' => $recordId]);
        }
    }

    /**
     * IMPORTANTE: En los Widgets de Saade, las acciones se registran aquí
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('viewSession')
                ->modalHeading("Detalles de la Sesión")
                ->modalContent(function (array $arguments) {
                    $rule = ScheduleRule::query()
                        ->with(['service.specialist', 'room'])
                        ->find($arguments['record']);

                    if (! $rule) return "No se encontraron datos.";

                    return view('filament.admin.resources.schedule-rules.pages.view-session', [
                        'rule' => $rule,
                    ]);
                })
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Cerrar'),
        ];
    }

    // Mantenemos este por compatibilidad
    protected function getActions(): array
    {
        return $this->getFormActions();
    }


    public function headerActions(): array
    {
        return [
            Action::make('create')
                ->label('Nueva Regla')
                ->color('primary')
                ->url(fn(): string => ScheduleRuleResource::getUrl('create')),
        ];
    }

    public function onSelect(array $info): void
    {
        $this->redirect(ScheduleRuleResource::getUrl('create'));
    }

    public function getFormSchema(): array
    {
        return [];
    }
}
