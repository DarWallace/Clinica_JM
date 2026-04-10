# Skills

Habilidades especializadas que extienden las capacidades del agente sin cambiar su rol.

**Activación:** automática cuando el agente detecta que la tarea lo requiere, o manual cuando el usuario lo indica.

## Skills disponibles

### Diseño y arquitectura

| Skill | Triggers | Qué hace |
|-------|----------|----------|
| **API Design** | api, endpoint, REST | Diseño de APIs REST con convenciones HTTP, paginación, errores |
| **Data Modeling** | modelo, entidad, relación, ERD | Diseño de esquemas, relaciones, normalización, diagramas ER |

### Código y calidad

| Skill | Triggers | Qué hace |
|-------|----------|----------|
| **Refactoring Patterns** | refactor, clean, SOLID | Catálogo de técnicas: Guard Clauses, Extract Method, Polymorphism, DTO |
| **Code Review Checklist** | review, PR, checklist | Checklist por categoría: correctitud, seguridad, performance, tests |
| **Regex Builder** | regex, expresión regular, pattern | Construir, explicar y depurar regex paso a paso con cheat sheet |

### Testing

| Skill | Triggers | Qué hace |
|-------|----------|----------|
| **Testing Patterns** | mock, fake, factory, fixture | Test doubles (mock/fake/stub/spy), factories, data providers, anti-patrones |

### Base de datos

| Skill | Triggers | Qué hace |
|-------|----------|----------|
| **DB Optimization** | N+1, query lenta, índice | Diagnóstico con EXPLAIN, soluciones para N+1, índices, cache |
| **Migration Planning** | migración producción, alter table | Migraciones seguras, Expand→Migrate→Contract, tablas grandes, rollback |

### Infraestructura

| Skill | Triggers | Qué hace |
|-------|----------|----------|
| **Docker Environment** | docker, contenedor, setup local | docker-compose por stack, Dockerfile best practices, diagnóstico de problemas |
| **Dependency Management** | dependencia, package, audit | Evaluar, auditar, actualizar dependencias de forma segura |

### Git y colaboración

| Skill | Triggers | Qué hace |
|-------|----------|----------|
| **Git Conflict Resolution** | conflicto, merge, rebase | Resolución segura de conflictos por tipo |

### Diagnóstico

| Skill | Triggers | Qué hace |
|-------|----------|----------|
| **Error Diagnosis** | error, exception, stack trace | Diagnóstico sistemático con clasificación y patrones comunes |

### Gestión de proyecto

| Skill | Triggers | Qué hace |
|-------|----------|----------|
| **Session Management** | sesión, retomar, continuar, guardar estado | Guardar/cargar estado de sesión, continuidad entre conversaciones |
| **Codebase Analysis** | analizar proyecto, codebase, brownfield, aterrizar | Análisis rápido de codebase: stack, patterns, health |
| **Verification Patterns** | verificar, probar, validar, UAT | Patrones de verificación post-ejecución: goal-backward, checklists, evidencia |

## Estructura de una skill

```yaml
---
name: "{nombre}"
description: "Qué sabe hacer"
triggers: ["palabras clave que activan la skill"]
---
```

## Reglas
- Las skills complementan, no redefinen instrucciones ni el global.
- Deben ser autocontenidas.

Ver `global-copilot-instructions.md` §20 para la especificación completa.
