---
description: "Crear un componente React funcional, tipado, accesible y testeable"
---
# Crear Componente React

## Antes de generar
1. Leer los componentes existentes en el proyecto para detectar patrones (CSS solution, state management, testing framework).
2. Verificar si ya existe un componente similar que pueda reutilizarse o extenderse.
3. Identificar si es presentacional (solo UI) o container (conecta con estado/API).

## El componente debe incluir

### Estructura
- Componente funcional con hooks.
- Props tipadas con TypeScript interface (o PropTypes si el proyecto no usa TS).
- Valores por defecto para props opcionales.
- Exportación nombrada (no default export, salvo convención del proyecto).

### Estado y efectos
- Estado mínimo necesario. Levantar estado al padre si otros componentes lo necesitan.
- `useEffect` con cleanup para suscripciones, timers, event listeners.
- `useCallback`/`useMemo` solo cuando haya evidencia de problema de rendimiento, no preventivamente.
- Lógica reutilizable extraída en custom hooks (`use*`).

### Accesibilidad
- HTML semántico: `<button>`, `<nav>`, `<main>`, no `<div onClick>`.
- Labels asociados a inputs (`htmlFor` / `aria-labelledby`).
- Roles ARIA solo si no hay elemento nativo equivalente.
- Foco visible y navegación por teclado en elementos interactivos.

### Estilos
- Seguir la convención del proyecto (CSS Modules, Tailwind, styled-components, etc.).
- Si no hay convención: proponer CSS Modules o la solución más simple.
- Responsive: mobile-first.

### Testing
- Proponer tests con React Testing Library:
  - Renderizado correcto con props por defecto.
  - Interacción del usuario (click, input, submit).
  - Accesibilidad básica (roles, labels).
- No tests de snapshot salvo componentes estables y complejos.

## Si el componente es complejo (>100 líneas)
Proponer división en componentes más pequeños antes de implementar:
- Separar lógica (container) de presentación.
- Extraer subcomponentes reutilizables.

## Formato de salida
1. Código del componente.
2. Código de los tests.
3. Si se crearon hooks custom: código del hook y sus tests.

## Instrucciones de referencia
Aplicar `react.instructions.md`, `components.instructions.md`, `css.instructions.md`.
