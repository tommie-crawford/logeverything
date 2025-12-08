FROM php:8.3-fpm

# Basis extensions voor Symfony + DB + intl etc.
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libpq-dev libzip-dev librabbitmq-dev \
    && docker-php-ext-install intl pdo pdo_mysql opcache zip

RUN pecl install amqp

RUN echo "extension=amqp.so" > /usr/local/etc/php/conf.d/docker-php-ext-amqp.ini

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-scripts --no-progress

COPY . .
#
RUN composer dump-autoload --optimize \
    && php bin/console cache:warmup --env=prod

CMD ["php-fpm"]
