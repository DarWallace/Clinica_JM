---
description: "Crear un servicio Node.js modular, tipado y con buenas prácticas"
---
# Crear Servicio Node.js

## Antes de generar
1. Leer la estructura del proyecto: ¿Express, Fastify, NestJS, vanilla? ¿TypeScript o JS?
2. Leer servicios existentes para detectar patrones (inyección de dependencias, error handling, logging).
3. Verificar que no existe un servicio que cubra el mismo caso de uso.

## El servicio debe incluir

### Estructura
- Archivo en `src/services/` o según convención del proyecto.
- Naming: `{dominio}.service.ts` o `{Dominio}Service.ts` según convención.
- Exportación clara de la interfaz pública.

### Diseño
- **Modularidad**: lógica de negocio en el servicio, no en rutas/controllers.
- **Async/await** consistente. No mezclar callbacks con promesas.
- **Error handling**: errores tipados y descriptivos. No catch genéricos silenciosos.
- **Validación de input** en el boundary (antes de llamar al servicio), no dentro.
- **Tipado**: interfaces/tipos para parámetros y retornos.

### Dependencias externas
- Inyectar vía constructor o parámetro (facilita testing).
- Timeout y retry para llamadas a servicios externos.
- Fallback o error claro si la dependencia no responde.

### Logging
- Usar logger del proyecto (pino, winston, etc.), no `console.log`.
- Incluir contexto: operación, IDs relevantes, duración.

### Testing
- Tests unitarios con dependencias mockeadas.
- Tests de integración si interactúa con DB o APIs externas.
- Happy path + errores + edge cases.

## Qué entregar
1. Código del servicio completo.
2. Interface/tipo de entrada y salida.
3. Tests unitarios.
4. Si necesita configuración (env vars): documentar.

## Instrucciones de referencia
Aplicar `node.instructions.md`, `js.instructions.md`.
