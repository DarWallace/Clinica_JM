---
applyTo: "**/.planning/STATE.md"
description: "State management — memoria persistente del proyecto entre sesiones"
---

# STATE MANAGEMENT — REGLAS

Objetivo: mantener un fichero de estado que actúe como **memoria del proyecto entre sesiones**, resolviendo la pérdida de contexto al cerrar conversaciones.

## Concepto

`STATE.md` es el fichero que el agente lee **al inicio de cada sesión** para saber:
- Dónde se dejó el trabajo.
- Qué decisiones se han tomado.
- Qué está bloqueado.
- Qué convenciones tiene el proyecto.

Vive en `.planning/STATE.md` en la raíz del proyecto.

---

## Cuándo crear STATE.md

- Al inicializar un proyecto nuevo (fase 2 de `init-project.instructions.md`).
- Al aterrizar en un proyecto existente que no lo tiene.
- Cuando el usuario lo solicita explícitamente.

---

## Estructura de STATE.md

```markdown
# Estado del proyecto

## Referencia

**Proyecto:** {nombre}
**Stack:** {lenguaje + framework + versiones}
**Valor core:** {qué hace este proyecto en 1 línea}
**Foco actual:** {qué se está construyendo ahora}

## Posición actual

- **Fase/Milestone:** {nombre o descripción}
- **Tarea activa:** {TASK-XXXX o descripción breve}
- **Estado:** Planificando | En progreso | Verificando | Bloqueado | Fase completa
- **Última actividad:** {YYYY-MM-DD} — {qué se hizo}

## Progreso

| Fase/Área | Estado | Notas |
|-----------|--------|-------|
| {nombre} | ✅ Completa / 🔄 En progreso / ⏳ Pendiente / 🚫 Bloqueada | {breve} |

## Decisiones acumuladas

Decisiones tomadas durante el proyecto que afectan futuras sesiones:

- [{fecha}] {Decisión}: {detalle breve}
- [{fecha}] {Decisión}: {detalle breve}

## Blockers y preocupaciones

| Blocker | Impacto | Necesita |
|---------|---------|----------|
| {descripción} | {qué afecta} | {qué se necesita para resolverlo} |

## Deuda técnica detectada

- {Área}: {descripción breve} — Prioridad: {alta/media/baja}

## Continuidad de sesión

**Última sesión:** {YYYY-MM-DD HH:MM}
**Se detuvo en:** {descripción de la última acción completada}
**Próximos pasos:** {1-3 acciones concretas para continuar}
**Archivo de continuidad:** {ruta a .continue-here.md si existe, o "ninguno"}
```

---

## Reglas de uso

### Lectura
- **El agente DEBE leer `STATE.md` al inicio de cada sesión** si el fichero existe.
- Si no existe pero `.planning/` sí, ofrecer crearlo.
- Si no existe nada, seguir el flujo normal.

### Escritura
Actualizar STATE.md cuando:
- Se completa una tarea o fase.
- Se toma una decisión técnica relevante.
- Se identifica un blocker nuevo.
- Se detecta deuda técnica.
- Al final de una sesión larga (ver §24 del global).

### Qué NO poner en STATE.md
- Código o fragmentos largos (referenciar archivos).
- Historial completo de todas las acciones (solo estado actual y últimas decisiones).
- Información que ya está en README o Docs (solo referenciar).

### Tamaño
- **Máximo ~80 líneas**. Si crece mucho, mover detalle a ficheros referenciados.
- El objetivo es que el agente lo lea en <5 segundos de contexto.

---

## Fichero de continuidad (.continue-here.md)

Para sesiones largas o trabajo interrumpido, crear `.planning/.continue-here.md`:

```markdown
# Continuar aquí

**Sesión:** {YYYY-MM-DD HH:MM}
**Contexto:** {qué se estaba haciendo, 2-3 líneas}

## Estado del trabajo

### Completado
- [x] {paso completado}
- [x] {paso completado}

### En progreso
- [ ] {paso actual — dónde exactamente se quedó}

### Pendiente
- [ ] {próximo paso}
- [ ] {siguiente}

## Archivos relevantes
- `{ruta}` — {qué hacer con este archivo}
- `{ruta}` — {qué hacer con este archivo}

## Contexto técnico necesario
{Información que el agente de la próxima sesión necesita saber para continuar sin preguntar}

## Decisiones pendientes
{Si hay algo que necesita decisión del usuario antes de continuar}
```

### Reglas del fichero de continuidad
- Crear cuando la sesión se interrumpe en medio de una tarea de nivel estándar o superior.
- **Borrar** cuando se retoma la sesión y se completa el trabajo pendiente.
- Solo puede existir **un** `.continue-here.md` a la vez.
- Si existe al iniciar una sesión, el agente lo lee inmediatamente y ofrece retomar.

---

## Integración con el flujo global

> **⚠️ REFUERZO ANTI-OLVIDO:** Este fichero se pierde de vista cuando el contexto de la conversación crece. Para contrarrestar esto, el global §1.1 define un **protocolo de bookends** obligatorio que fuerza la lectura al inicio y la escritura al final. Si el agente no actualiza STATE.md al terminar una tarea, el Definition of Done (§14) no se cumple.

| Momento | Acción con STATE.md |
|---------|---------------------|
| Inicio de sesión | Leer STATE.md → cargar contexto (§1.1 APERTURA) |
| Después de init-project | Crear STATE.md inicial |
| Al completar una tarea | Actualizar posición y progreso |
| Al tomar decisión técnica | Añadir a "Decisiones acumuladas" |
| Al detectar deuda técnica | Añadir a "Deuda técnica detectada" |
| Cada 3-5 turnos (multi-turno) | Re-leer PLAN.md, actualizar progreso parcial (§1.1 DURANTE) |
| Al interrumpir sesión | Actualizar "Continuidad" + crear .continue-here.md (§1.1 CIERRE) |
| Al completar fase/milestone | Actualizar progreso y foco actual (§1.1 CIERRE) |

