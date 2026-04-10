---
applyTo: "**/*.vue"
description: "Reglas para Vue (Composition API)"
---

# VUE — REGLAS

Objetivo: componentes pequeños, tipados y fácilmente testeables.

Estilo
- Usar Composition API por defecto.
- Tipar props/emits con TypeScript cuando sea posible.
- Separar template, script (setup) y estilos scoped.

Organización
- Composables para lógica reutilizable (composables/)
- Components/ con tests unitarios y storybook si aplica

Prácticas recomendadas
- Evitar grandes componentes monolíticos; dividir en presentational/container.
- Manejar estado local vs global explícitamente y documentar por qué.
- Añadir pruebas unitarias con vitest/jest y pruebas E2E si la feature lo requiere.

Checklist rápido para PRs
- Lint (eslint) limpio
- Tipado de props/emits
- Tests unitarios añadidos o actualizados
- Documentación o story si el componente es público
