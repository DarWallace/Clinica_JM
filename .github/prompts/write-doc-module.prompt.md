---
description: "Crear documentación técnica completa de un módulo del proyecto"
---
# Documentar Módulo

## Antes de generar
1. Leer la estructura del módulo: archivos, clases, servicios, modelos, controladores.
2. Leer tests existentes del módulo para entender flujos y edge cases.
3. Leer documentación existente en `Docs/` para mantener consistencia de formato.
4. Identificar la audiencia: ¿para desarrolladores del equipo o consumidores de API?

## Estructura del documento

### Cabecera
```yaml
---
module: {nombre del módulo}
author: {nombre}
date: {YYYY-MM-DD}
last_updated: {YYYY-MM-DD}
status: current | deprecated
---
```

### 1. Descripción
- Qué hace el módulo (2-3 líneas).
- Qué problema resuelve.
- Relación con otros módulos.

### 2. Arquitectura
- Diagrama de componentes del módulo (Mermaid preferido).
- Clases/servicios principales y su responsabilidad (tabla).
- Flujo principal de datos (de la entrada a la salida).

### 3. Modelos y datos
- Tablas/entidades que gestiona.
- Relaciones principales.
- Campos relevantes y sus tipos/casts.

### 4. API / Endpoints (si aplica)
| Método | Ruta | Descripción | Auth |
|--------|------|-------------|------|
| GET | `/api/v1/{resource}` | Lista con paginación | Bearer |

- Request/Response examples.
- Códigos de error.

### 5. Servicios y lógica
- Métodos públicos principales de cada servicio.
- Parámetros y retorno.
- Reglas de negocio clave.

### 6. Configuración
- Variables de entorno necesarias.
- Configuración en archivos del framework.
- Jobs, schedulers, event listeners si aplican.

### 7. Seguridad
- Policies/guards que protegen el módulo.
- Datos sensibles y cómo se manejan.

### 8. Cómo testear
- Comandos para ejecutar tests del módulo.
- Datos de ejemplo o seeders necesarios.

### 9. Consideraciones
- Performance: queries costosas, caching.
- Limitaciones conocidas.
- Deuda técnica documentada.

## Formato de salida
Documento Markdown completo, listo para guardar en `Docs/{modulo}.md`.

## Instrucciones de referencia
Aplicar `docs.instructions.md`.
