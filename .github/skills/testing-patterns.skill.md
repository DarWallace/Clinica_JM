---
name: "testing-patterns"
description: "Patrones de testing: mocking, faking, factories, fixtures y estrategias por tipo de test"
triggers: ["mock", "fake", "stub", "factory", "fixture", "testear", "testing", "test double"]
---

# Skill: Testing Patterns

## Cuándo aplicar
- Al escribir tests que necesitan aislar dependencias.
- Al decidir si usar mock, fake, stub o spy.
- Al configurar factories y fixtures para datos de test.

## Test Doubles — Cuándo usar cada uno

| Double | Qué hace | Cuándo usar | Ejemplo |
|--------|----------|-------------|---------|
| **Stub** | Devuelve respuestas predefinidas | Cuando solo necesitas que la dependencia devuelva datos | API client que devuelve JSON fijo |
| **Mock** | Verifica que se llamó con los parámetros correctos | Cuando importa QUE se llamó, no solo el resultado | Verificar que se envió un email |
| **Fake** | Implementación funcional simplificada | Cuando necesitas comportamiento real pero sin infraestructura | InMemoryRepository en vez de Eloquent |
| **Spy** | Registra las llamadas para verificar después | Cuando quieres assert al final, no expectativas previas | Logger que registra qué se logueó |

### Regla de oro
> **Prefiere Fakes sobre Mocks.** Los fakes dan confianza real; los mocks solo verifican que llamaste al código correcto.

## Patrones por framework

### Laravel — Fakes nativos
```php
// Mail
Mail::fake();
// ... ejecutar acción ...
Mail::assertSent(OrderConfirmation::class, function ($mail) use ($order) {
    return $mail->order->id === $order->id;
});
Mail::assertNotSent(RefundNotification::class);

// Notification
Notification::fake();
$user->notify(new OrderShipped($order));
Notification::assertSentTo($user, OrderShipped::class);

// Event
Event::fake([OrderCreated::class]);
// ... crear order ...
Event::assertDispatched(OrderCreated::class);

// Queue / Job
Queue::fake();
dispatch(new ProcessPayment($order));
Queue::assertPushed(ProcessPayment::class);

// Storage
Storage::fake('s3');
// ... subir archivo ...
Storage::disk('s3')->assertExists('invoices/order-123.pdf');

// HTTP Client
Http::fake([
    'api.stripe.com/*' => Http::response(['status' => 'ok'], 200),
    'api.broken.com/*' => Http::response(null, 500),
]);
```

### PHPUnit — Mocks manuales
```php
// Mock básico
$gateway = $this->createMock(PaymentGateway::class);
$gateway->method('charge')->willReturn(new PaymentResult(true));
$service = new OrderService($gateway);

// Mock con verificación de parámetros
$gateway->expects($this->once())
    ->method('charge')
    ->with($this->equalTo(100.00), $this->equalTo('USD'))
    ->willReturn(new PaymentResult(true));

// Mock que lanza excepción
$gateway->method('charge')->willThrowException(new PaymentFailedException());
```

### JavaScript — Jest
```javascript
// Mock de módulo
jest.mock('./api', () => ({
  fetchUsers: jest.fn().mockResolvedValue([{ id: 1, name: 'Test' }]),
}));

// Mock de función
const callback = jest.fn();
processItems(items, callback);
expect(callback).toHaveBeenCalledTimes(3);
expect(callback).toHaveBeenCalledWith(expect.objectContaining({ id: 1 }));

// Spy
const spy = jest.spyOn(console, 'error').mockImplementation();
// ... código que loguea error ...
expect(spy).toHaveBeenCalledWith(expect.stringContaining('failed'));
spy.mockRestore();

// Timer mocks
jest.useFakeTimers();
startCountdown();
jest.advanceTimersByTime(3000);
expect(getDisplay()).toBe('0:00');
```

## Factories — Datos de test

### Laravel Factories
```php
// Definir
class OrderFactory extends Factory {
    public function definition(): array {
        return [
            'user_id' => User::factory(),
            'status' => 'pending',
            'total' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }

    // States para variantes
    public function confirmed(): static {
        return $this->state(['status' => 'confirmed']);
    }

    public function withItems(int $count = 3): static {
        return $this->has(OrderItem::factory()->count($count));
    }
}

// Usar en tests
$order = Order::factory()->confirmed()->withItems(5)->create();
$orders = Order::factory()->count(10)->create();
```

### Reglas de factories
> Reglas detalladas de factories (defaults válidos, states, relaciones, datos explícitos) en `tests-php.instructions.md`.

```php
// ❌ Mal — datos irrelevantes al test hardcodeados
$user = User::factory()->create([
    'name' => 'John',
    'email' => 'john@test.com',
    'phone' => '123456',
    'role' => 'admin',  // solo esto importa para el test
]);

// ✅ Bien — solo lo relevante
$user = User::factory()->create(['role' => 'admin']);
```

## Patrones de organización

### Setup compartido
```php
class OrderServiceTest extends TestCase {
    private OrderService $service;
    private User $user;

    protected function setUp(): void {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->service = app(OrderService::class);
    }
}
```

### Data Providers (tests parametrizados)
```php
/**
 * @dataProvider invalidOrderData
 */
public function test_create_order_validates_input(array $data, string $expectedError): void {
    $response = $this->actingAs($this->user)->post('/api/orders', $data);
    $response->assertUnprocessable();
    $response->assertJsonValidationErrors($expectedError);
}

public static function invalidOrderData(): array {
    return [
        'sin items' => [['items' => []], 'items'],
        'sin dirección' => [['items' => [['id' => 1]]], 'address'],
        'cantidad negativa' => [['items' => [['id' => 1, 'qty' => -1]]], 'items.0.qty'],
    ];
}
```

## Anti-patrones de testing

> Lista completa de anti-patrones en `tests-php.instructions.md`. Regla adicional de esta skill:

| Anti-patrón | Problema | Solución |
|-------------|----------|----------|
| **Datos mágicos** | No se entiende por qué se usa ese valor | Datos explícitos con nombres descriptivos |
| **Test sin nombre claro** | `test1()`, `testOrder()` | `test_cancel_order_in_shipped_status_throws_exception()` |

