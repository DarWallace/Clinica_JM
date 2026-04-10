---
description: "Generar un README profesional y completo para el proyecto"
---
# Generar README del proyecto

## Antes de generar
1. Leer la estructura del proyecto (carpetas top-level, archivos de config).
2. Leer `composer.json`, `package.json` o equivalente para detectar stack, dependencias y scripts.
3. Leer `.env.example` si existe (requisitos de configuración).
4. Leer `Docs/` si existe (para enlazar documentación adicional).
5. Verificar si ya existe un README y si es actualización o creación desde cero.

## Secciones a generar

### 1. Título y descripción
- Nombre del proyecto.
- Una línea que describe qué hace y para quién.

### 2. Requisitos previos
- Detectar del proyecto: runtime (PHP, Node, Python), versión, servicios (DB, Redis, etc.).
- Herramientas: Docker, Composer, npm/pnpm.

### 3. Instalación y setup local
- Pasos numerados, copy-paste ready.
- Desde clonar hasta verificar que funciona.
- Incluir: dependencias, `.env`, claves, migraciones, seeders, primer arranque.

### 4. Comandos útiles
- Tabla con los comandos detectados en `scripts` del proyecto + los comunes del stack.
- Tests, lint, build, serve, migraciones.

### 5. Estructura del proyecto
- Solo carpetas top-level con descripción de 1 línea cada una.
- No listar todos los archivos.

### 6. Documentación adicional
- Links a `Docs/`, `Analisis/`, APIs, CONTRIBUTING.md.

### 7. Variables de entorno (si hay `.env.example`)
- Tabla con las principales: nombre, descripción, valor de ejemplo.

## Formato de salida
- Markdown limpio y bien formateado.
- Máximo una pantalla de scroll para info esencial; detalles en Docs/.
- Copiar-y-pegar los comandos sin modificación.

## Instrucciones de referencia
Aplicar `readme.instructions.md`, `docs.instructions.md`.
