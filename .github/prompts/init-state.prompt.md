---
mode: "agent"
description: "Inicializar STATE.md para un proyecto — crea el fichero de estado persistente"
---

# Inicializar State

Crea el fichero `.planning/STATE.md` para este proyecto, siguiendo `state.instructions.md`.

## Instrucciones

1. **Leer contexto del proyecto:**
   - `README.md` (si existe)
   - Archivos de configuración (`composer.json`, `package.json`, etc.)
   - `.planning/codebase/` (si existe el mapeo)
   - Estructura de carpetas del proyecto

2. **Crear `.planning/` si no existe.**

3. **Crear `.planning/STATE.md`** con:
   - Referencia del proyecto (nombre, stack, valor core)
   - Posición actual (fase/milestone inicial)
   - Secciones vacías para decisiones, blockers, deuda técnica
   - Sección de continuidad de sesión

4. **Formato:** seguir la plantilla de `state.instructions.md`.

5. **Si ya existe STATE.md:** informar al usuario y ofrecer actualizarlo en vez de sobreescribirlo.

## Output esperado
- Fichero `.planning/STATE.md` creado.
- Confirmar al usuario qué se detectó del proyecto.

