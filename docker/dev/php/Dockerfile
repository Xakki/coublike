# Version: 0.0.3
FROM php:8.1-fpm
MAINTAINER Xakki <xakki@ya.ru>
ARG DEBIAN_FRONTEND=noninteractive
ENV REFRESHED_AT="2022–01–09"

USER root

RUN apt-get update && apt-get install -y --no-install-recommends apt-utils
RUN apt-get install -y cron mc htop dialog bash-completion curl wget bash nano git lsb-release apt-transport-https \
    ca-certificates gnupg2 tzdata man zip unzip memcached pkg-config
RUN apt-get install -y \
    libzip-dev libgmp-dev libffi-dev libssl-dev libicu-dev libmcrypt4 libmcrypt-dev libpng-dev libjpeg62-turbo-dev \
    libfreetype6-dev libmagickwand-dev libmemcached-dev zlib1g-dev libpq-dev freetds-bin freetds-dev freetds-common \
    libonig-dev graphviz fontconfig libc-client-dev libcurl4-openssl-dev
RUN pecl install imagick memcached xdebug
RUN docker-php-ext-enable imagick memcached xdebug
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#RUN curl -o composer https://getcomposer.org/download/2.1.3/composer.phar && chmod 0555 composer && mv composer /usr/bin/
RUN docker-php-ext-configure intl --enable-intl && \
    docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd pdo pgsql mysqli pdo_dblib pdo_pgsql pdo_mysql \
    intl iconv mbstring zip pcntl exif opcache sockets bcmath
###Possible values for ext-name:
### bcmath bz2 calendar ctype curl dba dom enchant exif ffi fileinfo filter ftp gd gettext gmp hash iconv imap intl json ldap mbstring mysqli 
### oci8 odbc opcache pcntl pdo pdo_dblib pdo_firebird pdo_mysql pdo_oci pdo_odbc pdo_pgsql pdo_sqlite pgsql phar posix pspell readline reflection 
### session shmop simplexml snmp soap sockets sodium spl standard sysvmsg sysvsem sysvshm tidy tokenizer xml xmlreader xmlwriter xsl zend_test zip

RUN docker-php-source delete
    
## INSTAL sphinxsearch
RUN apt-get install -y unixodbc libpq5
## apt-get install libmysqlclient-dev libpq-dev unixodbc-dev
## apt-get install libmariadb-client-lgpl-dev-compat
RUN wget -O /tmp/sphinx.tar.gz http://sphinxsearch.com/files/sphinx-3.2.1-f152e0b-linux-amd64.tar.gz
RUN tar xzf /tmp/sphinx.tar.gz -C /usr/local/lib/ && mv /usr/local/lib/sphinx-3.2.1 /usr/local/lib/sphinx && rm /tmp/sphinx.tar.gz
RUN wget -P /usr/local/lib/sphinx/dicts http://sphinxsearch.com/files/dicts/ru.pak
RUN mkdir /var/lib/sphinxsearch && mkdir /var/lib/sphinxsearch/data
### END
RUN apt-get autoremove --purge -y && apt-get autoclean -y && apt-get clean -y

COPY php.ini /usr/local/etc/php/
COPY www.conf /usr/local/etc/php-fpm.d/
COPY memcached.conf /etc/
COPY cronjob /etc/cron.d/
RUN chmod 0644 /etc/cron.d/cronjob
RUN crontab /etc/cron.d/cronjob

STOPSIGNAL SIGKILL

#RUN git config --global user.email "xakki@ya.ru" && git config --global user.name "Xakki"

#WORKDIR /app
#"php-fpm -R", 
CMD printenv > /etc/environment && php-fpm -R -D && /usr/local/lib/sphinx/bin/searchd -c /app/docker/dev/sphinxsearch/sphinx.conf && service memcached start && cron -f && tail -f /var/log/*.log -n 5
