FROM php:7.1-fpm

RUN apt-get update && apt-get install -y zlib1g-dev libicu-dev g++ libssl-dev

RUN pecl install apcu-5.1.5 && \
    docker-php-ext-enable apcu && \
    docker-php-ext-install \
        intl \
        mbstring \
        pdo_mysql \
        zip \
        bcmath \
        opcache

# Install mongo
RUN pecl install mongodb \
&& echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/ext-mongodb.ini

RUN apk add --no-cache nginx wget

RUN mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf

RUN mkdir -p /app
COPY . /app

RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
RUN cd /app && \
    /usr/local/bin/composer install --no-dev

RUN chown -R www-data: /app

CMD sh /app/docker/startup.sh