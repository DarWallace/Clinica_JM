---
applyTo: ["**/*.jsx", "**/*.tsx"]
description: "Reglas para React"
---

# REACT — RECOMENDACIONES PRÁCTICAS

Objetivo: mantener componentes claros, accesibles, testables y predecibles en aplicaciones React.

## Componentes y Hooks
- Preferir componentes funcionales y hooks.
- Encapsular lógica reutilizable en hooks personalizados (`use*`) y mantenerlos pequeños y testables.
- Usar `useCallback`/`useMemo` con criterio para evitar renderizados innecesarios cuando haya cuellos de rendimiento.

## Tipado y props
- Tipar props (TypeScript) cuando sea posible; definir interfaces claras.
- Evitar prop drilling: usar context o composición para compartir estado.
- Props mínimos: pasar solo lo necesario y documentarlas en comentarios/tsdocs.

## Accesibilidad (a11y)
- Usar `aria-*` y `useId` para relacionar labels con inputs.
- Evitar elementos no semánticos; priorizar roles y atributos para lectores de pantalla.

## Testing
- Tests unitarios para hooks y componentes con Jest + React Testing Library.
- Tests de integración para flujos importantes; e2e si el proyecto lo requiere.

## Arquitectura de UI
- Componentes presentacionales vs containers: separar lógica de UI.
- Mantener carpeta `components/` y `hooks/`; agrupar por feature cuando el proyecto crezca.

## Buenas prácticas
- Evitar side-effects en render; usar `useEffect` para efectos y limpieza.
- Mantener CSS modular y prefieres soluciones CSS-in-JS o BEM según convención del proyecto.
- Documentar patrones de diseño y componentes reutilizables en `Docs/`.

(Resumen orientado a claridad, accesibilidad, testabilidad y buenas prácticas modernas.)