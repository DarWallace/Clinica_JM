<p align="center">
<img src="https://bloonde.com/assets/img/bloonde.svg" alt="Bloonde" width="180">
</p>
<p align="center">
<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300">
</p>
<p align="center">
<img src="https://shop.filamentphp.com/cdn/shop/files/Logo-2.png?height=628&pad_color=fff&v=1669908723&width=1200" width="300">
</p>

# Inicializador Laravel - Filament + Livewire

Este repositorio sirve como base para nuevos proyectos en Bloonde. Incluye una configuración mínima, limpia y funcional para proyectos Laravel con backoffice basado en Filament y desarrollo frontend con Livewire.

Este repositorio es una plantilla, por lo que puedes crear directamente el nuevo repositorio usandolo como plantilla o siguiendo los pasos de más abajo

## ✨ Características incluidas

- [Laravel 12](https://laravel.com/docs/12.x) - Framework PHP moderno
- [Filament PHP 4.x](https://filamentphp.com/docs/4.x/panels/installation) - Panel de administración completo
- [Livewire 3.6](https://livewire.laravel.com/docs) - Framework reactivo para Laravel
- [Livewire Flux 2.1](https://flux.laravel.com/) - Componentes UI para Livewire
- [Livewire Volt 1.7](https://livewire.laravel.com/docs/volt) - API funcional para Livewire
- [Spatie Laravel Permission 6.x](https://spatie.be/docs/laravel-permission/v6/introduction) - Control de roles y permisos
- [Blade Icons](https://blade-ui-kit.com/blade-icons) - Iconografía SVG optimizada
- [TailwindCSS 4.0](https://tailwindcss.com/docs) - Framework CSS utility-first
- Configuración optimizada para desarrollo y producción
- Scripts automatizados de instalación y desarrollo  

---

## � Instalación rápida

### Script de instalación automática (Recomendado)

```bash
# Clona el proyecto
git clone --depth=1 https://github.com/Bloonde/laravel-initializer.git nuevo-proyecto
cd nuevo-proyecto

# Ejecuta el script de instalación
./setup.sh
```

El script automatiza:
- ✅ Instalación de dependencias (Composer + NPM)
- ✅ Configuración del archivo `.env`
- ✅ Generación de clave de aplicación
- ✅ Creación de base de datos SQLite
- ✅ Ejecución de migraciones
- ✅ Creación de usuario administrador
- ✅ Compilación de assets
- ✅ Configuración de permisos

### Credenciales por defecto
- **Panel admin**: http://localhost:8000/admin
- **Email**: admin@admin.com
- **Password**: password

---

## 🔧 Instalación manual

### Opción 1: Clonar el starter y crear un nuevo repositorio

1. Clona el proyecto inicializador SIN historial
```bash
git clone --depth=1 https://github.com/Bloonde/laravel-initializer.git nuevo-proyecto
cd nuevo-proyecto
```

2. Elimina el antiguo repositorio Git
```bash
rm -rf .git
```

3. Inicia un nuevo repositorio Git
```bash
git init
git add .
git commit -m "Proyecto base creado desde starter"
```

4. Sube a tu nuevo repositorio GitHub
```bash
git remote add origin https://github.com/tu-org/nuevo-proyecto.git
git branch -M main
git push -u origin main
```

5. Ejecuta la instalación inicial
```bash
cp .env.example .env
composer install
npm install
php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm run build
```

### ⚠️ Docker con Laravel Sail

¿Usas Docker? Hemos instalado Sail para ello

**IMPORTANTE: Los contenedores usan los puertos 80 y 3306. Si tienes Apache/Nginx y MySQL/MariaDB instalados, deberás parar sus servicios**

1. Levanta los contenedores 
```bash
./vendor/bin/sail up
```

2. Crea un alias para facilitar el uso (opcional)
```bash
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
```

3. Ejecuta comandos dentro del contenedor
```bash
./vendor/bin/sail bash
```

---

## � Solución de problemas comunes

### Error: "Command 'filament:publish' is not defined"

**Problema**: En Filament 4.x, el comando `filament:publish` no existe. Los comandos han cambiado.

**Solución**: Los scripts ya están actualizados con los comandos correctos de Filament 4.x:

```bash
# ✅ Correcto en Filament 4.x:
php artisan filament:install --panels
php artisan make:filament-user

# ❌ Incorrecto (Filament 3.x):
php artisan filament:publish --tag=laravel-assets
```

**Comandos principales de Filament 4.x**:
- `php artisan filament:install --panels` - Instalar panel de administración
- `php artisan filament:install --scaffold` - Instalar con frontend assets
- `php artisan make:filament-user` - Crear usuario administrador
- `php artisan make:filament-resource ModelName` - Crear recurso
- `php artisan make:filament-page PageName` - Crear página personalizada

### Conflictos de puertos

**Problema**: Los puertos 80 y 3306 están en uso.

**Solución**:
```bash
# Para Apache/Nginx
sudo systemctl stop apache2
sudo systemctl stop nginx

# Para MySQL/MariaDB
sudo systemctl stop mysql
sudo systemctl stop mariadb

# O usa un docker-compose.override.yml personalizado
cp docker-compose.override.yml.example docker-compose.override.yml
# Edita los puertos en el archivo
```

### Docker no funciona

**Problema**: Docker Desktop no está iniciado o no tienes permisos.

**Solución**:
```bash
# Verificar que Docker esté corriendo
docker info

# En Linux, añadir usuario al grupo docker
sudo usermod -aG docker $USER
# Luego reinicia sesión
```

---

## �🛠️ Comandos de desarrollo

### Comandos Makefile (Recomendado)

El proyecto incluye un `Makefile` que detecta automáticamente si usas Docker o desarrollo local:

```bash
# Ver todos los comandos disponibles
make help

# Instalación automática (detecta Docker)
make install

# Desarrollo
make dev             # Iniciar entorno de desarrollo
make serve           # Solo servidor Laravel (local)
make up              # Iniciar contenedores (Docker)
make down            # Parar contenedores (Docker)

# Assets
make build           # Compilar para producción
make sail-dev        # Hot reload con Docker

# Base de datos
make migrate         # Ejecutar migraciones
make fresh           # Migración fresca con seeders
make user            # Crear usuario admin

# Utilidades
make clear           # Limpiar caches
make test            # Ejecutar tests
make shell           # Acceder al contenedor (Docker)
make logs            # Ver logs (Docker)
```

### Scripts de desarrollo detallados

**Sin Docker (local):**
```bash
# Inicia todos los servicios de desarrollo (servidor, queue, logs, vite)
composer run dev

# Solo servidor Laravel
php artisan serve

# Solo compilación de assets
npm run dev          # Modo desarrollo con watch
npm run build        # Compilación para producción

# Tests
composer run test
npm run test         # Si usas Vitest
```

**Con Docker (Laravel Sail):**
```bash
# Alias recomendado para simplificar comandos
alias sail='./vendor/bin/sail'

# Gestión de contenedores
sail up              # Iniciar contenedores
sail up -d           # Iniciar en background
sail down            # Parar contenedores
sail restart         # Reiniciar contenedores

# Desarrollo
sail npm run dev     # Compilación con hot reload
sail npm run build   # Compilación para producción
sail composer run dev # Todos los servicios de desarrollo

# Comandos dentro del contenedor
sail artisan serve   # No necesario, ya corre en puerto 80
sail artisan         # Comandos Artisan
sail composer        # Comandos Composer
sail npm             # Comandos NPM
sail shell           # Acceder al contenedor
sail root-shell      # Acceder como root

# Tests
sail test            # Ejecutar tests con PHPUnit
sail composer run test
```

### Comandos Artisan útiles

**Local:**
```bash
# Crear nuevo usuario admin para Filament
php artisan make:filament-user

# Limpiar caches
php artisan optimize:clear

# Generar recursos Filament
php artisan make:filament-resource ModelName
```

**Con Docker:**
```bash
# Crear nuevo usuario admin para Filament
sail artisan make:filament-user

# Limpiar caches
sail artisan optimize:clear

# Generar recursos Filament
sail artisan make:filament-resource ModelName
```

---

## 📁 Estructura del proyecto

```
├── app/
│   ├── Filament/           # Recursos y configuración de Filament
│   ├── Http/Controllers/   # Controladores HTTP
│   ├── Livewire/          # Componentes Livewire
│   │   ├── Actions/       # Acciones reutilizables
│   │   ├── Auth/          # Componentes de autenticación
│   │   └── Settings/      # Configuraciones
│   └── Models/            # Modelos Eloquent
├── resources/
│   ├── css/               # Estilos CSS/SCSS
│   ├── js/                # JavaScript/Alpine.js
│   └── views/             # Vistas Blade
│       ├── components/    # Componentes Blade
│       ├── flux/          # Componentes Flux
│       └── livewire/      # Vistas Livewire
├── tests/                 # Tests automatizados
└── setup.sh              # Script de instalación automática
```

---

## 🔄 Migración desde proyectos existentes

Si quieres actualizar un proyecto existente con estas mejoras:

1. **Actualizar dependencias**:
```bash
composer update
npm update
```

2. **Migrar a Filament 4.x**:
```bash
composer require filament/upgrade
php artisan filament:upgrade
```

3. **Actualizar TailwindCSS a v4**:
```bash
npm install @tailwindcss/vite@^4.0.7 tailwindcss@^4.0.7
```

---

## 📚 Documentación adicional

- [Laravel 12](https://laravel.com/docs/12.x) - Documentación oficial de Laravel
- [Filament 4.x](https://filamentphp.com/docs/4.x) - Documentación de Filament
- [Livewire 3.x](https://livewire.laravel.com/docs) - Documentación de Livewire
- [Livewire Flux](https://flux.laravel.com/) - Componentes UI para Livewire
- [TailwindCSS 4.0](https://tailwindcss.com/docs) - Documentación de Tailwind
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v6) - Control de roles y permisos

---

## 🤝 Contribuir

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/nueva-caracteristica`)
3. Commit tus cambios (`git commit -am 'Añadir nueva característica'`)
4. Push a la rama (`git push origin feature/nueva-caracteristica`)
5. Abre un Pull Request

---

## 📄 Licencia

Este proyecto está bajo la licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

---

<p align="center">
Desarrollado con ❤️ por <a href="https://bloonde.com">Bloonde</a>
</p>
4. Instalar filament:
   1. ```composer require filament/filament:"^3.3" -W```
   2. ```php artisan filament:install --panels```
5. Instalar laravel Permission ```composer require spatie/laravel-permission```
6. Revisa tu archivo .env o copialo de .env.example y modifica las variables que necesites
7. Ejecuta migraciones
