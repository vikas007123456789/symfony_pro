FROM php:7.3.9-fpm-buster

RUN apt-get update && apt-get install -y \
        git \
        autoconf \
        file \
        g++ \
        gcc \
        libc-dev \
        make \
        pkg-config \
        re2c \
        unzip \
        curl \
        libc-client2007e \
        libxslt1.1 \
        libmcrypt4 \
        libpng16-16 \
        libjpeg62-turbo \
        libfreetype6 \
        libicu63 \
        libzip4 \
        bison \
        libreadline7 \
        libssh2-1 \
    --no-install-recommends && rm -rf /var/lib/apt/lists/*

# workaround for installing ssh2 on PHP 7.3
# https://stackoverflow.com/questions/57217774/how-to-install-ssh2-for-php-7-3
RUN buildDeps=" \
        libssl-dev \
        libxml2-dev \
        libcurl4-openssl-dev \
        libc-client2007e-dev \
        libmcrypt-dev \
        libfreetype6-dev \
        libpng-dev \
        libkrb5-dev \
        freetds-dev \
        libxslt1-dev \
        default-libmysqlclient-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libbz2-dev \
        libreadline-dev \
        libzip-dev \
        libssh2-1-dev \
    " \
    && apt-get update && apt-get install -y $buildDeps --no-install-recommends && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install \
        bcmath \
        calendar \
        exif \
        gd \
        intl \
        mysqli \
        pcntl \
        pdo_mysql \
        sockets \
        zip \
    && pecl install apcu-5.1.17 redis-5.1.1 \
    && docker-php-ext-enable apcu redis \
    && cd /tmp \
    && git clone https://git.php.net/repository/pecl/networking/ssh2.git \
    && cd /tmp/ssh2/ \
    && .travis/build.sh \
    && docker-php-ext-enable ssh2 \
    && rm -rf /tmp/ssh2 \
    && apt-get purge -y --auto-remove $buildDeps

# Composer
ENV COMPOSER_VERSION 1.10.6
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN set -xe \
    && curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
    && curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
    && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" \
    && php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer --version=${COMPOSER_VERSION} \
    && rm -f /tmp/composer-setup.php /tmp/composer-setup.sig

# NodeJS
RUN set -xe \
    && curl -sL https://deb.nodesource.com/setup_14.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Yarn
RUN set -xe \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
    && apt-get update && apt-get install -y yarn \
    && rm -rf /var/lib/apt/lists/*
