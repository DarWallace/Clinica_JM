---
name: "git-conflict-resolution"
description: "Resolver conflictos de merge de forma segura y sin pérdida de código"
triggers: ["conflicto", "merge conflict", "conflict", "rebase", "merge", "cherry-pick"]
---

# Skill: Git Conflict Resolution

## Cuándo aplicar
- Cuando el usuario tiene conflictos de merge/rebase.
- Cuando necesita combinar cambios de dos ramas.
- Cuando un cherry-pick genera conflictos.

## Proceso de resolución

### 1. Entender el contexto
Antes de resolver, responder:
- ¿Qué ramas se están mergeando? (feature → main, main → feature, etc.)
- ¿Qué cambió en cada rama? (leer los dos lados del conflicto).
- ¿Cuál es la intención de cada cambio? (no solo las líneas — el propósito).

### 2. Leer el conflicto
```
<<<<<<< HEAD (rama actual)
Código de la rama actual
=======
Código de la rama entrante
>>>>>>> feature-branch (rama que se mergea)
```

### 3. Clasificar el tipo de conflicto

| Tipo | Descripción | Estrategia |
|------|-------------|------------|
| **Ambos modifican lo mismo** | Misma línea/bloque cambiado en ambas ramas | Combinar manualmente la intención de ambos |
| **Uno añade, otro modifica** | Una rama añadió código donde la otra modificó | Normalmente mantener ambos cambios |
| **Refactor vs feature** | Una rama refactorizó, la otra añadió feature | Aplicar la feature sobre el código refactorizado |
| **Borrado vs modificación** | Una rama borró código que la otra modificó | Decidir según la intención (¿el borrado era correcto?) |
| **Imports/config** | Conflictos en archivos auto-generados | Regenerar el archivo (composer.lock, package-lock.json) |

### 4. Resolver

**Reglas de oro:**
- **No perder código de ninguna rama** salvo que sea intencionalmente obsoleto.
- **Mantener la funcionalidad de ambas ramas** en el resultado.
- **Ejecutar tests después** de resolver cada conflicto.
- **No resolver apresuradamente**: un conflicto mal resuelto es peor que un conflicto sin resolver.

**Para archivos de lock (composer.lock, package-lock.json):**
```bash
# Aceptar la versión de la rama actual y regenerar
git checkout --ours composer.lock
composer install
git add composer.lock
```

**Para migraciones con conflicto de timestamp:**
- No combinar migraciones: mantener ambas con timestamps distintos.
- Verificar que no hay operaciones contradictorias en la misma tabla.

### 5. Verificar
Después de resolver todos los conflictos:
1. `git diff --check` — verificar que no quedan markers de conflicto.
2. Ejecutar tests completos.
3. Verificar manualmente los archivos conflictivos.
4. Compilar/ejecutar el proyecto.

## Comandos útiles
```bash
# Ver archivos con conflicto
git diff --name-only --diff-filter=U

# Ver el conflicto en detalle (3-way)
git diff --merge

# Abortar merge si hay problemas
git merge --abort

# Aceptar un lado completo para un archivo
git checkout --ours path/to/file     # mantener rama actual
git checkout --theirs path/to/file   # mantener rama entrante

# Después de resolver
git add path/to/resolved/file
git commit  # o git rebase --continue
```

## Errores comunes
- ❌ Aceptar un lado ciegamente (`--ours` o `--theirs` en todo) sin leer los cambios.
- ❌ Dejar markers de conflicto (`<<<<<<<`) en el código.
- ❌ No ejecutar tests después de resolver.
- ❌ Resolver conflictos en archivos de lock manualmente (regenerar siempre).
- ❌ Combinar migraciones que tocan la misma tabla sin verificar compatibilidad.

