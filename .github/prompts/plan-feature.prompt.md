---
mode: "agent"
description: "Pipeline completo para feature: discuss → plan → execute → verify"
---

# Planificar Feature (Pipeline)

Ejecuta el pipeline completo de `pipeline.instructions.md` para implementar una feature de nivel crítico.

## Variables
- `{feature}`: Descripción de la feature a implementar.

## Instrucciones

### Fase 1 — DISCUSS
1. Leer STATE.md si existe.
2. Analizar la feature `{feature}`.
3. Identificar áreas grises (decisiones no explícitas).
4. Preguntar al usuario las decisiones necesarias (máximo 5-8 preguntas).
5. Registrar decisiones en `.planning/features/{feature-slug}/CONTEXT.md`.

### Fase 2 — PLAN
1. Investigar el código existente del área afectada.
2. Consultar context cache o MCP si hay dudas técnicas.
3. Leer `.planning/codebase/CONVENTIONS.md` si existe.
4. Descomponer en tareas atómicas (2-4 por plan).
5. Definir verificación por tarea y verificación final.
6. Crear `.planning/features/{feature-slug}/PLAN.md`.

### Fase 3 — EXECUTE
1. Ejecutar tarea por tarea en orden de dependencias.
2. Verificar cada tarea después de implementarla.
3. Commit atómico por tarea.
4. Si algo falla: diagnosticar, corregir, reverificar.
5. Crear `.planning/features/{feature-slug}/SUMMARY.md`.

### Fase 4 — VERIFY
1. Aplicar verificación goal-backward.
2. Verificar criterios de aceptación del CONTEXT.md.
3. Ejecutar tests (nuevos + regresión).
4. Crear `.planning/features/{feature-slug}/VERIFICATION.md`.

## Flujo adaptativo
- Si el usuario dice "sin ceremonia" o "hazlo directo" → skip DISCUSS, PLAN resumido.
- Si la feature es clara y pequeña → modo rápido (sin ficheros separados).
- Si hay ambigüedad → DISCUSS completo antes de planificar.

## Output esperado
- Feature implementada y verificada.
- Artefactos del pipeline en `.planning/features/{slug}/`.
- STATE.md actualizado.

