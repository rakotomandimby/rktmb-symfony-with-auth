FROM archlinux:latest
RUN pacman -Syu --noconfirm               
RUN pacman -Sy               \
    --noconfirm              \
    php                      \ 
    php-apache  php-gd       \
    apache sqlite php-sqlite \
    php-memcached php-pgsql  \
    mariadb-clients wget     \
    php-intl php-sodium unzip postgresql-libs 


RUN rm -rfv /etc/httpd && mkdir -pv /etc/httpd
ADD ./httpd /etc/httpd/
RUN find /etc/httpd

RUN rm -rfv /etc/php && mkdir -pv /etc/php
ADD ./php /etc/php
RUN find /etc/php
RUN mkdir -pv /composer/bin
RUN wget  --quiet  https://getcomposer.org/download/latest-stable/composer.phar -O /composer/bin/composer

RUN chmod 755 /composer/bin/composer

RUN mkdir -pv /symfony/bin
WORKDIR /symfony/bin
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony5/bin/symfony /symfony/bin/symfony

ENV PATH="/composer/bin:/symfony/bin:"${PATH}

RUN mkdir -pv /srv/http/
ADD ./code    /srv/http/

WORKDIR /srv/http
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN COMPOSER_ALLOW_SUPERUSER=1 composer config extra.symfony.allow-contrib true
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction
RUN COMPOSER_ALLOW_SUPERUSER=1 composer require symfony/apache-pack

RUN find /srv/http/public

EXPOSE 80
ADD ./entrypoint.sh /entrypoint.sh
RUN chmod 755 /entrypoint.sh 
RUN chown -R http:http /srv/http

ENTRYPOINT  ["/entrypoint.sh"]
