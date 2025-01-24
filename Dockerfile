FROM php:7.4-fpm-alpine as builder

# Install build dependencies
RUN apk add --no-cache \
    libressl-dev \
    pkgconfig \
    nodejs \
    npm \
    $PHPIZE_DEPS \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libxml2-dev \
    libzip-dev

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install gd \
    && pecl install mongodb-1.17.2 \
    && docker-php-ext-enable mongodb

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Copy only package files first to leverage cache
COPY package*.json composer.* /app/
WORKDIR /app

# Install JS dependencies
RUN npm install -g npm@9.6.5 && \
    npm install

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy the rest of the application
COPY . /app
RUN npm run dev

# Final stage
FROM php:7.4-fpm-alpine

# Install runtime dependencies
RUN apk add --no-cache \
    nginx \
    freetype \
    libjpeg-turbo \
    libpng \
    libzip \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install gd \
    && pecl install mongodb-1.17.2 \
    && docker-php-ext-enable mongodb

# Setup nginx
RUN mkdir -p /run/nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Copy application from builder
COPY --from=builder /app /app
RUN chown -R www-data: /app

WORKDIR /app
CMD sh /app/docker/startup.sh
