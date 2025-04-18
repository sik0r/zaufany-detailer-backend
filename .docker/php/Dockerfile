ARG PHP_VERSION=8.3
ARG ALPINE_VERSION=3.19
FROM php:${PHP_VERSION}-fpm-alpine${ALPINE_VERSION} AS php_fpm

# Install dependencies required for extensions
# hadolint ignore=DL3018
RUN apk add --no-cache \
    build-base \
    autoconf \
    bash \
    git \
    linux-headers \
    icu-dev \
    libzip-dev \
    libpng-dev \
    libtool \
    libxml2-dev \
    postgresql-dev \
    imagemagick \
    imagemagick-dev \
    oniguruma-dev \
    && docker-php-ext-install -j$(nproc) \
    bcmath \
    exif \
    gd \
    intl \
    mbstring \
    opcache \
    pdo_pgsql \
    soap \
    sockets \
    zip

# Install Imagick
RUN set -eux; \
    pecl install imagick; \
    docker-php-ext-enable imagick

# Install Xdebug
RUN set -eux; \
    pecl install xdebug; \
    docker-php-ext-enable xdebug

# Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy custom php config
COPY ./conf.d/custom.ini /usr/local/etc/php/conf.d/zz-custom.ini

# Set working directory
WORKDIR /app

# Expose port 9000 and start php-fpm server
EXPOSE 9000

CMD ["php-fpm"]

# --- Development stage ---
FROM php_fpm AS php_fpm_dev

# Add user/group with the same UID/GID as the host user
# This avoids permission issues with mounted volumes
ARG UID=1000
ARG GID=1000
RUN addgroup -g ${GID} -S appuser && adduser -u ${UID} -S appuser -G appuser

# Switch to non-root user
USER appuser 