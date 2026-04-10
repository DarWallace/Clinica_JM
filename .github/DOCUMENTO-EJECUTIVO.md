# NelkoDev-Copilot — Documento ejecutivo

> **Audiencia:** Dirección, jefes de equipo, leads técnicos y responsables de producto.
> **Versión:** 2.0 — Marzo 2026
> **Qué es esto:** Descripción completa del sistema de asistencia IA que gobierna la calidad, seguridad y productividad del desarrollo en nuestros proyectos.

---

## Índice

1. [Resumen ejecutivo](#1-resumen-ejecutivo)
2. [¿Qué problema resuelve?](#2-qué-problema-resuelve)
3. [Cómo funciona](#3-cómo-funciona)
4. [Componentes del sistema](#4-componentes-del-sistema)
5. [Catálogo de agentes](#5-catálogo-de-agentes)
6. [Pipeline de features (discuss → plan → execute → verify)](#6-pipeline-de-features)
7. [Beneficios por rol](#7-beneficios-por-rol)
8. [Seguridad integrada (OWASP)](#8-seguridad-integrada)
9. [Gestión del conocimiento](#9-gestión-del-conocimiento)
10. [Métricas de impacto](#10-métricas-de-impacto-esperadas)
11. [Coste y requisitos](#11-coste-y-requisitos)
12. [Preguntas frecuentes para dirección](#12-preguntas-frecuentes)

---

## 1. Resumen ejecutivo

**NelkoDev-Copilot** es un sistema de instrucciones, agentes especializados y flujos de trabajo que se integra con GitHub Copilot para estandarizar y elevar la calidad del desarrollo de software en toda la organización.

### En una frase

> Un equipo de 17 asistentes IA especializados que trabajan bajo las reglas, convenciones y estándares de seguridad de nuestra empresa — disponibles 24/7 para cada desarrollador.

### Datos clave

| Concepto | Valor |
|----------|-------|
| **Agentes especializados** | 17 roles activables (reviewer, architect, security, DBA, UX…) |
| **Instrucciones por stack** | 27 ficheros cubriendo Laravel, React, Vue, Node, Python, PHP… |
| **Skills técnicas** | 13 habilidades que se activan bajo demanda |
| **Templates de tareas** | 27 prompts reutilizables para tareas comunes |
| **Inversión de tiempo en setup** | ~5 minutos por proyecto (copiar una carpeta) |
| **Dependencias externas** | Ninguna. Solo requiere licencia de GitHub Copilot |

---

## 2. ¿Qué problema resuelve?

### Sin NelkoDev-Copilot

| Problema | Consecuencia |
|----------|-------------|
| Copilot genera código **genérico** que no sigue las convenciones del equipo | Tiempo perdido en revisiones y retrabajos |
| Cada desarrollador aplica convenciones **a su manera** | Código inconsistente, difícil de mantener |
| La seguridad depende de que **alguien se acuerde** de revisarla | Vulnerabilidades que pasan a producción |
| Los tests se escriben **"cuando hay tiempo"** | Regresiones frecuentes |
| Nuevo miembro del equipo necesita **semanas** para entender un proyecto | Onboarding lento y costoso |
| Se repiten los **mismos errores** entre proyectos | No hay aprendizaje organizacional |
| Las features complejas se implementan **sin planificar** | Scope creep, retrabajos, bugs |
| Al cerrar el IDE se **pierde todo el contexto** de trabajo | El día siguiente empieza de cero |

### Con NelkoDev-Copilot

| Solución | Resultado |
|----------|-----------|
| Instrucciones por stack obligan a seguir convenciones | **Código consistente** en todo el equipo |
| Checklist OWASP automático en cada cambio | **Seguridad verificada** por defecto |
| Política de testing integrada | **Tests obligatorios** en cambios con lógica |
| Agente `@codebase-mapper` produce mapa del proyecto en minutos | **Onboarding en horas**, no semanas |
| Sistema de learnings comparte correcciones | **El equipo aprende** de cada error |
| Pipeline discuss→plan→execute→verify | **Features planificadas**, sin improvisación |
| State management persiste entre sesiones | **Continuidad**: retomas donde dejaste |

---

## 3. Cómo funciona

### Arquitectura simplificada

```
┌────────────────────────────────────────────────────────────────┐
│                    DESARROLLADOR                               │
│                                                                │
│   "Crea un endpoint para listar órdenes filtradas por estado"  │
│   "@reviewer revisa app/Services/OrderService.php"             │
│   "@planner planifica el módulo de pagos"                      │
└───────────────────────┬────────────────────────────────────────┘
                        │
┌───────────────────────▼────────────────────────────────────────┐
│               GITHUB COPILOT (motor IA)                        │
│                                                                │
│   Lee automáticamente:                                         │
│   ┌─────────────────────────────────────────────────────┐      │
│   │  .github/copilot-instructions.md                    │      │
│   │  ← Reglas globales: seguridad, testing, calidad     │      │
│   ├─────────────────────────────────────────────────────┤      │
│   │  .github/instructions/laravel.instructions.md       │      │
│   │  ← Convenciones del stack detectado                 │      │
│   ├─────────────────────────────────────────────────────┤      │
│   │  .github/agents/reviewer.agent.md                   │      │
│   │  ← Si el usuario activó un agente especializado     │      │
│   ├─────────────────────────────────────────────────────┤      │
│   │  .planning/STATE.md                                 │      │
│   │  ← Estado del proyecto (memoria entre sesiones)     │      │
│   └─────────────────────────────────────────────────────┘      │
│                                                                │
│   Genera código alineado con TODAS estas reglas                │
└───────────────────────┬────────────────────────────────────────┘
                        │
┌───────────────────────▼────────────────────────────────────────┐
│                  CÓDIGO GENERADO                               │
│                                                                │
│   ✅ Sigue las convenciones del proyecto                       │
│   ✅ Seguridad verificada (OWASP)                              │
│   ✅ Tests incluidos si hay lógica nueva                       │
│   ✅ Estado del proyecto actualizado                           │
│   ✅ Resumen de entrega con pasos para probar                  │
└────────────────────────────────────────────────────────────────┘
```

### ¿Qué ve el desarrollador?

El desarrollador abre el chat de Copilot en su IDE (VS Code o PHPStorm) y pide lo que necesita en **lenguaje natural**. El sistema aplica las reglas automáticamente:

```
Desarrollador:  "Añade validación de email único al registro"

Copilot (con NelkoDev-Copilot):
  1. Lee las convenciones del proyecto → detecta que se usan FormRequests
  2. Crea un FormRequest (no valida en el controller)
  3. Añade regla 'unique:users,email'
  4. Escribe test de validación
  5. Verifica seguridad: input validado ✅, no hay XSS ✅
  6. Entrega resumen: archivos creados, cómo probar, seguridad verificada

Sin NelkoDev-Copilot:
  → Podría validar directamente en el controller (inconsistente)
  → Podría olvidar el test
  → No verificaría seguridad
  → No reportaría qué archivos tocó
```

### Instalación por proyecto

```bash
# Solo copiar una carpeta a la raíz del proyecto
cp -r .github/ tu-proyecto/.github/

# Commitear para que todo el equipo la tenga
git add .github/ && git commit -m "feat: añadir NelkoDev-Copilot" && git push
```

**No requiere** instalación de software, CLI, base de datos ni servicios adicionales.

---

## 4. Componentes del sistema

```
.github/                               ← Se copia a cada proyecto
├── copilot-instructions.md            ← Cerebro: reglas globales (seguridad, testing, calidad)
├── instructions/ (27 ficheros)        ← Convenciones por stack/área
├── agents/ (17 agentes)               ← Roles especializados activables
├── skills/ (13 habilidades)           ← Conocimiento técnico bajo demanda
├── prompts/ (27 templates)            ← Tareas comunes reutilizables
├── context/                           ← Cache de documentación consultada
└── learnings/                         ← Base de conocimiento de errores corregidos

.planning/                             ← Se crea por proyecto (memoria del proyecto)
├── STATE.md                           ← Estado actual, decisiones, progreso
├── codebase/                          ← Mapa del codebase (stack, arquitectura, convenciones)
└── features/                          ← Pipeline por feature (context, plan, resumen, verificación)
```

### Qué hace cada componente

| Componente | Analogía | Qué aporta |
|-----------|----------|------------|
| **Instrucciones globales** | La constitución del equipo | Reglas que SIEMPRE aplican: seguridad, testing, calidad |
| **Instrucciones por stack** | Manual del framework | Convenciones específicas de Laravel, React, Vue, etc. |
| **Agentes** | Especialistas bajo demanda | Roles que el desarrollador activa cuando los necesita |
| **Skills** | Conocimiento técnico | Se activan automáticamente cuando la tarea las requiere |
| **Prompts** | Plantillas de tareas | Flujos predefinidos para tareas comunes (crear migración, componente…) |
| **Context cache** | Biblioteca técnica | Documentación consultada, guardada para no repetir búsquedas |
| **Learnings** | Lecciones aprendidas | Errores corregidos que el sistema no vuelve a cometer |
| **State management** | Memoria del proyecto | El asistente "recuerda" dónde se dejó el trabajo entre sesiones |

---

## 5. Catálogo de agentes

Los agentes son el corazón visible del sistema. Cada uno es un **rol especializado** que el desarrollador activa con un simple comando en el chat de Copilot.

### Agentes generales — Para el día a día del equipo de desarrollo

| Agente | Qué hace | Cuándo usarlo | Quién lo usa |
|--------|----------|---------------|-------------|
| **🔍 Reviewer** | Revisión de código clasificada por severidad: 🔴 Crítico (bloquea), 🟡 Importante, 🔵 Sugerencia. Incluye checklist de seguridad OWASP. | Al finalizar una feature, antes de un PR, al recibir código de otro miembro. | Desarrolladores, Tech Leads |
| **🏗️ Architect** | Diseña arquitectura con diagramas Mermaid, evalúa trade-offs entre opciones, produce ADRs (Architecture Decision Records). | Antes de crear un módulo nuevo, al diseñar integraciones, al decidir entre tecnologías. | Tech Leads, Arquitectos, Senior Devs |
| **🎓 Mentor** | Explica conceptos mientras implementa. Adapta la profundidad al nivel del usuario. Usa analogías, diagramas y ejemplos del proyecto real. | Onboarding de nuevos miembros, formación en tecnologías nuevas, resolución de dudas técnicas. | Juniors, Mids, cualquier desarrollador aprendiendo |
| **🐛 Debugger** | Depuración sistemática con metodología root-cause. Forma hipótesis, las verifica con evidencia, propone el fix mínimo. Incluye test de regresión. | Cuando hay un bug y no se encuentra la causa. Antes de "probar cosas a ver si funciona". | Todos los desarrolladores |
| **📋 Planner** | Descompone features en tareas INVEST: independientes, estimables, testeables. Identifica riesgos y dependencias. | Antes de empezar una feature mediana o grande. En grooming/planning. | Tech Leads, Developers, Product Owners |

### Agentes de pipeline — Para features complejas

| Agente | Qué hace | Cuándo usarlo | Quién lo usa |
|--------|----------|---------------|-------------|
| **⚡ Executor** | Implementa planes tarea por tarea con commits atómicos. Verifica cada tarea antes de avanzar. Actualiza el estado del proyecto. | Después de que el Planner haya generado un plan. Para ejecución disciplinada y trazable. | Desarrolladores |
| **✅ Verifier** | Verificación escéptica "goal-backward": no confía en que "funciona" — lo comprueba en 3 niveles (existe → tiene contenido real → está conectado). | Después de implementar. Antes de marcar una feature como terminada. | Desarrolladores, QA, Tech Leads |
| **🔬 Researcher** | Investiga dominio, stack o tecnología antes de implementar. Compara opciones con trade-offs. Guarda los hallazgos para no repetir investigaciones. | Antes de elegir una librería, al integrar un servicio nuevo, ante incertidumbre técnica. | Developers, Architects |
| **🗺️ Codebase Mapper** | Analiza un proyecto existente y produce 4 documentos: stack, arquitectura, convenciones y deuda técnica. El "mapa" que todo nuevo miembro necesita. | Al llegar a un proyecto existente, antes de un refactor grande, para onboarding. | Nuevos miembros, Tech Leads, Managers |

### Agentes disciplinares — Especialistas de dominio

| Agente | Qué hace | Cuándo usarlo | Quién lo usa |
|--------|----------|---------------|-------------|
| **🔒 Security** | Auditoría de seguridad basada en OWASP Top 10. Clasificación por riesgo (crítico/alto/medio/bajo). Cada hallazgo con escenario de explotación y fix concreto. | Antes de un release, al implementar auth/pagos, en revisiones de seguridad periódicas. | Developers, Security team, Tech Leads |
| **🚀 Performance** | Análisis full-stack: N+1 queries, bundle size, Core Web Vitals, cache, infraestructura. Cada optimización con impacto estimado y ratio esfuerzo/beneficio. | Cuando algo es lento, antes de un lanzamiento, en auditorías de rendimiento. | Developers, DevOps, Tech Leads |
| **🗄️ DBA** | Diseño de esquemas normalizados, optimización de queries con EXPLAIN, migraciones seguras en producción con plan de rollback. | Al diseñar tablas nuevas, cuando queries son lentas, antes de migraciones en producción. | Backend Devs, DBAs, Tech Leads |
| **👤 UX** | Revisión de usabilidad y accesibilidad WCAG 2.1 AA. Verifica teclado, contraste, lectores de pantalla, touch targets, formularios. | Al crear interfaces, antes de lanzamientos, en auditorías de accesibilidad. | Frontend Devs, Diseñadores, Product Owners |
| **📈 SEO** | Auditoría SEO técnica: rastreo, indexación, datos estructurados (JSON-LD), Core Web Vitals, HTML semántico, SSR/SSG. | En webs públicas, al crear landing pages, antes de lanzamientos de marketing. | Frontend Devs, Marketing, Product Owners |
| **🔧 DevOps** | CI/CD, Docker, infraestructura como código, observabilidad, despliegues seguros. Diseña pipelines, configura entornos, establece alertas. | Al configurar CI/CD, al crear entornos Docker, al preparar despliegues. | DevOps, Backend Devs, Tech Leads |
| **🧪 Tester** | Diseña estrategia de testing: pirámide de tests, casos de prueba por técnica (partición de equivalencia, valores frontera, tablas de decisión, transiciones de estado). | Al definir cómo testear una feature, al detectar áreas sin cobertura, en auditorías de calidad. | QA, Developers, Tech Leads |

### Mapa visual: cuándo usar cada agente

```
                        CICLO DE VIDA DE UNA FEATURE

  IDEA            DISEÑO           IMPLEMENTACIÓN      REVISIÓN        PRODUCCIÓN
   │                │                    │                │                │
   │  @researcher   │  @architect        │  @executor     │  @reviewer     │  @performance
   │  @planner      │  @dba (esquema)    │  @mentor       │  @security     │  @seo
   │                │  @ux (diseño)      │  @debugger     │  @verifier     │  @devops
   │                │                    │  @tester       │  @ux (audit)   │
   ▼                ▼                    ▼                ▼                ▼
```

---

## 6. Pipeline de features

Para features complejas (módulos nuevos, integraciones, cambios de arquitectura), el sistema ofrece un **pipeline estructurado** que garantiza que la implementación pasa por las fases correctas.

### Flujo visual

```
  ┌─────────────┐     ┌─────────────┐     ┌─────────────┐     ┌─────────────┐
  │   DISCUSS   │ ──▶ │    PLAN     │ ──▶ │   EXECUTE   │ ──▶ │   VERIFY    │
  │             │     │             │     │             │     │             │
  │ Capturar    │     │ Investigar  │     │ Implementar │     │ Verificar   │
  │ decisiones  │     │ Descomponer │     │ tarea por   │     │ que TODO    │
  │ del equipo  │     │ en tareas   │     │ tarea con   │     │ funciona    │
  │ antes de    │     │ atómicas y  │     │ commits     │     │ de verdad   │
  │ implementar │     │ verificables│     │ atómicos    │     │ (no solo    │
  │             │     │             │     │             │     │  que existe)│
  └─────────────┘     └─────────────┘     └─────────────┘     └─────────────┘
       ↑                                                            │
       │                    🔄 Si algo falla                        │
       └────────────────────────────────────────────────────────────┘
```

### ¿Qué produce cada fase?

| Fase | Documento generado | Para qué sirve |
|------|--------------------|----------------|
| **Discuss** | `CONTEXT.md` | Registro de todas las decisiones del equipo/producto antes de codificar |
| **Plan** | `PLAN.md` | Plan de implementación con tareas, estimaciones, dependencias y riesgos |
| **Execute** | `SUMMARY.md` | Registro de lo que se implementó, commits, desviaciones del plan |
| **Verify** | `VERIFICATION.md` | Evidencia de que la feature funciona: tests, verificación manual, criterios cumplidos |

### ¿Por qué importa a la dirección?

| Sin pipeline | Con pipeline |
|-------------|-------------|
| "Ya está hecho" (pero nadie verificó) | Evidencia documentada de que funciona |
| Scope creep invisible | Alcance definido y registrado antes de empezar |
| "¿Qué se decidió y por qué?" | CONTEXT.md con todas las decisiones |
| El desarrollador se fue y nadie sabe qué hizo | SUMMARY.md con cada tarea y commit |
| "Funciona en mi máquina" | VERIFICATION.md con verificación goal-backward |

---

## 7. Beneficios por rol

### 👨‍💻 Para desarrolladores

| Beneficio | Cómo |
|-----------|------|
| **Código consistente** sin esfuerzo extra | Las instrucciones por stack aplican automáticamente |
| **Menos retrabajos** en code review | El `@reviewer` detecta problemas antes del PR |
| **Debugging más rápido** | `@debugger` con metodología sistemática en vez de "prueba y error" |
| **No perder contexto entre días** | State management guarda dónde te quedaste |
| **Aprendizaje continuo** | `@mentor` enseña mientras implementa |
| **Templates para tareas repetitivas** | 25 prompts listos para crear migraciones, componentes, tests… |

### 👨‍💼 Para tech leads / jefes de equipo

| Beneficio | Cómo |
|-----------|------|
| **Convenciones cumplidas sin estar encima** | El sistema las aplica automáticamente |
| **Seguridad verificada en cada cambio** | Checklist OWASP integrado, no opcional |
| **Visibilidad del progreso** | STATE.md muestra posición, decisiones, blockers |
| **Onboarding acelerado** | `@codebase-mapper` mapea el proyecto en minutos |
| **Trazabilidad de decisiones** | Pipeline genera CONTEXT.md con decisiones documentadas |
| **Estimaciones más fiables** | `@planner` con T-shirt sizing y análisis de riesgos |
| **Menos "bugs de viernes"** | `@verifier` verifica que la feature funciona antes de mergear |

### 📊 Para product owners / analistas

| Beneficio | Cómo |
|-----------|------|
| **Criterios de aceptación formalizados** | El Planner los define como condiciones verificables sí/no |
| **Alcance controlado** | Scope guard impide cambios fuera de lo pedido |
| **Visibilidad sin acceder al código** | STATE.md, CONTEXT.md y VERIFICATION.md son legibles por no-técnicos |
| **Decisiones de producto registradas** | La fase DISCUSS captura y guarda cada decisión |
| **Estimaciones descompuestas** | El plan muestra cada tarea con su estimación, no solo un número global |

### 🎨 Para diseñadores UX/UI

| Beneficio | Cómo |
|-----------|------|
| **Accesibilidad verificada** | `@ux` audita WCAG 2.1 AA en el código generado |
| **Patrones de interacción correctos** | Verificación de teclado, foco, touch targets, formularios |
| **SEO técnico cubierto** | `@seo` verifica HTML semántico, datos estructurados, rendimiento |
| **Decisiones de UX documentadas** | CONTEXT.md captura decisiones de diseño antes de implementar |

### 🏢 Para dirección / management

| Beneficio | Cómo |
|-----------|------|
| **Reducción de riesgo** | Seguridad OWASP en cada cambio, no al final del proyecto |
| **Calidad predecible** | Mismas reglas en todos los proyectos, todos los desarrolladores |
| **Conocimiento que no se pierde** | Learnings, context cache y codebase mapping persisten |
| **ROI visible** | Menos bugs en producción, onboarding más rápido, menos retrabajos |
| **Sin vendor lock-in** | Todo es Markdown en una carpeta. No hay servicio externo, base de datos ni suscripción adicional |
| **Escalable** | Funciona igual con 2 desarrolladores que con 50 |

---

## 8. Seguridad integrada

La seguridad no es un paso adicional — está integrada en cada acción del sistema.

### Cómo funciona

```
  Desarrollador pide un cambio
         │
         ▼
  ¿El cambio toca input, output, auth, datos sensibles, SQL, archivos o integraciones?
         │
    SÍ ──┼──── El sistema verifica automáticamente:
         │     ✅ Input validado (no inyección)
         │     ✅ Output escapado (no XSS)
         │     ✅ Auth/authz aplicado (no acceso no autorizado)
         │     ✅ Datos sensibles protegidos (no en logs ni respuestas)
         │     ✅ SQL seguro (no inyección SQL)
         │     ✅ CSRF protegido
         │     ✅ Secretos fuera del código
         │     ✅ Rate limiting si aplica
         │
    NO ──┼──── Cambio cosmético: se aplica sin verificación de seguridad
         │
         ▼
  Entrega incluye: "Seguridad: verificados [puntos]. Sin riesgos / Riesgos: [detalle]"
```

### Garantías

- Alineado con **OWASP Top 10**, **ASVS** y **Cheat Sheets**.
- **No desactivable**: ni siquiera los agentes pueden saltarse la verificación de seguridad (es un invariante del sistema — §6).
- **El agente `@security`** ofrece auditorías completas bajo demanda con clasificación CVSS.
- Cada hallazgo incluye **escenario de explotación** y **fix concreto** — no solo el problema.

---

## 9. Gestión del conocimiento

El sistema incluye tres mecanismos para que el conocimiento del equipo **no se pierda**:

### 1. Learnings (lecciones aprendidas)

Cuando el asistente comete un error y el usuario lo corrige, el sistema propone guardar la corrección como una **regla concreta** que no volverá a violar.

**Ejemplo real:**
> *"Al crear un endpoint, el agente puso la validación en el controller. El proyecto usa FormRequests. Corrección: siempre crear FormRequest en este proyecto."*

Este learning se aplica automáticamente en futuras sesiones. Si se repite frecuentemente, se promueve a instrucción permanente.

### 2. Context cache (documentación consultada)

Cuando el sistema consulta documentación oficial de un framework (Laravel, React, Vue…), guarda un **resumen condensado** para que la próxima consulta sea instantánea. Incluye fecha de expiración para mantenerse actualizado.

### 3. Codebase mapping (mapa del proyecto)

Al aterrizar en un proyecto existente, el sistema genera 4 documentos que cualquier nuevo miembro puede leer:

| Documento | Contenido |
|-----------|-----------|
| **STACK.md** | Tecnologías, versiones, dependencias, integraciones |
| **ARCHITECTURE.md** | Estructura de carpetas, capas, puntos de entrada, diagramas |
| **CONVENTIONS.md** | Naming, patterns, estilo de código, cómo testear |
| **CONCERNS.md** | Deuda técnica, TODOs, áreas sin tests, recomendaciones priorizadas |

**Impacto:** un nuevo miembro del equipo puede entender un proyecto en **horas en lugar de semanas**.

---

## 10. Métricas de impacto esperadas

| Área | Métrica | Impacto esperado |
|------|---------|------------------|
| **Code reviews** | Hallazgos críticos por PR | ↓ 40-60% (detectados antes del PR) |
| **Seguridad** | Vulnerabilidades en producción | ↓ Significativa (verificación automática en cada cambio) |
| **Onboarding** | Tiempo hasta primera contribución productiva | ↓ 50-70% (codebase mapping + mentor) |
| **Retrabajos** | PRs devueltos por convenciones | ↓ 60-80% (convenciones aplicadas automáticamente) |
| **Testing** | Cobertura de features nuevas | ↑ 100% en lógica de negocio (tests obligatorios) |
| **Consistencia** | Varianza de estilo entre desarrolladores | → 0% (mismas reglas para todos) |
| **Continuidad** | Tiempo perdido al inicio del día | ↓ 80-90% (state management + session resume) |
| **Planificación** | Scope creep no controlado | ↓ Significativa (scope guard + pipeline) |
| **Bugs de integración** | Bugs por piezas "no conectadas" | ↓ Significativa (verificación goal-backward) |

> **Nota:** estas métricas son estimaciones basadas en la experiencia con sistemas similares. Se recomienda medir el baseline antes de la adopción y comparar a los 3 y 6 meses.

---

## 11. Coste y requisitos

### Requisitos

| Requisito | Detalle |
|-----------|---------|
| **Licencia GitHub Copilot** | Individual, Business o Enterprise — por desarrollador |
| **IDE compatible** | VS Code (recomendado, soporte completo) o PHPStorm (2024.3+) |
| **Instalación** | Copiar carpeta `.github/` al proyecto. Sin CLI, sin base de datos, sin servicios |

### Coste total

| Concepto | Coste |
|----------|-------|
| **NelkoDev-Copilot** | **$0** — es una carpeta de archivos Markdown |
| **GitHub Copilot Business** | ~$19/usuario/mes (precio de GitHub) |
| **Mantenimiento del sistema** | ~1-2h/mes para actualizar instrucciones si cambian convenciones |

### Lo que NO requiere

- ❌ No requiere servidor ni infraestructura adicional.
- ❌ No requiere base de datos.
- ❌ No requiere software adicional (no hay CLI, no hay daemon).
- ❌ No envía datos a servicios de terceros (todo es local + GitHub Copilot).
- ❌ No tiene vendor lock-in (todo son ficheros Markdown, portable a cualquier herramienta futura).

---

## 12. Preguntas frecuentes

### ¿Sustituye a los desarrolladores?

**No.** Es una herramienta que amplifica la capacidad del equipo existente. Automatiza lo repetitivo (convenciones, checklists de seguridad, templates) para que los desarrolladores se enfoquen en lo que aporta valor: diseño, decisiones, lógica de negocio.

### ¿Y si Copilot genera código incorrecto?

El sistema tiene múltiples capas de protección:
1. Las instrucciones guían hacia patrones correctos.
2. El `@reviewer` detecta errores antes del PR.
3. El `@verifier` comprueba que el resultado funciona.
4. Los tests de regresión detectan roturas.
5. Si algo se escapa, el learning evita que se repita.

**Copilot es una herramienta, no un sustituto del criterio humano.** El código siempre debe revisarse.

### ¿Funciona con nuestro stack?

El sistema incluye instrucciones para **Laravel, React, Vue, Node.js, Python, PHP** y frameworks asociados (Filament, Livewire, etc.). Para stacks no cubiertos, se crean instrucciones específicas siguiendo la misma estructura.

### ¿Qué pasa si un desarrollador no quiere usarlo?

El sistema es **transparente**: no cambia el flujo de trabajo. Copilot simplemente genera mejor código. Los agentes son opcionales — solo se activan si el desarrollador los invoca. El beneficio mínimo (convenciones + seguridad) aplica automáticamente sin acción del usuario.

### ¿Se puede personalizar por proyecto?

**Sí**, esa es la idea. Cada proyecto tiene su propia copia de `.github/` con instrucciones adaptadas a su stack y convenciones. Los proyectos Laravel no necesitan instrucciones de React, y viceversa.

### ¿Cómo se mantiene actualizado?

- **Convenciones**: se actualizan los ficheros `.instructions.md` cuando el equipo adopta nuevos patrones.
- **Learnings**: se crean automáticamente cuando el sistema comete errores.
- **Context cache**: se refresca automáticamente según el TTL configurado (30-90 días según framework).
- **Esfuerzo estimado**: 1-2 horas al mes.

### ¿Es compatible con GitHub Actions?

**Sí.** Los archivos de Copilot viven en `.github/` pero no interfieren con workflows de GitHub Actions (que están en `.github/workflows/`). Conviven sin problema.

### ¿Se puede medir el ROI?

Sí. Se recomienda:
1. Medir **antes**: PRs devueltos, bugs en producción, tiempo de onboarding, cobertura de tests.
2. Adoptar NelkoDev-Copilot.
3. Medir **después** (3 y 6 meses): mismas métricas.
4. Comparar.

### ¿Qué diferencia hay con usar Copilot "tal cual"?

| Copilot sin configurar | Copilot + NelkoDev-Copilot |
|------------------------|---------------------------|
| Genera código genérico | Genera código con las convenciones de tu equipo |
| No verifica seguridad | Checklist OWASP automático |
| No recuerda decisiones previas | State management entre sesiones |
| Cada desarrollador recibe respuestas distintas | Mismo estándar para todo el equipo |
| No planifica | Pipeline discuss→plan→execute→verify |
| No aprende de errores | Learnings acumulativos |

---

> **Documento generado:** 2026-03-19
> **Sistema:** NelkoDev-Copilot v2.0
> **Contacto técnico:** El equipo de desarrollo puede consultar `GUIA-DE-USO.md` para detalles de implementación.

