---
applyTo: "README.md"
description: "Guía para README del proyecto"
---

# README — REGLAS

Objetivo: que cualquier desarrollador nuevo pueda entender, instalar y ejecutar el proyecto en menos de 15 minutos.

## Secciones obligatorias

### 1. Título y descripción
- Nombre del proyecto.
- Una línea que describe qué hace y para quién.
- Badges opcionales: CI status, versión, licencia.

### 2. Requisitos previos
- Runtime y versión: PHP 8.x, Node 20.x, Python 3.11+, etc.
- Servicios: MySQL/PostgreSQL, Redis, etc.
- Herramientas: Docker, Composer, npm/pnpm, etc.
- SO: si hay restricciones o instrucciones específicas por plataforma.

### 3. Instalación y setup local
- Pasos numerados, copiar-y-pegar.
- Incluir: clonar repo, instalar dependencias, copiar `.env`, generar claves, migraciones, seeders.
- Comando para verificar que funciona (`php artisan serve`, `npm run dev`, etc.).

### 4. Comandos útiles
- Tabla o lista con los comandos más frecuentes del día a día.
- Tests, lint, build, deploy local, migraciones, seeders.

### 5. Estructura del proyecto
- Descripción breve de las carpetas principales y su propósito.
- No listar todos los archivos: solo las carpetas top-level relevantes.

### 6. Documentación adicional
- Links a `Docs/`, `Analisis/`, wiki, APIs externas.
- Link a CONTRIBUTING.md si existe.

## Secciones opcionales (según proyecto)
- Arquitectura: link a `architecture.md` o diagrama breve.
- Variables de entorno: tabla con las principales y sus valores por defecto/ejemplo.
- Deploy: instrucciones o link al runbook.
- Troubleshooting: problemas comunes y soluciones.

## Reglas
- Mantener actualizado: si cambia un comando o requisito, actualizar el README.
- No incluir información que cambie con frecuencia (versiones de dependencias específicas) — referenciar el fichero correspondiente.
- Usar Markdown limpio con secciones bien delimitadas.
- Máximo una página de scroll para la info esencial. Detalles en Docs/.
