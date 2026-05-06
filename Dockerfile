# Dockerfile para monorepo (Frontend React + Backend PHP)

# Etapa 1: Build del Frontend
FROM node:18-alpine AS frontend-builder

WORKDIR /app

# Copiar package files
COPY package*.json ./

# Instalar dependencias
RUN npm install

# Copiar código fuente
COPY . .

# Build del frontend
RUN npm run build

# Etapa 2: Backend PHP con Apache
FROM php:8.1-apache

# Instalar extensiones de PHP necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Configurar DocumentRoot
ENV APACHE_DOCUMENT_ROOT=/var/www/html/backend/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copiar código del backend
WORKDIR /var/www/html
COPY backend ./backend

# Instalar dependencias de PHP
WORKDIR /var/www/html/backend
RUN composer install --no-dev --optimize-autoloader

# Copiar frontend compilado al public del backend
COPY --from=frontend-builder /app/dist /var/www/html/backend/public

# Ejecutar migraciones
RUN php migrations/create_tables.php || echo "Migraciones se ejecutarán en el primer arranque"

# Dar permisos
RUN chown -R www-data:www-data /var/www/html/backend
RUN chmod -R 755 /var/www/html/backend

# Exponer puerto
EXPOSE 80

# Comando de inicio
CMD ["apache2-foreground"]
