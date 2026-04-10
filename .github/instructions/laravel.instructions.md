---
applyTo: "app/**"
description: "Reglas para desarrollo en Laravel siguiendo buenas prácticas"
---

# LARAVEL — RECOMENDACIONES OPERATIVAS (ALTO NIVEL)

Objetivo: orientar decisiones técnicas y prácticas de ingeniería que garanticen mantenibilidad, seguridad, testabilidad y trazabilidad en proyectos Laravel.

## Estructura y organización
- Mantener controladores delgados; delegar lógica a Services, Actions, Jobs o Repositories.
- Organizar código por contexto o módulos (Domain/Module) cuando el dominio crece.
- Colocar servicios en `app/Services`, contratos en `App\Contracts` y DTOs en `app/DTOs` cuando aporte claridad.
- Respeta convenciones PSR-4 y usa nombres explícitos para clases y métodos.

## Eloquent y modelo de datos
- Aprovecha Eloquent (scopes, casts, accessors/mutators) pero evita lógica de negocio compleja en modelos.
- Prefiere consultas explícitas y repositorios/queries objects para consultas complejas o con joins.
- Usa casts y custom casts para normalizar datos y proteger la capa de persistencia.

## Validación y seguridad
- Validación centralizada con FormRequest para casos complejos; `prepareForValidation()` para normalizar datos.
- Autorizar con Policies / Gates — no mezclar autorización en controladores.
- Nunca confiar en entrada del cliente: sanitiza y valida todo. Sigue OWASP Top10 como guía.
- Maneja secrets fuera del repo (env, volúmenes secretos, secret manager).

## Autenticación y autorización
- Usa middleware para control de acceso reutilizable.
- Para APIs, emplea tokens con expiración o OAuth2 / Laravel Sanctum / Passport según necesidad.
- Logging de eventos críticos (login, cambios de permisos, creación/borrado de recursos).

## Jobs, Colas y Tareas asíncronas
- Externaliza trabajo pesado a Jobs/Queues; asegúrate de idempotencia y manejo de reintentos.
- Configura monitoreo de colas (horizon/metrics) y alertas de fallos.

## Testing
- Prioriza tests unitarios para lógica de dominio y tests de integración/feature para flujos importantes.
- Usa factories y seeders enfocados en escenarios reproducibles. Mantén base de datos en memoria o contenedores para CI.
- Cobertura razonable en áreas críticas; cada PR debe añadir tests que cubran la funcionalidad nueva.

## Migrations y datos
- Evita cambios destructivos sin migración y rollback plan claro.
- Versiona migraciones y mantenerlas idempotentes cuando sea posible.

## Calidad y CI/CD
- Pipeline mínimo: lint (pint), static analysis (phpstan/psalm), tests (phpunit/pest), composer audit/dependency-check.
- Ejecuta tests en entornos aislados y cachea dependencias para velocidad.
- Automatiza despliegues con estrategia de rollback y migraciones controladas.

## Observabilidad y trazabilidad
- Registra operaciones relevantes y errores con contexto (request id, user id).
- Añade trazabilidad de cambios en `.planning/features/` o sistema de auditoría para acciones críticas.

## Performance y escalabilidad
- Cachea consultas costosas y fragmentos cuando aplique (toggleable por entorno).
- Optimiza N+1 con `with()` y revisa consultas en PRs que introduzcan nuevos endpoints.

## Convenciones y estilo
- Sigue PSR-12 en formato y estructura. Mantén tests, docs y convenciones claras en `Docs/`.
- Cada PR debe contener descripción, tests y comandos para reproducir cambios localmente.

(Orientación breve focalizada en prácticas de arquitectura, testing, seguridad, CI/CD y trazabilidad.)