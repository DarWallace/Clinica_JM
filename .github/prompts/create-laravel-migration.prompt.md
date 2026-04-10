---
description: "Crear migración Laravel segura siguiendo patrón Expand → Migrate → Contract"
---
# Crear Migración Laravel

## Antes de generar
1. Leer las migraciones existentes para entender el esquema actual de la tabla afectada.
2. Verificar que no existe ya una migración que haga lo mismo.
3. Identificar si el cambio es destructivo (elimina/renombra columnas) o seguro (añade columnas/índices).

## Qué debe incluir la migración

### Método `up()`
- Tipos de columna correctos y específicos: `unsignedBigInteger` para IDs, `decimal` para dinero, `enum` o cast PHP para estados.
- Valores por defecto o `nullable()` para columnas nuevas (evitar romper registros existentes).
- Índices en columnas usadas en WHERE, ORDER BY o JOIN.
- Foreign keys con `constrained()` y `onDelete()` explícito.
- Comentario breve al inicio: qué hace y por qué.

### Método `down()`
- Rollback completo que deje la DB en el estado anterior exacto.
- Si el rollback es destructivo (pierde datos), documentarlo con comentario.

### Cambios destructivos
Si la migración elimina o renombra columnas/tablas, seguir el patrón **Expand → Migrate → Contract**:
1. **Migración 1 (Expand)**: añadir la nueva estructura sin eliminar la antigua.
2. **Job/Script (Migrate)**: mover datos de la estructura antigua a la nueva.
3. **Migración 2 (Contract)**: eliminar la estructura antigua (en un release posterior).

Proponer las migraciones separadas y el Job si aplica.

### Performance
- Si la tabla tiene >1M registros, advertir sobre bloqueo y proponer alternativas (migraciones por lotes, `pt-online-schema-change`).
- Documentar si requiere downtime o mantenimiento.

## Naming
`YYYY_MM_DD_HHMMSS_{accion}_{tabla}.php` — ejemplo: `2026_03_11_120000_add_status_to_orders.php`.

## Formato de salida
1. Código completo de la migración (up + down).
2. Si es destructivo: código de las migraciones adicionales y Job.
3. Comando para ejecutar y verificar: `php artisan migrate` y `php artisan migrate:rollback`.

## Instrucciones de referencia
Aplicar `migrations.instructions.md`.
