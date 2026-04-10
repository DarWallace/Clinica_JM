# Context Cache

Cache de documentación externa consultada vía MCP/Context7, condensada y reutilizable entre sesiones.

## Flujo

```
¿Necesito docs de X? → ¿Existe context/X? → Sí + no expirado → Usar
                                           → No o expirado → Consultar MCP → Guardar aquí → Usar
```

## Naming

`{framework}_{tema}_{version}.context.md`

Ejemplos:
- `filament_actions_v3.context.md`
- `laravel_queues_11x.context.md`
- `vue_composables_3.4.context.md`

## Expiración

| Tipo | TTL |
|------|-----|
| Framework major version | 90 días |
| Librería con releases frecuentes | 30 días |
| API externa / SaaS | 14 días |

## Reglas
- Condensar, no copiar. Máximo ~200 líneas por fichero.
- Incluir versión siempre.
- No acumular: máximo 15 ficheros antes de revisar.

Ver `global-copilot-instructions.md` §22 para la especificación completa.

