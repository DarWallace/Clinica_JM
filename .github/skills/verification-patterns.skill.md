---
name: "verification-patterns"
description: "Patrones de verificación post-ejecución: goal-backward, checklists, evidencia"
triggers: ["verificar", "verify", "funciona", "probar", "validar", "UAT", "acceptance"]
---

# Skill: Verification Patterns

## Cuándo aplicar
- Después de implementar una feature (pipeline fase VERIFY).
- Cuando el usuario pregunta "¿funciona?" o "¿está bien?".
- Antes de dar por completada una tarea de nivel estándar o superior.

> **Nota:** La metodología goal-backward (verdades, 3 niveles, anti-patrones) está definida en `verification.instructions.md`. Esta skill complementa con patrones adicionales.

---

## Patrón 1: Smoke Test

Verificación rápida de que lo básico funciona.

```markdown
### Smoke test
- [ ] La app compila/arranca sin errores
- [ ] El endpoint/página principal responde
- [ ] Los tests existentes pasan
- [ ] El nuevo código no genera warnings/errores en logs
```

**Cuándo usar:** Después de cambios pequeños (nivel estándar simple).

---

## Patrón 2: Boundary Verification

Verificar los **límites** del comportamiento.

```markdown
### Boundaries verificados
| Input | Esperado | Real | ✅/❌ |
|-------|----------|------|-------|
| Datos válidos mínimos | 201 + orden creada | — | — |
| Sin auth | 401 | — | — |
| Campo requerido faltante | 422 + mensaje | — | — |
| Datos de otro usuario | 403 | — | — |
| Payload vacío | 422 | — | — |
| Valores límite (max chars) | 422 si excede | — | — |
```

**Cuándo usar:** Validaciones, formularios, endpoints con reglas.

---

## Patrón 3: Integration Trace

Seguir el **flujo de datos** de punta a punta.

```
Request HTTP
  → Route (¿registrada?) 
    → Middleware (¿auth aplicado?)
      → Controller (¿llama al service correcto?)
        → Service (¿lógica correcta?)
          → Model (¿persiste datos?)
            → Response (¿formato correcto?)
              → Side effects (¿email/evento/job disparado?)
```

**Cuándo usar:** Features que cruzan múltiples capas.

---

## Patrón 4: Regression Shield

Verificar que lo existente **sigue funcionando**.

```bash
# Ejecutar suite completa de tests
php artisan test          # Laravel
npm test                  # Node/React/Vue
pytest                    # Python

# Verificar que no se rompieron tests existentes
# Comparar: tests antes vs después del cambio
```

**Cuándo usar:** Siempre, en todo cambio de nivel estándar+.

---

## Patrón 5: Evidence Collection

Para cada verificación, recoger **evidencia concreta**:

| Tipo de evidencia | Cómo recogerla |
|-------------------|---------------|
| Test output | `php artisan test --filter=NombreTest` |
| Respuesta HTTP | `curl -X POST ...` o output del test feature |
| Estado DB | `assertDatabaseHas(...)` en test |
| Logs | Revisar `storage/logs/laravel.log` |
| UI | Screenshot o descripción del estado visual |

**Regla:** "Funciona" sin evidencia = no verificado.

---

> **Anti-patrones y checklist rápido:** ver `verification.instructions.md`.

