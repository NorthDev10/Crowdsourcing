FROM php:7.2.2-fpm

WORKDIR "/var/www"

# replace shell with bash so we can source files
RUN rm /bin/sh && ln -s /bin/bash /bin/sh

RUN apt-get update && apt-get install -y libmcrypt-dev \
    mysql-client libmagickwand-dev --no-install-recommends libmemcached-dev \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install bcmath bz2 calendar iconv mbstring mysqli opcache pdo_mysql zip xml \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd \
    && pecl install memcached redis \
    && docker-php-ext-enable memcached.so redis.so

RUN apt-get update && pecl install xdebug


# Install Composer.
ENV COMPOSER_HOME /root/composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV PATH $COMPOSER_HOME/vendor/bin:$PATH

RUN apt-get install -my wget gnupg

# Install nodejs via NVM maneger
# nvm environment variables
ENV NVM_DIR /usr/local/nvm
ENV NODE_VERSION 9.4.0

# Download nvm
# https://github.com/creationix/nvm#install-script
RUN curl --silent -o- https://raw.githubusercontent.com/creationix/nvm/v0.31.2/install.sh | bash

# install node and npm
RUN source $NVM_DIR/nvm.sh \
    && nvm install $NODE_VERSION \
    && nvm use $NODE_VERSION

# Install yarn maneger
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list
RUN apt-get install apt-transport-https -y
RUN apt-get update && apt-get install yarn -y

RUN composer global require "laravel/installer"