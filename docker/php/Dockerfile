FROM php:8-fpm

RUN apt-get update  \
    && apt-get install -y \
      curl; \

    docker-php-ext-configure shmop --enable-shmop; \

    docker-php-ext-install -j "$(nproc)" \
      shmop;

WORKDIR /var/www

CMD ["php-fpm"]
