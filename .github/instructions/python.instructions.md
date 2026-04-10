---
applyTo: "**/*.py"
description: "Python limpio, modular y claro"
---

# PYTHON — REGLAS

Objetivo: código legible, testeable y mantenible.

Estilo y convenciones
- Seguir PEP8 y usar black como formateador recomendado.
- Tipado: añadir type hints en funciones públicas y en return cuando sea relevante.
- Nombres explícitos y módulos pequeños.

Estructura de proyecto mínima
- src/ o package_name/
- tests/ con pytest
- requirements.txt o pyproject.toml

Prácticas recomendadas
- Evitar side effects en import time.
- Inyectar dependencias (evitar singletons globales).
- Documentar con docstrings y ejemplo de uso en README.
- Añadir scripts de lint y tests en CI.

Checklist rápido para PRs
- Linter limpio
- Tests añadidos o afectados cubiertos
- Tipos y docstrings claros
- Cambios documentados en changelog si aplica
