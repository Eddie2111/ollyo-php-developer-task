FROM php:8.2-apache

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite (optional, if needed for routing)
RUN a2enmod rewrite

# Copy custom PHP configuration (optional)
# COPY php.ini /usr/local/etc/php/
