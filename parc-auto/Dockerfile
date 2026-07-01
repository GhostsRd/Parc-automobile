# ==========================================
# ÉTAPE 1 : Compilation des assets (CSS/JS) avec Node
# ==========================================
FROM node:20-alpine AS assets-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# ==========================================
# ÉTAPE 2 : Image finale de production (PHP)
# ==========================================
FROM php:8.2-fpm-alpine

# Installer les dépendances système requises (Ajout de libzip-dev)
RUN apk add --no-cache \
    bash \
    curl \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    oniguruma-dev

# Installer les extensions PHP (Ajout de zip)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Récupérer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

# Récupérer les assets compilés à l'étape 1
COPY --from=assets-builder /app/public/build ./public/build

# Installer les dépendances PHP de production
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Droits d'accès pour Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
