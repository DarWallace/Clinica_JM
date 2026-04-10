---
applyTo: "tests/**"
description: "Buenas prácticas para testing PHP (PHPUnit/Pest)"
---

# TESTS PHP — REGLAS

## Principio: tests que documentan comportamiento y protegen contra regresiones

## Estructura y naming
- Naming descriptivo: `test_user_can_create_order_with_valid_data()` o `it('creates order with valid data')` en Pest.
- Un fichero de test por clase/feature testeada. Ubicación espejo: `tests/Unit/Services/OrderServiceTest.php` para `app/Services/OrderService.php`.
- Agrupar con `describe` o métodos de grupo cuando haya muchos escenarios.

## Patrón obligatorio: Arrange → Act → Assert
1. **Arrange**: preparar datos, mocks, estado inicial.
2. **Act**: ejecutar la operación a testear.
3. **Assert**: verificar resultado, estado, side effects.
- Separar visualmente las 3 fases con línea en blanco.

## Tests unitarios
- Testear lógica de servicios, actions, helpers, value objects.
- Mockear dependencias externas (APIs, mail, filesystem, colas).
- No mockear Eloquent en tests unitarios: usar base de datos en memoria o factories.
- Cada test debe ser atómico: no depender del orden de ejecución ni de otros tests.

## Tests feature/integración
- Testear flujos completos: HTTP request → response, incluyendo validación, auth y side effects.
- Usar `RefreshDatabase` o `LazilyRefreshDatabase` para aislamiento.
- Verificar código de respuesta, estructura JSON, redirecciones y mensajes de sesión.
- Incluir tests para usuarios autenticados y no autenticados (auth boundary).

## Tests de regresión
- Cada bugfix DEBE incluir un test que reproduzca el bug (debe fallar sin el fix).
- Documentar en el test qué bug se corrige (referencia a issue/ticket).

## Factories y datos
- Usar factories con estados (`->state()`) para escenarios específicos.
- No hardcodear IDs ni datos que dependan del orden de inserción.
- Seeders solo para datos base (roles, permisos, config). No para tests.

## Mocks y fakes
- Preferir fakes de Laravel (`Mail::fake()`, `Event::fake()`, `Queue::fake()`) sobre mocks manuales.
- Mockear solo lo necesario: si puedes testear sin mock, hazlo.
- Verificar que los mocks se llaman con los argumentos esperados (`assertSent`, `assertDispatched`).

## Cobertura
- Priorizar cobertura en: servicios, validaciones, autenticación/autorización, integraciones.
- No buscar 100% de cobertura: buscar cobertura de los flujos críticos y edge cases.
- Cada PR debe añadir tests para la funcionalidad nueva o modificada.

## Anti-patrones
- ❌ Tests que dependen de datos de otros tests o del orden de ejecución.
- ❌ Tests que verifican implementación interna (qué método se llamó) en vez de comportamiento.
- ❌ Tests con múltiples asserts no relacionados (testear una cosa por test).
- ❌ Tests que requieren servicios externos reales (APIs, SMTP, S3) sin mock.
- ❌ Tests sin nombre descriptivo o con nombres genéricos (`testMethod1`).
