FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl unzip zip libpng-dev libonig-dev libxml2-dev libzip-dev libsodium-dev libpq-dev \
    default-mysql-client libfreetype6-dev libjpeg62-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip sodium

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# Install dependencies Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# ✅ Generate APP_KEY agar artisan tidak error
RUN cp .env.example .env && php artisan key:generate

# ✅ Berikan permission agar storage bisa di-link
RUN chmod -R 775 storage bootstrap/cache

# ✅ Buat symlink nanti saat container dijalankan, bukan saat build

EXPOSE 8000

# ✅ Jalankan storage:link saat container running
CMD php artisan storage:link && php artisan serve --host=0.0.0.0 --port=8000
