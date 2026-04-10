---
description: "Refactorizar código frontend para claridad, modularidad, rendimiento y accesibilidad"
---
# Refactor Frontend

## Antes de refactorizar
1. Leer el componente/archivo completo y sus dependencias (imports, hooks, stores, estilos).
2. Leer los tests existentes.
3. Detectar el framework (React, Vue, vanilla) y las convenciones del proyecto.
4. Identificar si el refactor es de un solo componente o afecta a varios.

## Análisis previo
1. **Problemas detectados**: qué se va a mejorar y por qué (bullets concretos).
2. **Impacto**: ¿afecta a otros componentes que importan este? ¿Rompe props/emits?
3. **Plan**: orden de cambios de menor a mayor riesgo.

## Áreas de mejora

### Modularidad
- Dividir componentes monolíticos (>150 líneas) en subcomponentes.
- Separar lógica (container/hooks/composables) de presentación.
- Extraer lógica reutilizable a custom hooks / composables.

### Estado
- Clarificar qué estado es local vs global y por qué.
- Eliminar estado derivado: si se calcula de props/otro estado, usar `computed`/`useMemo`.
- Levantar estado al padre si múltiples hijos lo necesitan.

### Efectos y eventos
- Limpiar subscripciones, timers y event listeners en cleanup/unmount.
- Reducir efectos: ¿se puede resolver con estado derivado en vez de effect?
- Evitar effects que dependen de muchas variables (señal de que la lógica es compleja).

### Rendimiento
- Identificar renders innecesarios: `React.memo`, `useMemo`, `v-once`, `computed`.
- Lazy-load de componentes pesados.
- Virtualización de listas largas si aplica.

### Accesibilidad
- HTML semántico.
- Labels, aria-*, focus management.
- Contraste y touch targets.

## Reglas de scope
- No cambiar la interfaz pública del componente (props/emits) sin aprobación.
- No romper tests existentes.
- Si se detectan problemas en componentes que importan este, listarlos como nota.

## Formato de salida
1. **Análisis**: problemas, plan, riesgos.
2. **Código refactorizado** aplicado directamente.
3. **Componentes extraídos** (si se dividió).
4. **Tests actualizados** si el refactor lo requiere.

## Instrucciones de referencia
Aplicar `components.instructions.md`, `react.instructions.md` o `vue.instructions.md`, `css.instructions.md`.
