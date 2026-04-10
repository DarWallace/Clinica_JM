# Changelog

Todas las mejoras y cambios notables de este inicializador Laravel se documentarán en este archivo.

## [2.0.2] - 2025-08-26

### 🐛 Correcciones críticas
- **Comandos Filament 4.x corregidos**: Reemplazado `filament:publish` por `filament:install --panels`
- **Compatibilidad Filament 4.x**: Todos los scripts ahora usan comandos válidos de Filament 4.x
- **Scripts post-autoload**: Eliminado comando obsoleto `filament:upgrade` del composer.json
- **Documentación actualizada**: Agregada sección de troubleshooting para comandos de Filament

### 📝 Comandos corregidos
- ✅ `php artisan filament:install --panels` (correcto para v4.x)
- ❌ `php artisan filament:publish --tag=laravel-assets` (obsoleto en v4.x)

---

## [2.0.1] - 2025-08-26

### 🐛 Correcciones
- **Script Docker mejorado** (`setup-docker-v2.sh`): Soluciona el error "./vendor/bin/sail: No existe el archivo"
- **Orden de instalación corregido**: Instala Composer localmente antes de usar Sail
- **Verificaciones mejoradas**: Mejor manejo de errores y verificación de requisitos
- **Documentación de troubleshooting**: Sección de solución de problemas comunes
- **Detección automática**: Los scripts detectan automáticamente la mejor versión a usar

### 🔧 Mejorado
- **Manejo de errores**: Mejor feedback durante la instalación
- **Verificación de MySQL**: Timeout aumentado y mejor detección
- **Logs informativos**: Más información durante el proceso de instalación

---

## [2.0.0] - 2025-08-26

### ✨ Añadido
- **Filament 4.x**: Actualización a la última versión estable
- **Livewire Flux 2.1**: Componentes UI modernos para Livewire
- **Livewire Volt 1.7**: API funcional para Livewire
- **Blade Icons**: Iconografía SVG optimizada
- **TailwindCSS 4.0**: Última versión con mejor rendimiento
- **Script de instalación automática** (`setup.sh`): Automatiza toda la configuración inicial
- **Script específico para Docker** (`setup-docker.sh`): Configuración completa con Laravel Sail
- **Comandos de desarrollo mejorados**: Script `composer run dev` con concurrently
- **Documentación mejorada**: README completamente reescrito con mejores instrucciones
- **Soporte completo para Docker**: Configuración optimizada de Laravel Sail

### 🐳 Docker & Laravel Sail
- **Configuración automática**: Detección de Docker y configuración de contenedores
- **Base de datos MySQL**: Configuración automática en contenedores
- **Variables de entorno optimizadas**: .env.example con configuración para Docker
- **Scripts de desarrollo**: Comandos específicos para Sail
- **Verificación de puertos**: Detección de conflictos con servicios locales

### 🔄 Actualizado
- **Laravel Framework**: Mantenido en v12.0 (más estable)
- **Spatie Laravel Permission**: Actualizado a v6.21
- **Laravel Pint**: Actualizado a v1.18
- **Laravel Sail**: Actualizado a v1.43
- **Axios**: Actualizado a v1.8.2
- **Vite**: Actualizado a v6.2.4
- **Laravel Vite Plugin**: Actualizado a v1.2.0

### 🔧 Mejorado
- **Configuración de Composer**: Añadido `filament/upgrade` para migraciones
- **Configuración de NPM**: Reorganización de dependencias (dev vs runtime)
- **Scripts de desarrollo**: Comando unificado para desarrollo con hot reload
- **Configuración de TailwindCSS**: Archivo moderno con paths optimizados
- **Archivo .gitignore**: Entradas adicionales para mejor limpieza del repo
- **Estabilidad**: Cambio a `minimum-stability: beta` para Filament 4.x

### 📚 Basado en análisis de repositorios
Mejoras implementadas tras análisis de:
- `Bloonde/compras-galisur`
- `Bloonde/centro-pediatrico` 
- `Bloonde/linguameeting-app`

### 🎯 Características comunes identificadas
- Uso consistente de Filament para administración
- Livewire como framework reactivo principal
- TailwindCSS 4.x para estilos
- Spatie Laravel Permission para control de acceso
- Configuración optimizada para desarrollo y producción

---

## [1.0.0] - Versión inicial
- Configuración básica de Laravel con Filament 3.x
- Integración con Livewire
- Spatie Laravel Permission
- Configuración Docker con Laravel Sail
