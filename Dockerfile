# Menggunakan image PHP resmi dengan Apache
FROM php:8.3-apache

# Menginstal ekstensi yang diperlukan
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql zip sockets

# Menginstal Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Menyalin file aplikasi ke direktori kerja container
COPY . /var/www/html

# Mengatur direktori kerja
WORKDIR /var/www/html

# Menginstal dependensi PHP menggunakan Composer
RUN composer install

# Mengaktifkan modul Apache yang diperlukan
RUN a2enmod rewrite

# Menyalin file konfigurasi Apache
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN chmod +x .docker/docker-entrypoint.sh

# Mengekspos port 80
EXPOSE 80

CMD [ ".docker/docker-entrypoint.sh" ]
# Menjalankan Apache di foreground
#CMD ["apache2-foreground"]