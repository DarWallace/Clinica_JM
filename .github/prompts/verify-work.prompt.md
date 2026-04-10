---
mode: "agent"
description: "Verificar trabajo completado — goal-backward verification"
---

# Verificar Trabajo

Ejecuta verificación goal-backward sobre el trabajo recientemente completado, siguiendo `verification.instructions.md`.

## Variables
- `{scope}`: Qué verificar (feature, archivo, endpoint, PR, etc.)

## Instrucciones

1. **Cargar contexto:**
   - Leer STATE.md para entender qué se estaba haciendo.
   - Leer CONTEXT.md de la feature (si existe en `.planning/features/`).
   - Leer PLAN.md y SUMMARY.md (si existen).
   - Leer el código implementado.

2. **Definir verdades:**
   - ¿Qué debe ser VERDAD para que `{scope}` esté completo?
   - Listar cada condición como verificable sí/no.

3. **Verificar en 3 niveles:**
   - **Existencia**: ¿los archivos/funciones/endpoints existen?
   - **Sustancia**: ¿el código tiene lógica real, no placeholders?
   - **Integración**: ¿las piezas están conectadas entre sí?

4. **Verificar criterios de aceptación:**
   - Cada criterio del CONTEXT.md o backlog item → ✅/❌ con evidencia.

5. **Ejecutar tests:**
   - Tests nuevos → ¿pasan?
   - Suite completa → ¿regresión?

6. **Producir veredicto:**
   - ✅ APROBADO — si todo funciona.
   - ❌ RECHAZADO — con lista concreta de correcciones necesarias.

## Output esperado
```markdown
## Verificación — {scope}

### Verdades verificadas
| # | Verdad | Existencia | Sustancia | Integración | Estado |
|---|--------|-----------|-----------|-------------|--------|

### Criterios de aceptación
| Criterio | Estado | Evidencia |

### Tests
- Nuevos: {N} — {resultado}
- Regresión: {resultado}

### Veredicto
✅/❌ — {justificación}
```

