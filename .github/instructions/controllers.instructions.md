---
applyTo: "app/Http/Controllers/**"
description: "Reglas para controladores Laravel"
---

# CONTROLLERS — REGLAS

## Principio: controladores delgados
El controlador recibe la petición, delega y devuelve respuesta. No contiene lógica de negocio.

## Responsabilidades del controlador
1. Recibir el request (inyectar FormRequest para validación).
2. Autorizar la acción (vía FormRequest `authorize()` o Policy).
3. Delegar la lógica a un Service, Action o Job.
4. Devolver la respuesta (view, JSON, redirect).

## Reglas
- **Validar y autorizar al inicio** del método, no dentro de condicionales.
- **No hacer queries complejas** directamente en controladores. Delegar a Services o Repositories.
- **No manipular datos de negocio** en el controlador. Si necesitas transformar datos, usa un DTO o el Service.
- **Un método por acción HTTP** (index, store, show, update, destroy). Si un controlador crece, dividir en controladores invocables (`__invoke`).
- **Devolver responses tipados**: `JsonResponse`, `RedirectResponse`, `View`. Evitar returns ambiguos.
- **No acoplar controladores** a implementaciones concretas: inyectar interfaces/contratos cuando sea razonable.

## Naming
- Nombre en singular + `Controller`: `UserController`, `OrderController`.
- Controladores invocables para acciones únicas: `ApproveOrderController`.
- Agrupar por dominio si el proyecto crece: `App\Http\Controllers\Admin\`, `App\Http\Controllers\Api\V1\`.

## FormRequests
- Crear un FormRequest por cada store/update que tenga validación no trivial.
- Usar `prepareForValidation()` para normalizar datos antes de validar.
- Usar `authorize()` para autorización en el FormRequest, no en el controlador.
- Naming: `StoreUserRequest`, `UpdateOrderRequest`.

## Respuestas API
- Usar API Resources (`JsonResource`, `ResourceCollection`) para serializar respuestas.
- Códigos HTTP semánticos: 201 para creación, 204 para delete sin body, 422 para validación.
- Envolver errores en formato consistente (`message`, `errors`).

## Anti-patrones
- ❌ Query Builder o Eloquent directamente en el controlador para consultas complejas.
- ❌ Lógica condicional de negocio (if/else de reglas de dominio) en el controlador.
- ❌ Enviar emails, despachar eventos o manipular archivos directamente en el controlador.
- ❌ Controladores con más de ~60 líneas por método (señal de que falta delegar).
