# syntax=docker/dockerfile:1

#############################
# Stage 1: Base Image
#############################
FROM dunglas/frankenphp:1-php8.3 AS frankenphp_base

WORKDIR /app

# Add source code
COPY --link . ./

# Install packages
RUN apt-get update && apt-get install -y --no-install-recommends \
    acl \
    file \
    gettext \
    git \
    gnupg \
    curl \
    unzip \
    chromium \
    chromium-driver \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN set -eux; \
    install-php-extensions \
        @composer \
        apcu \
        intl \
        gd \
        opcache \
        zip \
        pdo_pgsql \
        xdebug

# FNM for managing Node versions
ARG version=20
RUN curl -fsSL https://fnm.vercel.app/install | bash -s -- --install-dir './fnm' && \
    cp ./fnm/fnm /usr/bin && fnm install $version

# Set Composer ENV vars
ENV COMPOSER_ALLOW_SUPERUSER=1

# Mercure default config
ENV MERCURE_TRANSPORT_URL=bolt:///data/mercure.db
ENV PHP_INI_SCAN_DIR=":$PHP_INI_DIR/app.conf.d"

# Panther settings
ENV PANTHER_NO_SANDBOX=1
ENV PANTHER_CHROME_ARGUMENTS='--disable-dev-shm-usage'

# Add PHP/Caddy config
COPY --link frankenphp/conf.d/app.ini $PHP_INI_DIR/app.conf.d/
COPY --link --chmod=755 frankenphp/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
COPY --link frankenphp/Caddyfile /etc/caddy/Caddyfile

# Add /app to git's safe directories
RUN git config --global --add safe.directory /app

# Define the docker entrypoint (it is located at frankenphp/docker-entrypoint.sh)
ENTRYPOINT ["docker-entrypoint"]

# Start healtheck for base container
HEALTHCHECK --start-period=60s CMD curl -f http://localhost:2019/metrics || exit 1

# Run frankenphp with the custom Caddyfile
CMD [ "frankenphp", "run", "--config", "/etc/caddy/Caddyfile" ]

#############################
# Stage 2: Dev Image
#############################
FROM frankenphp_base AS frankenphp_dev

WORKDIR /app

# Copy assets from node image
COPY --link --from=frankenphp_base /app .

# Set env variables
ENV APP_ENV=local
ENV XDEBUG_MODE=off
ENV FRANKENPHP_WORKER_CONFIG=watch

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY --link frankenphp/conf.d/app.prod.ini $PHP_INI_DIR/app.conf.d/

CMD [ "frankenphp", "run", "--config", "/etc/caddy/Caddyfile" ]
