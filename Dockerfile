FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    supervisor

RUN apt-get install -y docker.io

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs=20.12.1-1nodesource1

RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl

RUN sed -i 's/listen = 127.0.0.1:9000/listen = 0.0.0.0:9000/' /usr/local/etc/php-fpm.d/www.conf

COPY . /var/www
WORKDIR /var/www

RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN npm install && npm run build

COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

RUN php artisan key:generate

EXPOSE 9000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
