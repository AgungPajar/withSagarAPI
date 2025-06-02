# Gunakan PHP dengan FPM dan ekstensi Laravel umum
FROM php:8.2-fpm

# Install dependensi dasar
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip nginx \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Atur direktori kerja
WORKDIR /var/www

# Salin semua file ke container
COPY . .

# Install dependensi Laravel
RUN composer install --no-dev --optimize-autoloader

# Set permission folder penting
RUN chmod -R 777 storage bootstrap/cache

# Salin konfigurasi nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Salin script start
COPY start-container.sh /start-container.sh
RUN chmod +x /start-container.sh

EXPOSE 80

CMD ["/start-container.sh"]
