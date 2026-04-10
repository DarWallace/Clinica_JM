#!/bin/bash

# Colores para el output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}🐳 Inicializando proyecto Laravel con Docker (Sail) - Versión Mejorada${NC}"
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

print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

# Verificar requisitos
print_info "Verificando requisitos..."

if ! command -v docker &> /dev/null; then
    print_error "Docker no está instalado"
    exit 1
fi

if ! docker info >/dev/null 2>&1; then
    print_error "Docker no está corriendo. Inicia Docker Desktop o el daemon de Docker"
    exit 1
fi

if ! docker compose version >/dev/null 2>&1; then
    print_error "Docker Compose no está disponible"
    exit 1
fi

if ! command -v composer &> /dev/null; then
    print_error "Composer no está instalado localmente. Es necesario para instalar Laravel Sail"
    print_info "Instala Composer desde: https://getcomposer.org/"
    exit 1
fi

print_status "Todos los requisitos están disponibles"

# Crear archivo .env si no existe
if [ ! -f .env ]; then
    print_status "Creando archivo .env para Docker..."
    cp .env.example .env

    # Configurar para MySQL en lugar de SQLite
    if grep -q "DB_CONNECTION=sqlite" .env; then
        sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' .env
        sed -i 's/# DB_HOST=mysql/DB_HOST=mysql/' .env
        sed -i 's/# DB_PORT=3306/DB_PORT=3306/' .env
        sed -i 's/# DB_DATABASE=laravel/DB_DATABASE=laravel/' .env
        sed -i 's/# DB_USERNAME=sail/DB_USERNAME=sail/' .env
        sed -i 's/# DB_PASSWORD=password/DB_PASSWORD=password/' .env
    fi
else
    print_info "Archivo .env ya existe"
fi

# Verificar puertos
print_info "Verificando puertos..."
if lsof -Pi :80 -sTCP:LISTEN -t >/dev/null 2>&1; then
    print_warning "El puerto 80 está en uso. El contenedor usará el puerto 80 interno"
fi

if lsof -Pi :3306 -sTCP:LISTEN -t >/dev/null 2>&1; then
    print_warning "El puerto 3306 está en uso. Puede haber conflictos con MySQL local"
fi

# Paso 1: Instalar dependencias localmente para tener Sail disponible
print_status "Instalando dependencias de Composer localmente (para tener Sail)..."
composer install --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs

# Verificar que Sail esté disponible
if [ ! -f "./vendor/bin/sail" ]; then
    print_error "Laravel Sail no se instaló correctamente"
    exit 1
fi

# Paso 2: Construir e iniciar contenedores
print_status "Construyendo e iniciando contenedores Docker..."
./vendor/bin/sail up -d --build

# Esperar un poco para que los contenedores se inicien
print_info "Esperando a que los contenedores se inicien..."
sleep 10

# Paso 3: Verificar que MySQL esté listo
print_status "Esperando a que MySQL esté listo..."
max_attempts=30
attempt=0
while [ $attempt -lt $max_attempts ]; do
    if ./vendor/bin/sail exec mysql mysqladmin ping -h localhost --silent 2>/dev/null; then
        break
    fi
    attempt=$((attempt + 1))
    echo -n "."
    sleep 2
done

if [ $attempt -eq $max_attempts ]; then
    print_error "MySQL no pudo iniciarse correctamente"
    print_info "Intentando ver los logs..."
    ./vendor/bin/sail logs mysql
    exit 1
fi

echo "" # Nueva línea después de los puntos
print_status "MySQL está listo"

# Paso 4: Reinstalar dependencias dentro del contenedor
print_status "Reinstalando dependencias dentro del contenedor..."
./vendor/bin/sail composer install --no-interaction --prefer-dist --optimize-autoloader

# Paso 5: Generar clave de aplicación
print_status "Generando clave de aplicación..."
./vendor/bin/sail artisan key:generate

# Paso 6: Ejecutar migraciones
print_status "Ejecutando migraciones..."
./vendor/bin/sail artisan migrate --force

# Paso 7: Crear usuario administrador (si no existe)
print_status "Creando usuario administrador..."
./vendor/bin/sail artisan make:filament-user --name="Admin" --email="admin@admin.com" --password="password" || true

# Paso 8: Instalar y configurar Filament
print_status "Instalando Filament..."
./vendor/bin/sail artisan filament:install --panels

# Paso 9: Instalar dependencias de npm
print_status "Instalando dependencias de npm..."
./vendor/bin/sail npm install

# Paso 10: Compilar assets
print_status "Compilando assets..."
./vendor/bin/sail npm run build

# Paso 11: Configurar permisos de storage
print_status "Configurando permisos de storage..."
./vendor/bin/sail artisan storage:link

# Paso 12: Limpiar caches
print_status "Limpiando caches..."
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan view:clear
./vendor/bin/sail artisan route:clear

echo ""
echo -e "${GREEN}🎉 ¡Proyecto Docker inicializado correctamente!${NC}"
echo ""
echo -e "${BLUE}Estado de los contenedores:${NC}"
./vendor/bin/sail ps
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
echo -e "  Añade esto a tu ~/.bashrc o ~/.zshrc"
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
