#!/bin/bash

# Colores para el output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}🚀 Inicializando proyecto Laravel con Filament + Livewire${NC}"
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

# Preguntar si quiere usar Docker
echo -e "${YELLOW}¿Quieres usar Docker con Laravel Sail? [y/N]:${NC}"
read -r use_docker

if [[ $use_docker =~ ^[Yy]$ ]]; then
    print_status "Ejecutando configuración con Docker..."
    if [ -f "./setup-docker-v2.sh" ]; then
        exec ./setup-docker-v2.sh
    else
        exec ./setup-docker.sh
    fi
    exit 0
fi

print_status "Ejecutando configuración local..."

# Verificar si composer está instalado
if ! command -v composer &> /dev/null; then
    print_error "Composer no está instalado"
    exit 1
fi

# Verificar si npm está instalado
if ! command -v npm &> /dev/null; then
    print_error "npm no está instalado"
    exit 1
fi

# Instalar dependencias de Composer
print_status "Instalando dependencias de Composer..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Crear archivo .env si no existe
if [ ! -f .env ]; then
    print_status "Creando archivo .env..."
    cp .env.example .env
fi

# Generar clave de aplicación
print_status "Generando clave de aplicación..."
php artisan key:generate

# Crear base de datos SQLite si no existe
if [ ! -f database/database.sqlite ]; then
    print_status "Creando base de datos SQLite..."
    touch database/database.sqlite
fi

# Ejecutar migraciones
print_status "Ejecutando migraciones..."
php artisan migrate --force

# Crear usuario administrador para Filament
print_status "Creando usuario administrador..."
php artisan make:filament-user --name="Admin" --email="admin@admin.com" --password="password"

# Instalar y configurar Filament
print_status "Instalando Filament..."
php artisan filament:install --panels

# Instalar dependencias de npm
print_status "Instalando dependencias de npm..."
npm install

# Compilar assets
print_status "Compilando assets..."
npm run build

# Configurar permisos de storage
print_status "Configurando permisos de storage..."
php artisan storage:link

# Limpiar caches
print_status "Limpiando caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo ""
echo -e "${GREEN}🎉 ¡Proyecto inicializado correctamente!${NC}"
echo ""
echo -e "${BLUE}Comandos útiles:${NC}"
echo -e "  ${YELLOW}composer run dev${NC}     - Iniciar servidor de desarrollo con hot reload"
echo -e "  ${YELLOW}php artisan serve${NC}   - Iniciar servidor Laravel"
echo -e "  ${YELLOW}npm run dev${NC}         - Compilar assets en modo desarrollo"
echo -e "  ${YELLOW}npm run build${NC}       - Compilar assets para producción"
echo ""
echo -e "${BLUE}Panel de administración:${NC}"
echo -e "  URL: ${YELLOW}http://localhost:8000/admin${NC}"
echo -e "  Email: ${YELLOW}admin@admin.com${NC}"
echo -e "  Password: ${YELLOW}password${NC}"
echo ""
echo -e "${GREEN}¡Listo para desarrollar! 🚀${NC}"
