#!/bin/bash

# Jalankan PHP-FPM di background
php-fpm -D

# Jalankan nginx (foreground)
nginx -g "daemon off;"
