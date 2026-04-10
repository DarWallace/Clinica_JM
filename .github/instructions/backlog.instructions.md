---
applyTo: "**/backlog.md"
description: "Gestión del backlog de producto y técnico"
---

# BACKLOG — REGLAS

Objetivo: mantener un backlog claro, priorizado y accionable que permita al equipo y al agente entender qué hay que hacer, por qué y con qué criterios de éxito.

## Estructura del backlog

### Formato del archivo
- Archivo único por proyecto o módulo: `backlog.md` en la raíz o en `Docs/backlog.md`.
- Si el backlog crece mucho (>50 items), dividir por módulo: `Docs/backlog/{modulo}.md`.

### Cabecera del backlog

```markdown
# Backlog — {Nombre del proyecto/módulo}
Última actualización: YYYY-MM-DD
Responsable: Nombre
```

## Estructura de cada item del backlog

Cada item debe contener:

```markdown
### BL-XXXX: {Título descriptivo}
- **Tipo**: feature | bugfix | tech-debt | improvement | research
- **Prioridad**: critical | high | medium | low
- **Estimación**: XS | S | M | L | XL
- **Estado**: backlog | ready | in-progress | review | done | blocked
- **Módulo/Área**: {módulo afectado}
- **Dependencias**: [BL-YYYY, BL-ZZZZ] o "ninguna"

**Descripción:**
Como {usuario/rol}, quiero {acción} para {beneficio/objetivo}.
{Contexto adicional si es necesario: por qué existe este item, qué problema resuelve.}

**Criterios de aceptación:**
- [ ] CA-1: {Condición verificable que debe cumplirse}.
- [ ] CA-2: {Otra condición verificable}.
- [ ] CA-3: {Condición de seguridad si aplica}.

**Tests mínimos esperados:**
- [ ] Test unitario: {qué lógica cubre}.
- [ ] Test feature/integración: {qué flujo verifica}.
- [ ] Test de regresión (si es bugfix): {reproduce el bug original}.

**Notas:**
- {Decisiones técnicas relevantes, alternativas consideradas, links a análisis}.
```

## Priorización

### Criterios de prioridad
| Prioridad | Criterio | Ejemplo |
|-----------|----------|---------|
| **Critical** | Bloquea producción, pérdida de datos, seguridad | Bug que expone datos sensibles, sistema caído |
| **High** | Bloquea desarrollo de otros items, afecta a usuarios activos | Feature comprometida con cliente, bug visible |
| **Medium** | Mejora significativa, deuda técnica con impacto | Refactor de módulo crítico, nueva feature planificada |
| **Low** | Nice-to-have, mejoras cosméticas, deuda técnica menor | Mejora de UX, cleanup de código, optimización menor |

### Estimación (T-shirt sizing)
| Tamaño | Esfuerzo aproximado | Complejidad |
|--------|---------------------|-------------|
| **XS** | < 2 horas | Fix puntual, typo, ajuste de config |
| **S** | 2-4 horas | Feature simple, refactor menor, bugfix con lógica |
| **M** | 1-2 días | Feature con validación, nuevo endpoint, componente con lógica |
| **L** | 3-5 días | Módulo nuevo, integración, refactor estructural |
| **XL** | > 1 semana | Épica que necesita descomposición |

**Regla**: items XL deben descomponerse en items más pequeños antes de empezar.

## Tipos de items

### Feature
Funcionalidad nueva visible para el usuario o el sistema.
- Criterios de aceptación funcionales obligatorios.
- Tests feature/integración obligatorios.
- Documentación si cambia comportamiento público.

### Bugfix
Error que produce comportamiento incorrecto.
- Incluir: pasos para reproducir, comportamiento actual, comportamiento esperado.
- Test de regresión obligatorio.
- Referencia al reporte/issue original.

### Tech-debt
Deuda técnica que dificulta el mantenimiento o introduce riesgo.
- Justificar por qué merece priorizarse ahora vs. después.
- Definir alcance: qué se mejora, qué NO se toca.
- Tests que verifiquen que el refactor no introduce regresión.

### Improvement
Mejora de algo existente (performance, UX, DX, observabilidad).
- Definir métrica de éxito: "latencia de X baja de 200ms a <100ms".
- Antes/después medible cuando sea posible.

### Research
Investigación o spike técnico para reducir incertidumbre.
- Definir pregunta a responder y timebox.
- Output esperado: documento de decisión, PoC, o ADR.
- No producir código de producción directamente.

## Reglas de gestión

### Mantenimiento
- Revisar el backlog al menos una vez por semana/sprint.
- Actualizar estados: no dejar items en "in-progress" sin actividad.
- Archivar items completados (mover a sección "Done" o eliminar si no aporta historial).
- Items bloqueados: documentar el bloqueo y qué se necesita para desbloquear.

### Calidad de los items
- Cada item debe ser **independiente**: ejecutable sin depender de otros items en progreso (salvo dependencias explícitas).
- Cada item debe ser **verificable**: los criterios de aceptación permiten decir "sí/no" sin ambigüedad.
- Cada item debe ser **estimable**: si no se puede estimar, necesita un spike/research primero.
- Historias pequeñas: si un item se estima como XL, descomponerlo antes de empezar.

### Para el agente
- Cuando el usuario pida crear un item de backlog, seguir esta estructura.
- Cuando el usuario pida implementar un item del backlog, leer el item completo y verificar criterios de aceptación y tests esperados antes de empezar.
- Al completar un item, actualizar su estado a "done" y verificar que todos los criterios de aceptación se cumplen.
