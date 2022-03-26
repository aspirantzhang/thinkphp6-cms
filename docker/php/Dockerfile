FROM php:8.1.2-apache
# ENV http_proxy http://host.docker.internal:10809
# ENV https_proxy http://host.docker.internal:10809
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN echo "deb https://mirrors.aliyun.com/debian/ bullseye main non-free contrib" > /etc/apt/sources.list && \
    echo "deb-src https://mirrors.aliyun.com/debian/ bullseye main non-free contrib" >> /etc/apt/sources.list && \
    echo "deb https://mirrors.aliyun.com/debian-security/ bullseye-security main" >> /etc/apt/sources.list && \
    echo "deb-src https://mirrors.aliyun.com/debian-security/ bullseye-security main" >> /etc/apt/sources.list && \
    echo "deb https://mirrors.aliyun.com/debian/ bullseye-updates main non-free contrib" >> /etc/apt/sources.list && \
    echo "deb-src https://mirrors.aliyun.com/debian/ bullseye-updates main non-free contrib" >> /etc/apt/sources.list && \
    echo "deb https://mirrors.aliyun.com/debian/ bullseye-backports main non-free contrib" >> /etc/apt/sources.list && \
    echo "deb-src https://mirrors.aliyun.com/debian/ bullseye-backports main non-free contrib" >> /etc/apt/sources.list
RUN apt-get update && \
    apt-get install -y git && \
    docker-php-ext-install pdo pdo_mysql && \
    a2enmod rewrite && \
    composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/

RUN docker-php-ext-enable xdebug
