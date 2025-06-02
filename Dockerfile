# Gunakan PHP dengan FPM dan ekstensi Laravel umum
FROM php:8.2-cli

# Install dependensi dasar
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libsodium-dev \
    libpg-dev \
    default-mysql-client \
    default-limbsqlclient-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    && docker-php-ext-install --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_pgspql pdo_mysql mbstring exif pcntl bcmath gd zip sodium

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Atur direktori kerja
WORKDIR /var/www/html

# Salin semua file ke container
COPY . .
EXPOSE 8000

# Install dependensi Laravel
RUN composer install
RUN npm install


CMD php artisan serve --force && php artisan serve -- host=0.0.0 --port=8000
