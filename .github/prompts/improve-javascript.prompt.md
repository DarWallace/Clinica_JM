---
description: "Mejorar un archivo JavaScript aplicando Clean Code y buenas prácticas modernas"
---
# Mejorar JavaScript

## Antes de modificar
1. Leer el archivo completo y sus imports/dependencias.
2. Leer los tests existentes (si los hay).
3. Identificar el rol del archivo: util, componente, servicio, middleware, etc.
4. Detectar la convención del proyecto (ESM vs CJS, TS vs JSDoc, framework de testing).

## Análisis a realizar
1. **Complejidad**: funciones largas, anidamiento profundo, condicionales complejas.
2. **Naming**: variables/funciones con nombres descriptivos vs crípticos.
3. **Side effects**: mutación de estado global, efectos en imports, closures peligrosos.
4. **Errores**: promesas sin catch, try/catch vacíos, errores silenciados.
5. **Duplicación**: código repetido que puede extraerse.
6. **Tipado**: ¿falta JSDoc o TypeScript en funciones públicas?

## Mejoras a aplicar
- Simplificar lógica: early returns, guard clauses, `?.` y `??`.
- Funciones pequeñas (~20 líneas): extraer cuando una función hace más de una cosa.
- Eliminar efectos colaterales: funciones puras cuando sea posible.
- Modularizar: un archivo por responsabilidad.
- Nombres descriptivos: verbo + sustantivo para funciones, sustantivo para variables.
- ES6+: destructuring, template literals, `const`/`let`, arrow functions.
- Manejar errores correctamente: catch con contexto, no silenciar.

## Formato de salida
1. **Resumen de cambios** (bullets: qué se mejoró y por qué).
2. **Código mejorado** aplicado directamente al archivo.
3. **Tests** a añadir o actualizar (si el cambio afecta lógica).

## Reglas de scope
- Mantener la API pública del módulo (no romper imports de otros archivos).
- Si la mejora requiere cambios en otros archivos, listarlos pero no modificarlos sin permiso.

## Instrucciones de referencia
Aplicar `js.instructions.md`.
