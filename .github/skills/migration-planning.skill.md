---
name: "migration-planning"
description: "Planificar y ejecutar migraciones de base de datos seguras en producción"
triggers: ["migración producción", "migration production", "alter table", "cambio de esquema", "rollback", "downtime", "zero downtime"]
---

# Skill: Migration Planning

## Cuándo aplicar
- Al planificar migraciones que irán a producción.
- Cuando una migración afecta tablas con millones de registros.
- Cuando se necesita cambio de esquema sin downtime.
- Al ejecutar migraciones destructivas (renombrar, eliminar columnas/tablas).

## Clasificación de riesgo

| Operación | Riesgo | Lock de tabla | Tiempo estimado |
|-----------|--------|---------------|-----------------|
| Añadir columna nullable | 🟢 Bajo | No (MySQL 8.0+) | Instantáneo |
| Añadir columna NOT NULL + default | 🟢 Bajo | No (MySQL 8.0+) | Instantáneo |
| Añadir índice | 🟡 Medio | No con ALGORITHM=INPLACE | Depende del tamaño |
| Renombrar columna | 🟠 Alto | No (MySQL 8.0+) | Instantáneo, pero requiere código compatible |
| Cambiar tipo de columna | 🟠 Alto | Puede lockear | Depende del tamaño |
| Eliminar columna | 🟠 Alto | No, pero irreversible | Instantáneo |
| Eliminar tabla | 🔴 Crítico | No, pero irreversible | Instantáneo |
| Añadir FK a tabla grande | 🟠 Alto | Puede lockear | Depende del tamaño |
| Migración de datos (UPDATE masivo) | 🔴 Crítico | Puede lockear | Lineal al volumen |

## Patrón: Expand → Migrate → Contract

Para cambios destructivos sin downtime, dividir en 3 fases:

### Ejemplo: Renombrar columna `name` → `full_name`

**Fase 1 — EXPAND (deploy 1)**
```php
// Migración: añadir nueva columna
Schema::table('users', function (Blueprint $table) {
    $table->string('full_name')->nullable()->after('name');
});

// Modelo: escribir en ambas columnas
class User extends Model {
    protected static function booted() {
        static::saving(function ($user) {
            $user->full_name = $user->full_name ?? $user->name;
        });
    }
}
```

**Fase 2 — MIGRATE (script/job)**
```php
// Backfill: copiar datos existentes
User::whereNull('full_name')->chunkById(1000, function ($users) {
    foreach ($users as $user) {
        $user->update(['full_name' => $user->name]);
    }
});
```

**Fase 3 — CONTRACT (deploy 2, después de verificar)**
```php
// Migración: eliminar columna vieja
Schema::table('users', function (Blueprint $table) {
    $table->dropColumn('name');
});

// Modelo: usar solo full_name
```

**Entre cada fase:**
- Deploy a producción.
- Verificar que todo funciona.
- Monitorear errores.
- Mínimo 24h entre Expand y Contract en producción.

## Migraciones en tablas grandes (>1M filas)

### Estimar tiempo
```sql
-- Ver tamaño de la tabla
SELECT 
    TABLE_NAME, TABLE_ROWS, 
    ROUND(DATA_LENGTH / 1024 / 1024, 2) AS 'Data MB',
    ROUND(INDEX_LENGTH / 1024 / 1024, 2) AS 'Index MB'
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'my_database' AND TABLE_NAME = 'my_table';
```

### Crear índice sin lock
```sql
-- MySQL 8.0+
ALTER TABLE orders ADD INDEX idx_status_created (status, created_at), ALGORITHM=INPLACE, LOCK=NONE;
```

```php
// Laravel — con raw para controlar algorithm
DB::statement('ALTER TABLE orders ADD INDEX idx_status_created (status, created_at) ALGORITHM=INPLACE LOCK=NONE');
```

### UPDATE masivo sin lockear
```php
// ❌ Nunca
DB::table('orders')->update(['processed' => true]);

// ✅ En batches
DB::table('orders')
    ->where('processed', false)
    ->chunkById(1000, function ($orders) {
        $ids = $orders->pluck('id');
        DB::table('orders')->whereIn('id', $ids)->update(['processed' => true]);
    });

// ✅ O con job queued para operaciones muy grandes
dispatch(new BackfillOrdersProcessed());
```

## Checklist pre-migración

### Antes del deploy
- [ ] ¿La migración tiene `down()`? (rollback posible).
- [ ] ¿Se testeó en staging con volumen similar a producción?
- [ ] ¿Se estimó el tiempo de ejecución?
- [ ] ¿Hay backup reciente de la base de datos?
- [ ] ¿El código es compatible con el esquema anterior Y el nuevo? (para deploy sin downtime).
- [ ] ¿Se avisó al equipo?

### Durante el deploy
- [ ] Monitorear tiempo de ejecución de la migración.
- [ ] Monitorear locks y queries lentas.
- [ ] Monitorear errores de la aplicación.

### Después del deploy
- [ ] Verificar que la migración se ejecutó correctamente.
- [ ] Verificar que la aplicación funciona.
- [ ] Verificar que los datos son correctos (spot check).
- [ ] Confirmar que no hay queries lentas nuevas.

## Rollback plan

Para cada migración, documentar:
```markdown
### Rollback: {migración}
- **Comando**: `php artisan migrate:rollback --step=1`
- **Impacto del rollback**: {qué se pierde}
- **Datos recuperables**: sí/no — {cómo}
- **Tiempo estimado**: {minutos}
- **Requiere downtime**: sí/no
```

## Formato de respuesta

```markdown
### Plan de migración: {descripción}

**Riesgo**: 🟢/🟡/🟠/🔴
**Tabla(s)**: {nombres} ({filas estimadas})
**Estrategia**: directa / expand-migrate-contract
**Downtime**: sí ({duración}) / no

### Fases
1. {fase con código de migración}
2. {fase de verificación}
3. {fase de cleanup}

### Rollback plan
{Cómo deshacer si falla}

### Checklist
{Pre/durante/post}
```

