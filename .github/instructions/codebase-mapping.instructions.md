---
applyTo: "**/.planning/codebase/**"
description: "Mapeo estructurado de codebase para proyectos existentes (brownfield)"
---

# CODEBASE MAPPING — REGLAS

Objetivo: analizar sistemáticamente un codebase existente para producir documentos que permitan al agente (y al equipo) trabajar con pleno conocimiento del proyecto.

---

## Cuándo ejecutar el mapeo

- Al aterrizar en un **proyecto existente** por primera vez.
- Cuando el usuario lo solicita: *"Mapea el codebase"* o *"Analiza el proyecto"*.
- Antes de inicializar un proyecto nuevo en un directorio que ya tiene código.
- Cuando el agente no tiene suficiente contexto para implementar con confianza.

---

## Áreas de análisis

El mapeo se divide en **4 focos** que pueden ejecutarse en paralelo o secuencialmente:

### 1. Stack tecnológico (`STACK.md`)

Analizar:
- Archivos de configuración: `composer.json`, `package.json`, `requirements.txt`, `go.mod`, `Cargo.toml`, etc.
- Archivos de config: `tsconfig.json`, `vite.config.*`, `webpack.config.*`, `.babelrc`, etc.
- Variables de entorno: `.env.example` (nunca `.env` real).
- Docker/CI: `Dockerfile`, `docker-compose.yml`, `.github/workflows/`.

Producir:
```markdown
# Stack tecnológico

## Runtime
| Capa | Tecnología | Versión | Notas |
|------|-----------|---------|-------|
| Lenguaje | PHP | 8.3 | — |
| Framework | Laravel | 11.x | — |
| Base de datos | MySQL | 8.0 | — |
| Cache | Redis | 7.x | Sessions + cache |
| Frontend | Vue 3 | 3.4.x | Composition API |
| Build | Vite | 5.x | — |

## Dependencias principales
| Paquete | Versión | Propósito |
|---------|---------|-----------|
| laravel/sanctum | ^4.0 | API auth |
| spatie/permission | ^6.0 | Roles y permisos |
| ... | ... | ... |

## Integraciones externas
| Servicio | SDK/API | Propósito | Auth |
|----------|---------|-----------|------|
| Stripe | stripe-php | Pagos | API Key |
| SendGrid | — | Email transaccional | API Key |

## Infraestructura
- **Local:** Docker Compose
- **CI/CD:** GitHub Actions
- **Hosting:** {detectar o "no detectado"}
```

### 2. Arquitectura (`ARCHITECTURE.md`)

Analizar:
- Estructura de carpetas de primer y segundo nivel.
- Puntos de entrada: HTTP (routes), CLI (commands), Queue (jobs), Scheduler.
- Capas: cómo está organizado el código (MVC, DDD, modular, monolito).
- Flujo de datos principal.

Producir:
```markdown
# Arquitectura

## Estructura de carpetas
{Árbol de primer y segundo nivel con descripción de cada carpeta relevante}

## Capas y responsabilidades
| Capa | Carpeta | Responsabilidad | Ejemplo |
|------|---------|-----------------|---------|
| Presentación | `app/Http/Controllers/` | Recibir requests, delegar, responder | `UserController` |
| Aplicación | `app/Services/` | Lógica de negocio, orquestación | `OrderService` |
| Dominio | `app/Models/` | Entidades, reglas de dominio | `Order` |
| Infraestructura | `app/Repositories/` | Acceso a datos | `OrderRepository` |

## Puntos de entrada
- **HTTP:** {N} rutas definidas en `routes/`
- **CLI:** {N} comandos en `app/Console/Commands/`
- **Queue:** {N} jobs en `app/Jobs/`
- **Scheduler:** ver `routes/console.php`

## Diagrama de componentes
{Mermaid diagram}

## Módulos / Dominios
| Módulo | Descripción | Archivos clave | Estado |
|--------|-------------|----------------|--------|
```

### 3. Convenciones (`CONVENTIONS.md`)

Analizar:
- Naming: clases, métodos, variables, rutas, vistas, tests.
- Patterns: Services, Actions, Repositories, DTOs, Events, etc.
- Estilo de código: linter config, formatter, PSR/ESLint.
- Git: branching, commits, PRs.
- Tests: framework, estructura, naming.

