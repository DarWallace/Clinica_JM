---
applyTo: "**"
description: "Pipeline estructurado para features complejas: discuss → plan → execute → verify"
---

# PIPELINE — REGLAS

Objetivo: definir un flujo estructurado para tareas de **nivel crítico** (§4 del global) que garantice que las features complejas se implementan con contexto, planificación, ejecución limpia y verificación.

Inspirado en el patrón spec-driven development, adaptado al ecosistema NelkoDev-Copilot.

---

## Cuándo usar el pipeline

- **Obligatorio** para tareas de nivel **crítico** (§4 global).
- **Recomendado** para tareas estándar complejas (múltiples archivos, lógica de negocio no trivial).
- **No necesario** para nivel trivial o estándar simple.

El usuario puede activarlo explícitamente: *"Usa el pipeline para esta feature"*.

---

## Fases del pipeline

```
┌─────────────────────────────────────────────────────┐
│               PIPELINE DE FEATURE                   │
├─────────────────────────────────────────────────────┤
│                                                     │
│  ┌───────────┐   ┌───────────┐   ┌───────────┐     │
│  │  DISCUSS  │ → │   PLAN    │ → │  EXECUTE  │     │
│  │           │   │           │   │           │     │
│  │ Capturar  │   │ Investigar│   │ Implementar│    │
│  │ decisiones│   │ Descomponer│  │ Commitear  │    │
│  │ del usuario│  │ Verificar │   │ Validar    │    │
│  └───────────┘   └───────────┘   └───────────┘     │
│        │                                │           │
│        │         ┌───────────┐          │           │
│        │         │  VERIFY   │ ←────────┘           │
│        │         │           │                      │
│        │         │ ¿Funciona?│                      │
│        │         │ ¿Cumple?  │                      │
│        │         └───────────┘                      │
│        │               │                            │
│        │          ✅ DONE / 🔄 ITERATE              │
└────────┴───────────────┴────────────────────────────┘
```

---

## FASE 1 — DISCUSS (Capturar decisiones)

**Objetivo:** eliminar ambigüedades antes de planificar. El agente pregunta lo que necesita saber para evitar suposiciones.

### Proceso
1. Analizar la feature/requisito solicitado.
2. Identificar **áreas grises** (decisiones no explícitas):
   - **UI/Visual:** Layout, densidad, estados vacíos, responsive.
   - **API/Backend:** Formato de respuesta, manejo de errores, paginación.
   - **Datos:** Esquema, relaciones, validaciones, valores por defecto.
   - **Lógica:** Reglas de negocio, casos borde, permisos.
   - **Integraciones:** Servicios externos, webhooks, eventos.
3. Preguntar al usuario **solo las áreas grises relevantes**.
4. Registrar las decisiones.

### Output: CONTEXT.md
```markdown
# Contexto de la feature: {nombre}

## Decisiones del usuario
- {Decisión 1}: {lo que el usuario decidió}
- {Decisión 2}: {lo que el usuario decidió}

## Decisiones diferidas
- {Tema}: Se decide más adelante / fuera de scope

## Discrecionalidad del agente
- {Tema}: El agente decide usando su criterio (razón: {por qué})

## Restricciones
- {Restricción técnica o de negocio}
```

**Ubicación:** `.planning/features/{feature-slug}/CONTEXT.md`

### Reglas
- **No abrumar** al usuario con preguntas. Máximo 5-8 preguntas por ronda.
- **Proponer defaults** razonables: "¿Paginación de 20 items? (recomendado)".
- **Las decisiones del usuario son ley**: no se cuestionan en fases posteriores.
- Si el usuario dice "decide tú", registrar como "discrecionalidad del agente" con justificación.

---

## FASE 2 — PLAN (Investigar y descomponer)

**Objetivo:** producir un plan de implementación con tareas atómicas verificables.

### Proceso
1. **Investigar** (si es necesario):
   - Leer código existente del área afectada.
   - Consultar context cache / MCP si hay dudas sobre APIs/frameworks.
   - Identificar patrones y convenciones del repo.
