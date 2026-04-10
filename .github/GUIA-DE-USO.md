# NelkoDev-Copilot — Guía de uso para equipos

> **Wiki interna** para configurar y aprovechar al máximo el sistema de instrucciones de GitHub Copilot en tus proyectos.

---

## Índice

1. [¿Qué es esto?](#1-qué-es-esto)
2. [Instalación rápida](#2-instalación-rápida)
3. [Compatibilidad: PHPStorm vs VS Code](#3-compatibilidad-phpstorm-vs-vs-code)
4. [Estructura de la carpeta `.github/`](#4-estructura-de-la-carpeta-github)
5. [Instrucciones globales (`copilot-instructions.md`)](#5-instrucciones-globales)
6. [Instrucciones por stack (`instructions/`)](#6-instrucciones-por-stack)
7. [Agentes (`agents/`)](#7-agentes)
8. [Prompts reutilizables (`prompts/`)](#8-prompts-reutilizables)
9. [Skills (`skills/`)](#9-skills)
10. [Context Cache (`context/`)](#10-context-cache)
11. [Learnings (`learnings/`)](#11-learnings)
12. [Instrucciones de commits (`git-commit-instructions.md`)](#12-instrucciones-de-commits)
13. [Flujo de trabajo recomendado](#13-flujo-de-trabajo-recomendado)
14. [Consejos para el equipo](#14-consejos-para-el-equipo)
15. [FAQ — Preguntas frecuentes](#15-faq)
16. [Solución de problemas](#16-solución-de-problemas)

---

## 1. ¿Qué es esto?

Esta carpeta `.github/` contiene un **sistema completo de instrucciones** que personaliza el comportamiento de GitHub Copilot (Chat, Edits y Agent Mode) en tu proyecto. Es como entrenar a Copilot con las convenciones, reglas y buenas prácticas de tu equipo.

### ¿Qué problema resuelve?

Sin esta configuración, Copilot genera código genérico que puede no seguir las convenciones del proyecto. Con ella:

- Copilot genera código alineado con el stack y las convenciones del repo.
- Verificación de seguridad (OWASP) en cada cambio relevante.
- Testing automático: Copilot propone tests cuando corresponde.
- Roles especializados (reviewer, architect, debugger...) activables por comando.
- Templates de tareas comunes reutilizables por todo el equipo.
- Base de conocimiento compartida de errores y correcciones.

### ¿Cómo funciona?

GitHub Copilot lee automáticamente los archivos de `.github/` cuando están en la raíz de tu proyecto. No necesitas configuración adicional en el IDE — **solo copiar la carpeta**.

---

## 2. Instalación rápida

### Paso a paso

```bash
# 1. Copiar la carpeta .github/ en la raíz de tu proyecto
cp -r /ruta/a/.github /ruta/a/tu-proyecto/.github

# 2. Verificar que la estructura quedó correcta
tu-proyecto/
├── .github/
│   ├── copilot-instructions.md     ← Configuración principal
│   ├── git-commit-instructions.md  ← Formato de commits
│   ├── agents/                     ← Roles activables
│   ├── context/                    ← Cache de documentación
│   ├── instructions/               ← Reglas por stack/área
│   ├── learnings/                  ← Base de conocimiento
│   ├── prompts/                    ← Templates de tareas
│   └── skills/                     ← Habilidades especializadas
├── src/
├── ...
```

```bash
# 3. Commitear la carpeta al repo para que todo el equipo la tenga
git add .github/
git commit -m "feat: añadir configuración NelkoDev-Copilot para el equipo"
git push
```

> **Importante:** commitea la carpeta al repositorio. Así todo el equipo tendrá la misma configuración de Copilot automáticamente al hacer `git pull`.

---

## 3. Compatibilidad: PHPStorm vs VS Code

### Visual Studio Code

| Funcionalidad | Soporte | Cómo activar |
|---|---|---|
| **Instrucciones globales** (`copilot-instructions.md`) | Completo | Automático al tener el archivo en `.github/` |
| **Instrucciones por archivo** (`instructions/*.instructions.md`) | Completo | Automático — Copilot las aplica según el campo `applyTo` de la cabecera YAML |
| **Prompts reutilizables** (`prompts/*.prompt.md`) | Completo | Usar `/` en Copilot Chat para ver y ejecutar los prompts disponibles |
| **Agentes** (`agents/*.agent.md`) | Completo | Escribir `@` + nombre del agente en Copilot Chat (ej: `@reviewer`) o pedir "Activa el agente reviewer" |
| **Commits con GitMoji** (`git-commit-instructions.md`) | Completo | Usar el ícono de sparkle en el panel de Source Control para generar el mensaje |
| **Context cache** (`context/`) | Manual | El agente los lee y crea cuando los necesita |
| **Learnings** (`learnings/`) | Manual | El agente los consulta según el área de trabajo |

**Requisitos en VS Code:**
- Extensión **GitHub Copilot** instalada y activa (v1.200+ recomendada).
- Extensión **GitHub Copilot Chat** instalada.
- Habilitar en Settings: `github.copilot.chat.codeGeneration.useInstructionFiles` → `true`.

### JetBrains (PHPStorm, WebStorm, IntelliJ IDEA)

| Funcionalidad | Soporte | Cómo activar |
|---|---|---|
| **Instrucciones globales** (`copilot-instructions.md`) | Completo | Automático al tener el archivo en `.github/` |
| **Instrucciones por archivo** (`instructions/*.instructions.md`) | Completo | Automático — se aplican según `applyTo` |
| **Prompts reutilizables** (`prompts/*.prompt.md`) | Completo | Usar `/` en Copilot Chat para listar y ejecutar prompts |
| **Agentes** (`agents/*.agent.md`) | Completo | Usar `@` + nombre del agente en el chat |
| **Commits con GitMoji** (`git-commit-instructions.md`) | Completo | En la ventana de commit, usar el ícono de Copilot para generar el mensaje |
| **Context cache** (`context/`) | Manual | El agente los lee y crea cuando los necesita |
| **Learnings** (`learnings/`) | Manual | El agente los consulta según el área de trabajo |

**Requisitos en JetBrains:**
- Plugin **GitHub Copilot** instalado (versión 2024.3+ recomendada).
- Cuenta GitHub con licencia Copilot activa.
- Ir a **Settings → Languages & Frameworks → GitHub Copilot** y verificar que está habilitado.

> **Nota:** La funcionalidad de instrucciones personalizadas funciona de forma idéntica en ambos IDEs. La carpeta `.github/` es el estándar oficial de GitHub Copilot para configuración a nivel de proyecto.

---

## 4. Estructura de la carpeta `.github/`

```
.github/
│
├── copilot-instructions.md          Cerebro principal — reglas globales
├── git-commit-instructions.md       Formato de mensajes de commit (GitMoji)
│
├── instructions/                    Reglas por stack/área (se aplican automáticamente)
│   ├── laravel.instructions.md         → Convenciones Laravel
│   ├── react.instructions.md           → Convenciones React
│   ├── vue.instructions.md             → Convenciones Vue
│   ├── php.instructions.md             → Convenciones PHP general
│   ├── python.instructions.md          → Convenciones Python
│   ├── node.instructions.md            → Convenciones Node.js
│   ├── controllers.instructions.md     → Reglas para controladores
│   ├── models.instructions.md          → Reglas para modelos
│   ├── services.instructions.md        → Reglas para servicios
│   ├── migrations.instructions.md      → Reglas para migraciones
│   ├── tests-php.instructions.md       → Reglas para testing PHP
│   ├── components.instructions.md      → Reglas para componentes UI
│   ├── css.instructions.md             → Reglas CSS
│   ├── js.instructions.md              → Reglas JavaScript
│   ├── filament.instructions.md        → Convenciones Filament
│   ├── init-project.instructions.md    → Protocolo de inicialización
│   ├── docs.instructions.md            → Reglas de documentación
│   ├── readme.instructions.md          → Cómo escribir READMEs
│   ├── architecture.instructions.md    → Decisiones de arquitectura
│   ├── analisis.instructions.md        → Documentos de análisis
│   ├── trace.instructions.md           → Trazabilidad de cambios
│   ├── backlog.instructions.md         → Gestión de backlog
│   └── task.instructions.md            → Gestión de tareas
│
├── agents/                          Roles activables por el usuario
│   ├── reviewer.agent.md               → Revisor de código estricto
│   ├── architect.agent.md              → Diseñador de arquitectura
│   ├── mentor.agent.md                 → Explicador pedagógico
│   ├── debugger.agent.md               → Depurador sistemático
│   ├── planner.agent.md                → Planificador de features
│   ├── executor.agent.md               → Implementador disciplinado por plan
│   ├── verifier.agent.md               → Verificador escéptico goal-backward
│   ├── researcher.agent.md             → Investigador de dominio y stack
│   ├── codebase-mapper.agent.md        → Mapeador de codebase existente
│   ├── documenter.agent.md             → Documentador técnico
│   ├── security.agent.md               → Auditor de seguridad OWASP
│   ├── performance.agent.md            → Analista de rendimiento
│   ├── dba.agent.md                    → Especialista en bases de datos
│   ├── ux.agent.md                     → Revisor de usabilidad/accesibilidad
│   ├── devops.agent.md                 → CI/CD e infraestructura
│   ├── tester.agent.md                 → Estrategia de testing y QA
│   └── seo.agent.md                    → Auditoría SEO técnica
│
├── prompts/                         Templates de tareas reutilizables
│   ├── analyze-php-class.prompt.md
│   ├── code-review.prompt.md
│   ├── create-laravel-migration.prompt.md
│   ├── create-laravel-resource.prompt.md
│   ├── create-react-component.prompt.md
│   ├── create-vue-component.prompt.md
│   ├── filament-resource.prompt.md
│   ├── generate-readme.prompt.md
│   ├── generate-trace-entry.prompt.md
│   ├── implement-ddd-service.prompt.md
│   ├── improve-javascript.prompt.md
│   ├── init-project.prompt.md
│   ├── init-state.prompt.md
│   ├── map-codebase.prompt.md
│   ├── node-service.prompt.md
│   ├── plan-feature.prompt.md
│   ├── python-script.prompt.md
│   ├── refactor-clean-code.prompt.md
│   ├── refactor-frontend.prompt.md
│   ├── session-resume.prompt.md
│   ├── session-save.prompt.md
│   ├── update-docs.prompt.md
│   ├── verify-work.prompt.md
│   ├── write-analysis-functional.prompt.md
│   ├── write-analysis-technical.prompt.md
│   ├── write-doc-module.prompt.md
│   └── write-phpunit-tests.prompt.md
│
├── skills/                          Habilidades especializadas
│   ├── api-design.skill.md
│   ├── codebase-analysis.skill.md
│   ├── data-modeling.skill.md
│   ├── db-optimization.skill.md
│   ├── dependency-management.skill.md
│   ├── docker-environment.skill.md
│   ├── error-diagnosis.skill.md
│   ├── git-conflict-resolution.skill.md
│   ├── migration-planning.skill.md
│   ├── refactoring-patterns.skill.md
│   ├── regex-builder.skill.md
│   ├── testing-patterns.skill.md
│   └── verification-patterns.skill.md
│
├── context/                         Cache de documentación externa
│   └── (vacío inicialmente — se llena automáticamente)
│
└── learnings/                       Base de conocimiento de errores
    └── (vacío inicialmente — se llena con el uso)
```

---

## 5. Instrucciones globales

El archivo `copilot-instructions.md` es el **cerebro principal** del sistema. Define:

| Sección | Qué hace |
|---|---|
| **§0 Precedencia** | Orden de prioridad cuando hay instrucciones conflictivas |
| **§1 Principio base** | Leer → Identificar convenciones → Diseñar solución mínima → Validar |
| **§3 Comportamiento** | Profesional, proactivo, adaptable, honesto, enfocado |
| **§4 Niveles de tarea** | TRIVIAL / ESTÁNDAR / CRÍTICO — cada nivel tiene flujo diferente |
| **§6 Seguridad** | Verificación OWASP obligatoria en cambios estándar o superiores |
| **§7 Testing** | Cuándo testear y cuándo no |
| **§11 Scope Guard** | Solo hacer lo que se pide, no refactorizar de más |
| **§14 Definition of Done** | Checklist de completitud de un cambio |
| **§15 Output estructurado** | Formato de entrega al finalizar cada tarea |

### Niveles de tarea — Referencia rápida

| Nivel | Ejemplo | Testing | Seguridad | Documentar |
|---|---|---|---|---|
| **TRIVIAL** | Fix de typo, cambio de color CSS | No | No | No |
| **ESTÁNDAR** | Nuevo componente, bugfix con lógica | Sí | Sí | Solo si cambia contrato |
| **CRÍTICO** | Nuevo módulo, cambio en auth, migración destructiva | Sí (extenso) | Sí (detallado) | Sí |

> **Regla de oro:** en caso de duda entre estándar y crítico, tratar como crítico.

**No necesitas editar este archivo** salvo que quieras adaptar las reglas globales a tu equipo.

---

## 6. Instrucciones por stack

Los archivos en `instructions/` se aplican **automáticamente** según el contexto del archivo que estés editando. Cada uno tiene una cabecera YAML con `applyTo` que indica en qué rutas se activa.

### Ejemplo: `laravel.instructions.md`

```yaml
---
applyTo: "app/**"
description: "Reglas para desarrollo en Laravel"
---
```

Esto significa que cuando edites cualquier archivo dentro de `app/`, Copilot aplicará automáticamente las convenciones Laravel definidas en ese archivo.

### Instrucciones disponibles por categoría

**Backend:**
| Archivo | Se aplica en | Qué define |
|---|---|---|
| `laravel.instructions.md` | `app/**` | Estructura, Eloquent, validación, Jobs, testing Laravel |
| `php.instructions.md` | `*.php` | Convenciones PHP generales, PSR-12 |
| `controllers.instructions.md` | `*Controller*` | Controladores delgados, inyección, responses |
| `models.instructions.md` | `*Model*` | Eloquent, relaciones, scopes, casts |
| `services.instructions.md` | `*Service*` | Lógica de negocio, inyección de dependencias |
| `migrations.instructions.md` | `*migration*` | Migraciones seguras, rollback, Expand→Migrate→Contract |
| `filament.instructions.md` | `app/Filament/**` | Recursos, páginas, widgets Filament |

**Frontend:**
| Archivo | Se aplica en | Qué define |
|---|---|---|
| `react.instructions.md` | `*.jsx, *.tsx` | Hooks, componentes funcionales, state management |
| `vue.instructions.md` | `*.vue` | Composition API, composables, directivas |
| `components.instructions.md` | Componentes UI | Props, emits, slots, accesibilidad |
| `js.instructions.md` | `*.js, *.ts` | ES modules, async/await, error handling |
| `css.instructions.md` | `*.css, *.scss` | Naming, responsive, utilidades |

**Testing:**
| Archivo | Se aplica en | Qué define |
|---|---|---|
| `tests-php.instructions.md` | `tests/**` | PHPUnit/Pest, factories, assertions, mocking |

**Documentación y gestión:**
| Archivo | Qué define |
|---|---|
| `docs.instructions.md` | Cuándo y cómo documentar |
| `readme.instructions.md` | Estructura de READMEs |
| `analisis.instructions.md` | Documentos de análisis funcional/técnico |
| `architecture.instructions.md` | ADRs y decisiones de arquitectura |
| `task.instructions.md` | Formato de definición de tareas |
| `backlog.instructions.md` | Gestión de backlog |
| `trace.instructions.md` | Trazabilidad de cambios críticos |

### ¿Cómo personalizar?

Edita directamente el archivo `.instructions.md` que corresponda. Si necesitas instrucciones para un stack que no existe (ej: Go, Rust), crea un nuevo archivo con la misma estructura:

```yaml
---
applyTo: "*.go"
description: "Reglas para desarrollo en Go"
---

# GO — CONVENCIONES

## Estructura
- ...
```

---

## 7. Agentes

Los agentes son **roles alternativos** que cambian completamente el enfoque de Copilot para una tarea específica. Es como tener un equipo de especialistas a tu disposición.

### Cómo activar un agente

**En VS Code:** en Copilot Chat, escribe:
```
@reviewer revisa este PR
```
o bien:
```
Activa el agente reviewer
```

**En PHPStorm:** en Copilot Chat, escribe lo mismo:
```
@reviewer revisa este archivo
```

### Catálogo de agentes

#### Agentes generales

| Agente | Comando | Caso de uso |
|---|---|---|
| **Reviewer** | `@reviewer` | "Revisa este código antes de hacer PR" — Revisión estricta clasificada por severidad (critico, importante, sugerencia) |
| **Architect** | `@architect` | "Diseña la arquitectura para el módulo de pagos" — Diagramas Mermaid, ADRs, trade-offs, plan de implementación |
| **Mentor** | `@mentor` | "Explícame qué es el patrón Repository" — Enseña conceptos de forma pedagógica con ejemplos del proyecto |
| **Debugger** | `@debugger` | "Este endpoint devuelve 500 intermitentemente" — Diagnóstico sistemático: reproducción → hipótesis → causa raíz → fix → prevención |
| **Planner** | `@planner` | "Descompón esta feature en tareas" — Crea tareas INVEST estimadas con dependencias |
| **Executor** | `@executor` | "Implementa el plan paso a paso" — Ejecuta planes tarea por tarea con commits atómicos y verificación por paso |
| **Verifier** | `@verifier` | "Verifica que esta feature funciona" — Verificación escéptica goal-backward en 3 niveles (existe → contenido real → integración) |
| **Researcher** | `@researcher` | "Investiga opciones para integrar pagos" — Investigación de dominio/stack con trade-offs y context cache |
| **Codebase Mapper** | `@codebase-mapper` | "Mapea este proyecto" — Produce STACK.md, ARCHITECTURE.md, CONVENTIONS.md, CONCERNS.md |
| **Documenter** | `@documenter` | "Documenta el módulo de autenticación" — Documentación técnica anti-relleno, orientada al desarrollador |

#### Agentes disciplinares

| Agente | Comando | Caso de uso |
|---|---|---|
| **Security** | `@security` | "Audita la seguridad de este módulo" — OWASP Top 10, clasificación CVSS, remediación concreta |
| **Performance** | `@performance` | "Esta página carga lento" — Análisis full-stack: queries, bundles, cache, Core Web Vitals |
| **DBA** | `@dba` | "Diseña el esquema para el módulo de inventario" — Diseño de esquemas, índices, optimización de queries |
| **UX** | `@ux` | "Revisa la usabilidad de este formulario" — Usabilidad, accesibilidad WCAG 2.1 AA |
| **DevOps** | `@devops` | "Configura el CI/CD para este proyecto" — Docker, pipelines, infraestructura, despliegues |
| **Tester** | `@tester` | "Define la estrategia de testing para este módulo" — Casos de prueba, cobertura, QA |
| **SEO** | `@seo` | "Audita el SEO de la landing" — Rastreo, indexación, Core Web Vitals, datos estructurados |

### Ejemplo práctico: usando el agente Reviewer

```
Tú: @reviewer Revisa el archivo app/Services/PaymentService.php

Copilot (como Reviewer):
CRITICO
- Línea 45: el monto no se valida antes de enviarlo a la pasarela.
  → Fix: añadir validación decimal > 0 antes de $gateway->charge().

IMPORTANTE  
- Línea 23: query N+1 al cargar las transacciones del usuario.
  → Fix: usar ->with('transactions') en la query.

SUGERENCIA
- Línea 12: el nombre $data es genérico. Considerar $paymentRequest.
```

### Ejemplo práctico: usando el agente Debugger

```
Tú: @debugger El endpoint POST /api/orders devuelve 422 pero los datos son correctos

Copilot (como Debugger):
## Fase 1 — Reproducción
Verificando el flujo: request → middleware → FormRequest → Controller...

## Fase 2 — Hipótesis
- H1 (90%): El FormRequest valida un campo que ya no existe en el frontend.
- H2 (10%): Middleware de validación adicional interfiriendo.

## Fase 3 — Diagnóstico
Confirmado H1: OrderRequest.php exige 'discount_code' como required, 
   pero el frontend ya no envía ese campo.

## Fase 4 — Fix
...
```

---

## 8. Prompts reutilizables

Los prompts son **templates de tareas** que ejecutan un flujo predefinido. Son como recetas que cualquier miembro del equipo puede usar para obtener resultados consistentes.

### Cómo usar un prompt

**En VS Code y PHPStorm:** en Copilot Chat, escribe `/` y verás la lista de prompts disponibles:

```
/create-laravel-migration
/create-react-component
/init-project
/write-phpunit-tests
...
```

Selecciona el prompt y Copilot te guiará por el flujo.

### Catálogo de prompts

#### Backend
| Prompt | Qué hace |
|---|---|
| `/create-laravel-migration` | Crea migración segura con patrón Expand→Migrate→Contract para cambios destructivos |
| `/create-laravel-resource` | Crea recurso API con transformer, validación y tests |
| `/filament-resource` | Crea recurso Filament completo (table, form, pages) |
| `/implement-ddd-service` | Implementa servicio con DDD: DTO, contrato, implementación, tests |
| `/analyze-php-class` | Analiza una clase PHP: responsabilidades, dependencias, sugerencias |

#### Frontend
| Prompt | Qué hace |
|---|---|
| `/create-react-component` | Crea componente React con props tipadas, tests y stories |
| `/create-vue-component` | Crea componente Vue con Composition API, props, emits y tests |
| `/improve-javascript` | Analiza y mejora código JS: performance, legibilidad, buenas prácticas |
| `/refactor-frontend` | Refactoriza frontend: extrae componentes, limpia CSS, mejora accesibilidad |

#### Documentación y análisis
| Prompt | Qué hace |
|---|---|
| `/generate-readme` | Genera README completo siguiendo las convenciones del proyecto |
| `/write-doc-module` | Documenta un módulo: propósito, API, ejemplos, configuración |
| `/write-analysis-functional` | Escribe análisis funcional de una feature |
| `/write-analysis-technical` | Escribe análisis técnico con diagramas y decisiones |

#### Testing y calidad
| Prompt | Qué hace |
|---|---|
| `/write-phpunit-tests` | Genera tests PHPUnit/Pest con factories, assertions y edge cases |
| `/refactor-clean-code` | Refactoriza código aplicando Clean Code y SOLID |
| `/code-review` | Code review multi-stack con checklists por severidad y stack |
| `/verify-work` | Verificación goal-backward del trabajo realizado |

#### Pipeline y sesiones
| Prompt | Qué hace |
|---|---|
| `/init-project` | Ejecuta protocolo completo de inicialización/aterrizaje en un proyecto |
| `/init-state` | Inicializa STATE.md y estructura `.planning/` |
| `/plan-feature` | Planifica una feature con pipeline discuss→plan→execute→verify |
| `/map-codebase` | Mapea un codebase existente (stack, arquitectura, convenciones) |
| `/session-save` | Guarda el estado de la sesión para retomar después |
| `/session-resume` | Retoma una sesión guardada previamente |
| `/update-docs` | Sincroniza GUIA-DE-USO y DOCUMENTO-EJECUTIVO con los ficheros reales |

#### Infraestructura y otros
| Prompt | Qué hace |
|---|---|
| `/generate-trace-entry` | Crea entrada de trazabilidad para cambios críticos |
| `/node-service` | Crea servicio Node.js con estructura y tests |
| `/python-script` | Crea script Python con estructura, args y error handling |

### Ejemplo práctico

```
Tú: /create-laravel-migration Necesito añadir un campo 'status' tipo enum 
    a la tabla orders con valores: pending, processing, completed, cancelled

Copilot:
1. Leo las migraciones existentes de orders...
2. Genero la migración:

// 2026_03_16_120000_add_status_to_orders.php
Schema::table('orders', function (Blueprint $table) {
    $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])
          ->default('pending')
          ->after('total');
    $table->index('status');
});

3. Comando: php artisan migrate
4. Rollback verificado
```

---

## 9. Skills

Las skills son **habilidades especializadas** que Copilot consulta cuando la tarea las requiere. Se activan cuando un agente, instrucción o flujo las referencia, o cuando el usuario las solicita explícitamente.

### Skills disponibles

| Skill | Se activa con | Qué aporta |
|---|---|---|
| **API Design** | "crear endpoint", "diseñar API", "REST" | Convenciones HTTP, paginación, errores, versionado |
| **Codebase Analysis** | "analizar codebase", "estructura del proyecto" | Análisis rápido de 5 min: stack, patrones, deuda técnica |
| **Data Modeling** | "modelo", "entidad", "relación", "ERD" | Normalización, relaciones, diagramas ER |
| **DB Optimization** | "N+1", "query lenta", "índice" | EXPLAIN, índices, eager loading, cache |
| **Dependency Management** | "dependencia", "package", "audit" | Evaluar, auditar, actualizar dependencias |
| **Docker Environment** | "docker", "contenedor" | docker-compose, Dockerfile, troubleshooting |
| **Error Diagnosis** | "error", "exception", "stack trace" | Diagnóstico con clasificación y patrones |
| **Git Conflict Resolution** | "conflicto", "merge", "rebase" | Resolución segura por tipo de conflicto |
| **Migration Planning** | "migración producción", "alter table" | Expand→Migrate→Contract, rollback, tablas grandes |
| **Refactoring Patterns** | "refactor", "clean code", "SOLID" | Guard Clauses, Extract Method, Strategy, DTO |
| **Regex Builder** | "regex", "expresión regular" | Construcción paso a paso, explicación, testing |
| **Testing Patterns** | "mock", "fake", "factory", "fixture" | Test doubles, factories, data providers |
| **Verification Patterns** | "verificar", "funciona", "validar" | Smoke test, boundary verification, integration trace, evidence collection |

### Ejemplo práctico

```
Tú: Tengo una query que tarda 3 segundos en la página de listado de orders

Copilot (activa automáticamente DB Optimization):
1. Analizando la query...
2. Ejecutando EXPLAIN...
3. Problema detectado: full table scan + N+1 en la relación con items
4. Solución:
   - Añadir índice compuesto en (user_id, status, created_at)
   - Usar ->with('items') para eager loading
   - Resultado esperado: < 100ms
```

---

## 10. Context Cache

La carpeta `context/` es un **caché de documentación externa** que Copilot llena automáticamente cuando consulta documentación de frameworks o librerías.

### ¿Para qué sirve?

Cuando Copilot necesita consultar la documentación de Laravel 11 para implementar algo, guarda un resumen en `context/`. La próxima vez que alguien del equipo necesite lo mismo, Copilot lee del caché en vez de consultar de nuevo, ahorrando tiempo y tokens.

### Ejemplo de archivo context

```
context/laravel_queues_11x.context.md
```

```yaml
---
source: "Context7 | docs oficiales"
framework: "Laravel"
version: "11.x"
topic: "Queues"
fetched: "2026-03-10"
expires: "2026-06-10"
---

## Resumen
Cómo configurar y usar Jobs/Queues en Laravel 11...

## Conceptos clave
...
```

### Expiración

| Tipo | Vigencia |
|---|---|
| Framework (Laravel 11, Vue 3) | 90 días |
| Librería (Filament, Livewire) | 30 días |
| API externa / SaaS | 14 días |

> **No necesitas gestionar esta carpeta manualmente.** Copilot la llena y la consulta automáticamente. Si crece demasiado (>15 archivos), pide a Copilot: "limpia el context cache".

---

## 11. Learnings

La carpeta `learnings/` es una **base de conocimiento viva** donde se registran errores cometidos y sus correcciones.

### ¿Cómo funciona?

1. **Copilot comete un error** (ej: genera código con un patrón que el proyecto no usa).
2. **Tú lo corriges** y Copilot detecta el error.
3. **Copilot propone crear un learning** — tú apruebas o rechazas.
4. **En futuras sesiones**, Copilot consulta los learnings antes de generar código en esa área.

### Ejemplo de learning

```
learnings/laravel_form_requests.md
```

```markdown
## Contexto
Al crear un endpoint, el agente puso la validación directamente en el controlador.

## Error
El proyecto usa FormRequests para toda validación. El agente no lo detectó.

## Corrección
Siempre crear un FormRequest para validación en este proyecto. No validar en controlador.

## Aplicar cuando
Se crea o modifica un endpoint con validación en un proyecto Laravel.
```

### Beneficio para el equipo

Los learnings se commitean al repo, así que **todo el equipo se beneficia** de las correcciones. Si alguien del equipo corrige un error de Copilot, ese error no se repite para nadie más.

> **Regla de higiene:** máximo 20 learnings. Cuando un learning se repite mucho, debería promoverse a una regla en el `*.instructions.md` correspondiente y eliminarse de learnings.

---

## 12. Instrucciones de commits

El archivo `git-commit-instructions.md` configura a Copilot para generar mensajes de commit con la convención **GitMoji + Conventional Commits**.

### Cómo usarlo

**VS Code:**
1. Haz tus cambios y añádelos al staging (`git add`).
2. En el panel de **Source Control**, haz clic en el ícono de sparkle/Copilot junto al campo de mensaje de commit.
3. Copilot genera el mensaje automáticamente.

**PHPStorm:**
1. Haz tus cambios y abre la ventana de commit.
2. Haz clic en el ícono de Copilot para generar el mensaje.
3. Revisa y confirma.

### Formato de los commits

```
feat: implementar filtro de búsqueda en listado de orders
fix: corregir cálculo de descuento cuando el cupón ha expirado
refactor: extraer lógica de pago a PaymentService
test: añadir tests para el flujo de cancelación de orders
docs: actualizar README con instrucciones de despliegue
```

### Emojis más comunes

| Emoji | Tipo | Uso |
|---|---|---|
| `:sparkles:` | feat | Nueva funcionalidad |
| `:bug:` | fix | Corrección de bug |
| `:recycle:` | refactor | Refactorización sin cambio funcional |
| `:white_check_mark:` | test | Añadir o corregir tests |
| `:memo:` | docs | Documentación |
| `:wrench:` | chore | Configuración, CI/CD |
| `:zap:` | perf | Mejora de rendimiento |
| `:lock:` | security | Fix de seguridad |

---

## 13. Flujo de trabajo recomendado

### Para el día a día

```
1. Abre tu proyecto (con .github/ ya commitado)
2. Abre Copilot Chat en tu IDE
3. Pide lo que necesitas en lenguaje natural:
   - "Crea un endpoint para listar usuarios con paginación"
   - "Añade validación al formulario de registro"
   - "Corrige este bug: el total no incluye IVA"
4. Copilot aplica automáticamente las convenciones del proyecto
5. Revisa el código generado
6. Usa Copilot para generar el mensaje de commit
```

### Para tareas complejas

```
1. Usa un prompt: /create-laravel-migration
2. O activa un agente: @architect diseña el módulo de pagos
3. Copilot sigue un flujo estructurado con análisis, diseño y validación
4. Al finalizar, recibes un resumen con archivos modificados y cómo probar
```

### Para features críticas (Pipeline)

```
1. Usa el prompt: /plan-feature
2. DISCUSS: Copilot te pregunta las decisiones clave antes de implementar
3. PLAN: Descompone la feature en tareas atómicas verificables
4. EXECUTE: Implementa tarea por tarea con commits atómicos
5. VERIFY: Verificación goal-backward de que todo funciona
6. Artefactos guardados en .planning/features/{slug}/
```

### Para gestión de sesiones

```
# Al empezar una sesión (automático si existe .planning/STATE.md)
Copilot detecta estado del proyecto y trabajo pendiente

# Al terminar una sesión larga
Usa: /session-save
Copilot guarda el estado y crea .continue-here.md si hay trabajo pendiente

# Al retomar al día siguiente
Usa: /session-resume
Copilot carga el contexto y ofrece retomar donde dejaste
```

### Para aterrizar en un proyecto existente

```
1. Usa el prompt: /map-codebase
2. Copilot analiza: stack, arquitectura, convenciones, deuda técnica
3. Genera 4 documentos en .planning/codebase/
4. Crea STATE.md como memoria del proyecto
5. Ahora Copilot conoce tu proyecto y genera código alineado
```

### Para revisión de código

```
1. @reviewer revisa app/Services/OrderService.php
2. Recibes un informe clasificado por severidad
3. Aplica los fixes críticos y evalúa las sugerencias
```

### Para onboarding de nuevo miembro

```
1. El nuevo miembro clona el repo (ya tiene .github/)
2. Pide: /init-project aterriza en este proyecto
3. Copilot analiza el proyecto y genera un mapa completo:
   - Stack y versiones
   - Estructura de carpetas con descripción
   - Convenciones detectadas
   - Estado de tests y documentación
4. El nuevo miembro puede preguntar: @mentor explícame cómo funciona el módulo de auth
```

---

## 14. Consejos para el equipo

### Hazlo

- **Commitea la carpeta `.github/`** para que todo el equipo tenga la misma configuración.
- **Usa lenguaje natural** — Copilot entiende mejor "crea un endpoint para listar orders filtradas por estado" que instrucciones técnicas crípticas.
- **Aprovecha los agentes** para tareas especializadas — no uses el modo genérico para todo.
- **Revisa siempre el código generado** — Copilot es una herramienta, no un sustituto del criterio humano.
- **Aprueba los learnings útiles** — mejoran la calidad de todo el equipo.
- **Personaliza las instrucciones** si tu proyecto tiene convenciones específicas que Copilot no detecta automáticamente.
- **Usa `/init-project`** al aterrizar en un proyecto nuevo — el mapa que genera ahorra horas de exploración.

### Evita

- **No confíes ciegamente** — siempre verifica lógica de negocio, queries, y seguridad.
- **No ignores los warnings de seguridad** — si Copilot dice "Seguridad: riesgo en input validation", revísalo.
- **No edites `copilot-instructions.md` sin consenso** — es la configuración de todo el equipo.
- **No acumules learnings indefinidamente** — promociónanos a instrucciones cuando se estabilicen.
- **No uses agentes para tareas triviales** — un fix de typo no necesita `@architect`.
- **No desactives la verificación de seguridad (§6)** — es un invariante del sistema por diseño.

### Tips avanzados

1. **Encadena agentes:** primero `@architect` para diseñar, luego implementa, luego `@reviewer` para revisar.
2. **Usa el pipeline** para features grandes: `@planner` → `@executor` → `@verifier` garantiza calidad.
3. **Contextualiza tus peticiones:** "En el módulo de pagos, usando el patrón que ya usamos en OrderService..." es mejor que "crea un servicio".
4. **Pide explicaciones:** si no entiendes algo, activa `@mentor` — está diseñado para enseñar sin juzgar.
5. **Usa el debugger antes de buscar en Stack Overflow:** `@debugger` sigue una metodología sistemática que a menudo encuentra el bug más rápido.
6. **Crea prompts custom** para tareas repetitivas de tu equipo — solo copia la estructura de un prompt existente.
7. **Mapea el codebase** al llegar a un proyecto: `/map-codebase` genera 4 documentos que mejoran todo lo que Copilot genera después.
8. **Guarda sesiones largas:** `/session-save` evita perder contexto. Al día siguiente `/session-resume` te pone al día.
9. **Investiga antes de implementar:** `@researcher` explora el dominio/librería antes de que escribas una línea de código.
10. **Verifica con escepticismo:** `@verifier` no confía en "debería funcionar" — verifica con evidencia real.

---

## 15. FAQ

### ¿Necesito licencia de GitHub Copilot?

Sí. Cada miembro del equipo necesita una licencia activa de GitHub Copilot (Individual, Business o Enterprise).

### ¿Funciona con Copilot Free?

Las instrucciones globales y por archivo funcionan. Los agentes y prompts requieren Copilot Chat, disponible en planes pagos.

### ¿Puedo usar esto sin GitHub Copilot?

La carpeta `.github/` es específica de GitHub Copilot. Sin embargo, las instrucciones están en Markdown, así que pueden servir como documentación de convenciones para el equipo independientemente de la herramienta.

### ¿Copilot lee TODOS los archivos de `.github/` en cada petición?

No. Lee `copilot-instructions.md` siempre (instrucciones globales). Las instrucciones específicas se cargan según el campo `applyTo`. Los agentes solo se cargan cuando los activas. Prompts cuando los ejecutas. Skills cuando detecta que aplican.

### ¿Puedo tener instrucciones diferentes por proyecto?

Sí, esa es la idea. Copiar la carpeta `.github/` en cada proyecto y personalizar las instrucciones según el stack y convenciones de ese proyecto.

### ¿Los cambios en `.github/` requieren reiniciar el IDE?

No. Copilot relee los archivos en cada nueva conversación de chat. Si editas una instrucción, abre una nueva conversación para que aplique.

### ¿Puedo crear mis propios agentes/prompts/skills?

Sí. Sigue la estructura documentada en los README de cada carpeta (`agents/README.md`, `skills/README.md`) y crea tus ficheros. Copilot los detecta automáticamente.

### ¿Esto interfiere con GitHub Actions u otros usos de `.github/`?

No. GitHub Copilot usa archivos específicos (`copilot-instructions.md`, carpetas `instructions/`, `agents/`, `prompts/`, etc.). Los workflows de GitHub Actions viven en `.github/workflows/` y no se ven afectados.

### ¿Qué pasa si el equipo ya tiene una carpeta `.github/`?

Solo añade los archivos de Copilot. Si ya existe `.github/workflows/` u otros archivos, no se ven afectados. Simplemente fusiona el contenido.

---

## 16. Solución de problemas

### Copilot no aplica las instrucciones

1. **Verifica la ubicación:** la carpeta `.github/` debe estar en la **raíz** del proyecto.
2. **Verifica el nombre:** el archivo debe llamarse exactamente `copilot-instructions.md`.
3. **Abre nueva conversación:** Copilot carga las instrucciones al iniciar el chat, no en caliente.
4. **VS Code:** verifica que `github.copilot.chat.codeGeneration.useInstructionFiles` está en `true` (Settings → buscar "instruction").
5. **PHPStorm:** verifica que el plugin de GitHub Copilot está actualizado (2024.3+).

### Los prompts no aparecen con `/`

1. Verifica que los archivos están en `.github/prompts/` con extensión `.prompt.md`.
2. Verifica que cada prompt tiene cabecera YAML con `description`.
3. Reinicia la conversación de chat.

### Los agentes no se activan con `@`

1. Verifica que los archivos están en `.github/agents/` con extensión `.agent.md`.
2. Verifica que tienen cabecera YAML con `name` y `description`.
3. Prueba con el comando completo: "Activa el agente {nombre}".

### Copilot genera código que no sigue las convenciones

1. Verifica que las convenciones están documentadas en el `*.instructions.md` correcto.
2. Verifica el campo `applyTo` — puede que no coincida con la ruta del archivo.
3. Si Copilot insiste en un patrón incorrecto, **aprueba un learning** para corregirlo.
4. Proporciona más contexto en tu prompt: "Siguiendo el patrón de OrderService..."

### El context cache está desactualizado

Pide a Copilot:
```
Limpia el context cache y actualiza la documentación de [framework] [versión]
```

---

> **Última actualización:** 2026-03-19
> **Sistema:** NelkoDev-Copilot  
> **Versión del sistema de instrucciones:** 2.0

