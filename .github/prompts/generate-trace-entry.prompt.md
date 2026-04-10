---
description: "Generar entrada de trazabilidad para un cambio de nivel crítico"
---
# Crear Entrada de Trazabilidad

## Antes de generar
1. Leer `trace.instructions.md` para seguir el formato oficial.
2. Identificar el slug de la feature o pedir uno al usuario.
3. Recopilar: objetivo, archivos modificados, decisiones tomadas durante la implementación.

## Ubicación
`.planning/features/{feature-slug}/SUMMARY.md`

Si ya existe un SUMMARY.md del pipeline, **enriquecer** con las secciones de trazabilidad que falten. No crear fichero duplicado.

## Contenido a generar

### Cabecera
```markdown
# Resumen de ejecución: {feature}

**Fecha:** {YYYY-MM-DD}
**Estado:** ✅ Completado | 🔄 En progreso
**Archivos afectados:** {N}
```

### Secciones obligatorias

**Objetivo:**
1-3 líneas: qué se hizo y por qué.

**Tareas realizadas:**
Lista de acciones ejecutadas con estado (✅/❌, cada paso en 1 línea).

**Decisiones:**
- Qué alternativas se consideraron.
- Por qué se eligió la opción actual (1-2 líneas por decisión).

**Archivos afectados:**
- Lista de rutas con breve descripción de qué se cambió en cada una.

**Riesgos y mitigaciones:**
- Riesgos identificados y qué se hizo para mitigarlos.
- Si no hay riesgos relevantes: "Sin riesgos identificados."

**Verificación:**
- Criterios de aceptación verificados.
- Evidencia: referencia a tests, respuestas HTTP, logs.

## Reglas
- Ser conciso: 6-12 líneas por sección (salvo Pasos).
- No inventar información: si no se dispone de un dato, dejarlo como `TBD` o preguntar.
- Enlazar a PRs/issues cuando existan.

## Instrucciones de referencia
Seguir `trace.instructions.md` como fuente de verdad para formato y estructura.
