---
mode: "agent"
description: "Retomar sesión anterior — cargar estado y trabajo pendiente"
---

# Retomar Sesión

Carga el estado del proyecto y retoma el trabajo donde se dejó.

## Instrucciones

1. **Leer `.planning/STATE.md`:**
   - Posición actual del proyecto.
   - Foco actual.
   - Última actividad.
   - Decisiones acumuladas.
   - Blockers activos.

2. **Verificar `.planning/.continue-here.md`:**
   - Si existe: leer contexto del trabajo interrumpido.
   - Leer los archivos relevantes listados.
   - Informar al usuario qué quedó pendiente.

3. **Presentar resumen al usuario:**
   ```markdown
   📋 **Estado del proyecto:**
   - **Foco:** {del STATE.md}
   - **Última actividad:** {fecha} — {descripción}
   - **Estado:** {planificando/en progreso/verificando/completo}

   ⏸️ **Trabajo pendiente:** {si hay .continue-here.md}
   - {Descripción del trabajo interrumpido}
   - Completado: {N} de {M} pasos
   - Próximo paso: {descripción}

   🚫 **Blockers activos:** {si hay}
   - {Lista de blockers}
   ```

4. **Preguntar al usuario:**
   - "¿Retomo el trabajo pendiente?"
   - O "¿Qué quieres hacer en esta sesión?"

5. **Si retoma:**
   - Cargar contexto completo (CONTEXT.md, PLAN.md de la feature activa).
   - Continuar desde el paso "en progreso" del .continue-here.md.
   - Al completar: eliminar .continue-here.md y actualizar STATE.md.

## Output esperado
- Resumen del estado actual.
- Contexto cargado y listo para trabajar.
- Si hay trabajo pendiente: retomado o archivado según decisión del usuario.

