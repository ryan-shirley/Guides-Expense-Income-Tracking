# FROM php:8.0.5
# RUN apt-get update -y && apt-get install -y openssl zip unzip git
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# RUN docker-php-ext-install pdo


# RUN pecl install mongodb
# # RUN echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/ext-mongodb.ini

# # RUN pecl install mongodb && docker-php-ext-enable mongodb
# RUN echo "extension=mongodb.so" >> /usr/local/etc/php/php.ini

# WORKDIR /app
# COPY . /app
# RUN composer install



FROM php:8.0.5-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    openssl
    # pkg-config

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

RUN pecl install mongodb
RUN echo "extension=mongodb.so" >> /usr/local/etc/php/php.ini

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# # Create system user to run Composer and Artisan Commands
# RUN useradd -G www-data,root -u $uid -d /home/$user $user
# RUN mkdir -p /home/$user/.composer && \
#     chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www

USER $user

WORKDIR /app
COPY . /app
RUN composer install






# FROM composer:1.9.0 as build
# WORKDIR /app
# COPY . /app
# RUN composer global require hirak/prestissimo && composer install

# FROM php:8.0.5
# RUN docker-php-ext-install pdo pdo_mysql

# EXPOSE 8080
# COPY --from=build /app /var/www/
# COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
# COPY .env.example /var/www/.env
# RUN chmod 777 -R /var/www/storage/ && \
#     echo "Listen 8080" >> /etc/apache2/ports.conf && \
#     chown -R www-data:www-data /var/www/ && \
#     a2enmod rewrite