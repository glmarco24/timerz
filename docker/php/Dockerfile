FROM php:8.3-fpm

# System deps needed for Composer and PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       git \
       unzip \
       libzip-dev \
       libcurl4-openssl-dev \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions required by Laravel
RUN docker-php-ext-install \
      pdo_mysql \
      zip \
      curl

# Install Composer (copy from official composer image)
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Opcache is optional but recommended for performance (uncomment if desired)
# RUN docker-php-ext-install opcache

WORKDIR /app
