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

# Installer les dépendances système (Ajout de gettext-dev et icu-dev)
RUN apk add --no-cache \
    bash \
    curl \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    gettext-dev \
    icu-dev

# Installer les extensions PHP indispensables (Ajout de gettext, intl, opcache)
# gettext : Résout l'erreur "Call to undefined function _()"
# intl : Évite les crashs sur la gestion des devises/dates locales
# opcache : Boost crucial pour la production
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip gettext intl opcache

# Récupérer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copier d'abord uniquement composer pour optimiser le cache Docker
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --prefer-dist

# Copier le reste de l'application
COPY . .

# Récupérer les assets compilés à l'étape 1
COPY --from=assets-builder /app/public/build ./public/build

# Finaliser l'installation (génération de l'autoloader optimisé)
RUN composer dump-autoload --no-dev --optimize

# Sécuriser les permissions (Pour éviter l'erreur 419 en mode "file")
# On s'assure que TOUT le dossier de stockage appartient à l'utilisateur PHP-FPM
RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs bootstrap/cache && \
    chown -R www-data:www-data /var/www && \
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
