FROM php:7.4-fpm-alpine

RUN apk add libressl-dev pkgconfig nodejs npm

RUN apk add --update \
		$PHPIZE_DEPS \
		freetype-dev \
		libjpeg-turbo-dev \
		libpng-dev \
		libxml2-dev \
		libzip-dev \
	&& docker-php-ext-configure gd --with-jpeg --with-freetype \
	&& docker-php-ext-install gd

RUN apk add --no-cache nginx wget \
    ${PHPIZE_DEPS} \
    && pecl install mongodb-1.17.2 \
    && docker-php-ext-enable \
    mongodb \
    && apk del \
    ${PHPIZE_DEPS}

RUN mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf

RUN mkdir -p /app
COPY . /app

# setup npm for Vue.js
RUN npm install -g npm@9.6.5
RUN cd /app && \
    npm install
RUN cd /app && \
    npm run dev


RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
RUN cd /app && \
    /usr/local/bin/composer install --no-dev

RUN chown -R www-data: /app

CMD sh /app/docker/startup.sh
