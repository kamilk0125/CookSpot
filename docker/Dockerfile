FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip

ADD entrypoint.sh /var/docker/

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www

ENTRYPOINT [ "bash", "/var/docker/entrypoint.sh" ]

CMD [ "php-fpm" ]