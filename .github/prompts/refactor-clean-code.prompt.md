---
description: "Refactorizar código aplicando Clean Code, SOLID y buenas prácticas"
---
# Refactorización Clean Code

## Antes de refactorizar
1. Leer el archivo completo y sus dependencias (imports, clases padre, interfaces).
2. Leer los tests existentes para no romperlos.
3. Identificar el lenguaje y las convenciones del proyecto.
4. Determinar el scope: ¿qué se refactoriza y qué NO se toca?

## Análisis previo (producir antes de cambiar código)
1. **Problemas detectados**: lista concisa de lo que se va a mejorar y por qué.
2. **Riesgo del refactor**: qué podría romperse (imports externos, API pública, tests).
3. **Plan**: orden de los cambios, de menor a mayor riesgo.

## Técnicas a aplicar (según el caso)

### Simplificación de lógica
- Early returns / guard clauses para eliminar anidamiento.
- Reemplazar condicionales complejas con polimorfismo o strategy pattern (solo si aporta claridad).
- Extraer condicionales a métodos con nombre descriptivo: `if ($this->isEligibleForDiscount())`.

### Extracción
- Métodos largos → métodos pequeños con nombre que describe la intención.
- Clases con múltiples responsabilidades → dividir por SRP.
- Código duplicado → método/función reutilizable o trait/mixin.

### Naming
- Renombrar variables/métodos/clases para que describan intención, no implementación.
- `$data` → `$orderItems`, `process()` → `calculateInvoiceTotal()`.

### Dependencias
- Reemplazar `new Dependency()` dentro de métodos por inyección de constructor.
- Introducir interfaces para dependencias que puedan cambiar (DB, APIs, notificaciones).

### Cohesión
- Agrupar métodos que trabajan con los mismos datos.
- Separar métodos que no comparten estado ni contexto.

## Reglas de scope (CRITICAL)
- **No cambiar la API pública** (nombres de métodos públicos, parámetros, tipos de retorno) sin aprobación explícita.
- **No romper tests existentes**. Si el refactor requiere actualizar tests, hacerlo.
- **No refactorizar archivos fuera del scope** de la tarea. Si se detectan problemas en otros archivos, listarlos como nota.
- Priorizar cambios de bajo riesgo sobre cambios ambiciosos.

## Formato de salida
1. **Análisis**: problemas detectados, plan, riesgos (bullets breves).
2. **Código refactorizado** aplicado directamente al archivo.
3. **Tests actualizados** si el refactor lo requiere.
4. **Resumen**: qué se cambió y por qué (3-5 bullets).

## Instrucciones de referencia
Aplicar el `*.instructions.md` del lenguaje/stack correspondiente.
