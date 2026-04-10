---
applyTo: "**/*.php"
description: "Reglas para proyectos PHP"
---

# PHP — REGLAS

## Principio: PHP moderno, tipado y limpio

## Estilo y convenciones
- Seguir PSR-12 para formato y estructura de código.
- Usar PHP 8.0+ features: union types, named arguments, enums, match, readonly properties, fibers cuando apliquen.
- Nombres explícitos para clases, métodos y variables. Evitar abreviaturas crípticas.
- Un class por file. Namespace alineado con PSR-4.

## Tipado
- Tipar parámetros, retornos y propiedades de clase explícitamente.
- Evitar `mixed` salvo cuando sea genuinamente necesario. Preferir union types.
- Usar `strict_types` (`declare(strict_types=1)`) en todos los ficheros.
- Usar enums nativos (PHP 8.1+) en vez de constantes para conjuntos cerrados de valores.

## Manejo de errores
- Lanzar excepciones específicas de dominio, no genéricas (`InvalidArgumentException` < `InvalidOrderStatusException`).
- No silenciar errores con `@`. No catch vacíos.
- Documentar en PHPDoc las excepciones que un método puede lanzar.

## Estructura y organización
- Clases pequeñas con responsabilidad clara.
- Inyectar dependencias por constructor. Evitar `new` dentro de métodos (dificulta testing).
- Extraer lógica repetida a traits, helpers o servicios.
- Mantener métodos con menos de ~30 líneas. Si crece, refactorizar.

## Dependencias
- Mantener `composer.json` limpio: solo dependencias necesarias.
- Fijar versiones minor/patch en producción. Revisar actualizaciones con `composer outdated`.
- Ejecutar `composer audit` regularmente para vulnerabilidades.
- No instalar paquetes sin mantenedor activo o con CVEs conocidos.

## Testing
- Tests unitarios con PHPUnit o Pest.
- Code coverage razonable en áreas críticas (servicios, validaciones).
- Análisis estático con PHPStan o Psalm nivel medio-alto.

## Anti-patrones
- ❌ Variables globales o funciones sueltas fuera de helpers justificados.
- ❌ Lógica compleja en constructores.
- ❌ Clases con más de ~300 líneas sin justificación.
- ❌ Concatenación de SQL manual. Usar query builders o prepared statements.
- ❌ Outputs directos (`echo`, `print`) fuera de comandos CLI.
