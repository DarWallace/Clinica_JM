---
applyTo: "src/components/**"
description: "Organización de componentes frontend"
---

# COMPONENTES — REGLAS

## Principio: componentes pequeños, reutilizables y accesibles

## Diseño
- **Componentes presentacionales**: solo UI, reciben datos por props, sin side-effects.
- **Componentes container**: conectan con estado/API, delegan renderizado a presentacionales.
- Mantener cada componente con una sola responsabilidad.
- Si un componente supera ~150 líneas, evaluar si se puede dividir.

## Naming
- PascalCase para componentes: `UserCard`, `OrderList`, `PaymentForm`.
- Nombres descriptivos que reflejen qué hacen, no cómo: `UserAvatar` sobre `RoundImage`.
- Prefijo para tipos especiales si el proyecto lo requiere: `Base*` (genéricos), `The*` (singletons).

## Estructura de archivos
- Un componente por archivo.
- Colocar junto al componente: tests, estilos y stories si aplican.
- Agrupar por feature cuando el proyecto crezca: `components/orders/`, `components/auth/`.

## Props e interfaz pública
- Props mínimas: pasar solo lo que el componente necesita.
- Tipar props (TypeScript/PropTypes/defineProps) siempre.
- Documentar props no obvias con comentarios o TSDoc.
- Valores por defecto explícitos para props opcionales.
- Emitir eventos con nombres descriptivos: `@update:status`, `@confirm-delete`.

## Estado
- Estado local para UI (abrir/cerrar, hover, focus).
- Estado compartido vía composables/hooks, context o store según escala.
- Documentar por qué se usa estado global vs local cuando no sea obvio.

## Accesibilidad
- HTML semántico: usar `<button>`, `<nav>`, `<main>`, `<article>` en vez de `<div>` genéricos.
- Atributos `aria-*` cuando el HTML semántico no sea suficiente.
- Labels asociados a inputs (`for`/`id` o `aria-labelledby`).
- Focus visible y navegación por teclado en elementos interactivos.
- Roles ARIA solo cuando no haya elemento HTML nativo equivalente.

## Testing
- Tests unitarios para componentes con lógica (condicionales, handlers).
- Usar Testing Library (RTL para React, Vue Test Utils para Vue): testear comportamiento, no implementación.
- Verificar: renderizado correcto, interacción del usuario, accesibilidad básica.
- Tests de snapshot solo si aportan valor (componentes estables con output complejo).

## Anti-patrones
- ❌ Componentes que hacen fetch, transforman datos Y renderizan (dividir responsabilidades).
- ❌ Prop drilling profundo (más de 3 niveles): usar composition, context o store.
- ❌ Componentes con más de 10 props (señal de que hace demasiado).
- ❌ Lógica de negocio en componentes UI.
- ❌ Estilos con selectores globales que afecten a otros componentes.
