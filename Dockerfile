FROM php:7.4-fpm

USER root

WORKDIR /var/www

# Install dependencies
RUN apt-get update \
	# gd
	&& apt-get install -y --no-install-recommends build-essential  openssl nginx libfreetype6-dev libjpeg-dev libpng-dev libwebp-dev zlib1g-dev libzip-dev gcc g++ make vim unzip curl git jpegoptim optipng pngquant gifsicle locales libonig-dev nodejs npm  \
	&& docker-php-ext-configure gd  \
	&& docker-php-ext-install gd \
	# gmp
	&& apt-get install -y --no-install-recommends libgmp-dev \
	&& docker-php-ext-install gmp \
	# pdo_mysql
	&& docker-php-ext-install pdo_mysql mbstring \
	# pdo
	&& docker-php-ext-install pdo \
	# opcache
	&& docker-php-ext-enable opcache \
	# zip
	&& docker-php-ext-install zip \
	&& apt-get autoclean -y \
	&& rm -rf /var/lib/apt/lists/* \
	&& rm -rf /tmp/pear/
    # Mongo db
    # ${PHPIZE_DEPS} \
    # && pecl install mongodb \
    # && docker-php-ext-enable \
    # mongodb \
    # && apk del \
    # ${PHPIZE_DEPS}

# Copy files
COPY . /var/www

# COPY ./deploy/local.ini /usr/local/etc/php/local.ini

COPY ./docker/nginx.conf /etc/nginx/nginx.conf

RUN chmod +rwx /var/www

RUN chmod -R 777 /var/www

# setup npm
RUN npm install -g npm@latest

RUN npm install

# include your other npm run scripts e.g npm rebuild node-sass

# run your default build command here mine is npm run prod
RUN npm run prod

# setup composer and laravel
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install --working-dir="/var/www"

RUN composer dump-autoload --working-dir="/var/www"

RUN php artisan route:clear

RUN php artisan route:cache

RUN php artisan config:clear

RUN php artisan config:cache

EXPOSE 80

RUN ["chmod", "+x", "./docker/post_deploy.sh"]

CMD [ "sh", "./docker/post_deploy.sh" ]
# CMD php artisan serve --host=127.0.0.1 --port=9000






# FROM php:7.4-fpm-alpine

# RUN apk add --no-cache nginx wget \
#     ${PHPIZE_DEPS} \
#     && pecl install mongodb \
#     && docker-php-ext-enable \
#     mongodb \
#     && apk del \
#     ${PHPIZE_DEPS}

# RUN mkdir -p /run/nginx

# COPY docker/nginx.conf /etc/nginx/nginx.conf

# RUN mkdir -p /app
# COPY . /app

# RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
# RUN cd /app && \
#     /usr/local/bin/composer install --no-dev

# RUN chown -R www-data: /app

# CMD sh /app/docker/startup.sh