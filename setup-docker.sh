#!/bin/bash

# Colores para el output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}🐳 Inicializando proyecto Laravel con Docker (Sail)${NC}"
echo "=================================================="

# Función para imprimir mensajes de estado
print_status() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Verificar si Docker está instalado y corriendo
if ! command -v docker &> /dev/null; then
    print_error "Docker no está instalado"
    exit 1
fi

if ! docker info >/dev/null 2>&1; then
    print_error "Docker no está corriendo. Inicia Docker Desktop o el daemon de Docker"
    exit 1
fi

# Verificar si Docker Compose está disponible
if ! docker compose version >/dev/null 2>&1; then
    print_error "Docker Compose no está disponible"
    exit 1
fi

# Verificar si Composer está instalado localmente (necesario para instalar Sail)
if ! command -v composer &> /dev/null; then
    print_error "Composer no está instalado localmente. Es necesario para instalar Laravel Sail"
    exit 1
fi

# Crear archivo .env si no existe
if [ ! -f .env ]; then
    print_status "Creando archivo .env para Docker..."
    cp .env.example .env

    # Configurar para MySQL en lugar de SQLite
    sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' .env
    sed -i 's/# DB_HOST=mysql/DB_HOST=mysql/' .env
    sed -i 's/# DB_PORT=3306/DB_PORT=3306/' .env
    sed -i 's/# DB_DATABASE=laravel/DB_DATABASE=laravel/' .env
    sed -i 's/# DB_USERNAME=sail/DB_USERNAME=sail/' .env
    sed -i 's/# DB_PASSWORD=password/DB_PASSWORD=password/' .env
fi

# Verificar puertos
print_status "Verificando puertos disponibles..."
if lsof -Pi :80 -sTCP:LISTEN -t >/dev/null 2>&1; then
    print_warning "El puerto 80 está en uso. Considera parar Apache/Nginx local"
fi

if lsof -Pi :3306 -sTCP:LISTEN -t >/dev/null 2>&1; then
    print_warning "El puerto 3306 está en uso. Considera parar MySQL/MariaDB local"
fi

# Primero necesitamos instalar dependencias de Composer para tener Sail disponible
print_status "Instalando dependencias de Composer localmente..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Ahora podemos usar Sail
print_status "Construyendo e iniciando contenedores Docker..."
./vendor/bin/sail up -d --build

# Esperar a que MySQL esté listo
print_status "Esperando a que MySQL esté listo..."
./vendor/bin/sail exec mysql bash -c 'until mysqladmin ping -h localhost --silent; do sleep 1; done'

# Reinstalar dependencias dentro del contenedor para asegurar compatibilidad
print_status "Reinstalando dependencias dentro del contenedor..."
./vendor/bin/sail composer install --no-interaction --prefer-dist --optimize-autoloader

# Generar clave de aplicación
print_status "Generando clave de aplicación..."
./vendor/bin/sail artisan key:generate

# Ejecutar migraciones
print_status "Ejecutando migraciones..."
./vendor/bin/sail artisan migrate --force

# Crear usuario administrador para Filament
print_status "Creando usuario administrador..."
./vendor/bin/sail artisan make:filament-user --name="Admin" --email="admin@admin.com" --password="password"

# Instalar y configurar Filament
print_status "Instalando Filament..."
./vendor/bin/sail artisan filament:install --panels

# Instalar dependencias de npm
print_status "Instalando dependencias de npm..."
./vendor/bin/sail npm install

# Compilar assets
print_status "Compilando assets..."
./vendor/bin/sail npm run build

# Configurar permisos de storage
print_status "Configurando permisos de storage..."
./vendor/bin/sail artisan storage:link

# Limpiar caches
print_status "Limpiando caches..."
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan view:clear
./vendor/bin/sail artisan route:clear

echo ""
echo -e "${GREEN}🎉 ¡Proyecto Docker inicializado correctamente!${NC}"
echo ""
echo -e "${BLUE}Comandos útiles con Sail:${NC}"
echo -e "  ${YELLOW}./vendor/bin/sail up${NC}        - Iniciar contenedores"
echo -e "  ${YELLOW}./vendor/bin/sail down${NC}      - Parar contenedores"
echo -e "  ${YELLOW}./vendor/bin/sail artisan${NC}   - Ejecutar comandos Artisan"
echo -e "  ${YELLOW}./vendor/bin/sail composer${NC}  - Ejecutar Composer"
echo -e "  ${YELLOW}./vendor/bin/sail npm${NC}       - Ejecutar NPM"
echo -e "  ${YELLOW}./vendor/bin/sail shell${NC}     - Acceder al contenedor"
echo ""
echo -e "${BLUE}Alias recomendado:${NC}"
echo -e "  ${YELLOW}alias sail='./vendor/bin/sail'${NC}"
echo ""
echo -e "${BLUE}Panel de administración:${NC}"
echo -e "  URL: ${YELLOW}http://localhost/admin${NC}"
echo -e "  Email: ${YELLOW}admin@admin.com${NC}"
echo -e "  Password: ${YELLOW}password${NC}"
echo ""
echo -e "${BLUE}Desarrollo con hot reload:${NC}"
echo -e "  ${YELLOW}./vendor/bin/sail npm run dev${NC}"
echo ""
echo -e "${GREEN}¡Listo para desarrollar con Docker! 🐳${NC}"
