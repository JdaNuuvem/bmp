FROM php:8.2-apache

# Install dependencies and extensions
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Enable Apache mod_rewrite for clean URLs if needed (often used in PHP apps)
RUN a2enmod rewrite

# Copy application files to the container
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Fix permissions
RUN chown -R www-data:www-data /var/www/html/
