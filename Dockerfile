FROM php:8.5-fpm

RUN apt-get update && apt-get install -y \
  git \
  curl \
  zip \
  unzip \
  libsqlite3-dev \
  && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_sqlite pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN rm -rf vendor

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

ENTRYPOINT [ "sh", "-c", "test -d vendor || composer install --no-interaction --prefer-dist && php-fpm" ]

EXPOSE 9000
