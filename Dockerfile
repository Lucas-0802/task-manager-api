FROM php:8.5-fpm

# Instalar dependências mínimas do sistema
RUN apt-get update && apt-get install -y \
  git \
  curl \
  zip \
  unzip \
  libsqlite3-dev \
  && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_sqlite pdo_mysql

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos do projeto
COPY . .

# Instalar apenas dependências PHP (sem build)
RUN composer install --no-interaction --no-progress

# Dar permissões corretas ao usuario www-data
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# PHP-FPM já expõe 9000 por padrão
EXPOSE 9000
