---
mode: "agent"
description: "Guardar estado de sesión para retomar después"
---

# Guardar Sesión

Guarda el estado actual del trabajo para poder retomarlo en una sesión futura.

## Instrucciones

1. **Actualizar `.planning/STATE.md`:**
   - Posición actual (fase, tarea, estado).
   - Última actividad (fecha y descripción).
   - Nuevas decisiones tomadas en esta sesión.
   - Nuevos blockers detectados.
   - Deuda técnica descubierta.
   - Sección de continuidad: dónde se detuvo, próximos pasos.

2. **Si hay trabajo en progreso no completado**, crear `.planning/.continue-here.md`:
   - Contexto de lo que se estaba haciendo.
   - Pasos completados vs pendientes.
   - Archivos relevantes con indicación de qué hacer.
   - Contexto técnico necesario para la próxima sesión.

3. **Si todo el trabajo se completó limpiamente:**
   - Solo actualizar STATE.md.
   - NO crear .continue-here.md.
   - Si existía uno anterior, eliminarlo.

4. **Confirmar al usuario:**
   ```
   💾 Estado guardado:
   - STATE.md actualizado — {resumen de posición}
   - .continue-here.md: {creado / no necesario / eliminado}
   - Próximos pasos: {1-2 acciones para la próxima sesión}
   ```

## Reglas
- Si `.planning/STATE.md` no existe, crearlo siguiendo `state.instructions.md`.
- No inflar STATE.md: máximo ~80 líneas.
- Solo un `.continue-here.md` a la vez.

