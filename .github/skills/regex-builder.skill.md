---
name: "regex-builder"
description: "Construir, explicar y depurar expresiones regulares paso a paso"
triggers: ["regex", "expresión regular", "regexp", "pattern", "preg_match", "match", "replace"]
---

# Skill: Regex Builder

## Cuándo aplicar
- Cuando el usuario necesita crear una expresión regular.
- Cuando el usuario no entiende una regex existente.
- Cuando una regex no matchea lo esperado.

## Cómo construir una regex

### 1. Definir requisitos
Antes de escribir la regex, responder:
- ¿Qué debe matchear? (ejemplos concretos que SÍ deben matchear).
- ¿Qué NO debe matchear? (ejemplos concretos que NO deben matchear).
- ¿Match completo o parcial? (¿anclar con `^...$`?).
- ¿Hay variaciones? (mayúsculas, espacios opcionales, formatos alternativos).

### 2. Construir incrementalmente
No escribir la regex completa de golpe. Ir paso a paso:
1. Empezar con el patrón más simple que matchea algo.
2. Añadir restricciones una a una.
3. Testear con cada ejemplo (SÍ y NO) en cada paso.

### 3. Explicar la regex

**Formato de explicación:**
```
Regex: ^[A-Z]{2}\d{4}-[A-Z]$

^           → Inicio de la cadena
[A-Z]{2}   → Exactamente 2 letras mayúsculas
\d{4}      → Exactamente 4 dígitos
-           → Un guion literal
[A-Z]      → Exactamente 1 letra mayúscula
$           → Fin de la cadena

Matchea: "AB1234-C", "ZZ0000-A"
No matchea: "ab1234-C", "ABC1234-C", "AB123-C"
```

## Cheat sheet de elementos

### Caracteres
| Elemento | Significado | Ejemplo |
|----------|-------------|---------|
| `.` | Cualquier carácter excepto `\n` | `a.c` → "abc", "a1c" |
| `\d` | Dígito (0-9) | `\d{3}` → "123" |
| `\D` | No dígito | `\D+` → "abc" |
| `\w` | Alfanumérico + `_` | `\w+` → "hello_123" |
| `\W` | No alfanumérico | `\W` → "@", " " |
| `\s` | Espacio en blanco | `\s+` → " ", "\t" |
| `\S` | No espacio | `\S+` → "hello" |
| `\b` | Límite de palabra | `\bcat\b` → "cat" no "catch" |

### Cuantificadores
| Elemento | Significado | Greedy | Lazy |
|----------|-------------|--------|------|
| `*` | 0 o más | `.*` | `.*?` |
| `+` | 1 o más | `.+` | `.+?` |
| `?` | 0 o 1 | `a?` | — |
| `{n}` | Exactamente n | `\d{3}` | — |
| `{n,m}` | Entre n y m | `\d{2,4}` | `\d{2,4}?` |
| `{n,}` | n o más | `\d{2,}` | `\d{2,}?` |

### Grupos y alternación
| Elemento | Significado | Ejemplo |
|----------|-------------|---------|
| `(abc)` | Grupo de captura | `(\d+)-(\d+)` captura ambos números |
| `(?:abc)` | Grupo sin captura | `(?:https?)://` no captura el protocolo |
| `(?<name>abc)` | Grupo con nombre | `(?<year>\d{4})` |
| `a\|b` | Alternación (OR) | `cat\|dog` → "cat" o "dog" |

### Anclas y lookaround
| Elemento | Significado | Ejemplo |
|----------|-------------|---------|
| `^` | Inicio de línea | `^Hello` |
| `$` | Fin de línea | `world$` |
| `(?=abc)` | Lookahead positivo | `\d(?=px)` → "5" en "5px" |
| `(?!abc)` | Lookahead negativo | `\d(?!px)` → "5" en "5em" |
| `(?<=abc)` | Lookbehind positivo | `(?<=\$)\d+` → "100" en "$100" |
| `(?<!abc)` | Lookbehind negativo | `(?<!\$)\d+` → "100" en "€100" |

## Patrones comunes

```
# Email (simplificado, no RFC completo)
^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$

# Teléfono español
^(\+34|0034)?[6-9]\d{8}$

# URL
^https?:\/\/[^\s/$.?#].[^\s]*$

# Fecha DD/MM/YYYY
^(0[1-9]|[12]\d|3[01])\/(0[1-9]|1[0-2])\/\d{4}$

# IP v4
^(\d{1,3}\.){3}\d{1,3}$

# UUID v4
^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$

# Slug (kebab-case)
^[a-z0-9]+(?:-[a-z0-9]+)*$

# Password (min 8, mayúscula, minúscula, número)
^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$

# HTML tag
<([a-zA-Z][a-zA-Z0-9]*)\b[^>]*>(.*?)<\/\1>
```

## Errores comunes
- ❌ No escapar metacaracteres: `.` matchea TODO, usar `\.` para punto literal.
- ❌ Greedy por defecto: `.*` consume todo, usar `.*?` para lazy.
- ❌ Olvidar anclar: sin `^$` la regex matchea en cualquier parte del string.
- ❌ Regex para validar emails de forma estricta: usar la del lenguaje/framework, no reinventar.
- ❌ Regex para parsear HTML: usar un parser de DOM, no regex.

## Formato de respuesta

```markdown
### Regex
`{la expresión regular}`

### Explicación
{Desglose elemento por elemento}

### Matchea
- ✅ `{ejemplo 1}`
- ✅ `{ejemplo 2}`

### No matchea
- ❌ `{ejemplo 1}`
- ❌ `{ejemplo 2}`

### Implementación
{Código en el lenguaje del proyecto}
```

