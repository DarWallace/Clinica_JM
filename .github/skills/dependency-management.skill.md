---
name: "dependency-management"
description: "Evaluar, auditar, actualizar y gestionar dependencias de forma segura"
triggers: ["dependencia", "dependency", "paquete", "package", "npm install", "composer require", "actualizar", "upgrade", "audit", "vulnerabilidad"]
---

# Skill: Dependency Management

## Cuándo aplicar
- Al añadir una dependencia nueva.
- Al actualizar dependencias existentes.
- Cuando `npm audit` o `composer audit` reportan vulnerabilidades.
- Al evaluar si usar una librería o implementar internamente.

## Añadir una dependencia nueva

### Checklist de evaluación
Antes de instalar cualquier paquete, evaluar:

| Criterio | Cómo verificar | Red flag |
|----------|---------------|----------|
| **Mantenimiento** | Último commit, frecuencia de releases | >6 meses sin actividad |
| **Popularidad** | Descargas semanales, stars | <1000 descargas/semana |
| **Issues** | Ratio abiertos/cerrados, tiempo de respuesta | Muchos issues abiertos sin respuesta |
| **Seguridad** | CVEs conocidos, `npm audit` / `composer audit` | CVEs activos sin fix |
| **Tamaño** | Bundle size (bundlephobia.com), dependencias transitivas | >500KB para una utilidad simple |
| **Licencia** | Compatibilidad con el proyecto | GPL en proyecto comercial |
| **Alternativas** | ¿Se puede hacer sin dependencia? ¿Hay alternativa más ligera? | — |

### Decisión: ¿librería o implementación propia?

**Usar librería cuando:**
- La funcionalidad es compleja y bien resuelta (auth, crypto, date parsing, PDF).
- Mantenerla internamente costaría más que la dependencia.
- La librería es estándar del ecosistema (Lodash, Carbon, Axios).

**Implementar internamente cuando:**
- La funcionalidad es simple (<50 líneas).
- La librería añade dependencias transitivas pesadas.
- Solo necesitas 1 función de una librería de 200.
- El dominio es crítico y necesitas control total (lógica de negocio core).

## Actualizar dependencias

### Tipos de actualización
| Tipo | Semver | Riesgo | Acción |
|------|--------|--------|--------|
| **Patch** | 1.0.x | Bajo | Actualizar sin miedo, solo bugfixes |
| **Minor** | 1.x.0 | Medio | Actualizar, leer changelog, ejecutar tests |
| **Major** | x.0.0 | Alto | Leer guía de migración, planificar, testear extensamente |

### Proceso de actualización segura
1. **Verificar estado actual**: `composer outdated` / `npm outdated`.
2. **Leer changelogs** de las versiones nuevas.
3. **Actualizar en branch separado**.
4. **Ejecutar tests completos**.
5. **Verificar manualmente** las áreas afectadas.
6. **Deploy a staging** antes de producción.

### Actualización masiva
```bash
# PHP — actualizar todo lo minor/patch
composer update --with-all-dependencies

# Node — actualizar patch/minor dentro de rangos
npm update

# Node — ver qué está desactualizado
npm outdated

# Actualizar una dependencia específica
composer require vendor/package:^2.0
npm install package@latest
```

## Auditar vulnerabilidades

### Comandos
```bash
# PHP
composer audit

# Node
npm audit
npm audit fix          # fix automático (solo semver-compatible)
npm audit fix --force  # fix forzado (puede romper, revisar)

# Ambos — verificar en CI
composer audit --format=json
npm audit --json
```

### Cuando hay vulnerabilidades

| Severidad | Acción | Plazo |
|-----------|--------|-------|
| **Critical/High** | Actualizar inmediatamente o buscar workaround | Hoy |
| **Moderate** | Planificar actualización | Esta semana |
| **Low** | Evaluar si aplica al proyecto | Próximo sprint |

**Si no hay fix disponible:**
1. ¿La vulnerabilidad aplica a nuestro uso? (leer el advisory).
2. ¿Hay workaround? (configuración, validación extra).
3. ¿Hay alternativa al paquete?
4. Documentar el riesgo aceptado si se decide no actuar.

## Lockfiles

**Reglas:**
- `composer.lock` / `package-lock.json` **siempre** en control de versiones.
- Nunca editar lockfiles manualmente.
- En CI: usar `composer install` (no `update`) y `npm ci` (no `install`).
- Si hay conflicto en lockfile: regenerar, no mergear manualmente.

## Limpieza
```bash
# PHP — ver paquetes no usados
composer why vendor/package    # quién depende de este paquete

# Node — detectar dependencias no usadas
npx depcheck

# Eliminar paquetes huérfanos
composer remove vendor/package
npm uninstall package
```

## Formato de respuesta

```markdown
### Evaluación de dependencia: `{paquete}`
| Criterio | Estado | Detalle |
|----------|--------|---------|
| Mantenimiento | ✅/⚠️/❌ | {detalle} |
| Seguridad | ✅/⚠️/❌ | {detalle} |
| Tamaño | ✅/⚠️/❌ | {detalle} |
| Licencia | ✅/⚠️/❌ | {detalle} |

### Recomendación
{Instalar / Buscar alternativa / Implementar internamente}

### Alternativas evaluadas
| Paquete | Pros | Contras |
```