Producir:
```markdown
# Convenciones del proyecto

## Naming
| Elemento | Convención | Ejemplo |
|----------|-----------|---------|
| Clases | PascalCase | `OrderService` |
| Métodos | camelCase | `calculateTotal()` |
| Rutas API | kebab-case plural | `/api/order-items` |
| Migraciones | snake_case | `create_orders_table` |
| Tests | snake_case descriptivo | `test_user_can_create_order` |

## Patrones usados
| Patrón | Dónde | Ejemplo |
|--------|-------|---------|
| Services | Lógica de negocio | `app/Services/OrderService.php` |
| FormRequests | Validación | `app/Http/Requests/StoreOrderRequest.php` |
| Resources | Transformación API | `app/Http/Resources/OrderResource.php` |

## Estilo de código
- **Linter:** {nombre y config}
- **Formatter:** {nombre}
- **Standard:** {PSR-12, ESLint standard, etc.}

## Tests
- **Framework:** {PHPUnit/Pest/Jest/Vitest}
- **Estructura:** `tests/Unit/`, `tests/Feature/`
- **Naming:** `test_{acción}_{condición}_{resultado}`
- **Comando:** `{cómo ejecutar tests}`

## Git
- **Branching:** {trunk-based / gitflow / otro}
- **Commits:** {convención observada}
- **PRs:** {proceso observado}
```

### 4. Deuda técnica y preocupaciones (`CONCERNS.md`)

Analizar:
- N+1 queries, queries sin optimizar.
- Código duplicado.
- Falta de tests en áreas críticas.
- Vulnerabilidades de seguridad observables.
- Dependencias obsoletas.
- TODOs y FIXMEs en el código.
- Arquitectura frágil o sobre-compleja.

Producir:
```markdown
# Preocupaciones técnicas

## Deuda técnica

| # | Área | Descripción | Impacto | Esfuerzo fix | Prioridad |
|---|------|-------------|---------|--------------|-----------|
| 1 | {área} | {descripción concreta} | {alto/medio/bajo} | {S/M/L} | {alta/media/baja} |

## TODOs/FIXMEs encontrados
| Archivo | Línea | Contenido | Severidad |
|---------|-------|-----------|-----------|
| `{ruta}` | {N} | {texto del TODO} | {alta/media/baja} |

## Áreas sin cobertura de tests
- {Módulo/área}: {descripción del riesgo}

## Dependencias obsoletas
| Paquete | Versión actual | Última versión | Breaking changes |
|---------|---------------|----------------|------------------|

## Recomendaciones priorizadas
1. **[Inmediato]** {qué hacer primero}
2. **[Sprint actual]** {qué hacer pronto}
3. **[Próximo sprint]** {qué puede esperar}
```

---

## Ubicación de los documentos

```
.planning/
└── codebase/
    ├── STACK.md
    ├── ARCHITECTURE.md
    ├── CONVENTIONS.md
    └── CONCERNS.md
```

---

## Reglas

- **Paths concretos**: siempre incluir rutas de archivos, no descripciones vagas.
- **Patrones > listas**: mostrar cómo se hacen las cosas (con ejemplo de código real del repo) no solo qué existe.
- **Prescriptivo**: "Usar camelCase para métodos" es útil; "algunos métodos usan camelCase" no lo es.
- **Estado actual solamente**: describir lo que ES, no lo que fue o lo que debería ser (excepto en CONCERNS.md).
- **No leer .env**: solo `.env.example`. Nunca exponer secretos.
- **Máximo ~150 líneas por fichero**. Si un área necesita más detalle, crear subficheros.

---

## Integración con otros flujos

| Flujo | Cómo usa el codebase mapping |
|-------|------------------------------|
| `init-project` | Fase 1 (reconocimiento) consume el mapping si existe |
| `pipeline` (PLAN) | El planner lee CONVENTIONS.md y ARCHITECTURE.md para diseñar |
| `pipeline` (EXECUTE) | El ejecutor sigue CONVENTIONS.md para escribir código idiomático |
| Agente `reviewer` | Verifica contra CONVENTIONS.md |
| Agente `architect` | Usa ARCHITECTURE.md como baseline |
| STATE.md | Referencia el mapping para contexto rápido |

