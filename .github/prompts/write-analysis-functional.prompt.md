---
description: "Generar análisis funcional completo para una feature o módulo"
---
# Análisis Funcional

## Antes de generar
1. Leer el contexto proporcionado por el usuario (requisitos, conversaciones, tickets).
2. Leer la estructura del proyecto para entender módulos existentes.
3. Si hay análisis previos en `Analisis/funcional/`, revisarlos para evitar solapamiento.

## Estructura del análisis

### Cabecera
```yaml
---
id: ANAL-FXXX
title: {nombre de la feature}
author: {nombre}
date: {YYYY-MM-DD}
status: draft
version: "1.0"
related: [{tickets, PRs, otros análisis}]
---
```

### 1. Resumen ejecutivo
1-3 líneas que cualquier persona del equipo pueda entender.

### 2. Objetivo
- ¿Qué problema resuelve?
- ¿Quién se beneficia?
- ¿Cómo se mide el éxito? (KPIs concretos si aplican).

### 3. Actores
| Actor | Rol | Interacción |
|-------|-----|-------------|
| {usuario, admin, sistema, API externa} | {qué hace} | {cómo interactúa con la feature} |

### 4. Alcance
- **In scope**: qué incluye este análisis.
- **Out of scope**: qué se excluye explícitamente y por qué.

### 5. Reglas funcionales
Lista numerada de reglas de negocio:
- RF-01: {regla verificable}.
- RF-02: {regla verificable}.
- Cada regla debe poder traducirse en un criterio de aceptación.

### 6. Flujo principal
Describir el flujo paso a paso (happy path):
1. El usuario hace X.
2. El sistema valida Y.
3. El sistema ejecuta Z.
4. El usuario ve el resultado W.

Incluir diagrama de flujo si el flujo tiene más de 5 pasos (Mermaid o ASCII).

### 7. Flujos alternativos y casos límite
- ¿Qué pasa si la validación falla?
- ¿Qué pasa si el servicio externo no responde?
- ¿Qué pasa con datos vacíos, duplicados, límites?
- Permisos: ¿qué pasa si el usuario no tiene acceso?

### 8. Dependencias
- Módulos del sistema que se ven afectados.
- APIs externas necesarias.
- Datos que deben existir previamente.

### 9. Criterios de aceptación
- [ ] CA-1: {condición verificable, derivada de las reglas funcionales}.
- [ ] CA-2: {otra condición}.
- Cada criterio debe poder responderse con sí/no.

## Formato de salida
Documento Markdown completo, listo para guardar en `Analisis/funcional/{feature}.md`.

## Instrucciones de referencia
Aplicar `analisis.instructions.md`, `docs.instructions.md`.
