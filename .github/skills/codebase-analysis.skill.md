---
name: "codebase-analysis"
description: "Análisis rápido de codebase existente: estructura, patterns, health"
triggers: ["analizar proyecto", "codebase", "brownfield", "aterrizar", "explorar código", "mapear"]
---

# Skill: Codebase Analysis

## Cuándo aplicar
- Al aterrizar en un proyecto existente sin documentación.
- Cuando el usuario pide "analiza este proyecto" o "explora el código".
- Antes de un refactor grande para entender el estado actual.
- Cuando el agente necesita contexto que no tiene para implementar con confianza.

---

## Análisis rápido (5 minutos)

Para situaciones donde se necesita contexto rápido sin el mapeo completo:

### Paso 1: Detectar stack
```
Leer: package.json | composer.json | requirements.txt | go.mod | Cargo.toml
→ Identificar: lenguaje, framework, versión, dependencias principales
```

### Paso 2: Entender estructura
```
Listar carpetas de primer nivel
→ Identificar: patrón (MVC, DDD, modular, monolito)
→ Identificar: dónde va la lógica, las rutas, los tests
```

### Paso 3: Detectar convenciones
```
Leer 2-3 archivos representativos:
- Un controller/handler
- Un modelo/entidad
- Un test
→ Extraer: naming, patterns, estilo de código
```

### Paso 4: Health check rápido
```
¿Hay tests? → ¿Qué framework? ¿Cuántos?
¿Hay linter/formatter config?
¿Hay .env.example?
¿Hay README?
¿Hay CI/CD?
```

### Output rápido
```markdown
**Stack:** {lenguaje + framework + versión}
**Patrón:** {MVC/DDD/modular/monolito}
**Tests:** {sí/no, framework, cantidad estimada}
**Health:** {bueno/aceptable/preocupante}
**Convenciones clave:** {2-3 patterns principales}
```

---

## Análisis completo

Para el mapeo completo, activar el agente `codebase-mapper` o seguir `codebase-mapping.instructions.md`.

---

## Detección de patrones comunes

### Backend patterns
| Señal | Patrón detectado |
|-------|-----------------|
| `app/Services/*.php` | Service layer |
| `app/Actions/*.php` | Action pattern |
| `app/Repositories/*.php` | Repository pattern |
| `app/DTOs/*.php` | Data Transfer Objects |
| `app/Events/*.php` + `app/Listeners/*.php` | Event-driven |
| `app/Http/Requests/*.php` | Form Requests (validación separada) |
| `app/Http/Resources/*.php` | API Resources (transformación) |
| `src/Domain/*/` | DDD |
| `app/Modules/*/` | Modular monolith |

### Frontend patterns
| Señal | Patrón detectado |
|-------|-----------------|
| `src/components/` + `src/pages/` | Page-based routing |
| `src/features/*/` | Feature-based structure |
| `src/stores/` o `src/store/` | Centralized state (Pinia/Vuex/Redux) |
| `src/composables/` | Vue Composition API |
| `src/hooks/` | React custom hooks |
| `src/services/` o `src/api/` | API layer separado |
| `src/utils/` o `src/helpers/` | Utilidades compartidas |

### Señales de calidad
| Señal | Indica |
|-------|--------|
| `.eslintrc` / `phpcs.xml` | Linting configurado ✅ |
| `tests/` con >10 archivos | Testing culture ✅ |
| `.github/workflows/` | CI/CD configurado ✅ |
| `.env.example` | Configuración documentada ✅ |
| `TODO:` / `FIXME:` frecuentes | Deuda técnica reconocida ⚠️ |
| Tests solo en `tests/Unit/` | Faltan tests de integración ⚠️ |
| Sin `tests/` | Sin cultura de testing 🔴 |

---

## Reglas

- **Nunca leer `.env`** — solo `.env.example`.
- **Rutas concretas**: siempre paths de archivos, nunca descripciones genéricas.
- **No asumir**: si no puedes verificar leyendo el código, no lo afirmes.
- **Proporcional**: análisis rápido para preguntas rápidas, mapeo completo para aterrizaje.
- **Prescriptivo**: "Los services van en `app/Services/`" es útil; "hay una carpeta Services" no.

