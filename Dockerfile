FROM php:7.4-fpm-alpine

RUN apt-get install  -y openssl libssl-dev libcurl4-openssl-dev
RUN pecl install mongodb-1.6.0
RUN docker-php-ext-enable /usr/local/lib/php/extensions/no-debug-non-zts-20180731/mongodb.so

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