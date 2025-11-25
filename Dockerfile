# --------------------------------------------------------------------------
# STAGE 1: Build y Dependencias (composer, npm/vite)
# --------------------------------------------------------------------------
FROM php:8.1-fpm-alpine AS laravel_build

RUN apk update && apk add \
    curl git build-base libxml2-dev sqlite-dev zip unzip \
    nodejs npm libpng-dev libjpeg-turbo-dev freetype-dev \
    postgresql-dev

# Instalar extensiones necesarias para Laravel + PostgreSQL
RUN docker-php-ext-configure gd \
    --with-jpeg=/usr/include/ \
    --with-freetype=/usr/include/
RUN docker-php-ext-install pdo pdo_pgsql bcmath gd opcache ctype fileinfo dom xml

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear usuario de aplicación
RUN adduser -D -u 1000 appuser
WORKDIR /var/www

# Copiar código
COPY . /var/www
RUN chown -R appuser:appuser /var/www

USER appuser

# Instalar dependencias
RUN composer install --ignore-platform-reqs --no-dev --prefer-dist --optimize-autoloader

# Instalar dependencias de Node y build con Vite
RUN npm install
RUN npm run build

# --------------------------------------------------------------------------
# STAGE 2: Producción (Nginx + PHP-FPM)
# --------------------------------------------------------------------------
FROM php:8.1-fpm-alpine AS final

RUN apk add --no-cache nginx libpng-dev libjpeg-turbo-dev freetype-dev \
    build-base libxml2-dev sqlite-dev zip unzip postgresql-dev

# Extensiones PHP para producción
RUN docker-php-ext-configure gd \
    --with-jpeg=/usr/include/ \
    --with-freetype=/usr/include/
RUN docker-php-ext-install pdo pdo_pgsql bcmath gd opcache ctype fileinfo dom xml

WORKDIR /var/www
COPY --from=laravel_build /var/www /var/www

# Copiar configuración Nginx
COPY ./nginx.conf /etc/nginx/http.d/default.conf

RUN mkdir -p /run/nginx && \
    chown -R appuser:appuser /var/www /var/lib/nginx /var/log/nginx /var/tmp/nginx

RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

USER appuser

EXPOSE 80
EXPOSE 9000

CMD sh -c "php-fpm -D && nginx -g 'daemon off;'"