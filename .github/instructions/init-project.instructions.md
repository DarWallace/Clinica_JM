---
applyTo: "**"
description: "Protocolo de inicialización y aterrizaje en proyectos nuevos o existentes"
---

# INIT PROJECT — REGLAS

Objetivo: garantizar que cualquier miembro del equipo (o agente) produce los mismos artefactos y evaluaciones al aterrizar en un proyecto, ya sea nuevo o existente.

## Cuándo se ejecuta este protocolo

- **Proyecto nuevo**: al crear el repositorio y antes de escribir la primera línea de código de negocio.
- **Proyecto existente**: al incorporarse por primera vez al proyecto, o cuando se detecta que faltan artefactos base.
- **Auditoría**: cuando se quiere verificar que el proyecto tiene su documentación base completa.

---

## FASE 1 — RECONOCIMIENTO (siempre, nuevo o existente)

Antes de crear o modificar nada, recopilar información:

### 1.1 Lectura del proyecto
- [ ] Leer `README.md` (si existe).
- [ ] Leer archivos de configuración: `composer.json`, `package.json`, `pyproject.toml`, `Dockerfile`, `.env.example`.
- [ ] Leer estructura de carpetas top-level.
- [ ] Identificar stack: lenguaje, framework, versión, dependencias principales.
- [ ] Leer `Docs/`, `Analisis/`, `.planning/` si existen.
- [ ] Leer tests existentes: ¿hay framework de testing? ¿Hay tests? ¿Qué cobertura?
- [ ] Leer migraciones existentes (si aplica): esquema actual de la base de datos.
- [ ] Identificar integraciones externas (APIs, servicios, pasarelas de pago, etc.).

### 1.2 Producir resumen de reconocimiento
Generar un resumen breve (~15-25 líneas) con:

```markdown
## Resumen de reconocimiento

**Proyecto:** {nombre}
**Stack:** {lenguaje + framework + versiones}
**Base de datos:** {motor + versión si se detecta}
**Estado del repo:** nuevo | en desarrollo | producción | legacy
**Dependencias principales:** {top 5-8}

### Estructura
{Carpetas principales con 1 línea de descripción cada una}

### Artefactos existentes
- README: ✅/❌ (estado: completo | incompleto | ausente)
- Docs/: ✅/❌
- Analisis/: ✅/❌
- Tests: ✅/❌ (framework: {nombre}, cobertura estimada: {%})
- CI/CD: ✅/❌ (herramienta: {nombre})
- .env.example: ✅/❌
- .planning/: ✅/❌

### Integraciones externas
{Lista de APIs/servicios detectados o "ninguna detectada"}

### Convenciones observadas
{Patrones de naming, estructura, testing, etc. que el proyecto ya usa}

### Gaps detectados
{Lista de artefactos o documentación que falta}
```

---

## FASE 2 — ARTEFACTOS BASE (crear lo que falte)

### Para proyecto NUEVO

Crear todos los artefactos base:

| Artefacto | Responsable | Instrucciones de referencia |
|-----------|-------------|----------------------------|
| `README.md` | Obligatorio | `readme.instructions.md` |
| `.env.example` | Obligatorio | Detectar del stack |
| `.gitignore` | Obligatorio | Detectar del stack |
| `Docs/` (carpeta) | Crear vacía | — |
| `Analisis/` (carpeta) | Crear vacía | — |
| Análisis de aterrizaje | Obligatorio | Ver Fase 3 |
| Framework de tests | Obligatorio | Detectar del stack, configurar mínimo |
| Linter/Formatter config | Obligatorio | Detectar del stack |
| `backlog.md` | Recomendado | `backlog.instructions.md` |

### Para proyecto EXISTENTE

Evaluar qué falta y crear solo lo ausente:

| Artefacto | Si falta... |
|-----------|-------------|
| `README.md` | Crear o completar secciones faltantes (ver `readme.instructions.md`) |
| `.env.example` | Crear a partir de `.env` (sin valores sensibles) |
| `Docs/` | Crear carpeta. No llenarla de docs vacíos. |
| `Analisis/` | Crear carpeta. Generar análisis de aterrizaje (Fase 3). |
| Tests | Si no hay framework: proponer al equipo. No imponer. |
| Linter config | Si no hay: proponer. Respetar convenciones existentes. |

**Regla**: no crear artefactos que el equipo no va a mantener. Si no hay cultura de `Docs/`, no forzar 10 documentos que nadie leerá. Proponer lo mínimo útil.

---

## FASE 3 — ANÁLISIS DE ATERRIZAJE

El análisis de aterrizaje es el primer documento de `Analisis/` y sirve como **mapa del proyecto** para cualquier persona nueva.

### Ubicación
`Analisis/aterrizaje_{proyecto}.md`

### Cabecera
```yaml
---
id: ANAL-INIT-001
title: Análisis de aterrizaje — {proyecto}
author: {nombre}
date: {YYYY-MM-DD}
status: current
version: "1.0"
---
```

### Contenido obligatorio

#### 1. Visión general
- ¿Qué hace el proyecto? (3-5 líneas).
- ¿Para quién? (usuarios finales, equipo interno, API consumers).
- ¿En qué fase está? (MVP, producción, legacy, rewrite).

#### 2. Stack técnico
| Capa | Tecnología | Versión | Notas |
|------|-----------|---------|-------|
| Backend | Laravel | 11.x | PHP 8.3 |
| Frontend | Vue 3 | 3.4.x | Composition API |
| Base de datos | MySQL | 8.0 | — |
| Cache | Redis | 7.x | Sessions + cache |
| Infra | Docker | — | docker-compose local |

