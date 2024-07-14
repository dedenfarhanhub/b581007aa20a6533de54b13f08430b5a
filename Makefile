# Makefile

# Define the default target when running "make" without arguments
default: help

# Help target to display available targets
help:
	@echo "Available targets:"
	@echo "build     		- Run Docker Compose"
	@echo "build-local	 	- Setup without Docker"
	@echo "clean       		- Stop and remove containers"
	@echo "help        		- Display this help message"

# Run Docker Compose
build:
	@echo "Running Docker Compose..."
	@APP_VERSION=$(APP_VERSION) docker-compose up -d --build

# Build Without Docker
build-local:
	@echo "Running Docker Compose..."
	@php src/Migrations/CreateEmailLogsTable.php
	@php src/Migrations/CreateOauthAccessTokensTable.php
	@php src/Migrations/CreateOauthClientsTable.php
	@php src/Migrations/CreateUsersTable.php
	@php script/run_worker.php

# Stop and remove containers
clean:
	@echo "Stopping and removing containers..."
	@docker-compose down --remove-orphans

.PHONY: default help build build-local clean