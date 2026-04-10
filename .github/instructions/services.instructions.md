---
applyTo: "app/Services/**"
description: "Reglas para servicios y lógica de negocio"
---

# SERVICES — REGLAS

## Principio: el servicio es donde vive la lógica de negocio
Los servicios orquestan operaciones, aplican reglas de dominio y coordinan modelos, repositorios e integraciones externas.

## Diseño
- **Stateless**: no almacenar estado entre llamadas. Cada método recibe lo que necesita.
- **Inyección de dependencias** vía constructor. Registrar en Service Provider si se necesita binding a interfaz.
- **Responsabilidad única**: un servicio por contexto de negocio (`OrderService`, `PaymentService`). Si crece, dividir.
- **Métodos públicos** que representan operaciones de negocio: `createOrder()`, `processPayment()`, `cancelSubscription()`.
- **Métodos privados** para lógica auxiliar interna del servicio.

## Naming
- `{Dominio}Service`: `UserService`, `InvoiceService`, `NotificationService`.
- Ubicación: `app/Services/` o `app/Services/{Dominio}/` si hay muchos.
- Métodos: verbos que describan la acción: `create`, `update`, `cancel`, `process`, `calculate`, `sync`.

## Parámetros y retorno
- Preferir DTOs o Value Objects como parámetros cuando hay más de 3 argumentos.
- Tipar parámetros y retorno explícitamente (PHP 8+).
- Devolver el resultado de la operación, no void (salvo operaciones fire-and-forget).
- Lanzar excepciones de dominio para errores de negocio (`InsufficientBalanceException`, `OrderAlreadyCancelledException`).

## Transacciones
- Envolver operaciones que tocan múltiples tablas en `DB::transaction()`.
- No anidar transacciones innecesariamente.
- Colocar la lógica de dispatch de eventos/jobs DESPUÉS del commit de la transacción (`afterCommit`).

## Testing
- Cada servicio debe tener tests unitarios.
- Mockear dependencias externas (APIs, mail, filesystem), no modelos Eloquent en tests unitarios.
- Testear los casos happy path y los edge cases / errores de negocio.
- En bugfix: test de regresión que reproduzca el bug antes de corregirlo.

## Anti-patrones
- ❌ Servicios que son un wrapper sin valor (solo llaman al modelo sin añadir lógica).
- ❌ Servicios con estado mutable (propiedades que cambian entre llamadas).
- ❌ God services con 20+ métodos: dividir por responsabilidad.
- ❌ Catch genérico de excepciones dentro del servicio (dejar que burbujeen al controlador).
- ❌ Dependencias circulares entre servicios.
