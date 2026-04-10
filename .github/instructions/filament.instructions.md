---
applyTo: "app/Filament/**"
description: "Reglas para Resources, Pages y Widgets de Filament"
---

# FILAMENT — REGLAS

## Principio: seguir la API oficial de Filament, mantener lógica en Services

## Resources

### Estructura
- Seguir estructura oficial: `form()`, `table()`, `infolist()`, `getRelations()`, `getPages()`.
- Un Resource por modelo principal. No crear Resources para modelos auxiliares que se gestionan como relaciones.
- No duplicar Resources ya existentes: verificar antes de crear.

### Formularios (`form()`)
- Usar componentes de Filament nativos (`TextInput`, `Select`, `DatePicker`, etc.).
- Validación en el FormRequest o directamente en las reglas del componente (`.rules()`).
- Agrupar campos con `Section`, `Grid`, `Tabs` para mejorar UX.
- Usar `placeholder()`, `helperText()` y `hint()` para guiar al usuario.
- Campos condicionales con `->visible()` o `->hidden()` en vez de lógica JS custom.

### Tablas (`table()`)
- Definir columnas explícitamente: no mostrar todas las columnas del modelo.
- Usar `searchable()`, `sortable()` y `toggleable()` según convenga.
- Filtros con `Filter` y `SelectFilter` para facilitar búsqueda.
- Acciones en tabla: usar `Action`, `BulkAction` con confirmación para acciones destructivas.
- Paginación razonable (25-50 por defecto).

### Relaciones
- Usar `RelationManagers` para relaciones HasMany/BelongsToMany.
- Un RelationManager por relación. Naming: `{Relacion}RelationManager`.

## Pages
- Mantener lógica mínima en Pages: mover operaciones a Services o Actions.
- Usar `HeaderActions` para acciones principales.
- Custom Pages solo cuando los flujos estándar (Create/Edit/View/List) no cubran el caso.

## Widgets
- Dashboard widgets para métricas y resúmenes.
- Usar `StatsOverviewWidget` para contadores, `ChartWidget` para gráficos.
- Cachear datos costosos en widgets con `->pollingInterval()` o cache manual.
- No cargar datos pesados en widgets del dashboard: consultar datos agregados.

## Performance
- Optimizar queries: eager loading con `::getEloquentQuery()`.
- Evitar N+1 en tablas: usar `->relationship()` o `->getStateUsing()` con queries optimizadas.
- Cachear contadores y métricas que se calculen frecuentemente.
- Lazy-load relaciones en formularios con `Select::make()->relationship()->searchable()`.

## Seguridad
- Policies obligatorias para cada Resource: `viewAny`, `view`, `create`, `update`, `delete`.
- No poner lógica de permisos en vistas o componentes Filament: usar Policies.
- Proteger acciones sensibles con `->requiresConfirmation()` y verificación adicional si es necesario.
- Filtrar datos por tenant/usuario cuando aplique (scope en `::getEloquentQuery()`).

## Personalización
- Usar Filament Actions para operaciones custom en vez de botones HTML manuales.
- Notifications de Filament para feedback al usuario (`Notification::make()->success()`).
- Temas y branding: configurar en `AdminPanelProvider`, no hardcodear en componentes.

## Instalación (Windows)
- Evitar ejecución automática de `php artisan filament:install` en Windows sin auditar artefactos; puede fallar o generar conflictos con providers.
- Si falla, ejecutar instalación manualmente y verificar providers registrados en `config/app.php`.
- Publicar assets con `php artisan filament:assets` después de actualizaciones.

## Anti-patrones
- ❌ Lógica de negocio en `form()`, `table()` o `mount()` de Pages.
- ❌ Queries directas en Resources: usar el query builder del Resource o un Service.
- ❌ Permisos hardcodeados en vistas (`@if(auth()->user()->role === 'admin')`).
- ❌ Widgets con queries sin cachear que se ejecutan en cada page load.
- ❌ Custom Pages para flujos que Filament resuelve nativamente (CRUD estándar).
