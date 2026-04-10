---
name: "db-optimization"
description: "Diagnosticar y optimizar queries lentas, índices y esquemas de base de datos"
triggers: ["N+1", "query lenta", "slow query", "índice", "index", "performance db", "optimizar query", "explain"]
---

# Skill: DB Optimization

## Cuándo aplicar
- Cuando se detectan N+1 queries.
- Cuando un endpoint o página es lento por queries.
- Al diseñar esquemas para tablas con alto volumen.
- Cuando el usuario reporta problemas de performance en DB.

## Diagnóstico

### 1. Identificar queries problemáticas
- Activar query log o debugbar para ver las queries ejecutadas.
- Laravel: `DB::enableQueryLog()` + `DB::getQueryLog()` o Laravel Debugbar.
- Buscar patrones: muchas queries repetidas (N+1), queries sin WHERE, full table scans.

### 2. Analizar con EXPLAIN
```sql
EXPLAIN SELECT * FROM orders WHERE status = 'pending' ORDER BY created_at DESC;
```
Buscar:
- `type: ALL` → full table scan (malo).
- `type: index` o `ref` → usando índice (bien).
- `rows` alto → muchos registros escaneados.
- `Extra: Using filesort` → ordenación sin índice.
- `Extra: Using temporary` → tabla temporal (costoso).

### 3. Clasificar el problema
| Problema | Síntoma | Solución |
|----------|---------|----------|
| N+1 | Muchas queries iguales con IDs distintos | Eager loading (`with()`) |
| Falta de índice | Full table scan en WHERE/ORDER BY | Crear índice |
| Select * | Transfiere datos innecesarios | Select solo columnas necesarias |
| Query en loop | Query dentro de foreach | Batch query o eager load |
| Subquery costosa | Subselect lento en WHERE | JOIN o query separada |
| Sin paginación | Carga todos los registros | Paginar/cursor |

## Soluciones

### N+1 Queries (Eloquent)
```php
// ❌ N+1
$orders = Order::all();
foreach ($orders as $order) {
    echo $order->user->name; // 1 query por order
}

// ✅ Eager loading
$orders = Order::with('user')->get(); // 2 queries total
```

**Regla**: si muestras una relación en una lista, usa `with()`.

### Índices
```php
// Crear índice para columnas en WHERE, ORDER BY, JOIN
Schema::table('orders', function (Blueprint $table) {
    $table->index('status');                    // Filtro simple
    $table->index(['status', 'created_at']);     // Filtro + orden
    $table->index(['user_id', 'status']);        // FK + filtro
});
```

**Cuándo crear índice:**
- Columna en WHERE frecuente.
- Columna en ORDER BY frecuente.
- Foreign key (Laravel las indexa automáticamente con `constrained()`).
- Columnas en JOIN.

**Cuándo NO crear índice:**
- Tablas con <1000 registros (no aporta).
- Columnas con baja cardinalidad (boolean) salvo que sea parte de índice compuesto.
- Tablas con muchos INSERT/UPDATE y pocos SELECT.

### Selección de columnas
```php
// ❌ Select *
$users = User::all();

// ✅ Solo lo necesario
$users = User::select(['id', 'name', 'email'])->get();
```

### Paginación
```php
// ❌ Cargar todo
$orders = Order::all();

// ✅ Paginar
$orders = Order::paginate(25);

// ✅ Cursor para datasets grandes
$orders = Order::cursor()->each(function ($order) { });
```

### Chunking para operaciones masivas
```php
// ❌ Cargar 100k registros en memoria
Order::all()->each(fn ($o) => $o->process());

// ✅ Procesar en lotes
Order::chunk(1000, function ($orders) {
    foreach ($orders as $order) {
        $order->process();
    }
});
```

### Cache de queries costosas
```php
// Cache de contadores/agregaciones
$totalOrders = Cache::remember('orders.total', 3600, function () {
    return Order::count();
});
```

## Checklist de revisión
- [ ] ¿Hay N+1? (verificar con debugbar o query log).
- [ ] ¿Las columnas en WHERE/ORDER BY tienen índice?
- [ ] ¿Se paginan las listas?
- [ ] ¿Se usa `select()` para limitar columnas?
- [ ] ¿Las queries costosas están cacheadas?
- [ ] ¿Los batch operations usan `chunk()` o `cursor()`?

