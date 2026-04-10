---
applyTo: "app/Models/**"
description: "Reglas para modelos Eloquent"
---

# MODELS — REGLAS

## Principio: modelos como representación del dato
El modelo define la estructura del dato, sus relaciones y comportamientos intrínsecos (casts, scopes, accessors). No contiene lógica de negocio compleja.

## Propiedades obligatorias
- **`$fillable`** o **`$guarded`**: definir explícitamente. Preferir `$fillable` para ser explícito sobre qué se permite asignar masivamente.
- **`$casts`**: tipar atributos (dates, booleans, enums, JSON, custom casts). Evitar manipulación manual de tipos.
- **`$hidden`**: ocultar campos sensibles en serialización (password, tokens, secrets).

## Relaciones
- Definir relaciones como métodos explícitos con tipo de retorno: `public function orders(): HasMany`.
- Naming de relaciones: singular para BelongsTo/HasOne (`user()`), plural para HasMany/BelongsToMany (`orders()`).
- Documentar relaciones inversas para navegabilidad.
- Usar `withDefault()` en BelongsTo opcionales para evitar null checks.

## Scopes
- Scopes locales para filtros frecuentes: `scopeActive()`, `scopeByStatus()`.
- Scopes globales solo cuando apliquen SIEMPRE (ej: soft deletes, tenant filtering). Documentar su existencia.
- Naming: `scope` + verbo/adjetivo descriptivo.

## Accessors y Mutators
- Usar la sintaxis moderna de Laravel (Attribute class) para accessors/mutators.
- Solo para transformaciones simples de presentación/almacenamiento.
- Si la transformación implica lógica de negocio, mover a un Service.

## Convenciones de naming
- Modelo en singular, PascalCase: `User`, `OrderItem`.
- Tabla en plural, snake_case: `users`, `order_items` (convención Eloquent).
- Foreign keys: `{model}_id` en snake_case.
- Pivots: nombres de modelos en singular, orden alfabético: `order_product`.

## Observers y Events
- Usar Observers para side-effects del ciclo de vida (creating, updating, deleting).
- Preferir Events/Listeners sobre Observers cuando la lógica es compleja o afecta a múltiples sistemas.
- Documentar observers registrados en el modelo o en el Service Provider.

## Anti-patrones
- ❌ Lógica de negocio compleja dentro del modelo (cálculos, validaciones de dominio, integraciones).
- ❌ Queries complejas con joins dentro del modelo. Usar Repository o Query Object.
- ❌ Modelos con más de ~150 líneas (señal de que falta extraer traits, scopes o mover lógica).
- ❌ Usar `$guarded = []` en producción (inseguro).
- ❌ Relaciones sin tipo de retorno definido.
