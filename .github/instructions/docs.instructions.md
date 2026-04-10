---
applyTo: "Docs/**"
description: "Documentación técnica interna"
---

# DOCS — REGLAS

Objetivo: documentación mínima, útil y mantenible que desbloquee trabajo y reduzca ambigüedad.

## Principio: anti-documentación vacía
Solo crear documentación que cumpla al menos un criterio del global §8. Si no aporta valor, no se crea.

## Estructura
- Organizar por módulo o dominio: `Docs/{modulo}/`.
- Naming: snake_case, descriptivo: `api_autenticacion.md`, `flujo_pagos.md`.
- Mantener un `Docs/index.md` o README con índice de documentos si hay más de 5 docs.

## Contenido esperado por documento
- **Qué**: descripción breve del módulo/feature.
- **Por qué**: contexto y decisiones (link a análisis si existe).
- **Cómo**: instrucciones de uso, configuración, comandos.
- **Dónde**: archivos y rutas relevantes.
- **Quién**: responsable o equipo (opcional).

## Tipos de documentación comunes
| Tipo | Cuándo | Contenido clave |
|------|--------|-----------------|
| **Guía de módulo** | Módulo nuevo o complejo | Arquitectura, flujos, endpoints, modelos, configuración |
| **Guía de integración** | API externa o servicio | Autenticación, endpoints, payloads, errores, retry, contacto |
| **Runbook/Operaciones** | Deploy, incidentes | Pasos, comandos, checklist, troubleshooting |
| **Changelog** | Módulo con evolución frecuente | Versión, fecha, cambios, breaking changes |

## Mantenimiento
- Actualizar documentación cuando cambie el comportamiento documentado.
- Añadir fecha de última actualización en la cabecera del documento.
- Marcar como `deprecated` los docs obsoletos con link al sustituto.
- Revisar y aprobar cambios de documentación en PRs antes de mergear.
- Borrar docs muertos: si nadie los lee ni mantiene, eliminarlos.

## Formato
- Markdown con secciones cortas y ejemplos reproducibles.
- Usar enlaces relativos para docs internas.
- Diagramas en Mermaid o link a diagrams.net (formato editable).
- Código de ejemplo con lenguaje marcado en code blocks.

## Checklist de validación post-generación

Verificar después de crear o actualizar cualquier documento:

### Formato y estructura
- [ ] Título descriptivo en `# H1`.
- [ ] Descripción breve bajo el título (1-2 líneas).
- [ ] Tabla de contenidos si el documento tiene >3 secciones.
- [ ] Fecha de última actualización en cabecera.
- [ ] Secciones claras con títulos descriptivos.

### Contenido
- [ ] Ejemplos de código ejecutables y con language tag en code blocks.
- [ ] Información accionable: el lector sabe qué hacer después de leer.
- [ ] Sin duplicar información que ya existe en otro doc (linkear en su lugar).
- [ ] Sin jerga sin definir ni acrónimos sin expandir en primera aparición.

### Integración
- [ ] Índice/README del directorio actualizado con link al nuevo doc.
- [ ] Referencias cruzadas hacia/desde documentos relacionados.
- [ ] Convenciones del repo respetadas (naming, estructura, idioma).

### Calidad
- [ ] Sin typos ni errores gramaticales.
- [ ] Links internos verificados (apuntan a archivos reales, rutas relativas).
- [ ] Cada sección aporta valor (eliminar secciones vacías o con "TODO").
- [ ] Diagramas en Mermaid o link editable si aplica.

## Callouts estándar

Usar estos formatos para información destacada:

```markdown
> **⚠️ IMPORTANTE**: Información crítica que no debe ignorarse.

> **💡 TIP**: Consejo útil o buena práctica.

> **📝 NOTA**: Información complementaria.
```

## Emojis de sección (opcionales, para docs con muchas secciones)

| Emoji | Uso |
|-------|-----|
| 📋 | Resumen / Tabla de contenidos |
| 🎯 | Objetivo / Propósito |
| 🏗️ | Arquitectura / Estructura |
| ⚡ | Performance |
| 🔐 | Seguridad |
| 🧪 | Testing |
| 💡 | Ejemplos / Buenas prácticas |
| ❌ | Antipatrones / Errores comunes |
| 🚀 | Quick start |
| ⚠️ | Advertencias |
| ✅ | Checklist |


## Anti-patrones
- ❌ Documentación que repite lo que el código ya dice (getters, setters).
- ❌ Docs de más de 500 líneas sin índice.
- ❌ Documentación sin fecha ni autor.
- ❌ Docs que nadie mantiene: mejor no tener doc que tener doc incorrecta.
- ❌ Secciones vacías con "TODO" o "pendiente" que nunca se completan.
- ❌ Documentación de relleno creada "por si acaso".
