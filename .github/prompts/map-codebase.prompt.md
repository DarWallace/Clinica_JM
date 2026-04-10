---
mode: "agent"
description: "Mapear un codebase existente — analizar stack, arquitectura, convenciones y deuda técnica"
---

# Mapear Codebase

Ejecuta un mapeo completo del codebase actual siguiendo `codebase-mapping.instructions.md`.

## Instrucciones

1. **Exploración inicial:**
   - Leer archivos de configuración del proyecto.
   - Listar estructura de carpetas (2 niveles).
   - Leer README.md.
   - Leer `.env.example` (NUNCA `.env`).

2. **Análisis por foco — Crear 4 documentos:**

   ### STACK.md
   - Runtime: lenguaje, framework, versiones.
   - Dependencias principales con propósito.
   - Integraciones externas.
   - Infraestructura (Docker, CI/CD).

   ### ARCHITECTURE.md
   - Estructura de carpetas con descripción.
   - Capas y responsabilidades.
   - Puntos de entrada (HTTP, CLI, Queue, Scheduler).
   - Diagrama de componentes (Mermaid).
   - Módulos/dominios principales.

   ### CONVENTIONS.md
   - Naming (clases, métodos, variables, rutas, tests).
   - Patterns usados (con ejemplo real del repo).
   - Estilo de código (linter, formatter).
   - Git (branching, commits).
   - Tests (framework, estructura, naming).

   ### CONCERNS.md
   - Deuda técnica con impacto y prioridad.
   - TODOs/FIXMEs encontrados.
   - Áreas sin tests.
   - Dependencias obsoletas.
   - Recomendaciones priorizadas.

3. **Guardar en:** `.planning/codebase/`

4. **Crear STATE.md** si no existe (usando la información del mapeo).

## Reglas
- Rutas de archivos siempre, nunca descripciones vagas.
- Patrones con ejemplo real del repo.
- Prescriptivo ("Usar X") no descriptivo ("se usa X").
- Máximo ~150 líneas por fichero.
- NUNCA leer `.env`, solo `.env.example`.

## Output esperado
- 4 ficheros en `.planning/codebase/`.
- Resumen ejecutivo con top 3 preocupaciones.
- STATE.md creado o actualizado.

