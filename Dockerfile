FROM php:7.4-fpm-alpine

RUN apk add libressl-dev pkgconfig nodejs npm

RUN apk add --no-cache nginx wget libpng libjpeg-turbo-dev \
    ${PHPIZE_DEPS} \
    && pecl install mongodb \
    && docker-php-ext-enable \
    mongodb \
    && docker-php-ext-configure gd \
    --with-freetype --with-jpeg \
    && docker-php-ext-install gd --with-freetype --with-jpeg \
    && apk del libpng-dev \
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


RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
RUN cd /app && \
    /usr/local/bin/composer install --no-dev

RUN chown -R www-data: /app

CMD sh /app/docker/startup.sh
