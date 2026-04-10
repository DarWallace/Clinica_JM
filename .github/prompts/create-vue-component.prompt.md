---
description: "Crear un componente Vue 3 con Composition API, tipado, accesible y testeable"
---
# Crear Componente Vue

## Antes de generar
1. Leer los componentes existentes del proyecto para detectar patrones (Pinia vs Vuex, CSS solution, testing framework).
2. Verificar si ya existe un componente similar reutilizable.
3. Identificar si necesita composables para lógica compartida.

## El componente debe incluir

### Template
- HTML semántico: `<button>`, `<nav>`, `<header>`, no `<div @click>`.
- `v-bind` / `v-model` explícitos (no shorthands ambiguos en componentes complejos).
- Accesibilidad: labels en inputs, `aria-*` cuando sea necesario, foco visible.

### Script
- `<script setup lang="ts">` con Composition API.
- Props tipadas con `defineProps<{}>()` y valores por defecto con `withDefaults()`.
- Emits tipados con `defineEmits<{}>()`.
- Composables para lógica reutilizable (extraer a `composables/` si se usa en más de un componente).
- Evitar `watch` profundos sin justificación. Preferir `computed` sobre `watch` cuando sea posible.

### Estilos
- `<style scoped>` para aislamiento.
- Seguir la convención del proyecto (Tailwind, SCSS, BEM, etc.).
- Variables CSS para tokens de diseño reutilizables.

### Estado
- Estado local con `ref()` / `reactive()` para UI.
- Estado compartido con Pinia store si aplica. Documentar por qué se usa global vs local.
- Props + emits para comunicación padre-hijo. Evitar provide/inject excepto para cross-cutting concerns.

### Testing
- Proponer tests con Vue Test Utils + Vitest (o Jest según proyecto):
  - Renderizado con props por defecto.
  - Interacción del usuario (click, input, emit).
  - Props reactivas (cambio de prop → cambio de renderizado).
- Composables: tests unitarios independientes del componente.

## Si el componente es complejo (>120 líneas)
Proponer división antes de implementar:
- Extraer subcomponentes presentacionales.
- Extraer composables para lógica.

## Formato de salida
1. Código del componente (template + script setup + style scoped).
2. Código de los tests.
3. Composables extraídos (si aplica) con sus tests.

## Instrucciones de referencia
Aplicar `vue.instructions.md`, `components.instructions.md`, `css.instructions.md`.
