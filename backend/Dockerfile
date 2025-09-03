FROM php:8.2-fpm
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_mysql zip curl

WORKDIR /app

ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN chmod 777 -R /app


CMD ["/bin/sh", "-c", "if [ ! -d /app/vendor ]; then mkdir /app/vendor; fi && chmod -R 777 /app/vendor && cd /app && composer config process-timeout 4000 && composer install && php artisan jwt:secret && php artisan serve --host=0.0.0.0"]
