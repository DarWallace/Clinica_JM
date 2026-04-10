---
name: "refactoring-patterns"
description: "Catálogo de técnicas de refactoring con cuándo usar cada una y código de ejemplo"
triggers: ["refactor", "refactoring", "limpiar", "clean", "simplificar", "extraer", "dividir", "SOLID"]
---

# Skill: Refactoring Patterns

## Cuándo aplicar
- Cuando hay código complejo que necesita simplificarse.
- Al detectar code smells en una revisión.
- Cuando el usuario pide "limpiar" o "mejorar" código.

## Detección de code smells

### Olores que indican necesidad de refactor

| Smell | Señal | Patrón de refactor |
|-------|-------|-------------------|
| **Método largo** | >30 líneas | Extract Method |
| **Clase grande** | >300 líneas, >5 responsabilidades | Extract Class |
| **Lista de parámetros larga** | >3-4 parámetros | Introduce Parameter Object / DTO |
| **Condicional compleja** | if/else anidado >3 niveles | Guard Clauses, Strategy, Polymorphism |
| **Código duplicado** | Mismo bloque en 2+ lugares | Extract Method, Extract Trait/Mixin |
| **Feature envy** | Método usa más datos de otra clase que de la propia | Move Method |
| **Data clumps** | Mismos 3+ campos viajan juntos | Extract Class / Value Object |
| **Switch/case largo** | Switch con >5 cases sobre tipo | Polymorphism, Strategy |
| **Nombres crípticos** | `$d`, `process()`, `handle2()` | Rename |
| **Comentarios explicativos** | Comentario que explica qué hace el código | El código debería auto-explicarse → Rename + Extract |

## Patrones de refactoring

### 1. Guard Clauses (Reemplazar anidamiento)
```php
// ❌ Antes — anidamiento profundo
function processOrder($order) {
    if ($order !== null) {
        if ($order->isValid()) {
            if ($order->hasItems()) {
                // lógica real aquí
                return $this->calculate($order);
            }
        }
    }
    return null;
}

// ✅ Después — guard clauses
function processOrder($order) {
    if ($order === null) return null;
    if (!$order->isValid()) return null;
    if (!$order->hasItems()) return null;

    return $this->calculate($order);
}
```
**Cuándo:** condicionales anidadas que protegen la lógica principal.

### 2. Extract Method
```php
// ❌ Antes — método largo con varias responsabilidades
function createInvoice($order) {
    // calcular subtotal
    $subtotal = 0;
    foreach ($order->items as $item) {
        $subtotal += $item->price * $item->quantity;
    }

    // aplicar descuento
    $discount = $subtotal > 1000 ? $subtotal * 0.1 : 0;

    // calcular impuestos
    $tax = ($subtotal - $discount) * 0.21;

    return new Invoice($subtotal, $discount, $tax);
}

// ✅ Después — métodos pequeños con nombre descriptivo
function createInvoice($order) {
    $subtotal = $this->calculateSubtotal($order);
    $discount = $this->calculateDiscount($subtotal);
    $tax = $this->calculateTax($subtotal - $discount);

    return new Invoice($subtotal, $discount, $tax);
}
```
**Cuándo:** un bloque de código dentro de un método tiene un propósito identificable.

### 3. Replace Conditional with Polymorphism
```php
// ❌ Antes — switch sobre tipo
function calculateShipping($order) {
    switch ($order->type) {
        case 'standard': return $order->weight * 2.5;
        case 'express': return $order->weight * 5.0 + 10;
        case 'overnight': return $order->weight * 8.0 + 25;
    }
}

// ✅ Después — polimorfismo
interface ShippingCalculator {
    public function calculate(Order $order): float;
}

class StandardShipping implements ShippingCalculator {
    public function calculate(Order $order): float {
        return $order->weight * 2.5;
    }
}
// ... Express, Overnight implementan lo mismo
```
**Cuándo:** switch/if-else que decide comportamiento basándose en un tipo, y es probable que se añadan más tipos.

### 4. Introduce Parameter Object / DTO
```php
// ❌ Antes — muchos parámetros
function searchUsers($name, $email, $role, $status, $dateFrom, $dateTo, $page, $perPage) { }

// ✅ Después — DTO
class UserSearchCriteria {
    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?string $role = null,
        public ?string $status = null,
        public ?Carbon $dateFrom = null,
        public ?Carbon $dateTo = null,
        public int $page = 1,
        public int $perPage = 25,
    ) {}
}

function searchUsers(UserSearchCriteria $criteria) { }
```
**Cuándo:** >3 parámetros que viajan juntos, especialmente si se repiten en varios métodos.

### 5. Extract Class (dividir por SRP)
```php
// ❌ Antes — clase con múltiples responsabilidades
class UserService {
    public function register($data) { /* ... */ }
    public function login($credentials) { /* ... */ }
    public function sendWelcomeEmail($user) { /* ... */ }
    public function generateReport($filters) { /* ... */ }
    public function exportToCsv($users) { /* ... */ }
}

// ✅ Después — una responsabilidad por clase
class UserRegistrationService { public function register($data) { } }
class AuthService { public function login($credentials) { } }
class UserNotificationService { public function sendWelcomeEmail($user) { } }
class UserReportService { public function generate($filters) { } public function exportToCsv($users) { } }
```
**Cuándo:** una clase tiene métodos que no comparten datos internos ni propósito.

### 6. Replace Magic Numbers/Strings
```php
// ❌ Antes
if ($user->role === 'admin') { }
if ($order->total > 1000) { }
$tax = $subtotal * 0.21;

// ✅ Después
if ($user->role === UserRole::ADMIN) { }
if ($order->total > self::DISCOUNT_THRESHOLD) { }
$tax = $subtotal * TaxRate::STANDARD->value;
```
**Cuándo:** valores literales que tienen significado de negocio.

### 7. Compose Method
```php
// ❌ Antes — todo mezclado en un método
function processPayment($order) {
    // 50 líneas mezclando validación, cálculo, API call, logging...
}

// ✅ Después — el método principal lee como un índice
function processPayment($order) {
    $this->validateOrder($order);
    $amount = $this->calculateTotal($order);
    $result = $this->chargePaymentProvider($order, $amount);
    $this->recordTransaction($order, $result);
    $this->notifyCustomer($order, $result);
}
```
**Cuándo:** un método tiene múltiples "fases" o "pasos" mezclados.

## Reglas de seguridad al refactorizar
- ✅ Ejecutar tests ANTES del refactor (verificar que pasan).
- ✅ Refactorizar en pasos pequeños, commit por paso.
- ✅ Ejecutar tests DESPUÉS de cada paso.
- ❌ No cambiar comportamiento mientras refactorizas.
- ❌ No refactorizar y añadir features en el mismo commit.
- ❌ No refactorizar sin tests que protejan el comportamiento.

