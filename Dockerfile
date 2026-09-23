#Use the official PHP Image with Apache

FROM php:8.2-apache

# Install PostgreSQL extensions for PHP
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Document container port or Expose vApache's HTTPS port
EXPOSE 80

# Environment defaults (Can be overridden by docker-compose or .env)
ENV PGHOST=portfolio-db \
    PGDATABASE=portfolio_db \
    PGUSER=moin \
    PGPASSWORD=moin@123 \
    PGPORT=5432

# Copy all application source code into Apache document root
COPY ./src /var/www/html


# Set proper ownership and permission for Apache web server
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html


