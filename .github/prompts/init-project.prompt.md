---
description: "Ejecutar el protocolo de inicialización/aterrizaje en un proyecto nuevo o existente"
---
# Inicializar / Aterrizar en Proyecto

## Qué hace este prompt
Ejecuta el protocolo completo de `init-project.instructions.md` en 4 fases:
1. **Reconocimiento** — analizar el proyecto y producir resumen.
2. **Artefactos base** — crear lo que falte (README, .env.example, estructura).
3. **Análisis de aterrizaje** — generar el documento de mapa del proyecto.
4. **Verificación** — producir checklist de estado final.

## Cómo usarlo

### Proyecto NUEVO (repo recién creado)
Indicar al agente:
> Inicializa este proyecto. Es un proyecto nuevo con {stack}.

El agente ejecutará las 4 fases creando todos los artefactos desde cero.

### Proyecto EXISTENTE (incorporación o auditoría)
Indicar al agente:
> Aterriza en este proyecto. Es un proyecto existente.

El agente ejecutará las 4 fases pero solo creará lo que falte, respetando lo existente.

## Flujo de ejecución

### Fase 1 — Reconocimiento
1. Leer archivos de configuración del proyecto:
   - `composer.json` / `package.json` / `pyproject.toml` / `go.mod`
   - `Dockerfile` / `docker-compose.yml`
   - `.env.example` / `.env`
   - Archivos de CI: `.github/workflows/`, `Jenkinsfile`, `.gitlab-ci.yml`
2. Leer estructura de carpetas top-level.
3. Leer `README.md`, `Docs/`, `Analisis/` si existen.
4. Leer tests existentes: estructura, framework, cantidad.
5. Leer migraciones (si aplica) para entender el esquema de datos.
6. **Producir resumen de reconocimiento** con el formato de `init-project.instructions.md` §1.2.

### Fase 2 — Artefactos base
Evaluar qué artefactos faltan y crearlos:

**Para proyecto NUEVO:**
- [ ] Crear `README.md` siguiendo `readme.instructions.md`.
- [ ] Crear `.env.example` con las variables necesarias del stack.
- [ ] Crear `.gitignore` adecuado al stack.
- [ ] Crear carpeta `Docs/` (vacía o con index.md).
- [ ] Crear carpeta `Analisis/` (vacía).
- [ ] Verificar que el framework de tests está configurado. Si no, configurar el mínimo.
- [ ] Verificar que hay linter/formatter. Si no, configurar el estándar del stack.

**Para proyecto EXISTENTE:**
- [ ] Evaluar `README.md`: ¿existe? ¿Está completo? Completar secciones faltantes.
- [ ] Evaluar `.env.example`: ¿existe? Crear si falta (sin valores sensibles).
- [ ] Evaluar `Docs/` y `Analisis/`: crear carpetas si faltan.
- [ ] Evaluar tests: documentar estado actual, no imponer framework sin consenso.
- [ ] Documentar gaps detectados.

### Fase 3 — Análisis de aterrizaje
Generar `Analisis/aterrizaje_{proyecto}.md` con las 11 secciones definidas en `init-project.instructions.md` §Fase 3:

1. Visión general
2. Stack técnico (tabla)
3. Arquitectura de alto nivel (diagrama)
4. Módulos/dominios principales (tabla)
5. Base de datos (tablas y relaciones)
6. Integraciones externas (tabla)
7. Testing (framework, cobertura, comandos)
8. Desarrollo local (setup, comandos, gotchas)
9. Deuda técnica conocida (tabla)
10. Convenciones del proyecto (naming, patrones, estilo, branching)
11. Contactos y recursos

**Regla**: no inventar información. Si no se puede determinar algo, marcarlo como `TBD` y notificar qué falta.

### Fase 4 — Verificación
Producir el checklist de estado final con formato de `init-project.instructions.md` §Fase 4.
Marcar cada item como completado (✅) o pendiente (⬜) con motivo.

## Formato de salida
1. **Resumen de reconocimiento** (Fase 1).
2. **Artefactos creados/actualizados** (Fase 2) — lista de archivos con acción (creado/actualizado/ya existía).
3. **Análisis de aterrizaje** (Fase 3) — documento completo en `Analisis/`.
4. **Checklist de verificación** (Fase 4).

## Adaptación por tamaño de proyecto

| Tamaño | Indicador | Nivel de detalle |
|--------|----------|-----------------|
| **Micro** | <10 archivos, 1 módulo | Reconocimiento + README + análisis breve (5 secciones mín) |
| **Pequeño** | 10-50 archivos, 2-5 módulos | Protocolo completo, análisis con las 11 secciones |
| **Mediano** | 50-200 archivos, 5-15 módulos | Protocolo completo + backlog.md inicial sugerido |
| **Grande** | >200 archivos, >15 módulos | Protocolo completo + proponer análisis por módulo además del aterrizaje global |

## Instrucciones de referencia
Aplicar `init-project.instructions.md`, `readme.instructions.md`, `analisis.instructions.md`, `backlog.instructions.md`.

