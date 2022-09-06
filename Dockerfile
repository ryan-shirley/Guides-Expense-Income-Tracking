FROM php:7.4-fpm-alpine

ARG DRIVER_VERSION=1.10.0
ARG PHPLIB_VERSION=1.8.0

RUN apk --update add --virtual build-dependencies build-base openssl-dev autoconf \
  && pecl install "mongodb-${DRIVER_VERSION}"\
  && docker-php-ext-enable mongodb \
  && apk del build-dependencies build-base openssl-dev autoconf \
  && rm -rf /var/cache/apk/*

RUN addgroup -S gsgroup && adduser -S gsuser -G gsgroup

RUN mkdir /workspace \
    && cd /workspace \
    && wget https://raw.githubusercontent.com/composer/getcomposer.org/76a7060ccb93902cd7576b67264ad91c8a2700e2/web/installer -O - -q | php -- --quiet \
    && chmod +x /workspace/composer.phar \
    && ./composer.phar require "mongodb/mongodb=^$PHPLIB_VERSION"

USER gsuser

ENTRYPOINT ["/bin/sh", "-c"]  

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