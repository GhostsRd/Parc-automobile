# Étape 1 : On utilise une image PHP officielle avec FPM
FROM php:8.2-fpm-alpine

# Installer les dépendances système requises pour Laravel et MySQL
RUN apk add --no-cache \
    bash \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    oniguruma-dev

# Installer les extensions PHP nécessaires (notamment pdo_mysql pour la base de données)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installer Composer globalement
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail dans le conteneur
WORKDIR /var/www

# Copier les fichiers du projet dans le conteneur
COPY . .

# Installer les dépendances de production de Laravel
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Donner les bons droits aux dossiers de stockage et de cache de Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Exposer le port 9000 pour que PHP-FPM puisse recevoir les requêtes
EXPOSE 9000

CMD ["php-fpm"]
