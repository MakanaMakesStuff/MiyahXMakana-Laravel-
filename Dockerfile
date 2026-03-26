FROM php:8.4-fpm-alpine

# 1. Install system dependencies & build tools
RUN apk add --no-cache \
	bash \
	curl \
	pkgconf \
	libpng-dev \
	libzip-dev \
	icu-dev \
	icu-libs \
	libpng \
	libzip

# 2. Install PHP extensions
RUN docker-php-ext-install pdo_mysql gd zip intl bcmath

# 3. Get the latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# 4. Copy your application code
COPY . .

# 5. Fix permissions for Laravel (Added /var/www/database here)
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database

EXPOSE 9000
CMD ["php-fpm"]