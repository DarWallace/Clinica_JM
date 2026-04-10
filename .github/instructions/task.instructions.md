---
applyTo: "**/tasks/**"
description: "Definición de tareas para implementación"
---

# TASK — REGLAS

Objetivo: definir de forma clara y completa una tarea de implementación, asegurando que el desarrollador (o agente) entienda qué hacer, por qué, con qué restricciones y cómo verificar que está hecho.

## Diferencia entre Backlog Item y Task

| Concepto | Backlog Item | Task |
|----------|-------------|------|
| **Nivel** | Qué se necesita (requisito) | Cómo se implementa (ejecución) |
| **Quién lo escribe** | Product Owner, Tech Lead, equipo | Desarrollador, agente, Tech Lead |
| **Cuándo se crea** | Durante planning/grooming | Cuando se empieza a trabajar en un BL item |
| **Contenido** | User story, criterios de aceptación | Entendimiento técnico, plan, decisiones, tests |

Un Backlog Item puede generar una o varias Tasks.

## Estructura del archivo de tarea

Ubicación: `tasks/TASK-XXXX.md` o dentro del sistema de gestión del proyecto.

### Cabecera (YAML)

```yaml
---
id: TASK-XXXX
title: Breve descripción técnica
backlog_item: BL-XXXX
author: Nombre
created: YYYY-MM-DD
status: pending | in-progress | review | done | blocked
level: trivial | standard | critical
estimation: XS | S | M | L
---
```

## Contenido obligatorio

### 1. Entendimiento de la tarea
Descripción en las propias palabras del implementador de qué se ha entendido:
- ¿Qué se pide?
- ¿Por qué se pide? (contexto de negocio/técnico).
- ¿Qué problema resuelve?
- ¿Quién se beneficia?

**Regla**: si el implementador no puede escribir esta sección sin mirar el backlog item, la tarea no está suficientemente clara y necesita aclaración.

### 2. Alcance

**In scope:**
- Lista explícita de lo que se va a hacer.

**Out of scope:**
- Lista explícita de lo que NO se va a hacer y por qué.
- Incluir aquí las tentaciones de scope creep: "No se refactoriza X aunque esté relacionado".

### 3. Plan de implementación

Pasos concretos y ordenados:

```markdown
1. [ ] Crear migración para añadir campo `status` a tabla `orders`.
2. [ ] Actualizar modelo `Order`: cast, fillable, scope.
3. [ ] Crear/actualizar `OrderService::updateStatus()` con validación de transiciones.
4. [ ] Actualizar FormRequest con validación del nuevo campo.
5. [ ] Añadir endpoint PUT `/api/orders/{id}/status`.
6. [ ] Tests unitarios para `OrderService::updateStatus()`.
7. [ ] Test feature para el endpoint.
8. [ ] Actualizar documentación de API.
```

### 4. Decisiones técnicas

Documentar las decisiones tomadas durante la implementación:

| Decisión | Alternativa(s) | Por qué esta opción |
|----------|----------------|---------------------|
| Usar enum PHP para estados | Constantes en modelo | Type safety, autocompletado, validación nativa |
| Validar transiciones en Service | Validar en Model observer | Lógica de negocio pertenece al Service (convención del repo) |

### 5. Archivos afectados

```markdown
- `database/migrations/XXXX_add_status_to_orders.php` — nueva migración
- `app/Models/Order.php` — añadir cast y scope
- `app/Services/OrderService.php` — nuevo método updateStatus()
- `app/Http/Requests/UpdateOrderStatusRequest.php` — nuevo FormRequest
- `app/Http/Controllers/Api/OrderController.php` — nuevo método updateStatus
- `tests/Unit/Services/OrderServiceTest.php` — tests unitarios
- `tests/Feature/Api/OrderStatusTest.php` — test feature
```

### 6. Criterios de aceptación (heredados del BL + técnicos)

Copiar los criterios del backlog item y añadir los técnicos:

```markdown
**Funcionales (del BL):**
- [ ] CA-1: El usuario puede cambiar el estado de una orden de "pending" a "confirmed".
- [ ] CA-2: No se permite cambiar a un estado inválido (ej: de "cancelled" a "pending").
- [ ] CA-3: El cambio de estado se registra con timestamp y usuario.

**Técnicos (de la task):**
- [ ] CA-T1: Migración ejecuta y revierte sin errores.
- [ ] CA-T2: Validación de transiciones cubierta por test unitario.
- [ ] CA-T3: Endpoint responde 200 en transición válida y 422 en inválida.
- [ ] CA-T4: Sin regresión en tests existentes.
```

### 7. Tests

Definir explícitamente los tests que se van a escribir:

```markdown
**Tests unitarios:**
- `test_update_status_from_pending_to_confirmed_succeeds`
- `test_update_status_from_cancelled_to_pending_fails`
- `test_update_status_with_invalid_status_throws_exception`

**Tests feature:**
- `test_authenticated_user_can_update_order_status`
- `test_unauthenticated_user_cannot_update_order_status`
- `test_update_order_status_with_invalid_transition_returns_422`

**Test de regresión (si bugfix):**
- `test_bug_XXXX_order_status_change_does_not_lose_timestamps`
```

### 8. Notas y riesgos

```markdown
**Riesgos:**
- La tabla orders tiene 2M registros; la migración puede ser lenta.
  Mitigación: usar nullable + valor default, rellenar en Job posterior.

**Deuda técnica detectada (fuera de scope):**
- El OrderController tiene 8 métodos; considerar dividir en controllers invocables.
  No se aborda en esta task.

**Dependencias:**
- Requiere que BL-0042 (migración de usuarios) esté completada.
```

## Reglas de uso

### Para el desarrollador/agente
- Crear la task ANTES de empezar a codificar (aunque sea un borrador rápido).
- Actualizar la task durante la implementación: marcar pasos completados, añadir decisiones.
- Al terminar: verificar que todos los criterios de aceptación están cumplidos.

### Nivel de detalle según el nivel de tarea (global §4)
| Nivel | Detalle de la task |
|-------|-------------------|
| **Trivial** | No requiere fichero de task. El propio commit/output del agente es suficiente. |
| **Estándar** | Task mínima: entendimiento + alcance + criterios de aceptación + tests. |
| **Crítico** | Task completa: todas las secciones incluyendo decisiones, riesgos y plan detallado. |

### Transición de estados
```
pending → in-progress → review → done
                ↓
              blocked → (resolver) → in-progress
```

- **pending**: definida pero no empezada.
- **in-progress**: en desarrollo activo.
- **review**: implementación completa, pendiente de revisión.
- **done**: criterios de aceptación verificados, tests pasando, mergeada.
- **blocked**: documentar qué bloquea y qué se necesita.

