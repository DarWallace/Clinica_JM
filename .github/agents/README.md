# Agents

Roles alternativos que el usuario puede activar para que el asistente trabaje con un enfoque distinto.

**Activación:** el usuario dice al inicio de la conversación:
> Activa el agente `{nombre}`

**Desactivación:** el usuario lo indica o la conversación termina.

## Agentes disponibles

### Generales

| Agente | Comando | Qué hace |
|--------|---------|----------|
| **Reviewer** | `Activa el agente reviewer` | Revisión de código estricta por severidad (🔴🟡🔵) |
| **Architect** | `Activa el agente architect` | Diseño de arquitectura con diagramas, ADRs y trade-offs |
| **Mentor** | `Activa el agente mentor` | Explicaciones pedagógicas, enseña mientras implementa |
| **Debugger** | `Activa el agente debugger` | Depuración sistemática con metodología root-cause |
| **Planner** | `Activa el agente planner` | Descomposición de features en tareas INVEST estimadas |

### Pipeline y ejecución

| Agente | Comando | Qué hace |
|--------|---------|----------|
| **Executor** | `Activa el agente executor` | Ejecuta planes con commits atómicos, verificación por tarea y state management |
| **Verifier** | `Activa el agente verifier` | Verificación goal-backward: confirma que el trabajo realmente funciona |
| **Researcher** | `Activa el agente researcher` | Investiga dominio, stack y alternativas antes de implementar |
| **Codebase Mapper** | `Activa el agente codebase-mapper` | Mapea stack, arquitectura, convenciones y deuda técnica de un proyecto existente |

### Disciplinares

| Agente | Comando | Qué hace |
|--------|---------|----------|
| **SEO** | `Activa el agente seo` | Auditoría SEO técnica: rastreo, indexación, Core Web Vitals, datos estructurados |
| **Security** | `Activa el agente security` | Auditoría de seguridad OWASP Top 10 con clasificación CVSS y remediación |
| **Performance** | `Activa el agente performance` | Análisis de rendimiento full-stack: queries, bundles, cache, Core Web Vitals |
| **DBA** | `Activa el agente dba` | Diseño de esquemas, optimización de queries, migraciones seguras |
| **UX** | `Activa el agente ux` | Revisión de usabilidad y accesibilidad WCAG 2.1 AA |
| **DevOps** | `Activa el agente devops` | CI/CD, Docker, infraestructura, observabilidad, despliegues |
| **Tester** | `Activa el agente tester` | Estrategia de testing, casos de prueba, cobertura y QA |

## Estructura de un agente

```yaml
---
name: "{nombre}"
description: "Una línea describiendo el rol"
extends: global
replaces: [§3, §17]    # secciones del global que reemplaza (opcional)
---
```

## Reglas
- Un agente **no puede desactivar** §6 (seguridad), §11 (scope guard) ni §14 (Definition of Done).
- Lo que no redefina se hereda del global automáticamente.

Ver `global-copilot-instructions.md` §19 para la especificación completa.
