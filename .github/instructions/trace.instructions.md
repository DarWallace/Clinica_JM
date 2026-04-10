---
applyTo: "**/.planning/**/SUMMARY.md"
description: "Trazabilidad de tareas y cambios críticos"
---

# TRACE — REGLAS

Objetivo: mantener un registro mínimo y accionable por tarea que permita auditoría y reconstrucción de decisiones.

## Ubicación

La trazabilidad se integra en el pipeline de features (§24 del global):
- **Con pipeline activo**: CONTEXT.md + PLAN.md + SUMMARY.md + VERIFICATION.md ya cubren la trazabilidad.
- **Sin pipeline** (flujo alternativo): crear `.planning/features/{slug}/SUMMARY.md` con las secciones obligatorias.

## Secciones obligatorias de trazabilidad

Todo SUMMARY.md de un cambio crítico debe contener:

- **Objetivo**: ¿por qué se hace esto?
- **Tareas realizadas**: lista de acciones ejecutadas con estado (✅/❌).
- **Decisiones**: alternativas consideradas y por qué se eligió la actual.
- **Archivos afectados**: rutas y breve descripción del cambio.
- **Riesgos y mitigaciones**: impactos identificados y acciones para mitigarlos.
- **Verificación**: criterios de aceptación y evidencia (tests, respuestas HTTP, logs).

## Buenas prácticas
- Ser conciso: 6-12 líneas por sección salvo Tareas.
- Incluir enlaces a PRs/issues cuando aplique.
- Mantener como referencia histórica al completar la feature.
