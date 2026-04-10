---
description: "Generar análisis técnico detallado para implementación de una feature o cambio estructural"
---
# Análisis Técnico

## Antes de generar
1. Leer el análisis funcional asociado (si existe en `Analisis/funcional/`).
2. Leer la arquitectura actual: modelos, servicios, controladores, migraciones del área afectada.
3. Leer las integraciones externas existentes si el cambio las afecta.
4. Identificar el nivel de la tarea (estándar/crítico según global §4).

## Estructura del análisis

### Cabecera
```yaml
---
id: ANAL-TXXX
title: {componente/feature}
author: {nombre}
date: {YYYY-MM-DD}
status: draft
version: "1.0"
related: [ANAL-FXXX, TASK-XXXX]
---
```

### 1. Resumen ejecutivo
1-3 líneas técnicas: qué se va a construir/cambiar y el enfoque elegido.

### 2. Arquitectura implicada
- Capas afectadas: presentación, aplicación, dominio, infraestructura.
- Diagrama de componentes (Mermaid o ASCII) mostrando cómo encaja el cambio en el sistema.
- Patrón elegido y por qué (Service, Action, Event-driven, etc.).

### 3. Estructura de datos
- Tablas nuevas o modificadas: columnas, tipos, índices, foreign keys.
- Migraciones necesarias (referenciar `migrations.instructions.md`).
- Si hay cambios destructivos: plan Expand → Migrate → Contract.

### 4. Servicios y lógica
- Servicios nuevos o modificados: responsabilidad, métodos públicos, parámetros, retornos.
- DTOs o Value Objects si aplican.
- Flujo de la lógica principal (diagrama de secuencia si hay >3 actores).

### 5. API / Endpoints
Si hay cambios en API:
| Método | Ruta | Request | Response | Auth |
|--------|------|---------|----------|------|
| POST | `/api/v1/orders` | `{body}` | `{response}` | Bearer/Policy |

- Códigos de error y formato.
- Versionado si aplica.

### 6. Integraciones externas
- Servicio, URL, autenticación, payloads, rate limits.
- Timeout/retry strategy.
- Fallback si el servicio no responde.
- Contacto/documentación del proveedor.

### 7. Seguridad
- Puntos del checklist OWASP que aplican (del global §6).
- Modelo de autorización: qué policies/guards se necesitan.
- Datos sensibles: cómo se protegen.

### 8. Performance
- Estimación de volumen de datos / requests.
- Queries costosas: plan de optimización (índices, cache, eager loading).
- Bottlenecks identificados y mitigaciones.

### 9. Plan de tests
| Tipo | Qué cubre | Prioridad |
|------|-----------|-----------|
| Unit | Lógica del servicio X | Alta |
| Feature | Endpoint POST /orders | Alta |
| Integración | Webhook proveedor Y | Media |

### 10. Riesgos y mitigaciones
| Riesgo | Impacto | Probabilidad | Mitigación |
|--------|---------|-------------|------------|
| {riesgo} | Alto/Medio/Bajo | Alta/Media/Baja | {acción} |

### 11. Plan de implementación
Pasos ordenados con dependencias y estimación.

## Formato de salida
Documento Markdown completo, listo para guardar en `Analisis/tecnico/{component}.md`.

## Instrucciones de referencia
Aplicar `analisis.instructions.md`, `architecture.instructions.md`, `docs.instructions.md`.
