---
description: "Crear un servicio de dominio siguiendo DDD y arquitectura limpia"
---
# Crear Servicio DDD

## Antes de generar
1. Leer la estructura actual del proyecto: ¿ya usa DDD/capas? ¿Dónde viven los servicios?
2. Identificar el dominio/bounded context al que pertenece.
3. Leer modelos y servicios existentes del mismo dominio para detectar patrones.
4. Verificar que no existe ya un servicio que cubra el caso de uso.

## Diseño del servicio

### Capa de dominio
- **Service**: lógica de negocio pura, sin dependencias de framework.
- **Interfaces/Contracts**: para dependencias externas (repositorios, APIs, notificaciones).
- **DTOs**: para entrada y salida del servicio si hay más de 3 parámetros.
- **Excepciones de dominio**: errores específicos del negocio (`InsufficientBalanceException`, no `\Exception`).
- **Value Objects**: para conceptos con identidad propia (Money, Email, OrderStatus) si aportan type safety.

### Capa de infraestructura
- Implementaciones concretas de las interfaces: Eloquent repositories, HTTP clients, etc.
- Registrar bindings en Service Provider.

### Capa de aplicación
- El controlador/command inyecta el servicio y delega.
- No hay lógica de negocio en esta capa.

## Principios a seguir
- **Stateless**: el servicio no guarda estado entre llamadas.
- **Inyección de dependencias**: constructor injection, contra interfaces.
- **Responsabilidad única**: un servicio por caso de uso o grupo cohesivo de operaciones.
- **Testeable**: toda la lógica debe poder testearse sin framework, DB ni red.

## Qué entregar
1. **Interfaz/Contrato** (si aplica): `App\Contracts\{Dominio}\{Interface}.php`.
2. **Servicio**: `App\Services\{Dominio}\{Service}.php` — código completo con tipado estricto.
3. **DTOs** (si aplica): `App\DTOs\{Dominio}\{DTO}.php`.
4. **Excepciones de dominio** (si aplica).
5. **Tests unitarios**: cubriendo happy path + edge cases + errores de negocio.
6. **Registro en Service Provider** (binding interfaz → implementación).
7. Breve explicación de las decisiones clave (3-5 bullets).

## Anti-patrones a evitar
- ❌ Servicios que son wrappers sin valor (solo llaman al modelo).
- ❌ Dependencias directas de Eloquent dentro del servicio (usar repository/interface).
- ❌ Servicios con 20+ métodos públicos (dividir).
- ❌ Lógica de framework (Request, Response, Session) dentro del servicio.

## Instrucciones de referencia
Aplicar `services.instructions.md`, `php.instructions.md`.