#### 3. Arquitectura de alto nivel
- Diagrama de componentes (Mermaid o ASCII).
- Capas: cómo se organiza el código (MVC, DDD, modular, monolito).
- Puntos de entrada: HTTP, CLI, Queue, Scheduler.

#### 4. Módulos / Dominios principales
| Módulo | Descripción | Archivos clave | Estado |
|--------|-------------|----------------|--------|
| Auth | Registro, login, permisos | `app/Models/User.php`, `app/Policies/` | Estable |
| Orders | Gestión de pedidos | `app/Services/OrderService.php` | En desarrollo |

#### 5. Base de datos
- Tablas principales y sus relaciones (diagrama ER simple o lista).
- Migraciones pendientes o estado actual.
- Datos sensibles: dónde están, cómo se protegen.

#### 6. Integraciones externas
| Servicio | Propósito | Auth | Docs | Estado |
|----------|----------|------|------|--------|
| Stripe | Pagos | API Key en .env | {link} | Activa |

#### 7. Testing
- Framework: PHPUnit/Pest/Jest/Vitest.
- Cobertura actual estimada.
- Cómo ejecutar: `php artisan test`, `npm test`.
- Áreas sin cobertura y riesgo.

#### 8. Desarrollo local
- Pasos para levantar el proyecto desde cero (resumen, detalle en README).
- Comandos clave del día a día.
- Gotchas o problemas conocidos en setup local.

#### 9. Deuda técnica conocida
| Área | Descripción | Impacto | Prioridad |
|------|-------------|---------|-----------|
| N+1 queries | Listados de orders sin eager loading | Performance | Media |
| Auth legacy | Sistema de roles hardcodeado | Seguridad | Alta |

#### 10. Convenciones del proyecto
- Naming: cómo se nombran clases, métodos, rutas, vistas.
- Patrones: qué usa el proyecto (Services, Actions, Repositories, etc.).
- Estilo de código: PSR-12, eslint config, formatter.
- Branching: estrategia de ramas (gitflow, trunk-based, etc.).
- PR/Review: proceso y checklist.

#### 11. Contactos y recursos
- Responsables por área/módulo.
- Links a documentación externa, wikis, APIs.
- Canales de comunicación (Slack, Teams, etc.).

---

## FASE 4 — VERIFICACIÓN

Al finalizar, producir un checklist de estado:

```markdown
## Checklist de inicialización

### Reconocimiento
- [ ] Stack identificado.
- [ ] Estructura analizada.
- [ ] Convenciones documentadas.

### Artefactos base
- [ ] README.md completo o actualizado.
- [ ] .env.example presente.
- [ ] .gitignore adecuado.
- [ ] Framework de tests configurado.
- [ ] Linter/formatter configurado.

### State Management
- [ ] `.planning/STATE.md` creado (`state.instructions.md`).
- [ ] Codebase mapping generado si es proyecto existente (`codebase-mapping.instructions.md`).

### Documentación
- [ ] Análisis de aterrizaje creado.
- [ ] Gaps documentados.
- [ ] Backlog inicial creado (si proyecto nuevo).

### Para el equipo
- [ ] Cualquier desarrollador nuevo puede levantar el proyecto siguiendo el README.
- [ ] El análisis de aterrizaje permite entender el proyecto en <30 minutos.
```

---

## FASE 5 — CODEBASE MAPPING (solo proyectos existentes)

Para proyectos existentes con código significativo, ejecutar un mapeo del codebase siguiendo `codebase-mapping.instructions.md`:

1. Crear `.planning/codebase/` si no existe.
2. Generar los 4 documentos:
   - `STACK.md` — Tecnologías, dependencias, integraciones.
   - `ARCHITECTURE.md` — Estructura, capas, puntos de entrada.
   - `CONVENTIONS.md` — Naming, patterns, estilo, tests.
   - `CONCERNS.md` — Deuda técnica, TODOs, recomendaciones.
3. Estos documentos alimentan al planner, executor y reviewer en futuras sesiones.

**Regla:** el mapeo es **recomendado** para proyectos con >20 archivos de código. Para proyectos pequeños, el análisis de aterrizaje (Fase 3) es suficiente.

---

## FASE 6 — STATE MANAGEMENT

Al finalizar la inicialización, crear `.planning/STATE.md` siguiendo `state.instructions.md`:

1. Crear `.planning/` si no existe.
2. Generar STATE.md con:
   - Referencia del proyecto (nombre, stack, valor core).
   - Posición inicial (fase de inicio).
   - Secciones vacías para decisiones, blockers, deuda técnica.
   - Continuidad de sesión.
3. Este fichero será leído al inicio de cada sesión futura.

---

## Reglas importantes

- **Idempotencia**: ejecutar este protocolo dos veces no debe duplicar artefactos. Verificar antes de crear.
- **Proporcionalidad**: un proyecto de 3 archivos no necesita 15 documentos. Adaptar al tamaño real.
- **No inventar**: si no puedes determinar algo del proyecto (versión, estado, cobertura), marcarlo como `TBD` y preguntar.
- **Respetar lo existente**: en proyectos existentes, las convenciones del proyecto tienen precedencia (global §0). No imponer convenciones nuevas sin consenso.
- **Anti-documentación vacía**: no crear Docs/ con 10 ficheros stub que nadie va a llenar. Crear solo lo que se va a mantener.

