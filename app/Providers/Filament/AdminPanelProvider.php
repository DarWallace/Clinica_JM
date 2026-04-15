<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\Filament\Admin\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\Filament\Admin\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\Filament\Admin\Widgets')

            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])

            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentFullCalendarPlugin::make()
                    ->selectable()
                    ->editable()
                    ->config([
                        'initialView' => 'timeGridWeek',
                        'locale' => 'es',
                        'eventOverlap' => false,      // Evita visualmente que se encabalquen si son la misma sala
                        'slotEventOverlap' => false,  // Los pone uno al lado del otro en lugar de encima
                        'firstDay' => 1, // Lunes como primer día
                        'hiddenDays' => [0, 6], // 0 = Domingo, 6 = Sábado. ¡Adiós al fin de semana!
                        'slotMinTime' => '08:00:00',
                        'slotMaxTime' => '22:00:00',
                        'height' => 'auto',
                        // ESTO ES LO QUE BUSCAS:
                        'slotLabelFormat' => [
                            'hour' => 'numeric',
                            'minute' => '2-digit',
                            'omitZeroMinute' => false, // Obliga a mostrar el :00
                            'meridiem' => false,
                            'hour12' => false, // Formato 24h
                        ],
                        // También para el formato de hora dentro de los eventos (las barritas)
                        'eventTimeFormat' => [
                            'hour' => 'numeric',
                            'minute' => '2-digit',
                            'meridiem' => false,
                            'hour12' => false,
                        ],
                    ])
            ]);
    }
}
