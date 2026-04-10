---
description: "Revisar y optimizar un Resource de Filament existente"
---
# Optimizar Resource Filament existente

## Antes de revisar
1. Leer el Resource completo y su modelo asociado.
2. Leer los RelationManagers existentes.
3. Leer la Policy asociada (si existe).
4. Leer los tests existentes del Resource (si los hay).

## Checklist de revisión

### Estructura
- [ ] ¿Sigue la estructura oficial? (`form()`, `table()`, `getRelations()`, `getPages()`).
- [ ] ¿Hay lógica de negocio en el Resource? → Mover a Service.
- [ ] ¿Hay campos en el formulario que no están en `$fillable`?
- [ ] ¿Las relaciones editables tienen RelationManager?

### Formulario
- [ ] ¿Validación en cada campo o en FormRequest?
- [ ] ¿Campos agrupados con Section/Grid/Tabs si son >6?
- [ ] ¿UX helpers? (`placeholder`, `helperText`, `hint`).
- [ ] ¿Campos condicionales con `->visible()` en vez de JS?

### Tabla
- [ ] ¿Solo columnas relevantes? (no todas las del modelo).
- [ ] ¿`searchable()` en texto clave, `sortable()` en fechas/numéricos?
- [ ] ¿Filtros útiles para el usuario?
- [ ] ¿Acciones destructivas con `->requiresConfirmation()`?
- [ ] ¿Paginación razonable?

### Performance
- [ ] ¿Eager loading en `::getEloquentQuery()`?
- [ ] ¿N+1 en columnas que muestran relaciones?
- [ ] ¿Queries costosas en widgets sin cache?

### Seguridad
- [ ] ¿Policy definida con todos los métodos necesarios?
- [ ] ¿Scope de datos por tenant/usuario en `::getEloquentQuery()`?
- [ ] ¿Permisos en Policy, no hardcodeados en vista?

## Formato de salida
1. **Resumen**: qué problemas se encontraron (bullets).
2. **Cambios propuestos**: código con los cambios aplicados directamente.
3. **Performance**: mejoras de queries si aplican.
4. **Seguridad**: gaps identificados.

## Instrucciones de referencia
Aplicar `filament.instructions.md`.
