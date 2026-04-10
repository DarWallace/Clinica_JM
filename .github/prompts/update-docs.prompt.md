---
mode: "agent"
description: "Actualizar catálogos y conteos en GUIA-DE-USO.md y DOCUMENTO-EJECUTIVO.md leyendo los ficheros reales"
---

# Actualizar Documentación del Sistema

Sincroniza los catálogos, conteos y listados de `GUIA-DE-USO.md` y `DOCUMENTO-EJECUTIVO.md` con el estado real de la carpeta `.github/`.

## Instrucciones

### 1. Inventario real del sistema

Leer el contenido de las carpetas y contar ficheros reales:

```
.github/
├── instructions/*.instructions.md  → contar
├── agents/*.agent.md               → contar (excluir README.md)
├── skills/*.skill.md               → contar (excluir README.md)
├── prompts/*.prompt.md             → contar
```

### 2. Actualizar GUIA-DE-USO.md

- **§4 (Estructura)**: actualizar el árbol de directorios con los ficheros reales.
- **§7 (Agentes)**: actualizar la tabla de agentes con los que realmente existen en `agents/`. Leer la cabecera `description` de cada `.agent.md` para generar la descripción.
- **§8 (Prompts)**: actualizar la tabla de prompts con los que realmente existen en `prompts/`. Leer la cabecera `description` de cada `.prompt.md`.
- **§9 (Skills)**: actualizar la tabla de skills con las que realmente existen en `skills/`. Leer la cabecera `description` y `triggers` de cada `.skill.md`.
- **§6 (Instructions)**: verificar que las tablas de instrucciones por categoría son correctas.

### 3. Actualizar DOCUMENTO-EJECUTIVO.md

- **§1 Datos clave**: actualizar conteos (agentes, instrucciones, skills, prompts).
- **§4 Componentes**: actualizar el árbol con conteos correctos.
- **§5 Catálogo de agentes**: verificar que la tabla incluye todos los agentes.

### 4. Verificación

Después de actualizar, verificar:
- [ ] Los conteos en ambos documentos coinciden entre sí.
- [ ] Los conteos coinciden con los ficheros reales en disco.
- [ ] No hay ficheros listados que no existan.
- [ ] No hay ficheros que existan pero no estén listados.

## Output

```
📋 Documentación sincronizada:
- Agents: {N} (antes: {M})
- Instructions: {N} (antes: {M})
- Skills: {N} (antes: {M})
- Prompts: {N} (antes: {M})
- Ficheros actualizados: GUIA-DE-USO.md, DOCUMENTO-EJECUTIVO.md
```

