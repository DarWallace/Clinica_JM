---
applyTo: "**/package.json"
description: "Reglas y recomendaciones para servicios Node.js"
---

# NODE — REGLAS

## Principio: servicios Node.js limpios, tipados y observables

## package.json
- Mantener `package.json` limpio: sin dependencias huérfanas.
- Definir `engines` con la versión mínima de Node requerida.
- Scripts útiles y documentados: `start`, `dev`, `test`, `lint`, `build`.
- Separar `dependencies` de `devDependencies` correctamente.
- Usar lockfile (`package-lock.json` o `pnpm-lock.yaml`). Commitearlo siempre.

## Estructura de proyecto
- Separar concerns: `src/`, `tests/`, `config/`.
- Entry point claro (`src/index.ts` o `src/app.ts`).
- Variables de entorno en `.env` (no commitear), con `.env.example` como template.
- Configuración centralizada: un módulo que lee env y exporta config tipada.

## Tipado
- Usar TypeScript o JSDoc con `@ts-check` para tipado estático.
- Tipar parámetros, retornos y objetos de configuración.
- Evitar `any`: usar `unknown` y validar.

## Async y errores
- Usar `async/await` sobre callbacks y `.then()` chains.
- Manejar errores con try/catch explícito. No dejar promesas sin catch.
- Implementar graceful shutdown: cerrar conexiones y jobs al recibir SIGTERM.
- Timeout y retry en llamadas a servicios externos.

## Seguridad
- Escanear dependencias regularmente (`npm audit`, `snyk`).
- No guardar secrets en codebase. Usar env o secret manager.
- Validar input en boundaries (API, webhooks, colas).
- Usar helmet, cors, rate-limiting en APIs HTTP.
- Evitar `eval()`, `Function()` y deserialización no segura.

## Testing
- Tests unitarios con Jest, Vitest o el framework del proyecto.
- Tests de integración para APIs y servicios con dependencias externas mockeadas.
- Coverage razonable en lógica de negocio.

## Logging y observabilidad
- Usar un logger estructurado (pino, winston) en vez de `console.log`.
- Incluir request ID, timestamps y contexto en los logs.
- Health check endpoint para servicios HTTP.

## Anti-patrones
- ❌ Callbacks hell: refactorizar a async/await.
- ❌ Dependencias sin mantenedor o con CVEs conocidos.
- ❌ `require()` dinámico sin justificación.
- ❌ Mutación de objetos compartidos entre módulos.
- ❌ Servicios sin graceful shutdown.
