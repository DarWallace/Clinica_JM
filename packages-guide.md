## Guía de paquetes compartidos

Este archivo lista paquetes de Composer que se han usado en otros proyectos del equipo (Bloonde) con Filament PHP. Sirve como referencia rápida cuando surge una nueva funcionalidad: buscar aquí primero si ya existe un paquete probado en otro proyecto.

Reglas rápidas:
- Para cada paquete indico en qué proyecto(s) se ha usado y la versión encontrada en ese `composer.json`.
- También indico si el paquete ya está incluido en este proyecto (`laravel-initializer`).
- No sustituye a la revisión técnica: antes de añadir cualquier dependencia revisa compatibilidad PHP/Laravel y realiza pruebas locales.

---

### Paquetes solicitados

1. `filament/spatie-laravel-media-library-plugin` — versión usada: `^3.2`
	 - Proyectos donde aparece:
		 - compras-galisur — `"filament/spatie-laravel-media-library-plugin": "^3.2"`
	 - Incluido en este proyecto: No
	 - Comando propuesta:
		 `composer require filament/spatie-laravel-media-library-plugin:"^4.0" -W`
	 - Nota: plugin para integrar Filament con Spatie Media Library; revisa configuración y `vendor:publish`.

2. `laravel-lang/common` — versión usada: `^6.7`
	 - Proyectos donde aparece:
		 - centro-pediatrico — `"laravel-lang/common": "^6.7"`
	 - Incluido en este proyecto: No
	 - Comando propuesta:
		 `composer require laravel-lang/common`
	 - Nota: traducciones y utilidades de localización.

3. `spatie/laravel-activitylog` — versión usada: `^4.10`
	 - Proyectos donde aparece:
		 - centro-pediatrico — `"spatie/laravel-activitylog": "^4.10"`
	 - Incluido en este proyecto: No
	 - Comando propuesta:
		 `composer require spatie/laravel-activitylog`
	 - Nota: auditoría y registro de actividades en modelos; puede añadir migraciones.
         `php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"`
     - Publicar Config:
         `php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"`

4. `barryvdh/laravel-dompdf` — versión usada: `^3.1`
	 - Proyectos donde aparece:
		 - linguameeting-app — `"barryvdh/laravel-dompdf": "^3.1"`
	 - Incluido en este proyecto: No
	 - Comando propuesta:
		 `composer require barryvdh/laravel-dompdf`
	 - Nota: generar PDFs desde Blade; suele requerir `vendor:publish` para config.

5. `bezhansalleh/filament-shield` — versión usada: `*`
	 - Proyectos donde aparece:
		 - linguameeting-app — `"bezhansalleh/filament-shield": "*"`
	 - Incluido en este proyecto: No
	 - Comando propuesta:
		 `composer require bezhansalleh/filament-shield`
	 - Nota: extensión para Filament que facilita integración con permisos/roles; revisar compatibilidad con Filament.

6. `codeat3/blade-phosphor-icons` — versión usada: `^2.0`
	 - Proyectos donde aparece:
		 - linguameeting-app — `"codeat3/blade-phosphor-icons": "^2.0"`
	 - Incluido en este proyecto: No
	 - Comando propuesta:
		 `composer require codeat3/blade-phosphor-icons:^2.0`
	 - Nota: paquete alternativo de iconos para Blade; elige uno consistente con la UI del proyecto.
	 - Publicar configuración:
         `php artisan vendor:publish --tag=blade-phosphor-icons-config`

---

### Cómo usar esta guía

- Antes de implementar una nueva funcionalidad, busca en este fichero si existe un paquete que ya se haya usado.
- Si lo hay, revisa en el proyecto listado para ver cómo se integró (migraciones, config, providers, traits, tests).
- Copia las configuraciones y pruebas relevantes, y adapta versiones si es necesario. Prueba localmente y corre la suite de tests.

---

Documento generado a partir del análisis de `composer.json` de los repositorios:
- `Bloonde/compras-galisur`
- `Bloonde/centro-pediatrico`
- `Bloonde/linguameeting-app`


Notas rápidas post-instalación
- Revisa siempre si el paquete añade migraciones o comandos `vendor:publish`:
  - `spatie/laravel-activitylog` -> `php artisan vendor:publish --provider="Spatie\\Activitylog\\ActivitylogServiceProvider" --tag="activitylog-migrations"`
  - `filament/spatie-laravel-media-library-plugin` y `bezhansalleh/filament-shield` pueden requerir `vendor:publish` y configuración adicional en `config/`.
  - `barryvdh/laravel-dompdf` puede requerir publicar configuraciones: `php artisan vendor:publish --provider="Barryvdh\\DomPDF\\ServiceProvider"`.
- Después de instalar, ejecuta `composer install`/`composer update` según proceda, luego:
  - `php artisan vendor:publish` (si el paquete lo requiere)
  - `php artisan migrate` (si se agregaron migraciones)

Revisar siempre la documentación oficial por si este archivo se encuentra desactualizado
