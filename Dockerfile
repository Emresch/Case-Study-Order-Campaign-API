# Modern ve stabil bir PHP sürümü
FROM php:8.4-fpm

# Gerekli sistem kütüphaneleri
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Veritabanı ve performans için temel PHP eklentileri
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Cache ve asenkron queue (kuyruk) işlemleri için Redis eklentisi
RUN pecl install redis && docker-php-ext-enable redis

# Bağımlılık yönetimi için Composer'ın imaja kopyalanması
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Uygulama çalışma dizini
WORKDIR /var/www

# Gerekli dosya yazma/okuma izinleri
RUN chown -R www-data:www-data /var/www

EXPOSE 9000
CMD ["php-fpm"]