2. **Descomponer** en tareas atómicas:
   - Cada tarea = un cambio cohesivo que se puede commitear independientemente.
   - 2-4 tareas por plan (si necesita más, dividir en sub-planes).
   - Incluir archivos afectados, acción concreta y criterio de verificación.
3. **Ordenar** por dependencias.
4. **Verificar** el plan antes de ejecutar.

### Output: PLAN.md
```markdown
# Plan: {nombre de la feature}

**Feature:** {descripción en 1 línea}
**Contexto:** ver CONTEXT.md
**Estimación total:** {S/M/L}
**Fecha:** {YYYY-MM-DD}

## Estimación por fase

| Fase | Estimado |
|------|----------|
| Investigación / Discuss | — |
| Diseño / Plan | — |
| Implementación / Execute | — |
| Testing | — |
| Documentación | — |
| **Total** | **—** |

## Pre-requisitos
- {Qué debe existir antes de empezar}

## Tareas

### Tarea 1: {nombre descriptivo}
- **Archivos:** `{ruta/archivo}`
- **Acción:** {qué hacer — concreto, no ambiguo}
- **Verificación:** {cómo saber que está bien — comando, test, resultado esperado}
- **Commit sugerido:** `{tipo}({scope}): {mensaje}`

### Tarea 2: {nombre descriptivo}
- **Archivos:** `{ruta/archivo}`
- **Acción:** {qué hacer}
- **Verificación:** {cómo verificar}
- **Commit sugerido:** `{tipo}({scope}): {mensaje}`

### Tarea 3: {nombre descriptivo}
...

## Dependencias entre tareas
- Tarea 2 depende de Tarea 1
- Tarea 3 es independiente

## Riesgos
| Riesgo | Impacto | Mitigación |
|--------|---------|------------|

## Verificación final
{Cómo verificar que la feature completa funciona end-to-end}
```

**Ubicación:** `.planning/features/{feature-slug}/PLAN.md`

### Reglas de un buen plan
- Cada tarea debe ser ejecutable **sin interpretar** — instrucciones concretas.
- Cada tarea tiene verificación propia (no solo "debería funcionar").
- El plan respeta las decisiones de CONTEXT.md (no contradice al usuario).
- Si el plan revela que falta una decisión del usuario → volver a DISCUSS.

---

## FASE 3 — EXECUTE (Implementar)

**Objetivo:** ejecutar el plan tarea por tarea con cambios atómicos.

### Proceso
1. Leer el plan completo.
2. Para cada tarea (en orden de dependencias):
   a. **Re-leer PLAN.md** si han pasado >3 turnos desde la última lectura (§1.1 DURANTE).
   b. Implementar la acción descrita.
   c. Ejecutar la verificación de la tarea (si hay acceso a terminal) o proporcionar el comando al usuario.
   d. Si pasa → sugerir commit con el mensaje definido. Ejecutar si hay acceso a terminal y el usuario lo ha autorizado.
   e. Si falla → diagnosticar, corregir, reverificar.
   f. **Actualizar SUMMARY.md** con el resultado de la tarea.
3. Después de todas las tareas: ejecutar verificación final del plan.
4. **Ejecutar protocolo de cierre (§1.1)**: actualizar STATE.md, SUMMARY.md, gestionar .continue-here.md.

### Reglas de ejecución
- **Un cambio cohesivo por tarea**. Cada grupo de cambios es independiente y revertible.
- **Seguir el plan**. No desviarse sin justificación documentada.
- **Si algo no funciona como el plan esperaba**: documentar la desviación en SUMMARY.md.
- **No mezclar tareas** en un cambio. Si una tarea requiere cambios adicionales, son parte de esa tarea.
- **Aplicar §6 (seguridad)** en cada tarea que toque input/output/auth.

