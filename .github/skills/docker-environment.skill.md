---
name: "docker-environment"
description: "Configurar y diagnosticar entornos Docker para desarrollo local y CI"
triggers: ["docker", "contenedor", "container", "docker-compose", "Dockerfile", "imagen", "entorno local", "setup local"]
---

# Skill: Docker Environment

## Cuándo aplicar
- Al configurar Docker para un proyecto nuevo.
- Al diagnosticar problemas con contenedores.
- Al optimizar imágenes o tiempos de build.

## Configuración por stack

### Laravel + MySQL + Redis
```yaml
# docker-compose.yml
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    volumes:
      - .:/var/www/html
    ports:
      - "8000:8000"
    depends_on:
      db:
        condition: service_healthy
      redis:
        condition: service_healthy
    environment:
      - DB_HOST=db
      - REDIS_HOST=redis

  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: app
      MYSQL_ROOT_PASSWORD: secret
    volumes:
      - db_data:/var/lib/mysql
    ports:
      - "3306:3306"
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
      interval: 10s
      timeout: 5s
      retries: 3

  redis:
    image: redis:7-alpine
    ports:
      - "6379:6379"
    healthcheck:
      test: ["CMD", "redis-cli", "ping"]
      interval: 10s
      timeout: 5s
      retries: 3

volumes:
  db_data:
```

### Node.js + PostgreSQL
```yaml
services:
  app:
    build: .
    volumes:
      - .:/app
      - /app/node_modules  # excluir node_modules del volumen
    ports:
      - "3000:3000"
    environment:
      - DATABASE_URL=postgresql://app:secret@db:5432/app
    depends_on:
      db:
        condition: service_healthy

  db:
    image: postgres:16-alpine
    environment:
      POSTGRES_DB: app
      POSTGRES_USER: app
      POSTGRES_PASSWORD: secret
    volumes:
      - pg_data:/var/lib/postgresql/data
    ports:
      - "5432:5432"
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U app"]
      interval: 10s
      timeout: 5s
      retries: 3

volumes:
  pg_data:
```

## Dockerfile — Best practices

### PHP / Laravel
```dockerfile
FROM php:8.3-fpm-alpine AS base

# Extensiones del sistema
RUN apk add --no-cache \
    libpng-dev libjpeg-turbo-dev freetype-dev \
    libzip-dev icu-dev oniguruma-dev

# Extensiones PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd zip intl mbstring bcmath opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Dependencias primero (cachea layer si no cambian)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --prefer-dist

COPY . .
RUN composer dump-autoload --optimize

USER www-data
EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0"]
```

### Checklist de Dockerfile
- [ ] **Multi-stage build** para separar build y runtime.
- [ ] **Dependencias primero**: `COPY package*.json` antes de `COPY .` para cachear layer.
- [ ] **No root**: `USER node` / `USER www-data`.
- [ ] **`.dockerignore`**: excluir `.git`, `node_modules`, `.env`, `storage/logs`.
- [ ] **Tag específico**: `php:8.3-fpm-alpine`, no `php:latest`.
- [ ] **Healthcheck**: definido en compose o en Dockerfile.
- [ ] **Un proceso por contenedor**: no PHP + nginx + cron en el mismo contenedor.

## Diagnóstico de problemas comunes

### "Connection refused" a DB
```bash
# Verificar que el contenedor está corriendo
docker compose ps

# Verificar logs del contenedor de DB
docker compose logs db

# Verificar que la red es correcta
docker compose exec app ping db

# ¿El host en .env es correcto? (debe ser el nombre del servicio: 'db', no 'localhost')
```

### "Permission denied" en volúmenes (Linux)
```bash
# El usuario del contenedor no tiene permisos en el volumen montado
# Opción 1: crear usuario con mismo UID
RUN adduser -u 1000 -D appuser
USER appuser

# Opción 2: dar permisos en el host
chmod -R 777 storage bootstrap/cache  # solo en desarrollo local
```

### Build lento
```bash
# Usar BuildKit (más rápido, caching inteligente)
DOCKER_BUILDKIT=1 docker compose build

# Verificar .dockerignore (¿se copian node_modules o .git?)
cat .dockerignore

# Verificar que las layers se cachean (dependencias antes del código)
```

### "ENOSPC: no space left on device"
```bash
# Limpiar imágenes y contenedores sin usar
docker system prune -a --volumes
```

### Contenedor se reinicia en loop
```bash
# Ver los logs
docker compose logs --tail=50 app

# Entrar al contenedor para debug
docker compose exec app sh
# o si no arranca:
docker compose run --rm app sh
```

## Comandos útiles del día a día
```bash
# Levantar todo
docker compose up -d

# Ver logs en tiempo real
docker compose logs -f app

# Ejecutar comando en contenedor
docker compose exec app php artisan migrate
docker compose exec app npm test

# Reconstruir imagen
docker compose build --no-cache app

# Parar y limpiar todo
docker compose down -v  # -v elimina volúmenes (datos)

# Ver uso de recursos
docker stats
```

## .dockerignore mínimo
```
.git
.idea
.vscode
node_modules
vendor
storage/logs/*
storage/framework/cache/*
.env
*.log
docker-compose*.yml
Dockerfile
```

