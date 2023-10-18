FROM composer:latest AS composer
FROM php:8.2-fpm
ARG PHP_USER_ID=1000
ARG PHP_USER_NAME='libraryt-auto'

ENV PHP_USER_ID=${PHP_USER_ID}
ENV PHP_USER_NAME=${PHP_USER_NAME}

COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN apt-get update && \
     apt-get -y install \
         cron \
         supervisor \
         nano \
         htop \
         rsyslog \
         libzip-dev \
         zlib1g-dev \
         libpng-dev \
         && docker-php-ext-install zip && \
         apt-get update && \
         rm -rf /var/lib/apt/lists/* && \
         docker-php-ext-install gd mysqli pdo pdo_mysql zip && \
         docker-php-ext-enable gd pdo_mysql zip

COPY image-files/ /

RUN chmod 755 \
        /usr/local/bin/docker-entrypoint.sh \
        /usr/local/bin/docker-run.sh

RUN echo $PHP_USER_NAME
RUN useradd -m -u $PHP_USER_ID -o -s /bin/bash $PHP_USER_NAME || echo "User already exists."
RUN usermod -u $PHP_USER_ID $PHP_USER_NAME \
  && groupmod -g $PHP_USER_ID $PHP_USER_NAME \
  && mkdir /app \
  && chown -R $PHP_USER_ID:$PHP_USER_ID /app

COPY ./crontab /etc/cron.d/crontab

RUN chown root:root /etc/cron.d/crontab
RUN chmod 644 /etc/cron.d/crontab

RUN /usr/bin/crontab /etc/cron.d/crontab

RUN touch /var/log/cron.log
RUN sed -i '/imklog/s/^/#/' /etc/rsyslog.conf

WORKDIR /app

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["docker-run.sh"]