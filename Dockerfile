FROM quay.io/keboola/aws-cli AS aws
ARG AWS_SECRET_ACCESS_KEY
ARG AWS_ACCESS_KEY_ID
ARG AWS_SESSION_TOKEN

RUN /usr/bin/aws s3 cp s3://keboola-drivers/db2-odbc/v11.5.4_linuxx64_dsdriver.tar.gz /tmp/dsdriver.tar.gz

# Note 2023-02-03:
# The ODBC driver doesn't work properly with PHP >= 8.2.
# This is probably due to changes in escaping, see: https://github.com/php/php-src/issues/8300 (PHP 8.2).
# A password that contains "@" does not work, even if it is correct.
FROM php:8.1-cli

ARG COMPOSER_FLAGS="--prefer-dist --no-interaction"
ARG DEBIAN_FRONTEND=noninteractive
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_PROCESS_TIMEOUT 3600

WORKDIR /code/

COPY docker/php-prod.ini /usr/local/etc/php/php.ini
COPY docker/composer-install.sh /tmp/composer-install.sh

RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        locales \
        unzip \
        unixodbc-dev \
        ksh \
        ssh \
        libicu-dev \
	&& rm -r /var/lib/apt/lists/* \
	&& sed -i 's/^# *\(en_US.UTF-8\)/\1/' /etc/locale.gen \
	&& locale-gen \
	&& chmod +x /tmp/composer-install.sh \
	&& /tmp/composer-install.sh

RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl

# Install PHP odbc extension
RUN set -x \
    && docker-php-source extract \
    && cd /usr/src/php/ext/odbc \
    && phpize \
    && sed -ri 's@^ *test +"\$PHP_.*" *= *"no" *&& *PHP_.*=yes *$@#&@g' configure \
    && ./configure --with-unixODBC=shared,/usr \
    && docker-php-ext-install odbc \
    && docker-php-source delete

# Install DB2 Client
RUN mkdir -p /opt/ibm
WORKDIR /opt/ibm
COPY --from=aws /tmp/dsdriver.tar.gz /opt/ibm/
RUN tar -xf dsdriver.tar.gz

RUN ksh dsdriver/installDSDriver

ENV IBM_DB_HOME /opt/ibm/dsdriver
ENV LD_LIBRARY_PATH=$IBM_DB_HOME/lib
RUN echo "[Db2]\nDescription = Db2 Driver\nDriver = $IBM_DB_HOME/lib/libdb2o.so\nfileusage=1\ndontdlclose=1" > /etc/odbcinst.ini

ENV LANGUAGE=en_US.UTF-8
ENV LANG=en_US.UTF-8
ENV LC_ALL=en_US.UTF-8

WORKDIR /code/

## Composer - deps always cached unless changed
# First copy only composer files
COPY composer.* /code/

# Download dependencies, but don't run scripts or init autoloaders as the app is missing
RUN composer install $COMPOSER_FLAGS --no-scripts --no-autoloader

# Copy rest of the app
COPY . /code/

# Run normal composer - all deps are cached already
RUN composer install $COMPOSER_FLAGS

CMD ["php", "/code/src/run.php"]
