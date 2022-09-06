FROM php:7.4-fpm-alpine

RUN apk add libressl-dev pkgconfig

RUN apk add --no-cache nginx wget \
    ${PHPIZE_DEPS} \
    && pecl install mongodb \
    && docker-php-ext-enable \
    mongodb \
    && apk del \
    ${PHPIZE_DEPS}

RUN mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf

RUN mkdir -p /app
COPY . /app

RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
RUN cd /app && \
    /usr/local/bin/composer install --no-dev

RUN chown -R www-data: /app

CMD sh /app/docker/startup.sh