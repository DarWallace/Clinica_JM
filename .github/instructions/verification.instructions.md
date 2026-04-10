---
applyTo: "**/.planning/**/VERIFICATION.md"
description: "Verificación post-ejecución goal-backward"
---

# VERIFICATION — REGLAS

Objetivo: definir cómo verificar que el trabajo realizado **realmente funciona**, no solo que el código existe.

---

## Principio core

> **Tarea completada ≠ Objetivo alcanzado**

Un archivo creado no significa que la feature funciona. Un test que pasa no significa que cubre el caso real. La verificación empieza por el **objetivo** y trabaja hacia atrás.

---

## Metodología goal-backward

### Paso 1 — Definir verdades
¿Qué debe ser VERDAD para que este trabajo esté completo?

Ejemplo para "endpoint de crear orden":
- ✅ Un usuario autenticado puede crear una orden con datos válidos.
- ✅ Los datos inválidos devuelven errores específicos.
- ✅ La orden se persiste en la base de datos.
- ✅ El usuario no autenticado recibe 401.

### Paso 2 — Verificar existencia
¿Los archivos, funciones, endpoints, componentes **existen**?
- Leer el filesystem.
- Verificar que las rutas están registradas.
- Verificar que los modelos/servicios están creados.

### Paso 3 — Verificar sustancia
¿El código tiene **contenido real** o son placeholders?
- Leer el código: ¿hay lógica o solo `// TODO`?
- ¿Los métodos hacen lo que su nombre dice?
- ¿Las validaciones cubren los campos correctos?

### Paso 4 — Verificar integración
¿Las piezas están **conectadas entre sí**?
- ¿El controller llama al service correcto?
- ¿El service usa el model correcto?
- ¿Las rutas apuntan al controller correcto?
- ¿El frontend llama al endpoint correcto?

### Paso 5 — Verificar funcional
¿La feature **funciona** end-to-end?
- Ejecutar tests automatizados.
- Si es posible: probar manualmente o con comandos.
- Verificar cada criterio de aceptación.

### Paso 6 — Verificar regresión
¿Se **rompió algo** que ya funcionaba?
- Ejecutar suite completa de tests.
- Verificar que features relacionadas siguen funcionando.

---

## Niveles de verificación

| Nivel | Cuándo aplicar | Qué cubre |
|-------|---------------|-----------|
| **Mínimo** | Tarea trivial / estándar simple | Existencia + no errores + tests pasan |
| **Estándar** | Tarea estándar con lógica | Existencia + Sustancia + Tests + Regresión |
| **Completo** | Tarea crítica / pipeline | Los 6 pasos goal-backward completos |

---

## Checklist de verificación rápida

Para nivel estándar, usar este checklist inline (no crear VERIFICATION.md):

```markdown
### Verificación
- [ ] Archivos creados/modificados existen y tienen contenido.
- [ ] El código compila/ejecuta sin errores.
- [ ] Tests nuevos escritos y pasan.
- [ ] Tests existentes siguen pasando.
- [ ] Seguridad verificada (§6 del global).
```

---

## Errores comunes de verificación

| Error | Problema | Cómo evitarlo |
|-------|----------|---------------|
| "Los tests pasan" | Pero los tests no cubren el caso real | Verificar qué testean realmente los tests |
| "El archivo existe" | Pero es un placeholder con TODOs | Leer el contenido del archivo |
| "El endpoint responde 200" | Pero no persiste datos | Verificar side effects (DB, eventos, emails) |
| "Funciona en mi test" | Pero falta middleware de auth | Probar con y sin autenticación |
| "El código se ve bien" | Pero no está conectado a nada | Seguir el flujo de datos completo |

---

## Reglas

- **No confiar en lo que el código "dice que hace"**: verificar lo que **realmente hace**.
- **Verificar con evidencia**: "funciona" necesita prueba (output de test, respuesta HTTP, registro en DB).
- **Acceso condicional a terminal**: si el agente puede ejecutar comandos, ejecutar los tests y reportar el output. Si no puede, proporcionar los comandos exactos al usuario y solicitar el resultado. **Nunca afirmar que algo funciona sin evidencia.**
- **La verificación es proporcional**: no hacer verificación completa de 6 pasos para un typo fix.
- **Documentar gaps**: si algo no se pudo verificar, decirlo explícitamente, no ocultarlo.

