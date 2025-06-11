#!/bin/sh

# =============================================================================
# Docker Container Entrypoint Script
# 
# This script handles the initialization and startup of the Laravel application
# in a Docker container. It ensures the database is ready before starting the
# application and runs necessary database migrations and seeds.
# =============================================================================

# -----------------------------------------------------------------------------
# Database Connection Check
# 
# Wait for MySQL to be ready before proceeding with the application startup.
# This prevents the application from starting before the database is available.
# -----------------------------------------------------------------------------
echo "Waiting for MySQL..."
until mysqladmin ping -h api_db --silent; do
    echo "MySQL is unavailable - sleeping"
    sleep 2
done

echo "MySQL is up - executing migrations"

# -----------------------------------------------------------------------------
# Database Setup
# 
# Run Laravel migrations and seeds to set up the database schema and initial data.
# The 'migrate:fresh' command drops all tables and re-runs all migrations,
# ensuring a clean database state.
# -----------------------------------------------------------------------------
echo "Running migrations..."
php artisan migrate:fresh --seed

# -----------------------------------------------------------------------------
# Application Startup
# 
# Start PHP-FPM to handle incoming HTTP requests.
# Using 'exec' replaces the shell process with PHP-FPM,
# making it the main process in the container.
# -----------------------------------------------------------------------------
exec php-fpm