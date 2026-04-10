---
applyTo: "**/architecture.md"
description: "Reglas para documentación arquitectónica"
---

# ARCHITECTURE — REGLAS

Objetivo: documentar las decisiones estructurales del proyecto para que cualquier desarrollador entienda el "por qué" del diseño.

## Cuándo documentar arquitectura
- Al crear un módulo o servicio nuevo.
- Al tomar una decisión estructural que afecte múltiples partes del sistema.
- Al integrar un servicio externo con contrato complejo.
- Al cambiar patrones existentes (ej: migrar de monolito a módulos).

## Contenido del documento de arquitectura

### 1. Visión general
- Descripción del sistema/módulo en 3-5 líneas.
- Diagrama de alto nivel (capas, servicios, flujos principales).

### 2. Capas y responsabilidades
- Definir cada capa y su responsabilidad: presentación, aplicación, dominio, infraestructura.
- Reglas de dependencia entre capas (qué puede llamar a qué).
- Puntos de entrada al sistema (HTTP, CLI, Queue, Scheduler).

### 3. Decisiones arquitectónicas (ADR)
Un ADR por decisión relevante con este formato:

**ADR-XXXX: [Título]**
- **Contexto**: qué problema/necesidad motivó la decisión.
- **Decisión**: qué se decidió hacer.
- **Alternativas**: qué otras opciones se consideraron y por qué se descartaron.
- **Consecuencias**: trade-offs, riesgos, limitaciones conocidas.
- **Estado**: proposed | accepted | deprecated | superseded by ADR-YYYY.

### 4. Puntos de integración
- APIs externas: URL, autenticación, payloads, rate limits, contacto.
- Servicios internos: protocolos, contratos, healthchecks.
- Bases de datos: motor, esquema principal, estrategia de migraciones.

### 5. Requisitos no funcionales
- Performance: SLAs, latencias esperadas, throughput.
- Escalabilidad: estrategia (horizontal/vertical), bottlenecks conocidos.
- Seguridad: modelo de autenticación/autorización, datos sensibles.
- Disponibilidad: SLA objetivo, estrategia de failover.

### 6. Diagramas
- Incluir en formato editable: Mermaid (preferido), drawio, o ASCII art.
- Exportar a PNG/SVG y colocar junto al fuente editable.
- Tipos útiles: diagrama de componentes, diagrama de secuencia, diagrama de datos.

### 7. Riesgos y consideraciones
- Riesgos técnicos identificados y mitigaciones.
- Deuda técnica conocida y plan (si existe).
- Dependencias críticas y plan de contingencia.

## Mantenimiento
- Actualizar cuando cambie el diseño. Referenciar la PR que introdujo el cambio.
- Marcar ADRs obsoletos como `deprecated` o `superseded`.
- Revisar la arquitectura documentada al menos una vez por quarter o al inicio de cada fase de proyecto.
