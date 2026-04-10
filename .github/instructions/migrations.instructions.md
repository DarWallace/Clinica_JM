---
applyTo: "**/migrations/*.php"
description: "Reglas para migraciones de base de datos"
---

# MIGRATIONS — REGLAS

## Principio: migraciones seguras, reversibles y documentadas
Cada migración es un cambio atómico en el esquema de datos que debe poder ejecutarse y revertirse sin ambigüedad.

## Estructura
- Un cambio lógico por migración. No mezclar cambios no relacionados.
- Naming: `YYYY_MM_DD_HHMMSS_accion_tabla.php` (ej: `2026_03_11_120000_add_status_to_orders.php`).
- Siempre definir `up()` y `down()`. Si el rollback no es viable, documentar por qué en un comentario.

## Estrategia de cambios de esquema

### Cambios no destructivos (safe)
- Añadir columnas con valores por defecto o nullable.
- Añadir índices.
- Crear tablas nuevas.

### Cambios destructivos (requieren plan)
Seguir el patrón **Expand → Migrate → Contract**:
1. **Expand**: añadir la nueva columna/tabla sin eliminar la antigua.
2. **Migrate**: mover datos de la estructura antigua a la nueva (con Job o migración de datos separada).
3. **Contract**: eliminar la estructura antigua en una migración posterior, una vez confirmado que todo funciona.

- Nunca eliminar columnas o tablas directamente en una sola migración sin plan de rollback.
- Las migraciones de datos van en un Job o en una migración separada, nunca mezcladas con cambios de esquema.

## Rendimiento en tablas grandes
- Evitar `ALTER TABLE` bloqueante en tablas con millones de registros. Considerar herramientas como `pt-online-schema-change` o migraciones por lotes.
- Los índices en tablas grandes deben crearse con `ALGORITHM=INPLACE` o de forma asíncrona si el motor lo soporta.
- Documentar en la migración si requiere mantenimiento o downtime.

## Reglas de integridad
- Definir foreign keys con `constrained()` y `onDelete()` explícito.
- Usar tipos de columna apropiados: `unsignedBigInteger` para IDs, `decimal` para dinero, `text` para contenido largo.
- No usar `string` para datos que deberían ser `enum`, `boolean` o `integer`.
- Añadir índices para columnas que se usen en WHERE, ORDER BY o JOIN frecuentemente.

## Rollback
- El `down()` debe dejar la base de datos en el estado anterior exacto.
- Si el rollback es destructivo (pierde datos), documentar y pedir confirmación explícita.
- Testear rollback en entorno de staging antes de producción.

## Anti-patrones
- ❌ Migraciones que mezclan cambios de esquema con datos (seeds).
- ❌ Eliminar columnas/tablas sin plan de migración de datos.
- ❌ Migraciones con lógica condicional compleja (if/else basados en datos existentes).
- ❌ Usar `raw()` o SQL directo sin justificación (usar Schema Builder).
- ❌ Ignorar el `down()` o dejarlo vacío sin documentar por qué.
