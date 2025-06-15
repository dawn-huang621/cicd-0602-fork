FROM richarvey/nginx-php-fpm:3.1.6

# Laravel/Nginx 基本設定
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr
ENV COMPOSER_ALLOW_SUPERUSER 1

WORKDIR /var/www/html

# 複製程式碼
COPY . .

# 安裝 curl、bash、Node.js
RUN apt-get update && \
    apt-get install -y curl gnupg bash && \
    curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# 安裝 Node.js（加入 Vite 編譯支援）
RUN apt-get update && \
    curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# 安裝前端依賴並編譯
RUN npm install --no-bin-links && \
    npm run build

# Composer 安裝（如果你希望部署階段也安裝 PHP 套件，可保留）
RUN composer install --no-dev --optimize-autoloader

# 確保權限正確（避免 log 目錄不能寫入）
RUN chown -R www-data:www-data storage bootstrap/cache

CMD ["/start.sh"]
