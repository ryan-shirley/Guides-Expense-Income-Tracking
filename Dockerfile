FROM php:7.4-fpm-alpine

RUN apk add libressl-dev pkgconfig nodejs npm

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

# setup npm fir Vue.js
RUN npm install -g npm@latest
RUN cd /app && \
    npm install
RUN cd /app && \
    npm run dev


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN cd /app && \
    /usr/bin/composer install --no-dev

RUN chown -R www-data: /app

CMD sh /app/docker/startup.sh
