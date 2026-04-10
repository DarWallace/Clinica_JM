# Learnings

Base de conocimiento de errores y correcciones del agente. Reglas concretas derivadas de errores reales.

## Cuándo se crea un learning

El agente propone crear uno cuando:
1. Comete un error que el usuario corrige.
2. Descubre una convención no documentada del proyecto.
3. Una herramienta falla de forma recurrente.

**El agente propone, el usuario aprueba.**

## Estructura

`{area}_{descripcion}.md`

```yaml
---
area: "{stack/módulo/herramienta}"
created: "YYYY-MM-DD"
source: "error del agente | corrección del usuario | convención descubierta"
---
```

Seguido de: Contexto → Error → Corrección → Aplicar cuando.

## Ciclo de vida

1. Detección → 2. Propuesta → 3. Aprobación → 4. Aplicación → 5. Promoción a instruction → 6. Expiración

## Reglas
- Máximo 1 learning por error.
- Learnings concretos: "no usar X en Y" es un learning; "tener cuidado" no lo es.
- Máximo 20 ficheros antes de revisar y promover/eliminar.

Ver `global-copilot-instructions.md` §21 para la especificación completa.

