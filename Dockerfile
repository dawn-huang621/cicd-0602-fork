FROM php:8.2-fpm

# 安裝必要套件
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip curl git libpq-dev nodejs npm \
    && docker-php-ext-install pdo pdo_pgsql zip

# 設定工作目錄
WORKDIR /var/www/html

# 安裝 Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 安裝 Laravel 套件（開發環境可保留 --dev）
RUN composer global require laravel/installer

CMD ["php-fpm"]
