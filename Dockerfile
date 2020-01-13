FROM php:7.3-fpm
RUN apt-get update
RUN apt-get install -y vim\
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    &&  docker-php-ext-install pdo_mysql \
    &&  docker-php-ext-install -j$(nproc) iconv \
    &&  docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    &&  docker-php-ext-install -j$(nproc) gd exif
ADD php-fpm/conf/www.conf /etc/php-fpm.d/www.conf
ADD data /var/www/html
RUN chown www-data images