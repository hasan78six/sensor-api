# Base Image
# Use PHP 8.3 with FPM (FastCGI Process Manager) as the base image
FROM php:8.3-fpm

# System Dependencies
# Install required system packages for PHP extensions and development tools
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    default-mysql-client

# Clean up
# Remove package lists and cache to reduce image size
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# PHP Extensions
# Install required PHP extensions for Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composer
# Install Composer for PHP dependency management
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Application Setup
# Set the working directory for the application
WORKDIR /var/www

# Copy Application
# Copy the entire application into the container
COPY . .

# Dependencies
# Install PHP dependencies using Composer
RUN composer install

# Frontend Assets
# Install Node.js dependencies and build frontend assets
RUN npm install && npm run build

# Permissions
# Set proper permissions for Laravel storage and cache directories
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Entrypoint
# Copy and set up the entrypoint script for container initialization
COPY docker/app/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Container Configuration
# Set the entrypoint script and default command
ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]

# Expose port 9000 for PHP-FPM
EXPOSE 9000