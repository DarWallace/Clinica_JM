---
description: "Generar tests PHPUnit/Pest completos con estructura AAA, cobertura de lógica y edge cases"
---
# Crear Tests PHPUnit

## Antes de generar
1. Leer la clase/servicio a testear: métodos públicos, dependencias, excepciones.
2. Leer tests existentes del mismo módulo para detectar patrones (PHPUnit o Pest, factories, helpers).
3. Identificar el tipo de tests necesarios: unitarios, feature/integración, o ambos.
4. Leer las factories existentes para el modelo (si aplica).

## Tests a generar

### Tests unitarios (para servicios, actions, helpers, value objects)
- Un test por escenario, no por método.
- Naming descriptivo: `test_calculate_total_applies_discount_when_eligible()`.
- Patrón **Arrange → Act → Assert** con separación visual (línea en blanco entre fases).
- Mockear dependencias externas (APIs, mail, filesystem) con fakes de Laravel o mocks de PHPUnit.
- No mockear Eloquent: usar factories con `RefreshDatabase`.

### Tests feature (para endpoints, flujos HTTP)
- Testear el flujo completo: HTTP request → validación → lógica → response.
- Verificar: status code, estructura de respuesta, datos en DB, side effects (mail, events, jobs).
- Incluir tests con usuario autenticado y sin autenticar.
- Incluir tests de validación: campos requeridos, formatos inválidos, datos fuera de rango.

### Tests de regresión (para bugfixes)
- El test debe fallar SIN el fix aplicado y pasar CON el fix.
- Documentar en el test qué bug corrige: `// Regression: BL-0042 — order total was...`.

## Escenarios a cubrir
Para cada método/flujo:
1. **Happy path**: el caso normal, datos válidos, usuario autorizado.
2. **Validación**: datos inválidos, campos faltantes, formatos incorrectos.
3. **Autorización**: usuario sin permisos, usuario no autenticado.
4. **Edge cases**: datos vacíos, nulls, límites (0, MAX_INT), duplicados.
5. **Errores**: dependencia que falla, timeout, excepción de negocio.

## Formato de cada test
```php
public function test_description_of_expected_behavior(): void
{
    // Arrange
    $user = User::factory()->create();
    $data = ['field' => 'value'];

    // Act
    $result = $this->service->method($data);

    // Assert
    $this->assertEquals('expected', $result->field);
}
```

## Qué entregar
1. Código completo del archivo de tests.
2. Factories o seeders nuevos si se necesitan.
3. Lista de escenarios cubiertos (resumen).

## Instrucciones de referencia
Aplicar `tests-php.instructions.md`, `laravel.instructions.md`.
