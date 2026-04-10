---
description: "Crear scripts Python limpios, modulares, tipados y testeables"
---
# Crear Script Python

## Antes de generar
1. Leer la estructura del proyecto: ¿hay `src/`, `tests/`, `pyproject.toml`, `requirements.txt`?
2. Detectar versión de Python y dependencias existentes.
3. Verificar si la funcionalidad ya existe en el proyecto.

## El script debe incluir

### Estructura
- Seguir la convención del proyecto. Si no hay: `src/{module}/` + `tests/`.
- Entry point claro con `if __name__ == "__main__":`.
- Separar lógica de negocio de I/O (lectura de archivos, APIs, CLI).

### Estilo
- PEP 8 con formatter (black/ruff).
- Type hints en funciones públicas: parámetros + retorno.
- Docstrings en funciones públicas: qué hace, parámetros, retorno, excepciones.

### Diseño
- Funciones pequeñas y claras (~20 líneas máx).
- Clases cuando representen entidades/servicios con estado o múltiples operaciones cohesivas.
- Inyección de dependencias: pasar dependencias como parámetros, no como imports hardcodeados en la lógica.
- Evitar side effects en import time.
- Evitar variables globales mutables.

### Error handling
- Excepciones específicas: `ValueError`, `FileNotFoundError`, excepciones custom.
- No silenciar errores: `except: pass` está prohibido.
- Logging con `logging` module, no `print()` para producción.

### Testing
- Tests con pytest.
- Fixtures para setup reutilizable.
- Mocks para dependencias externas (files, APIs, DB).
- Tests parametrizados (`@pytest.mark.parametrize`) para múltiples escenarios.

## Qué entregar
1. Código del script/módulo.
2. Tests con pytest.
3. `requirements.txt` o actualización de `pyproject.toml` si se añaden dependencias.
4. Docstring de uso en el módulo principal.

## Instrucciones de referencia
Aplicar `python.instructions.md`.
