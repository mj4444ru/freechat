FROM php:7.1-apache
MAINTAINER mj4444@mail.ru

# apt-get
RUN apt-get update &&\
    apt-get -y install bzip2 zip unzip git mc wget openssl g++ icu-devtools nodejs mysql-client \
        libmcrypt-dev libssl-dev libcurl4-openssl-dev libcurl3-dev libicu52 libicu-dev \
        libzzip-dev zziplib-bin zlib1g-dev libbz2-dev libxml2-dev libgmp-dev \
        libfreetype6-dev libjpeg62-turbo-dev libpng12-dev libfreetype6-dev libjpeg-dev \
        ruby ruby-dev rubygems-integration inotify-tools

# docker-php ext-install:
RUN docker-php-ext-install mcrypt &&\
    docker-php-ext-install zip &&\
    docker-php-ext-install bz2 &&\
    docker-php-ext-install opcache &&\
    docker-php-ext-install mbstring &&\
    docker-php-ext-install bcmath &&\
    docker-php-ext-install pcntl &&\
    docker-php-ext-install ftp &&\
    docker-php-ext-install exif &&\
    docker-php-ext-install calendar &&\
    docker-php-ext-install sysvmsg &&\
    docker-php-ext-install sysvsem &&\
    docker-php-ext-install sysvshm &&\
    docker-php-ext-install wddx &&\
    docker-php-ext-install curl &&\
    docker-php-ext-install iconv &&\
    docker-php-ext-install gmp &&\
    docker-php-ext-configure gd --with-freetype-dir=/usr/include/freetype2/freetype --with-jpeg-dir=/usr/include/ &&\
    docker-php-ext-install gd &&\
    docker-php-ext-install gettext &&\
    docker-php-ext-install mysqli &&\
    docker-php-ext-install pdo_mysql &&\
    docker-php-ext-install intl &&\
    docker-php-ext-install soap

#RUN docker-php-ext-install openssl &&\
#    docker-php-ext-install zlib

# tidy up apt cache:
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# apache mods:
RUN a2enmod rewrite && a2enmod headers

RUN mkdir /usr/local/node &&\
    curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.2/install.sh | NVM_DIR=/usr/local/nvm bash &&\
    export NVM_DIR="/usr/local/nvm" &&\
    [ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh" &&\
    nvm install node &&\
    npm install -g concurrently &&\
    npm install -g typescript &&\
    gem install sass

RUN export NVM_DIR="/usr/local/nvm" &&\
    [ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh" &&\
    ln -s $NVM_BIN/* /usr/local/bin/

# Install composer
COPY install-composer /tmp/install-composer
RUN /tmp/install-composer && rm /tmp/install-composer && composer global require "fxp/composer-asset-plugin:^1.2.0"

COPY etc-php/conf-d /usr/local/etc/php/conf.d/
COPY etc-php/php.ini /usr/local/etc/php/php.ini
RUN ln -s /var/www/html/vendor/bin/codecept /usr/local/bin/codecept

WORKDIR /var/www/html
