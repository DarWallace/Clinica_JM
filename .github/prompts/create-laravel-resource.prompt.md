---
description: "Crear o revisar un Resource de Filament completo con estructura oficial, policies y optimización"
---
# Crear / Revisar Resource Filament

## Antes de generar
1. Leer el modelo Eloquent asociado: propiedades, relaciones, casts, scopes.
2. Verificar si ya existe un Resource para este modelo (no duplicar).
3. Leer las Policies existentes del modelo.
4. Identificar las relaciones que necesitan RelationManagers.

## Estructura del Resource

### `form()`
- Campos alineados con `$fillable` del modelo.
- Validación en cada campo (`.required()`, `.maxLength()`, `.rules()`) o en FormRequest.
- Agrupar con `Section`, `Grid` o `Tabs` para formularios con >6 campos.
- Usar `placeholder()`, `helperText()` y `hint()` para guiar al usuario.
- Campos condicionales con `->visible()` / `->hidden()`, no con JS custom.
- Relaciones: `Select::make()->relationship()->searchable()` con preload si la tabla es pequeña.

### `table()`
- Columnas relevantes (no mostrar todas las del modelo).
- `searchable()` en columnas de texto clave, `sortable()` en fechas y numéricos.
- `toggleable()` para columnas secundarias.
- Filtros: `SelectFilter` para estados/categorías, `Filter` custom para rangos de fechas.
- Acciones: `ViewAction`, `EditAction`, `DeleteAction` con `->requiresConfirmation()` en destructivas.
- `BulkActions` para operaciones masivas si aplican.
- Paginación: 25 por defecto.

### `getRelations()`
- Un `RelationManager` por cada relación HasMany/BelongsToMany editable.
- Naming: `{Relacion}RelationManager`.

### `getPages()`
- Páginas estándar: List, Create, Edit (y View si aplica).
- Custom Pages solo si los flujos estándar no cubren el caso.

## Seguridad
- Verificar que existe una Policy para el modelo. Si no, crearla con: `viewAny`, `view`, `create`, `update`, `delete`, `restore`, `forceDelete`.
- Scope de datos en `::getEloquentQuery()` si hay multi-tenancy o filtrado por usuario.
- No hardcodear permisos en la vista: usar Policy.

## Performance
- Eager loading en `::getEloquentQuery()` para columnas que muestran relaciones.
- Evitar N+1: no usar `->getStateUsing()` con queries individuales en columnas de tabla.
- Cachear contadores en widgets asociados.

## Formato de salida
1. Código completo del Resource.
2. Policy (si no existe).
3. RelationManagers necesarios.
4. Comando para verificar: `php artisan make:filament-resource --help` (como referencia).

## Instrucciones de referencia
Aplicar `filament.instructions.md`, `models.instructions.md`.