### Output: SUMMARY.md
```markdown
# Resumen de ejecución: {feature}

**Fecha:** {YYYY-MM-DD}
**Plan ejecutado:** PLAN.md

## Tareas completadas

### Tarea 1: {nombre}
- **Estado:** ✅ Completada
- **Commit sugerido:** `{tipo}({scope}): {mensaje}`
- **Notas:** {observaciones si las hay}

### Tarea 2: {nombre}
- **Estado:** ✅ Completada
- **Desviación:** {si hubo cambio respecto al plan, documentar}

## Verificación final
- {Resultado de la verificación end-to-end}

## Métricas
- **Tareas completadas:** {N} de {M}
- **Desviaciones:** {N}
- **Tiempo estimado:** {del plan} | **Tiempo real:** {aproximado}
```

**Ubicación:** `.planning/features/{feature-slug}/SUMMARY.md`

---

## FASE 4 — VERIFY (Verificar)

**Objetivo:** confirmar que la feature funciona como se esperaba, no solo que el código existe.

### Proceso — Verificación goal-backward
1. **Empezar por el objetivo**: ¿qué debe ser VERDAD para que esta feature esté completa?
2. **Verificar existencia**: ¿los archivos/funciones/endpoints existen?
3. **Verificar sustancia**: ¿el código hace lo que debe, no son placeholders?
4. **Verificar integración**: ¿las piezas están conectadas entre sí?
5. **Verificar criterios de aceptación**: ¿cada criterio del CONTEXT.md se cumple?
6. **Ejecutar tests**: ¿los tests nuevos pasan? ¿los existentes siguen pasando?

### Niveles de verificación

| Nivel | Qué verifica | Cómo |
|-------|-------------|------|
| **Existencia** | Archivos y funciones creados | Leer filesystem |
| **Sustancia** | Código real, no placeholders | Leer código, verificar lógica |
| **Integración** | Piezas conectadas | Seguir flujo de datos de input a output |
| **Funcional** | Feature funciona end-to-end | Ejecutar tests, probar manualmente |
| **Regresión** | No se rompió nada existente | Ejecutar suite de tests completa |

### Output: VERIFICATION.md
```markdown
# Verificación: {feature}

**Fecha:** {YYYY-MM-DD}
**Verificador:** agente / {nombre}

## Resultados

| Criterio | Estado | Evidencia |
|----------|--------|-----------|
| {criterio 1 del CONTEXT.md} | ✅ Cumple / ❌ Falla | {qué se verificó} |
| {criterio 2} | ✅ / ❌ | {evidencia} |

## Tests
- Tests nuevos: {N} — ✅ Todos pasan / ❌ Fallos: {lista}
- Regresión: ✅ Sin regresión / ❌ Tests rotos: {lista}

## Gaps detectados
{Si algo falta o no funciona como se esperaba}

## Veredicto
✅ **APROBADO** — Feature lista para merge
❌ **RECHAZADO** — Requiere correcciones: {lista}
```

**Ubicación:** `.planning/features/{feature-slug}/VERIFICATION.md`

### Si la verificación falla
1. Documentar qué falla en VERIFICATION.md.
2. Crear tareas correctivas (mini-plan).
3. Ejecutar correcciones.
4. Re-verificar solo los puntos que fallaron.

---

## Estructura de archivos del pipeline

```
.planning/
├── STATE.md                          # Estado global del proyecto
├── features/
│   ├── {feature-slug}/
│   │   ├── CONTEXT.md               # Decisiones del usuario
│   │   ├── PLAN.md                  # Plan de implementación
│   │   ├── SUMMARY.md              # Resumen de ejecución
│   │   └── VERIFICATION.md         # Resultado de verificación
│   └── {otra-feature}/
│       └── ...
└── .continue-here.md                # Continuidad de sesión (temporal)
```

---

## Modo rápido (Quick)

Para tareas estándar que se beneficiarían de estructura pero no necesitan el pipeline completo:

1. **Skip DISCUSS** — El usuario ya dijo lo que quiere con suficiente detalle.
2. **PLAN resumido** — Lista de 2-4 pasos con verificación, sin fichero separado.
3. **EXECUTE** — Implementar con commits atómicos.
4. **VERIFY inline** — Verificación en el output de entrega (§15 del global).

El modo rápido se activa cuando:
- La tarea es nivel estándar con alcance claro.
- El usuario dice "hazlo directo" o "sin ceremonia".
- No hay ambigüedades que requieran DISCUSS.

