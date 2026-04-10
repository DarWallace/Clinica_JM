---
applyTo: "Analisis/**"
description: "Análisis funcional y técnico"
---

# ANALISIS — REGLAS

Objetivo: mantener análisis claros, rastreables y útiles que desbloqueen implementación y decisiones.

## Cuándo crear un análisis
- Antes de implementar una feature compleja o un módulo nuevo (nivel crítico según el global).
- Cuando hay ambigüedad en requisitos que podría causar errores de implementación.
- Cuando un cambio impacta múltiples módulos, APIs o esquema de datos.
- NO crear análisis para tareas triviales o estándar bien definidas (ver global §8).

## Estructura de archivos
- Archivo por tipo y alcance: `Analisis/funcional/{feature}.md`, `Analisis/tecnico/{component}.md`.
- Naming: snake_case, descriptivo: `gestion_pagos.md`, `migracion_auth_v2.md`.
- Un análisis por feature/decisión. No mezclar análisis no relacionados.

## Cabecera obligatoria (YAML)

```yaml
---
id: ANAL-XXXX
title: Breve descripción
author: Nombre
date: YYYY-MM-DD
status: draft | review | approved | deprecated
version: "1.0"
related: [TASK-XXXX, PR #YY]
---
```

## Contenido mínimo

### Resumen ejecutivo
1-3 líneas que cualquier miembro del equipo pueda entender sin leer el resto.

### Objetivos y KPIs
- ¿Qué problema resuelve?
- ¿Cómo se mide el éxito? (métricas concretas si aplican).

### Alcance
- **In scope**: qué se incluye.
- **Out of scope**: qué se excluye explícitamente y por qué.

### Requisitos funcionales
- Lista numerada de requisitos con criterios de aceptación.
- Formato: RF-01: Como [usuario], quiero [acción] para [beneficio]. Criterio: [condición verificable].

### Requisitos no funcionales
- Performance, seguridad, accesibilidad, compatibilidad, escalabilidad.
- Solo los que apliquen al contexto.

### Diseño / Decisiones arquitectónicas
- Diagrama simple (Mermaid, ASCII o link a diagrams.net).
- Alternativas consideradas y por qué se eligió la actual.
- Formato ADR si la decisión es estructural: Contexto → Decisión → Alternativas → Consecuencias.

### Impacto
- **Base de datos**: cambios de esquema, migraciones necesarias.
- **API**: endpoints nuevos/modificados, cambios en contratos.
- **Infraestructura**: nuevos servicios, configuración, variables de entorno.
- **UI/UX**: pantallas afectadas, flujos de usuario.

### Plan de implementación
- Pasos ordenados con dependencias.
- Estimación de esfuerzo (T-shirt sizing: XS, S, M, L, XL).

### Checklist de aceptación
- [ ] Criterios funcionales verificados.
- [ ] Tests unitarios y de integración añadidos.
- [ ] Seguridad revisada (OWASP checklist del global).
- [ ] Documentación actualizada si aplica.
- [ ] Rollback plan definido si hay cambios destructivos.

## Buenas prácticas
- Mantener versión y changelog dentro del archivo.
- Separar supuestos (lo que asumimos) de dependencias (lo que necesitamos de otros).
- Vincular requisitos a historias/PRs y criterios de aceptación claros.
- Actualizar el status cuando cambie (draft → review → approved).
- Si un análisis queda obsoleto, marcarlo como `deprecated` con referencia al que lo sustituye.
