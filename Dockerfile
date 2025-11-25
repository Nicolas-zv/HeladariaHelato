# --------------------------------------------------------------------------
# STAGE 1: Build y Dependencias (composer, npm/vite)
# --------------------------------------------------------------------------
FROM php:8.1-fpm-alpine AS laravel_build

# Instalación de dependencias del sistema necesarias para PHP (e.g., gd, zip, pdo)
RUN apk update && apk add \
    curl \
    git \
    build-base \
    libxml2-dev \
    sqlite-dev \
    zip \
    unzip \
    nodejs \
    npm

# Instalar extensiones PHP requeridas por Laravel y las dependencias de tu proyecto
RUN docker-php-ext-install pdo pdo_mysql opcache bcmath
# Si usas barryvdh/laravel-dompdf o spatie/laravel-activitylog, necesitamos las siguientes:
RUN docker-php-ext-install ctype fileinfo dom xml

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuración del usuario para evitar correr como root
RUN adduser -D -u 1000 appuser
WORKDIR /var/www

# Copiar el código fuente y dar permisos al usuario
COPY . /var/www
RUN chown -R appuser:appuser /var/www

# Correr instalaciones de dependencias como 'appuser'
USER appuser

# Instalar dependencias de Composer (incluyendo dev si es necesario)
RUN composer install --ignore-platform-reqs --no-dev --prefer-dist --optimize-autoloader

# Instalar dependencias de Node.js y construir assets con Vite
RUN npm install
RUN npm run build

# --------------------------------------------------------------------------
# STAGE 2: Producción (Entorno de Ejecución Final)
# --------------------------------------------------------------------------
FROM php:8.1-fpm-alpine AS final

# Instalar Nginx
RUN apk add --no-cache nginx

# Instalar SOLO las extensiones PHP necesarias para la ejecución final
RUN apk add --no-cache \
    libxml2-dev \
    sqlite-dev \
    zip \
    unzip \
    build-base

RUN docker-php-ext-install pdo pdo_mysql opcache bcmath
RUN docker-php-ext-install ctype fileinfo dom xml

# Copiar archivos y configuraciones desde el stage de build
WORKDIR /var/www
COPY --from=laravel_build /var/www /var/www

# Copiar la configuración de Nginx (crearemos este archivo a continuación)
COPY ./docker/nginx/nginx.conf /etc/nginx/http.d/default.conf

# Crear el directorio de logs de Nginx y dar permisos
RUN mkdir -p /run/nginx && \
    chown -R appuser:appuser /var/www /var/lib/nginx /var/log/nginx /var/tmp/nginx

# Configurar permisos para los directorios de Laravel (storage/bootstrap)
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Cambiar al usuario de baja privilegio
USER appuser

# Exponer el puerto de PHP-FPM
EXPOSE 9000

# Comando de inicio: ejecutar PHP-FPM y Nginx
CMD sh -c "php-fpm -D && nginx -g 'daemon off;'"