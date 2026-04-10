---
applyTo: "**/*.js"
description: "Reglas para JavaScript"
---

# JS — REGLAS

## Principio: JavaScript moderno, funcional y predecible

## Estilo y convenciones
- Usar ES6+: `const`/`let` (nunca `var`), arrow functions, destructuring, template literals, optional chaining.
- Preferir funciones puras y módulos pequeños con responsabilidad clara.
- Naming explícito: `getUserById()` sobre `get()`, `isActive` sobre `flag`.
- Usar ESLint + Prettier en el pipeline. No commitear con warnings.

## Módulos
- Usar ES Modules (`import`/`export`) sobre CommonJS (`require`) cuando el proyecto lo soporte.
- Un export por responsabilidad. Evitar barrel files (`index.js`) excesivos que dificulten tree-shaking.
- Evitar efectos colaterales en archivos compartidos (no ejecutar lógica al importar).

## Tipado
- Si el proyecto usa TypeScript: tipado estricto, evitar `any`.
- Si es JS puro: usar JSDoc con `@param`, `@returns`, `@typedef` para documentar tipos.
- Validar datos en boundaries (input de usuario, respuestas de API).

## Manejo de errores
- Siempre manejar errores en promesas (`.catch()` o try/catch con async/await).
- Errores descriptivos: incluir contexto (qué operación falló, con qué datos).
- No silenciar errores con catch vacíos.

## Funciones
- Mantener funciones pequeñas (~20 líneas máx). Si crece, dividir.
- Parámetros: máximo 3 posicionales. Si necesitas más, usar un objeto de opciones.
- Evitar mutación de parámetros: trabajar con copias o retornar nuevos objetos.
- Documentar funciones públicas con JSDoc.

## Testing
- Tests unitarios para cada util, helper y función de lógica.
- Mocks para dependencias externas (fetch, filesystem, timers).
- Usar `describe` + `it` con nombres descriptivos.

## Anti-patrones
- ❌ `var` en cualquier contexto.
- ❌ Callbacks anidados (callback hell): refactorizar a async/await.
- ❌ Mutación de estado global o compartido.
- ❌ `eval()`, `with`, `arguments` magic object.
- ❌ Comparaciones con `==` (usar `===` siempre).
- ❌ Funciones de más de 50 líneas sin justificación.
