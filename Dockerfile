# Gunakan PHP CLI
FROM php:8.2-cli

# Install ekstensi dan dependensi
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
    libpq-dev \
    default-mysql-client \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_pgsql \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        sodium

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Atur direktori kerja
WORKDIR /var/www/html

# Salin semua file ke container
COPY . .

# Install Laravel dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Salin file .env jika belum ada
RUN cp .env.example .env

# Generate APP_KEY agar artisan bisa jalan
RUN php artisan key:generate

# Berikan permission untuk folder Laravel
RUN chmod -R 775 storage bootstrap/cache

# Jangan jalankan storage:link di tahap build karena bisa error
# Jalankan saat container running saja

# Expose port Laravel
EXPOSE 8080

# ✅ Jalankan storage:link & Laravel saat container berjalan
CMD php artisan storage:link && php artisan serve --host=0.0.0.0 --port=8080
