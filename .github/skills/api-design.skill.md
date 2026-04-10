---
name: "api-design"
description: "Diseñar APIs REST consistentes, versionadas y bien documentadas"
triggers: ["api", "endpoint", "REST", "recurso", "ruta", "contrato"]
---

# Skill: API Design

## Cuándo aplicar
- Al crear endpoints nuevos.
- Al modificar contratos de API existentes.
- Cuando el usuario pida diseñar una API completa.

## Principios de diseño

### URLs
- Sustantivos en plural para recursos: `/api/v1/orders`, `/api/v1/users`.
- Anidamiento máximo 2 niveles: `/api/v1/orders/{id}/items` ✅, `/api/v1/users/{id}/orders/{id}/items/{id}/details` ❌.
- Acciones que no son CRUD: usar verbos como sub-recurso: `POST /api/v1/orders/{id}/cancel`.
- Consistencia: si un recurso es `kebab-case`, todos lo son.

### Métodos HTTP
| Método | Uso | Idempotente | Body |
|--------|-----|-------------|------|
| GET | Leer recurso(s) | Sí | No |
| POST | Crear recurso | No | Sí |
| PUT | Reemplazar recurso completo | Sí | Sí |
| PATCH | Actualización parcial | No | Sí |
| DELETE | Eliminar recurso | Sí | No |

### Responses

**Códigos de estado correctos:**
| Código | Cuándo |
|--------|--------|
| 200 | OK — recurso devuelto o acción completada |
| 201 | Created — recurso creado (incluir Location header) |
| 204 | No Content — acción completada, sin body (DELETE) |
| 400 | Bad Request — input inválido |
| 401 | Unauthorized — no autenticado |
| 403 | Forbidden — autenticado pero sin permiso |
| 404 | Not Found — recurso no existe |
| 409 | Conflict — conflicto de estado (ej: duplicado) |
| 422 | Unprocessable Entity — validación fallida |
| 429 | Too Many Requests — rate limit |
| 500 | Internal Server Error — error del servidor |

**Formato de respuesta estándar:**
```json
{
  "data": { },
  "meta": { "page": 1, "per_page": 25, "total": 100 }
}
```

**Formato de error estándar:**
```json
{
  "error": {
    "code": "VALIDATION_FAILED",
    "message": "Descripción legible por humanos",
    "details": [
      { "field": "email", "message": "El email es requerido" }
    ]
  }
}
```

### Paginación
- Cursor-based para listas grandes o datos que cambian frecuentemente.
- Offset-based (`?page=1&per_page=25`) para listas pequeñas o estáticas.
- Incluir `meta` con info de paginación en la respuesta.

### Filtrado y ordenación
- Filtros: `?status=active&created_after=2026-01-01`.
- Ordenación: `?sort=created_at&order=desc`.
- Búsqueda: `?search=término`.
- No inventar sintaxis: usar convenciones simples y documentarlas.

### Versionado
- Preferir versionado en URL: `/api/v1/`, `/api/v2/`.
- No versionar por header salvo que el proyecto ya lo haga.
- Al crear v2: mantener v1 funcional durante periodo de deprecación.

### Seguridad
- Autenticación en todos los endpoints (excepto públicos explícitos).
- Rate limiting en endpoints públicos y de autenticación.
- Validar input en el boundary (FormRequest o equivalente).
- No exponer IDs internos si no es necesario (considerar UUIDs).
- No devolver datos sensibles en respuestas (passwords, tokens, secrets).

## Ejemplo de diseño

```markdown
## API: Gestión de Orders

### Endpoints
| Método | Ruta | Descripción | Auth |
|--------|------|-------------|------|
| GET | /api/v1/orders | Listar orders (paginado) | Bearer |
| GET | /api/v1/orders/{id} | Detalle de order | Bearer + Policy |
| POST | /api/v1/orders | Crear order | Bearer |
| PATCH | /api/v1/orders/{id} | Actualizar order | Bearer + Policy |
| DELETE | /api/v1/orders/{id} | Eliminar order | Bearer + Policy |
| POST | /api/v1/orders/{id}/cancel | Cancelar order | Bearer + Policy |

### Filtros disponibles (GET /orders)
?status=pending|confirmed|cancelled
?created_after=YYYY-MM-DD
?sort=created_at&order=desc
?per_page=25
```

