FROM php:8.3-fpm

# =========================
# System dependencies
# =========================
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# =========================
# PHP extensions
# =========================
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# =========================
# PHP config
# =========================
RUN echo "upload_max_filesize=50M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size=50M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit=512M" >> /usr/local/etc/php/conf.d/uploads.ini

# =========================
# Composer
# =========================
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# =========================
# Workdir (HARUS konsisten dengan docker-compose)
# =========================
WORKDIR /var/www

# =========================
# Copy dependency first (cache layer)
# =========================
COPY composer.json composer.lock ./

# =========================
# Install dependency SAFETY MODE
# =========================
RUN composer install \
    --no-interaction \
    --no-progress \
    --no-dev \
    --optimize-autoloader || true

# =========================
# Copy full project
# =========================
COPY . .

# =========================
# Fix Laravel permission (IMPORTANT)
# =========================
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# =========================
# (OPTIONAL SAFE) generate autoload
# =========================
RUN composer dump-autoload --optimize || true

# =========================
# Expose port
# =========================
EXPOSE 8000

# =========================
# Run Laravel
# =========================
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]