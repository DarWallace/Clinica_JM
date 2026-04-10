---
description: "Analizar una clase PHP: responsabilidades, SOLID, acoplamientos, tests necesarios y refactor sugerido"
---
# Analizar clase PHP

## Contexto
El usuario proporcionará un archivo PHP o una ruta a una clase. Antes de analizar:
1. Leer la clase completa y sus dependencias directas (interfaces, traits, clases padre).
2. Leer los tests existentes de esa clase (si los hay en `tests/`).
3. Identificar el rol de la clase en el proyecto (modelo, servicio, controlador, action, etc.).

## Análisis a realizar

### 1. Responsabilidades (SRP)
- ¿Cuántas responsabilidades tiene la clase? Listarlas.
- ¿Viola SRP? Si sí, proponer cómo dividir.

### 2. Dependencias y acoplamiento
- Listar dependencias inyectadas y hardcodeadas (`new` dentro de métodos).
- Identificar acoplamiento a implementaciones concretas vs interfaces.
- ¿Hay dependencias circulares?

### 3. Cohesión y tamaño
- Número de métodos públicos/privados y propiedades.
- ¿Los métodos trabajan con los mismos datos? (alta cohesión) o ¿cada uno toca cosas distintas? (baja cohesión).
- ¿Supera ~200 líneas? Señalar si es excesivo para su responsabilidad.

### 4. SOLID
Evaluar cada principio que aplique:
- **S** — SRP (ya cubierto arriba).
- **O** — ¿Se puede extender sin modificar? ¿Usa estrategias, polimorfismo?
- **L** — Si hereda, ¿respeta el contrato del padre?
- **I** — ¿Implementa interfaces con métodos que no usa?
- **D** — ¿Depende de abstracciones o de clases concretas?

### 5. Seguridad y performance
- ¿Hay input sin validar? ¿SQL sin ORM? ¿Datos sensibles expuestos?
- ¿Hay N+1? ¿Queries dentro de bucles? ¿Operaciones costosas sin cache?

### 6. Tests necesarios
- Listar tests unitarios que deberían existir (happy path + edge cases).
- Listar tests de integración si la clase interactúa con DB/APIs.
- Si ya existen tests, evaluar si cubren los flujos críticos.

### 7. Refactor sugerido
- Proponer cambios concretos, ordenados de mayor a menor impacto.
- Para cada cambio: qué se hace, por qué mejora, riesgo del cambio.
- Si el refactor es grande, proponer PRs incrementales.

## Formato de salida
Usar secciones con headers. Para cada punto: bullets concretos, no genéricos.
Si se propone refactor de código: mostrar el diff o el código propuesto directamente.

## Instrucciones de referencia
Aplicar `php.instructions.md` y el satélite correspondiente al tipo de clase (controllers, models, services).
