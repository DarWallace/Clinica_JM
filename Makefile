# Makefile para Laravel con Sail
# Uso: make [comando]

# Variables
SAIL = ./vendor/bin/sail
COMPOSER = composer
NPM = npm

# Detectar si Docker está disponible y corriendo
DOCKER_AVAILABLE := $(shell docker info >/dev/null 2>&1 && echo "yes" || echo "no")

# Comandos de ayuda
.PHONY: help
help: ## Mostrar este mensaje de ayuda
	@echo "Comandos disponibles:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

# Instalación
.PHONY: install install-local install-docker
install: ## Instalación automática (detecta Docker)
	@if [ "$(DOCKER_AVAILABLE)" = "yes" ]; then \
		echo "Docker disponible. Ejecutando instalación con Docker..."; \
		make install-docker; \
	else \
		echo "Docker no disponible. Ejecutando instalación local..."; \
		make install-local; \
	fi

install-local: ## Instalación local (sin Docker)
	./setup.sh

install-docker: ## Instalación con Docker (Laravel Sail)
	@if [ -f "./setup-docker-v2.sh" ]; then \
		./setup-docker-v2.sh; \
	else \
		./setup-docker.sh; \
	fi

# Desarrollo local
.PHONY: serve dev build test
serve: ## Iniciar servidor Laravel local
	php artisan serve

dev: ## Iniciar todos los servicios de desarrollo local
	$(COMPOSER) run dev

build: ## Compilar assets para producción
	$(NPM) run build

test: ## Ejecutar tests locales
	$(COMPOSER) run test

# Desarrollo con Docker
.PHONY: up down restart logs shell
up: ## Iniciar contenedores Docker
	$(SAIL) up -d

down: ## Parar contenedores Docker
	$(SAIL) down

restart: ## Reiniciar contenedores Docker
	$(SAIL) restart

logs: ## Ver logs de contenedores
	$(SAIL) logs -f

shell: ## Acceder al contenedor principal
	$(SAIL) shell

# Comandos Docker específicos
.PHONY: sail-dev sail-build sail-test
sail-dev: ## Iniciar desarrollo con hot reload (Docker)
	$(SAIL) npm run dev

sail-build: ## Compilar assets (Docker)
	$(SAIL) npm run build

sail-test: ## Ejecutar tests (Docker)
	$(SAIL) test

# Limpieza y mantenimiento
.PHONY: clear cache-clear fresh
clear: ## Limpiar caches
	@if [ "$(DOCKER_AVAILABLE)" = "yes" ] && docker ps | grep -q sail; then \
		$(SAIL) artisan optimize:clear; \
	else \
		php artisan optimize:clear; \
	fi

cache-clear: clear ## Alias para clear

fresh: ## Reinstalar dependencias y limpiar
	@if [ "$(DOCKER_AVAILABLE)" = "yes" ] && docker ps | grep -q sail; then \
		$(SAIL) composer install; \
		$(SAIL) npm install; \
		$(SAIL) artisan migrate:fresh --seed; \
	else \
		$(COMPOSER) install; \
		$(NPM) install; \
		php artisan migrate:fresh --seed; \
	fi

# Comandos Artisan comunes
.PHONY: migrate migrate-fresh tinker queue user
migrate: ## Ejecutar migraciones
	@if [ "$(DOCKER_AVAILABLE)" = "yes" ] && docker ps | grep -q sail; then \
		$(SAIL) artisan migrate; \
	else \
		php artisan migrate; \
	fi

migrate-fresh: ## Migración fresca con seeders
	@if [ "$(DOCKER_AVAILABLE)" = "yes" ] && docker ps | grep -q sail; then \
		$(SAIL) artisan migrate:fresh --seed; \
	else \
		php artisan migrate:fresh --seed; \
	fi

tinker: ## Abrir Laravel Tinker
	@if [ "$(DOCKER_AVAILABLE)" = "yes" ] && docker ps | grep -q sail; then \
		$(SAIL) artisan tinker; \
	else \
		php artisan tinker; \
	fi

queue: ## Iniciar worker de colas
	@if [ "$(DOCKER_AVAILABLE)" = "yes" ] && docker ps | grep -q sail; then \
		$(SAIL) artisan queue:work; \
	else \
		php artisan queue:work; \
	fi

user: ## Crear usuario admin para Filament
	@if [ "$(DOCKER_AVAILABLE)" = "yes" ] && docker ps | grep -q sail; then \
		$(SAIL) artisan make:filament-user; \
	else \
		php artisan make:filament-user; \
	fi

# Por defecto, mostrar ayuda
.DEFAULT_GOAL := help
