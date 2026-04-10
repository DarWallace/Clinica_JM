---
name: "error-diagnosis"
description: "Diagnóstico sistemático de errores a partir de logs, stack traces y síntomas"
triggers: ["error", "exception", "stack trace", "500", "fatal", "crash", "falla", "no funciona", "roto"]
---

# Skill: Error Diagnosis

## Cuándo aplicar
- Cuando el usuario comparte un error, stack trace o log.
- Cuando algo "no funciona" sin más contexto.
- Cuando un test falla sin razón obvia.

## Proceso de diagnóstico

### 1. Recopilar información
Si el usuario solo dice "no funciona", pedir (pero proponer valores por defecto):
- **Qué error** (mensaje, código, stack trace).
- **Cuándo empezó** (¿siempre? ¿desde cuándo?).
- **Qué se intentó hacer** (acción del usuario).
- **Entorno** (local/staging/prod, SO, versión).

### 2. Leer el stack trace (si hay)
Técnica de lectura bottom-up:
1. **Último frame del stack** = donde se lanzó la excepción.
2. **Subir buscando código propio** (ignorar frames de framework/vendor).
3. **Primer frame de código propio** = probable punto de fallo.
4. Leer ese archivo y método para entender el contexto.

### 3. Clasificar el error

| Categoría | Señales | Dónde buscar |
|-----------|---------|-------------|
| **Sintaxis/compilación** | Parse error, unexpected token | Archivo y línea exacta del error |
| **Tipo/null** | TypeError, NullPointerException, "on null" | Variable que se asume existente pero no lo es |
| **Validación** | 422, validation failed | FormRequest, reglas de validación |
| **Auth** | 401, 403, "unauthenticated" | Middleware, guards, policies |
| **DB** | QueryException, SQLSTATE | Migración, esquema, query |
| **Dependencia** | Class not found, module not found | Autoload, imports, composer/npm install |
| **Config** | ENV not set, connection refused | .env, config/, servicios externos |
| **Lógica** | Resultado incorrecto sin error | Condiciones, cálculos, flujo de datos |
| **Concurrencia** | Intermitente, race condition | Locks, transacciones, colas |

### 4. Patrones comunes y soluciones rápidas

**"Class not found"**
```bash
composer dump-autoload    # PHP
npm install               # Node
```

**"SQLSTATE table/column not found"**
```bash
php artisan migrate       # Migraciones pendientes
php artisan migrate:fresh --seed  # Reset completo (solo local)
```

**"Connection refused" (Redis/DB)**
- Verificar que el servicio está corriendo.
- Verificar `.env`: host, puerto, credenciales.
- Docker: verificar que los contenedores están up y en la misma red.

**"CSRF token mismatch"**
- Verificar `@csrf` en formularios Blade.
- Verificar que la sesión no expiró.
- En SPA: verificar que se envía el token X-XSRF-TOKEN.

**"419 Page Expired"**
- Sesión expirada. Regenerar con `Session::regenerate()`.
- Verificar config de sesión (driver, lifetime).

**Test falla con "Expected 200 got 302/500"**
- 302: falta autenticación en el test (`actingAs($user)`).
- 500: leer el log (`storage/logs/laravel.log`) para ver el error real.

### 5. Si no es un patrón conocido
1. Aislar: ¿es reproducible? ¿En qué condiciones?
2. Reducir: eliminar variables hasta encontrar la causa mínima.
3. Comparar: ¿funcionaba antes? ¿Qué cambió? (`git log`, `git diff`).
4. Buscar: ¿hay issues abiertos en el repo del framework/librería?

## Formato de respuesta

```markdown
### Error identificado
{Tipo de error y descripción}

### Causa
{Qué lo produce — concreto}

### Solución
{Pasos o código para resolver}

### Prevención
{Cómo evitar que vuelva a ocurrir — si aplica}
```

## Reglas
- Siempre leer el código del punto de fallo antes de proponer solución.
- No proponer "reinstalar todo" como primera opción.
- Si hay múltiples errores en cascada, resolver el primero (suele desbloquear el resto).
- Distinguir entre el error real y los síntomas derivados.

