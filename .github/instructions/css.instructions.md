---
applyTo: "**/*.css"
description: "Guía para estilos CSS"
---

# CSS — REGLAS

## Principio: estilos predecibles, mantenibles y accesibles

## Arquitectura
- Seguir la convención del proyecto: BEM, utility-first (Tailwind), CSS Modules, o CSS-in-JS.
- Si no hay convención establecida: preferir utility-first o BEM según el stack.
- Mantener especificidad baja: evitar `!important`, IDs como selectores, y anidamiento profundo (máx 3 niveles).
- Organizar por componente o por capa (base, layout, components, utilities).

## Variables y tokens de diseño
- Usar CSS custom properties (`--color-primary`, `--spacing-md`) para valores reutilizables.
- Centralizar tokens de diseño en un fichero base (`variables.css`, `tokens.css` o theme).
- Nombres semánticos: `--color-danger` sobre `--red-500` cuando se usen como tokens de diseño.
- Mantener consistencia: no mezclar px, rem, em sin criterio. Definir unidad base.

## Responsive
- Mobile-first: diseñar para móvil y escalar con `min-width` media queries.
- Usar unidades relativas (`rem`, `em`, `%`, `vw/vh`) para tipografía y layout.
- Breakpoints consistentes, definidos como variables o mixins.
- Testear en al menos 3 viewports: móvil, tablet, desktop.

## Performance
- Evitar selectores universales (`*`) y selectores muy costosos.
- Minimizar reflows: agrupar cambios de layout, usar `transform` sobre propiedades de layout para animaciones.
- Lazy-load CSS no crítico si el proyecto lo soporta.
- Purgar CSS no usado en producción (PurgeCSS, Tailwind purge).

## Accesibilidad
- Contraste mínimo WCAG AA (4.5:1 para texto normal, 3:1 para texto grande).
- Focus visible en elementos interactivos (`:focus-visible`). No eliminar outline sin alternativa.
- No depender solo del color para transmitir información (usar íconos, texto, patrones).
- Respetar `prefers-reduced-motion` para animaciones.
- Tamaños mínimos de touch target: 44x44px en móvil.

## Naming (BEM)
- Bloque: `.card`, `.nav`, `.form`.
- Elemento: `.card__title`, `.card__body`.
- Modificador: `.card--featured`, `.btn--disabled`.
- No anidar BEM: `.card__title--bold` ✅, `.card__title__icon` ❌ (crear nuevo bloque).

## Anti-patrones
- ❌ `!important` sin justificación documentada.
- ❌ Selectores de más de 3 niveles de anidamiento.
- ❌ Estilos inline en HTML para layout o branding (solo para valores dinámicos).
- ❌ Magic numbers: usar variables/tokens para espaciado, colores, tamaños.
- ❌ Eliminar `:focus` o `outline` sin alternativa accesible.
