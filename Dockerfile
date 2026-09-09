FROM php:8.2-apache

# Install PostgreSQL PHP extension
RUN docker-php-ext-install pgsql pdo_pgsql

# Copy the LifeLine project
COPY . /var/www/html/

EXPOSE 80