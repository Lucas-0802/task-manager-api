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

RUN chown -R www-data:www-data /var/www/html && \
  chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache && \
  chmod -R 755 /var/www/html

EXPOSE 9000

ENTRYPOINT [ "sh", "-c", "chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache; mkdir -p /var/www/html/storage/logs; chmod -R 777 /var/www/html/storage/logs; test -d vendor || composer install --no-interaction --prefer-dist; php artisan key:generate --force; php artisan migrate --force; php-fpm" ]
