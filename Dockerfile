FROM php:8.2-apache

# Install PostgreSQL development libraries
RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Copy LifeLine project
COPY . /var/www/html/

EXPOSE 80