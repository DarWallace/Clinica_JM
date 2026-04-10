---
description: "Code review multi-stack con checklists por tipo de archivo y capa"
---

# Code Review — Checklists Multi-Stack

## Variables
- `{scope}`: Archivos, PR o módulo a revisar.

## Antes de revisar
1. Leer los archivos completos del `{scope}` y sus dependencias directas.
2. Leer los tests asociados (si existen).
3. Detectar el stack del proyecto (composer.json, package.json, etc.).
4. Identificar el propósito del cambio (feature, bugfix, refactor).

---

## Checklist universal (aplica a cualquier stack)

### 🔴 Crítico (bloquea aprobación)

#### Seguridad
Aplicar el checklist de seguridad del global §6:
- [ ] Input validado, SQL seguro, output escapado (XSS).
- [ ] Auth/authz verificados, CSRF protegido.
- [ ] Secrets fuera del código, datos sensibles no expuestos.
- [ ] Archivos subidos controlados (MIME, tamaño, ruta).

#### Correctitud
- [ ] Lógica de negocio correcta — ¿hace lo que dice que hace?
- [ ] Sin race conditions o estados inconsistentes.
- [ ] Sin pérdida de datos (soft delete vs hard delete, transacciones).
- [ ] Error handling adecuado (no excepciones silenciadas, no catch genéricos vacíos).
- [ ] Breaking changes identificados y documentados.

### 🟡 Importante (debe corregirse)

#### Performance
- [ ] Sin N+1 queries (usar eager loading, joins, batch loading).
- [ ] Sin queries dentro de loops.
- [ ] Sin operaciones costosas (I/O, APIs) dentro de loops.
- [ ] Cache utilizado donde tiene sentido (queries repetitivos, datos estáticos).
- [ ] Sin cargar datasets completos cuando solo se necesitan subsets.

#### Testing
- [ ] Lógica nueva tiene tests que la cubren.
- [ ] Bugfix incluye test de regresión.
- [ ] Tests existentes siguen pasando (no regresión).
- [ ] Tests son independientes y atómicos.

#### Mantenibilidad
- [ ] Funciones/métodos ≤50 líneas (si no, candidato a extraer).
- [ ] Clases con responsabilidad única (o pocas y cohesivas).
- [ ] Sin dependencias circulares.
- [ ] Naming descriptivo (variables, funciones, clases).
- [ ] Sin código muerto ni comentado sin razón.

### 🔵 Sugerencias (mejora opcional)
- [ ] Uso idiomático del lenguaje/framework.
- [ ] Código duplicado que podría extraerse.
- [ ] Documentación en interfaces públicas complejas.
- [ ] Tipado estricto donde el lenguaje lo permita.

---

## Checklists específicos por stack

### PHP (Laravel / CI4 / Vanilla)
- [ ] `declare(strict_types=1);` en cada archivo.
- [ ] Type hints en parámetros, propiedades y returns.
- [ ] PSR-12 / PSR-4 respetado.
- [ ] Validación en FormRequest / clase de validación (no inline en controller).
- [ ] Controllers delgados — lógica de negocio en Services/Actions.
- [ ] Migraciones reversibles (si aplica).
- [ ] No se usa `dd()`, `dump()`, `var_dump()` en código final.
- [ ] Mass assignment protegido (`$fillable` / `$guarded`).

### JavaScript / TypeScript (React / Vue / Node)
- [ ] Sin `var` — solo `const`/`let`.
- [ ] Sin `any` innecesario (TypeScript).
- [ ] Event listeners limpiados en unmount/destroy.
- [ ] Async/await con manejo de errores (try/catch o .catch).
- [ ] Sin `console.log` en código de producción.
- [ ] Componentes con responsabilidad clara (no god components).
- [ ] Props/state tipados (PropTypes, TypeScript, o equivalente).
- [ ] Sin manipulación directa del DOM en frameworks reactivos.

### CSS / Estilos
- [ ] Sin `!important` salvo extrema necesidad.
- [ ] Sin colores hardcoded — usar variables/tokens CSS.
- [ ] Responsive considerado (mobile-first o media queries).
- [ ] Naming consistente (BEM, utility-first, o convención del proyecto).

### SQL / Migraciones
- [ ] Migraciones con `up()` y `down()` correctos.
- [ ] Índices en columnas de búsqueda/JOIN/WHERE frecuentes.
- [ ] Sin migraciones destructivas sin plan de rollback.
- [ ] Tipos de datos correctos (no varchar para fechas, no text para IDs).

### Python
- [ ] Type hints en funciones públicas.
- [ ] Sin `except:` genérico — capturar excepciones específicas.
- [ ] PEP 8 respetado.
- [ ] Virtual environment configurado.
- [ ] Sin secrets hardcoded.

---

## Antipatrones comunes (multi-stack)

### ❌ Lógica de negocio en la capa equivocada

| Dónde está | Dónde debería estar |
|------------|---------------------|
| Controller / Route handler | Service / Action / Use Case |
| Vista / Template | Controller (preparar datos) |
| Modelo / Entity | Service (reglas de negocio complejas) |

### ❌ Validación inline en controllers
**Problema**: reglas no reutilizables, difícil de testear.
**Solución**: extraer a FormRequest (Laravel), clase de validación, schema (Zod/Yup), o middleware.

### ❌ N+1 Queries
**Problema**: 1 query + N queries en loop.
**Solución**: eager loading (`with()` en Laravel, `addSelect` en Doctrine, `include` en Sequelize, joins en SQL).

### ❌ Catch genérico vacío
```
// ❌ Malo: error silenciado
try { ... } catch (e) { }

// ✅ Correcto: manejar o propagar
try { ... } catch (e) { logger.error(e); throw; }
```

### ❌ Secrets en código fuente
**Problema**: API keys, passwords, tokens hardcoded.
**Solución**: `.env`, secret manager, variables de entorno del CI/CD.

---

## Formato de salida

```markdown
## Code Review — {scope}

### Resumen
{1-3 líneas: qué hace el cambio, impresión general}

### 🔴 Crítico
- **[Archivo:Línea]** {Problema}
  → **Fix**: {solución concreta}

### 🟡 Importante
- **[Archivo:Línea]** {Problema}
  → **Fix**: {solución}

### 🔵 Sugerencias
- {Mejora opcional}

### Tests
{Estado de cobertura, tests faltantes}

### Seguridad
{Puntos del checklist OWASP verificados}

### Veredicto
✅ Aprobado | ⚠️ Aprobado con cambios menores | ❌ Requiere cambios
```

## Instrucciones de referencia
- `reviewer.agent.md` (para activar el rol de reviewer completo)
- §6 del global (seguridad OWASP)
- `*.instructions.md` del stack detectado

